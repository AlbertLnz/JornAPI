<?php

namespace App\Http\Controllers\v1\User;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use App\Services\User\DeleteUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function __construct(private DeleteUserService $deleteUserService, private TokenService $tokenService){}
    public function __invoke(Request $request)
    {

        try{
           $token= $this->tokenService->decodeToken($request->bearerToken());
            $this->deleteUserService->execute($token->sub);
            return response()->json(['message' => 'User deleted successfully'], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
        
    }
}
