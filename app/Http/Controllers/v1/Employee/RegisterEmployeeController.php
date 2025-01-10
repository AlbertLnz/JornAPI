<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\Employee;

use App\DTO\Employee\RegisterEmployeeDTO;
use App\Exceptions\NullDataException;
use App\Exceptions\UserAlReadyExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Services\Employee\RegisterEmployeeService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterEmployeeController extends Controller
{
    /**
     * Summary of __construct
     * @var RegisterEmployeeService $service
     */
    public function __construct(private RegisterEmployeeService $service) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function __invoke(RegisterEmployeeRequest $request): JsonResponse
    {
        try {
           $data = RegisterEmployeeDTO::toArray($request->all());
            $this->service->execute($data);

            return response()->json(['message' => 'Employee created successfully'], 201);
        } catch (UserAlReadyExists|NullDataException $e) {
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }

    }
}
