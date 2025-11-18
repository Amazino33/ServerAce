<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscrowAccount extends Model
{
    protected $fillable = [
        'payment_id',
        'gig_id',
        'amount',
        'status',
        'held_at',
        'released_at',
        'release_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'held_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function isHolding(): bool
    {
        return $this->status === 'holding';
    }

    public function release(string $notes = null): void
    {
        $this->update([
            'status' => 'released',
            'released_at' => now(),
            'release_notes' => $notes,
        ]);
    }
}
