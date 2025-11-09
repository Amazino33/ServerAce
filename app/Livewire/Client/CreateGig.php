<?php

namespace App\Livewire\Client;

use App\Models\Gig;
use App\Enums\GigStatus;
use Livewire\Component;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CreateGig extends Component
{
    public $modal = false;

    public $title = '';
    public $description = '';
    public $budgetType = 'range';
    public $budget_min = 50;
    public $budget_max = 5000;
    public $budget_fixed = 500;
    public $slugPreview = '';

    protected $rules = [
        'title' => 'required|string|min:10|max:120',
        'description' => 'required|string|min:100|max:5000',
        'budget_min' => 'required_if:budgetType,range|numeric|min:5',
        'budget_max' => 'required_if:budgetType,range|numeric|gt:budget_min',
        'budget_fixed' => 'required_if:budgetType,fixed|numeric|min:5',
    ];

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
        $this->validate();

        $gig = Gig::create([
            'client_id' => auth()->id(),
            'title' => $this->title,
            'slug' => SlugService::createSlug(Gig::class, 'slug', $this->title),
            'description' => $this->description,
            'budget_min' => $this->budgetType === 'range' ? $this->budget_min : null,
            'budget_max' => $this->budgetType === 'range' ? $this->budget_max : null,
            'budget_fixed' => $this->budgetType === 'fixed' ? $this->budget_fixed : null,
            'status' => GigStatus::OPEN->value,
        ]);

        $this->closeModal();
        $this->dispatch('toast', [
            'type' => 'success', 
            'title' => 'Gig posted!',
            'message' => 'Your gig is live: <a href="'.route('gigs.show', $gig->slug).'" class="underline font-bold">'.$gig->title.'</a>'
        ]);

        return redirect()->route('gigs.show', $gig->slug); // or wherever you list gigs
    }

    public function render()
    {
        return view('livewire.client.create-gig');
    }
}