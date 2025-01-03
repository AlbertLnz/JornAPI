<?php

namespace App\Http\Controllers\v1\User;

use App\Exceptions\ChangePassWordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\User\ChangePasswordService;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function __construct(private ChangePasswordService $service){}
    public function __invoke(ChangePasswordRequest $request){
        try {
            $this->service->execute($request->user(), $request->old_password, $request->new_password, $request->password_confirmation);
            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (ChangePassWordException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        
    }
}
