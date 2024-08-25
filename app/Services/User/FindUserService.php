<?php
declare(strict_types=1);
namespace App\Services\User;

use App\Exceptions\UserNotFound;
use App\Models\User;

class FindUserService{


    public static function execute(string $uuid): User
    {
        $user = User::where('id', $uuid)->first();
        if(!$user){
            throw new UserNotFound();
        }
        return $user;
    }
}