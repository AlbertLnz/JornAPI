<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\ChangePassWordException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService
{
    /**
     * Summary of execute
     *
     * @throws \App\Exceptions\ChangePassWordException
     */
    public function execute(User $user, string $oldPassword, string $newPassword): void
    {
        if (! Hash::check($oldPassword, $user->password)) {
            throw new ChangePassWordException('Old password is incorrect', 400);
        }

        DB::transaction(function () use ($user, $newPassword) {
            $user->password = Hash::make($newPassword);
            $user->save();
        });

    }
}
