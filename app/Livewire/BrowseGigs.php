<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Gig;
use Livewire\Component;
use Livewire\WithPagination;

class BrowseGigs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $sort = 'latest';
    public string $viewMode = 'grid'; // grid or list
    public ?int $minBudget = null;
    public ?int $maxBudget = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'latest']
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'category', 'sort', 'minBudget', 'maxBudget'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Gig::query()
            ->where('status', 'open')
            ->with(['client', 'category', 'media'])
            ->withCount('applications');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        // Budget filtering
        if ($this->minBudget) {
            $query->where(function($q) {
                $q->where('budget_fixed', '>=', $this->minBudget)
                  ->orWhere('budget_min', '>=', $this->minBudget);
            });
        }

        if ($this->maxBudget) {
            $query->where(function($q) {
                $q->where('budget_fixed', '<=', $this->maxBudget)
                  ->orWhere('budget_max', '<=', $this->maxBudget);
            });
        }

        // Sorting
        switch ($this->sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(budget_fixed, budget_min) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(budget_fixed, budget_max) DESC');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'applications':
                $query->orderBy('applications_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $gigs = $query->paginate(12);

        // Get categories with gig counts
        $categories = Category::where('is_active', true)
            ->whereHas('gigs', fn($q) => $q->where('status', 'open'))
            ->withCount(['gigs' => fn($q) => $q->where('status', 'open')])
            ->orderBy('menu_order')
            ->orderBy('name')
            ->get();

        return view('livewire.browse-gigs', [
            'gigs' => $gigs,
            'categories' => $categories,
        ])
        ->layout('layouts.app')
        ->title('Browse Server Gigs - ServerAce')
        ->with('metaDescription', 'Find expert server admins for Linux, Windows, cPanel, DDos, Optimization, and more. Fast delivery.');
    }

    public function setBudgetRange($min, $max)
    {
        $this->minBudget = $min;
        $this->maxBudget = $max;
        $this->resetPage();
    }

    public function clearBudget()
    {
        $this->minBudget = null;
        $this->maxBudget = null;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->minBudget = null;
        $this->maxBudget = null;
        $this->resetPage();
    }
}