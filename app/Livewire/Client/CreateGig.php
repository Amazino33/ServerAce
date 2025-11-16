<?php

namespace App\Livewire\Client;

use App\Models\Gig;
use App\Enums\GigStatus;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\WithFileUploads;

class CreateGig extends Component
{
    use WithFileUploads;

    // In your PostGigModal Livewire component
    protected $listeners = ['openModal'];
    public $modal = false;
    public $title = '';
    public $description = '';
    public $category_id = '';
    public $budgetType = 'range';
    public $budget_min = 50;
    public $budget_max = 5000;
    public $budget_fixed = 500;
    public $slugPreview = '';
    public $imagePreviews = [];
    public array $photos = []; // Keep this as photos


    protected $rules = [
        'title' => 'required|string|min:10|max:120',
        'description' => 'required|string|min:100|max:5000',
        'budget_min' => 'required_if:budgetType,range|numeric|min:5',
        'budget_max' => 'required_if:budgetType,range|numeric|gt:budget_min',
        'budget_fixed' => 'required_if:budgetType,fixed|numeric|min:5',
        'category_id' => 'required|exists:categories,id',
        'photos' => 'required|array|min:1|max:3',
        'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ];

    // Fixed: Changed from updatedTempImages to updatedPhotos
    public function updatedPhotos()
    {
        // Validate only the photos
        $this->validate([
            'photos' => 'required|array|min:1|max:3',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->imagePreviews = []; // Clears old previews
        
        // Save only preview URLs
        foreach($this->photos as $photo) {
            $this->imagePreviews[] = $photo->temporaryUrl();
        }
    }

    public function removeImage($index)
    {
        // Remove from both arrays
        unset($this->imagePreviews[$index]);
        unset($this->photos[$index]);

        // Re-index arrays
        $this->imagePreviews = array_values($this->imagePreviews);
        $this->photos = array_values($this->photos);

        // Tell browser to clear the image input
        $this->dispatch('clear-files');
    }
    
    // public function mount() 
    // {
    //     $this->category_id = Category::where('is_active', true)
    //          ->orderBy('menu_order')
    //          ->orderBy('name')
    //          ->first()
    //          ->id ?? ''; // Get first category ID or empty string
    // }

    public function openModal()
    {
        if (!auth()->user()?->canPostGig()) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Verify email first!']);
            return redirect()->route('verification.notice');
        }

        $this->modal = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->reset();
    }

    public function updatedTitle()
    {
        $this->slugPreview = Str::slug($this->title);
    }

    public function createGig()
    {
        // Validate all fields
        $this->validate();

  

        // Create the gig
        $gig = Gig::create([
            'client_id' => auth()->id(),
            'title' => $this->title,
            'slug' => SlugService::createSlug(Gig::class, 'slug', $this->title),
            'description' => $this->description,
            'category_id' => $this->category_id,
            'budget_min' => $this->budgetType === 'range' ? $this->budget_min : null,
            'budget_max' => $this->budgetType === 'range' ? $this->budget_max : null,
            'budget_fixed' => $this->budgetType === 'fixed' ? $this->budget_fixed : null,
            'status' => GigStatus::OPEN->value,
            'is_approved' => true,
        ]);

        // Fixed: Changed from $this->images to $this->photos
        foreach($this->photos as $photo) {
            $gig->addMedia($photo->getRealPath())
                ->usingName($photo->getClientOriginalName())
                ->toMediaCollection('images');
        }

        $this->dispatch('toast', [
            'type' => 'success', 
            'title' => 'Gig posted!',
            'message' => 'Your gig is live: <a href="'.route('gigs.show', $gig->slug).'" class="underline font-bold">'.$gig->title.'</a>'
        ]);
        
        $this->closeModal();
        $this->reset();

        return redirect()->route('gigs.show', $gig->slug);
    }

    public function render()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('menu_order')
            ->orderBy('name')
            ->get();
            
        return view('livewire.client.create-gig', [
            'categories' => $categories
        ]);
    }
}