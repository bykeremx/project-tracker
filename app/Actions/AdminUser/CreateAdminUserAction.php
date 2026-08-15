<?php

declare(strict_types=1);

namespace App\Actions\AdminUser;

use App\Models\User;

final class CreateAdminUserAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function execute(array $data): User
    {
        return User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
