<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\Models\User;

class ShowUserDTO
{
    public function __construct(
        public string $id,
        public string $email,
   
        public bool $isActive
    ){}

    public static function fromUser(User $user): self
    {
        return new self(
            $user->id,
            $user->email,
            $user->is_active
        );
    }

    



}
        