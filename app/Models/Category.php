<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'in_menu',
        'is_active',
        'menu_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'in_menu' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => false, // Don't change slug when name is updated
            ]
        ];
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function activeGigs()
    {
        return $this->hasMany(Gig::class)->live();
    }
}
