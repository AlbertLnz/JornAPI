<?php 

declare(strict_types=1);
namespace Tests\Unit\Controllers\User;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\v1\User\UserUpdateController;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\UserUpdateService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class UpdateUserControllerTest extends TestCase{
use DatabaseTransactions;
    private UserUpdateController $controller;
    private User $user;
    private TokenService $tokenService;
    private UserUpdateService $userUpdateService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->userUpdateService = Mockery::mock(UserUpdateService::class);
        $this->controller = new UserUpdateController($this->tokenService, $this->userUpdateService);
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(UserUpdateController::class, $this->controller);
    }

    public function testUpdateUser(){
        $userId = $this->user->id;
   
        $token = 'mocked-jwt-token';
        $decodedToken = (object) ['sub' => $userId];
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->with($token)
            ->andReturn($decodedToken);
            $this->userUpdateService->shouldReceive('execute')->once()
            ->with($request->email, $request->password, $userId)->andReturn($this->user);

           
    
            // Invoke the controller
            $response = $this->controller->__invoke($request);
    
            // Assert the response is a JsonResponse
            $this->assertInstanceOf(JsonResponse::class, $response);
              // Decode the JSON response
        $responseData = $response->getData();
        $this->assertEquals('User updated successfully', $responseData->message);
        $this->assertEquals(200, $response->getStatusCode());

    }

}