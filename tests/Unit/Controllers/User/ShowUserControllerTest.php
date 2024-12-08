<?php 

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\FindUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ShowUserControllerTest extends TestCase{
use DatabaseTransactions;
    private ShowUserController $controller;
    private User $user;
    private TokenService $tokenService;
    private FindUserService $findUserService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        
        // Mock the FindUserService
        $this->findUserService = Mockery::mock(FindUserService::class);
        $this->controller = new ShowUserController($this->tokenService, $this->findUserService);
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(ShowUserController::class, $this->controller);
    }

    public function testShowUser(){
        $userId = $this->user->id;

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
            ->andReturn($this->user);

        // Create a mock request with the bearer token
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        // Invoke the controller
        $response = $this->controller->__invoke($request);

        // Assert the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decode the JSON response
        $responseData = $response->getData();

        // Assert the response data is as expected
        $this->assertEquals('User found successfully', $responseData->message);
        $this->assertEquals($this->user->id, $responseData->user->id);
        $this->assertEquals($this->user->email, $responseData->user->email);

        // Assert the status code is 200
        $this->assertEquals(200, $response->getStatusCode());
        
       
    }
}