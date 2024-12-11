<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\User;

use App\DTO\User\UserDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use App\Services\User\FindUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ShowUserController extends Controller
{

    /**
     * Summary of __construct
     * @param \App\Services\User\FindUserService $findUserService
     */
    public function __construct( private FindUserService $findUserService){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
    
            $user = $request->user();
            return response()->json(['message' => 'User found successfully','user'=>UserDTO::fromModel($user)], 200);
        
   
       
    }

}
