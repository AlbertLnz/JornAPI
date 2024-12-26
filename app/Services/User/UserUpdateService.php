<?php 

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserUpdateService{


    public function execute( ?string $email,  ?string $uuid): User{

        $user = User::where('id', $uuid)->first();
        if(!$user){
            throw new UserNotFound();
        }
        DB::transaction(function () use ($user, $email,) {
            if($email != null){
                $user->email = $email;
            }
    
         
    
            $user->save();
        });
        return $user;
      
       
    }
}