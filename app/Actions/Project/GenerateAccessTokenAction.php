<?php

declare(strict_types=1);

namespace App\Actions\Project;

use App\Models\Project;
use Illuminate\Support\Str;

/**
 * 64 karakterlik kriptografik rastgele token üretir (Str::random).
 *
 * Çakışma olasılığı ihmal edilebilir düzeydedir; yine de UNIQUE index ihlaline
 * karşı exists() ile kontrol edilir. exists() sorgusu idx_access_token
 * UNIQUE index'ini kullanır (ref lookup, full scan yok).
 */
final class GenerateAccessTokenAction
{
    public function execute(): string
    {
        do {
            $token = Str::random(64);
        } while (Project::query()->where('access_token', $token)->exists());

        return $token;
    }
}
