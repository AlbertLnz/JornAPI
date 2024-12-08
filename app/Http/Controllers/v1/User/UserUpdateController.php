<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\User;

use App\DTO\User\ShowUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\Token\TokenService;
use App\Services\User\UserUpdateService;

class UserUpdateController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\User\UserUpdateService $userUpdateService
     */
    public function __construct( private UserUpdateService $userUpdateService){}
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserRequest $request)
    {
     
        $user = $request->user();
       $user= $this->userUpdateService->execute($request->email, $request->password, $user);
        return response()->json(['message' => 'User updated successfully','user'=>ShowUserDTO::fromUser($user)], 200);
    }
}
