<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'username' => ['required', 'string', Rule::unique(User::class)->ignore($this->user()->id)],
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_level' => 'nullable|in:beginner,intermediate,expert',
            'skills.*' => 'nullable|string',
            'portfolio_description' => 'nullable|string|max:2000',
            'company_name' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'website' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'profile_public' => 'sometimes|boolean',
            'available_for_work' => 'sometimes|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'portfolio.*' => 'image|mimes:jpeg,png,webp|max:5120',
        ];
    }
}
