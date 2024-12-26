<?php
declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\NullDataException;
use App\Exceptions\UserAlreadyExists;
use App\Jobs\SendRegisterNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserService{

    public function execute(?string $email, ?string $password): User
    {
        if($email == null || $password == null){
            throw new NullDataException('Email and password are required', 400);
        }
        $user = User::where('email', $email)->first();
        if($user){
            throw new UserAlreadyExists();
        }
      $user =  DB::transaction(function () use ($email, $password) {
           $data= User::create([
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            
            DB::afterCommit(function () use ($data) {
                SendRegisterNotification::dispatch($data);
            });
            return $data;
        });

   
      
       return $user;
    }
}