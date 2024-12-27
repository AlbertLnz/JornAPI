<?php

declare(strict_types=1);

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\DeleteUserService;
use Tests\TestCase;

class DeleteUserServiceTest extends TestCase
{
    private DeleteUserService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new DeleteUserService;
        $this->user = User::factory()->create();
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(DeleteUserService::class, $this->service);
    }

    public function test_delete_user_servicewithvaliddata()
    {
        $this->service->execute($this->user->id);
        $user = User::where('id', $this->user->id)->first();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(0, $user->is_active);
    }
}
