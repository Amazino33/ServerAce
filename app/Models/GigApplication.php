<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigApplication extends Model
{
    protected $fillable = [
        'gig_id',
        'freelancer_id',
        'cover_letter',
        'proposed_price', 
        'status'
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =========================================
    // RELATIONSHIPS
    // =========================================

    /**
     * The gig this application belongs to
     */
    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    /**
     * The freelancer who applied
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    // =========================================
    // QUERY SCOPES - Reusable filters
    // =========================================

    /**
     * Get only pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get only accepted applications
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Get only rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Order by newest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    
    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Check if application is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /** 
     * Check if application is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Accept this application 
     */
    public function accept()
    {
        return $this->update(['status' => 'accepted']);
    }

    /**
     * Reject this application
     */
    public function reject()
    {
        return $this->update(['status', 'rejected']);
    }

    /**
     * Get freelancer's name
     */
    public function getFreelancerNameAttribute()
    {
        return $this->freelancer->name;
    }

    /**
     * Get the gig title
     */
    public function getGigTitleAttribute()
    {
        return $this->gig->title;
    }
}
