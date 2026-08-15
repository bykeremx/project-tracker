<?php

declare(strict_types=1);

namespace App\Actions\AdminUser;

use App\Models\User;
use RuntimeException;

final class DeleteAdminUserAction
{
    public function execute(User $actor, User $admin): void
    {
        if ($actor->is($admin)) {
            throw new RuntimeException('Kendi yönetici kaydınız silinemez.');
        }

        $admin->delete();
    }
}
