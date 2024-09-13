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
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ShowEmployeeControllerTest extends TestCase{
use DatabaseTransactions;
    private ShowEmployeeController $controller;
    private Employee $user;
    private TokenService $tokenService;
    private FindUserService $findUserService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->findUserService = Mockery::mock(FindUserService::class);
        $this->controller = new ShowEmployeeController($this->tokenService, $this->findUserService);
        $this->user = Employee::factory()->create();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(ShowEmployeeController::class, $this->controller);
    }


    public function testShowEmployeeSuccess()
    {
        $userId = $this->user->user_id;

        // Generate a mock token and set up the decodeToken method to return the expected data
        $token = 'mocked-jwt-token';
        $decodedToken = (object) ['sub' => $userId];
        
        $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->with($token)
            ->andReturn($decodedToken);

        // Set up the findUserService to return the mock user when execute is called
        $this->findUserService->shouldReceive('execute')
            ->once()
            ->with($userId)
            ->andReturn($this->user->user);

        // Create a mock request with the bearer token
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        // Invoke the controller
        $response = $this->controller->__invoke($request);

        // Assert the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decode the JSON response
        $responseData = $response->getData();

        $this->assertEquals('Employee found successfully', $responseData->message);
        $this->assertEquals($this->user->name, $responseData->employee->name);
    }

/*     public function testShowEmployeeNotFound()
    {
        $userId = Uuid::uuid4()->toString();
        $decodedToken = (object) ['sub' => $userId];
        $this->expectException(HttpResponseException::class);

        $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->withAnyArgs()
            ->andReturn($decodedToken);

        $this->findUserService->shouldReceive('execute')
            ->once()
            ->with($userId)
            ->andThrowException(HttpResponseException::class);

        // Simulamos que el usuario no tiene empleado asociado
        $this->user->employee = null;

        $request = new Request();
        $request->bearerToken = 'mocked-jwt-token';

        $this->expectExceptionCode(404);

        $this->controller->__invoke($request);
    } */

}