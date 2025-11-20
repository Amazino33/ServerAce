<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'stripe_id',              // for Stripe customer ID
        'stripe_account_id',      // for Stripe Connect account ID
        'stripe_onboarded',
        'stripe_onboarded_at', 'location', 'phone', 'bio', 'avatar',
        'profile_public', 'available_for_work',
        'title', 'hourly_rate', 'experience_level', 'skills', 'portfolio_description',
        'company_name', 'industry', 'company_size',
        'website', 'linkedin_url', 'github_url', 'twitter_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define the collection
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portfolio')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile(false); // Multiple images
    }

    // Optional: conversions (thumbnails)
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'skills' => 'array',
            'profile_public' => 'boolean',
            'available_for_work' => 'boolean',
            'hourly_rate' => 'decimal:2',
        ];
    }

    
    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Checks if user is an admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if the user is a freelancer
     * @return bool
     */
    public function isFreelancer(): bool
    {
        return $this->role === UserRole::FREELANCER;
    }

    /**
     * Check if the user is a client
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->role === UserRole::CLIENT;
    }

    /**
     * Check if the user can post a gig
     * @return bool
     */
    public function canPostGig(): bool
    {
        return $this->role === UserRole::CLIENT && $this->hasVerifiedEmail();
    }

    /**
     * Checks if the user has already applied to a particular gig
     * @param \App\Models\Gig $gig
     * @return bool
     */
    public function hasAppliedTo(Gig $gig)
    {
        return $this->gigApplications()
                    ->where('gig_id', $gig->id)
                    ->exists();
    }

    /**
     * Get freelancer's payouts
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'freelancer_id');
    }
    
    /**
     * Check if freelancer is onboarded with Stripe
     */
    public function isStripeOnboarded(): bool
    {
        return (bool) $this->stripe_onboarded;
    }
    
    /**
     * Check if user needs to set up payments
     */
    public function needsPaymentSetup(): bool
    {
        return $this->role->value === 'freelancer' && !$this->stripe_onboarded;
    }


    
    // =========================================
    // RELATIONSHIPS
    // =========================================

    /**
     * Checks gigs application by this user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<GigApplication, User>
     */
    public function gigApplications()
    {
        return $this->hasMany(GigApplication::class, 'freelancer_id');
    }

    /**
     * Return gigs posted by the user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Gig, User>
     */
    public function postedGigs()
    {
        return $this->hasMany(Gig::class, 'client_id');
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class, 'client_id');
    }

    public function paymentsReceived()
    {
        return $this->hasMany(Payment::class, 'freelancer_id');
    }

    public function paymentsMade()
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    public function completedGigs()
    {
        return $this->hasMany(Gig::class, 'client_id')->where('status', 'completed');
    }

    public function reviewsReceived()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }
}
