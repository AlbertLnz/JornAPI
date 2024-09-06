<?php 

declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\v1\Auth\LogOutController;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;


class LogOutControllerTest extends TestCase
{
    use DatabaseTransactions;

    private LogOutController $controller;
    private User $user;
    private TokenService $tokenService;
    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->controller = new LogOutController($this->tokenService);
        $this->user = User::factory()->create();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(LogOutController::class, $this->controller);
    }

    public function testLogOut()
    {
         // Mock data
         $token = 'mocked-jwt-token';
         $jti = 'mocked-jti';
         $userID = $this->user->id;
 
         // Mock the TokenService methods
         $this->tokenService->shouldReceive('getJtiFromToken')
             ->once()
             ->with($token)
             ->andReturn($jti);
 
         $this->tokenService->shouldReceive('decodeToken')
             ->once()
             ->with($token)
             ->andReturn((object) ['sub' => $userID]);
 
         $this->tokenService->shouldReceive('revokeAllRefreshTokens')
             ->once()
             ->with($userID);
 
         // Mock the Cache store to use Redis and verify the put method is called
         Cache::shouldReceive('store')
             ->once()
             ->with('redis')
             ->andReturn(Mockery::mock([
                 'put' => true
             ]));
 
         // Create a mock request with the bearer token
         $request = new Request();
         $request->headers->set('Authorization', 'Bearer ' . $token);
 
         // Invoke the controller
         $response = $this->controller->__invoke($request);
 
         // Assert the response is a JsonResponse
         $this->assertInstanceOf(JsonResponse::class, $response);
 
         // Assert the response data
         $responseData = $response->getData();
         $this->assertEquals('Logged out successfully', $responseData->message);
 
         // Assert the status code is 200
         $this->assertEquals(200, $response->getStatusCode());
    }
    public function testLogOutWithInvalidToken()
    {
        $token = 'invalid-jwt-token';
        $this->expectException(HttpResponseException::class);
        // Mock the TokenService methods to simulate an invalid token scenario
        $this->tokenService->shouldReceive('getJtiFromToken')
            ->once()
            ->with($token)
            ->andReturn(null); // Simulate an invalid token

            $this->tokenService->shouldReceive('decodeToken')
            ->once()
            ->with($token)
            ->andThrow(new HttpResponseException((new JsonResponse(['message' => 'Invalid token'], 401))));

        // Create a mock request with the bearer token
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $token);

        // Expect the HttpResponseException to be thrown
        $this->expectException(HttpResponseException::class);
        
        // Invoke the controller
      $response=  $this->controller->__invoke($request);
      $this->assertEquals(200, $response->getStatusCode());

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}