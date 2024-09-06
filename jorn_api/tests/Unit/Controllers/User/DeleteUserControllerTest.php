<?php 

declare(strict_types=1);
namespace Tests\Unit\Controllers\User;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\v1\User\DeleteUserController;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\DeleteUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class DeleteUserControllerTest extends TestCase{
use DatabaseTransactions;
    private DeleteUserController $controller;
    private User $user;
    private TokenService $tokenService;
    private DeleteUserService $userDeleteService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->userDeleteService = Mockery::mock(DeleteUserService::class);
        $this->controller = new DeleteUserController( $this->userDeleteService,$this->tokenService);
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(DeleteUserController::class, $this->controller);
    }

    public function testDeleteUser(){
        $userId = $this->user->id;
        $token = 'mocked-jwt-token';
        $decodedToken = (object) ['sub' => $userId];
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);
        $this->tokenService->shouldReceive('decodeToken')
        ->once()
        ->with($token)
        ->andReturn($decodedToken);
        $this->userDeleteService->shouldReceive('execute')->once()
        ->with($userId);

        // Invoke the controller
        $response = $this->controller->__invoke($request);

        // Assert the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decode the JSON response
        $responseData = $response->getData();
        $this->assertEquals('User deleted successfully', $responseData->message);

    }
}