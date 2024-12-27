<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUserService
{
    public function __construct() {}

    /**
     * Summary of execute
     *
     * @throws \App\Exceptions\UserNotFound
     */
    public function execute(string $uuid): void
    {

        DB::transaction(function () use ($uuid) {
            User::where('id', $uuid)->update(['is_active' => 0]);

        });

    }
}
