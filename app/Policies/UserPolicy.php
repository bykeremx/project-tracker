<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * Yöneticiler users tablosundadır. Kendi kaydını silmek paneli kilitler.
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, User $admin): bool
    {
        return true;
    }

    public function delete(User $user, User $admin): bool
    {
        return $user->isNot($admin);
    }
}
