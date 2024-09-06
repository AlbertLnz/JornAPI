<?php 

declare(strict_types=1);
namespace App\Services\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Http\Request;

class DeleteUserService{


    public function __construct(){}
    public function execute(string $uuid): void{

 
       $user = User::where('id', $uuid)->update(['is_active' => 0]);
       if(!$user){
        throw new UserNotFound();
       }
        
    }
}