<?php

namespace App\Http\Controllers\v1\User;

use App\DTO\User\ShowUserDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use App\Services\User\FindUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ShowUserController extends Controller
{

    public function __construct(private TokenService $tokenService){}
    public function __invoke(Request $request)
    {
        try{
            $decode= $this->tokenService->decodeToken($request->bearerToken());
            $user= FindUserService::execute($decode->sub);
            return response()->json(['message' => 'User found successfully','user'=>ShowUserDTO::fromUser($user)], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
   
       
    }

}
