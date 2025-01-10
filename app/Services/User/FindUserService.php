<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\Exceptions\UserNotFound;
use App\Models\User;

class FindUserService
{
    /**
     * Summary of execute
     * @param string $uuid
     * @throws \App\Exceptions\UserNotFound
     * @return \App\DTO\User\UserDTO
     */
    public function execute(string $uuid): UserDTO
    {
        $user = User::where('id', $uuid)->first();
        if (! $user) {
            throw new UserNotFound;
        }

        return UserDTO::fromModel($user);
    }
}
