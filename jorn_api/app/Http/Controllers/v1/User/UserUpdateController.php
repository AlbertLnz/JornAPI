<?php

namespace App\Http\Controllers\v1\User;

use App\DTO\User\ShowUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\Token\TokenService;
use App\Services\User\UserUpdateService;
use Illuminate\Http\Request;

class UserUpdateController extends Controller
{

    public function __construct(private TokenService $tokenService, private UserUpdateService $userUpdateService){}
    public function __invoke(UpdateUserRequest $request)
    {
     
        $decode=  $this->tokenService->decodeToken($request->bearerToken());
       $user= $this->userUpdateService->execute($request->email, $request->password, $decode->sub);
        return response()->json(['message' => 'User updated successfully','user'=>ShowUserDTO::fromUser($user)], 200);
    }
}
