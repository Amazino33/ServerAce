<?php

namespace App\Models;

use App\Enums\GigStatus;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gig extends Model implements HasMedia
{
    use Sluggable, InteractsWithMedia;

    protected $casts = [
        'status' => GigStatus::class,
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'budget_fixed' => 'decimal:2',
        'delivered_at' => 'datetime',
        'assigned_to_inhouse' => 'boolean',
        'inhouse_assigned_at' => 'datetime',
    ];

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'category_id',
        'images',
        'budget_min',
        'budget_max',
        'budget_fixed',
        'status',
        'is_approved',
        'approved_at',
        'approved_by',
        'awarded_to',
        'assigned_to_inhouse',
        'inhouse_developer_id',
        'inhouse_assigned_at',
        'inhouse_assignment_notes',
    ];

    // Define a collection name
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
             ->singleFile(true) // This allows for multiple files
             ->useDisk('public');
    }

    // Define conversion for thumbnails
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(400)
             ->height(400)
             ->sharpen(10)
             ->performOnCollections('image');

        $this->addMediaConversion('preview')
             ->width(800)
             ->height(800)
             ->keepOriginalImageFormat();
    }

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

    
    // =========================================
    // RELATIONSHIPS
    // =========================================

    /**
     * This gig belongs to this client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Gig>
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    
    /**
     * This gig is worked by this freelancer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Gig>
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'awarded_to');
    }

    /**
     * This gig is assigned to this in-house developer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Gig>
     */
    public function inHouseDeveloper()
    {
        return $this->belongsTo(User::class, 'inhouse_developer_id');
    }
    
    /**
     * This gig is in this category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, Gig>
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * This query belongs to this user
     * @return \Illuminate\Database\Elloquent\
     * loquent\Relations\BelongsTo<User, Gig>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(GigApplication::class);
    }


    
    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Check if gig is editable
     * @return bool
     */
    public function isEditable(): bool
    {
        return in_array($this->status, [GigStatus::DRAFT, GigStatus::OPEN]);
    }

    /**
     * Gets route key name
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the budget for the gig
     * @return string
     */
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
    
    /**
     * Gets the first image of the gig
     * @param mixed $conversion
     * @return string
     */
    public function getFirstImageUrl($conversion = 'thumb')
    {   
        return $this->getFirstMediaUrl('images', $conversion);
    }

    /**
     * check is gig has been applied for
     * @param mixed $userId
     * @return bool
     */
    public function hasApplicationForm($userId)
    {
        return $this->applications()
                    ->where('freelancer_id', $userId)
                    ->exists();
    }

    /**
     * Check if gig has a pending application
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GigApplication, Gig>
     */
    public function pendingApplications()
    {
        return $this->applications()->where('status', 'pending');
    }

    /**
     * Check if gig has an accepted application.
     * @return GigApplication|object|null
     */
    public function acceptedApplications()
    {
        return $this->applications()->where('status', 'accepted')->first();
    }

    /**
     * Checks if gig has applications
     * @return bool
     */
    public function hasApplications(): bool
    {
        return $this->applications()->exists();
    }

    /**
     * Gets the number of pending applications
     * @return int
     */
    public function getPendingCountAttribute(): int
    {
        return $this->applications()->where('status', 'pending')->count();
    }

    /**
     * Gets the number of accepted applications
     * @return int
     */
    public function getAcceptedCountAttribute(): int
    {
        return $this->applications()->where('status', 'accepted')->count();
    }

    /**
     * allows only deleting of non accepted applications
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        // Prevents deletion when someone has accepted
        return !$this->applications()->where('status', 'accepted')->exists();
    }

    /**
     * Close a gig
     * @return bool
     */
    public function close()
    {
        return $this->update(['status' => GigStatus::COMPLETED->value]);
    }


    public function reopen()
    {
        return $this->update(['status' => GigStatus::OPEN->value]);
    }

    /**
     * Assign this gig to an in-house developer
     * @param int $developerId
     * @param string|null $notes
     * @return bool
     */
    public function assignToInHouseDeveloper($developerId, $notes = null)
    {
        return $this->update([
            'assigned_to_inhouse' => true,
            'inhouse_developer_id' => $developerId,
            'inhouse_assigned_at' => now(),
            'inhouse_assignment_notes' => $notes,
        ]);
    }

    /**
     * Remove in-house developer assignment
     * @return bool
     */
    public function removeInHouseAssignment()
    {
        return $this->update([
            'assigned_to_inhouse' => false,
            'inhouse_developer_id' => null,
            'inhouse_assigned_at' => null,
            'inhouse_assignment_notes' => null,
        ]);
    }

    /**
     * Check if gig is assigned to in-house developer
     * @return bool
     */
    public function isAssignedToInHouse(): bool
    {
        return (bool) $this->assigned_to_inhouse;
    }

    /**
     * Get the accepted freelancer for this gig
     * @return User|null
     */
    public function getAcceptedFreelancerAttribute()
    {
        $acceptedApp = $this->applications()->where('status', 'accepted')->first();
        return $acceptedApp ? $acceptedApp->freelancer : null;
    }

    /**
     * Get the worker assigned to this gig (either freelancer or in-house dev)
     * @return User|null
     */
    public function getAssignedWorkerAttribute()
    {
        if ($this->inhouse_developer_id) {
            return $this->inHouseDeveloper;
        }
        
        return $this->acceptedFreelancer;
    }

    /**
     * Check if gig has an assigned worker
     * @return bool
     */
    public function hasAssignedWorker(): bool
    {
        return $this->inhouse_developer_id !== null || $this->acceptedFreelancer !== null;
    }


    // public function getAllImageUrls($conversion = 'large')
    // {
    //     return $this->getMedia('images')->map(function($media) use ($conversion) {
    //         return $media->getUrl($conversion);
    //     })->toArray();
    // }


    
    // =========================================
    // QUERY SCOPES - Reusable filters
    // =========================================

    /**
     * Get only approved gigs
     * @param mixed $query
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Get only open gigs
     * @param mixed $query
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Get only live gigs
     * @param mixed $query
     */
    public function scopeLive($query)
    {
        return $query->where('is_approved', true)->where('status', 'open');
    }
}
