<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'gig_id',
        'client_id',
        'freelancer_id',
        'amount',
        'platform_fee',
        'freelancer_amount',
        'currency',
        'status',
        'payment_intent_id',
        'stripe_charge_id',
        'description',
        'paid_at',
        'released_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'freelancer_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class, 'gig_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function escrow(): HasOne
    {
        return $this->hasOne(EscrowAccount::class);
    }

    public function payout(): HasOne
    {
        return $this->hasOne(Payout::class);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isHeld(): bool
    {
        return $this->status === 'held';
    }

    public function isReleased(): bool
    {
        return $this->status === 'released';
    }

    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['held', 'released']);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'held',
            'paid_at' => now(),
        ]);
    }

    public function release(): void
    {
        $this->update([
            'status' => 'released',
            'released_at' => now(),
        ]);
    }
}
