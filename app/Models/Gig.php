<?php

namespace App\Models;

use App\Enums\GigStatus;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Gig extends Model
{
    use Sluggable;

    protected $casts = [
        'status' => GigStatus::class,
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'budget_min',
        'budget_max',
        'status',
        'awarded_to',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
                'onUpdate' => false, // Don't change slug when title is updated
            ]
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'awarded_to');
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [GigStatus::DRAFT, GigStatus::OPEN]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getBudgetDisplayAttribute(): string
    {
        if ($this->budget_fixed) {
            return '$' . number_format($this->budget_fixed, 2);
        }

        if ($this->budget_min && $this->budget_max) {
            return '$' . number_format($this->budget_min) . ' - $' . number_format($this->budget_max);
        }

        return 'Negotiable';
    }
}
