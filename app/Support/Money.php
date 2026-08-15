<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Admin ekranında tutar gösterimi. Müşteri sayfasına çıkmaz.
 */
final class Money
{
    public static function format(string|int|float|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        return number_format((float) $amount, 2, ',', '.').' TL';
    }
}
