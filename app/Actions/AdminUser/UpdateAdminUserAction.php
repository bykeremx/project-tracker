<?php

declare(strict_types=1);

namespace App\Actions\AdminUser;

use App\Models\User;

final class UpdateAdminUserAction
{
    /**
     * @param  array{name: string, email: string, password?: string|null}  $data
     */
    public function execute(User $admin, array $data): User
    {
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $admin->update($payload);

        return $admin;
    }
}
