<?php
declare(strict_types=1);
namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\Exceptions\UserNotFound;
use App\Models\User;

class FindUserService{


    public  function execute(string $uuid): array
    {
        $user = User::where('id', $uuid)->first();
        if(!$user){
            throw new UserNotFound();
        }
        return UserDTO::toArray($user->toArray());
    }
}