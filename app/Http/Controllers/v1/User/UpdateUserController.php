<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\User\UpdateUserService;

class UpdateUserController extends Controller
{
    /**
     * Summary of __construct
     */
    public function __construct(private UpdateUserService $userUpdateService) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserRequest $request)
    {

        $user = $request->user();
        $user = $this->userUpdateService->execute($request->email ?? null, $user->id);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }
}
