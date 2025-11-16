<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
}
