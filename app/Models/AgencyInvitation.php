<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyInvitation extends Model
{
    protected $fillable = [
        'agency_id',
        'email',
        'role',
        'token',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
