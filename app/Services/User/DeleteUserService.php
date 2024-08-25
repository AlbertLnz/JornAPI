<?php 

declare(strict_types=1);
namespace App\Services\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Http\Request;

class DeleteUserService{


    public function __construct(private TokenService $tokenService){}
    public function execute(string $token): void{

        $decode= $this->tokenService->decodeToken($token);
       $user = User::where('id', $decode->sub)->update(['is_active' => 0]);
       if(!$user){
        throw new UserNotFound();
       }
        
    }
}