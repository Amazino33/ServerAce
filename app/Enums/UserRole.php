<?php

namespace App\Enums;

enum UserRole: string
{
    case CLIENT = 'client';
    case FREELANCER = 'freelancer';
    case ADMIN = 'admin';

    // Optional: Human-readable labels
    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::FREELANCER => 'Freelancer',
            self::ADMIN => 'Administrator',
        };
    }

    // Optional: default role for new users
    public static function default(): self
    {
        return self::CLIENT;
    }
}
