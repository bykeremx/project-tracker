<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Proje genel durumunu temsil eder.
 *
 * Neden enum?
 * Serbest string yerine sabit değerler kullanarak geçersiz durum yazılmasını
 * derleme/çalışma anında engelleriz. Blade ve Action katmanı da bu tipi paylaşır.
 */
enum ProjectStatus: string
{
    case Draft = 'draft';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Taslak',
            self::InProgress => 'Devam Ediyor',
            self::Completed => 'Tamamlandı',
            self::OnHold => 'Beklemede',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700 ring-slate-600/10 dark:bg-slate-800 dark:text-slate-200 dark:ring-white/10',
            self::InProgress => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-400/20',
            self::Completed => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-200 dark:ring-emerald-400/20',
            self::OnHold => 'bg-orange-50 text-orange-800 ring-orange-600/20 dark:bg-orange-500/10 dark:text-orange-200 dark:ring-orange-400/20',
        };
    }
}
