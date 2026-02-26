<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgencyInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 🛡️ Ensure the person sending the invite is the OWNER of the agency
        $agency = $this->route('agency');

        return $this->user()
            ->agencies()
            ->where('agency_id', $agency->id)
            ->wherePivot('role', 'owner') // Only owners can invite!
            ->exists();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            // If you want to let them choose roles later, you can validate it here
            // 'role' => ['required', 'in:member,manager'],
        ];
    }
}
