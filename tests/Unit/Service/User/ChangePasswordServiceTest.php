<?php 

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\ChangePasswordService;
use App\Exceptions\ChangePassWordException;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordServiceTest extends TestCase
{

    public function test_change_password_success(){
        $user = User::factory()->create(['password' => 'password']);
        $service = new ChangePasswordService();
        $service->execute($user, 'password', 'newpassword');
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }
    public function test_cant_change_password()
    {
        $user = User::factory()->create();
        $service = new ChangePasswordService();
        $this->expectException(ChangePassWordException::class);
        $service->execute($user, '123456', '123456');
    } 

 
}