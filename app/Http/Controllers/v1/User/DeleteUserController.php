<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\User;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use App\Services\User\DeleteUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\User\DeleteUserService $deleteUserService
     */
    public function __construct(private DeleteUserService $deleteUserService){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request):JsonResponse
    {

        
           $user = $request->user();
            $this->deleteUserService->execute($user->id);
            return response()->json(['message' => 'User deleted successfully'], 200);
        
        
    }
}
