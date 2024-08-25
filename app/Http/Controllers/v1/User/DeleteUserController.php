<?php

namespace App\Http\Controllers\v1\user;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\User\DeleteUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{

    public function __construct(private DeleteUserService $deleteUserService){}
    public function __invoke(Request $request)
    {

        try{
            $this->deleteUserService->execute($request->bearerToken());
            return response()->json(['message' => 'User deleted successfully'], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
        
    }
}
