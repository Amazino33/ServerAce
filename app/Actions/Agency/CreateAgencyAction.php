<?php

namespace App\Actions\Agency;

use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CreateAgencyAction
{
    /**
     * @throws Exception
     */
    public function execute(User $user, array $data): Agency
    {
        // 1. Gaurd clause, check for dynamic limit from the user model
        if (!$user->canJoinNewAgency()) {
            throw new Exception('You have reached the maximum numbers of agencies allowed.');
        }

        // 2. Database transaction
        // If the agency is created but the pivot attachment fails, if rolls EVERYTHING back.
        return DB::transaction(function() use ($user, $data) {
            // Create the Agency
            $agency = Agency::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
            ]);

            // Attach the user to the pivot table with the 'owner' role
            $user->agencies()->attach($agency->id, ['role' => 'owner']);

            return $agency;
        });
    }
}
