<?php 
declare(strict_types=1);
namespace Tests\Unit\Controllers\Employee;

use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Models\Employee;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\FindUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ShowEmployeeControllerTest extends TestCase{
use DatabaseTransactions;
    private ShowEmployeeController $controller;
    private User $user;
    private TokenService $tokenService;
    private FindUserService $findUserService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->findUserService = Mockery::mock(FindUserService::class);
        $this->controller = new ShowEmployeeController($this->tokenService, $this->findUserService);
        $this->user = User::factory()->create();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(ShowEmployeeController::class, $this->controller);
    }

    public function testShowEmployeeSuccess()
    {
        $userId = $this->user->id;
        $decodedToken = (object) ['sub' => $userId];

        $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->withAnyArgs()
            ->andReturn($decodedToken);

        $this->findUserService->shouldReceive('execute')
            ->once()
            ->with($userId)
            ->andReturn($this->user);

        $request = new Request();
        $request->bearerToken = 'mocked-jwt-token';

        $response = $this->controller->__invoke($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = $response->getData();
        $this->assertEquals('Employee found successfully', $responseData->message);
        $this->assertEquals($this->employee->id, $responseData->employee->id);
    }

    public function testShowEmployeeNotFound()
    {
        $userId = $this->user->id;
        $decodedToken = (object) ['sub' => $userId];

        $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->withAnyArgs()
            ->andReturn($decodedToken);

        $this->findUserService->shouldReceive('execute')
            ->once()
            ->with($userId)
            ->andReturn($this->user);

        // Simulamos que el usuario no tiene empleado asociado
        $this->user->employee = null;

        $request = new Request();
        $request->bearerToken = 'mocked-jwt-token';

        $this->expectException(HttpResponseException::class);
        $this->expectExceptionMessage('User not found');
        $this->expectExceptionCode(404);

        $this->controller->__invoke($request);
    }

}