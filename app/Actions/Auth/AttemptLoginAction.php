<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Kimlik doğrulama iş kuralı controller dışında tutulur.
 * Başarısız denemede genel bir hata döner; hangi alanın yanlış olduğu sızdırılmaz.
 */
final class AttemptLoginAction
{
    /**
     * @param  array{email: string, password: string}  $credentials
     *
     * @throws ValidationException
     */
    public function execute(array $credentials, bool $remember = false): void
    {
        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }
    }
}
