<?php 

declare(strict_types=1);
namespace App\Services\User;

use App\Exceptions\UserNotFound;
use App\Models\User;


class DeleteUserService{


    public function __construct(){}
    /**
     * Summary of execute
     * @param string $uuid
     * @throws \App\Exceptions\UserNotFound
     * @return void
     */
    public function execute(string $uuid): void{

 
        User::where('id', $uuid)->update(['is_active' => 0]);
     
        
    }
}