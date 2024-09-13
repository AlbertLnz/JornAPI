<?php 
namespace Tests\Feature\User\ShowUser;

use App\DTO\User\ShowUserDTO;
use App\Models\Employee;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;


class ShowUserFoundTest extends TestCase
{
    use DatabaseTransactions;
    private TokenService $tokenService;
    private Employee $employee;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();

    }
    public function testShowUserFound()
    {
        $user =ShowUserDTO::fromUser($this->employee->user);

        $token =$this->tokenService->generateToken($this->employee->user_id,$this->employee->user->roles);
        Cache::store('redis')->put("user:{$this->employee->user_id}:token", $token, 3600); //
       
      $showUser = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->getJson('/api/user/show');
dump($showUser->getContent()); // Mostrar el contenido de la respuesta para depuración

        $showUser->assertStatus(200);
        $showUser->assertJsonStructure([
          'message',
          'user' 
        ]);

       
    }
}