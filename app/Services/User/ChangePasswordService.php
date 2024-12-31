<?php 
namespace App\Services\User;

use App\Exceptions\ChangePassWordException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService
{
    public function execute(User $user, $oldPassword, $newPassword, $passwordConfirmation): void
    {
        if (!Hash::check($oldPassword, $user->password)) {
            throw new ChangePassWordException('Old password is incorrect', 400);
        }

     
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}