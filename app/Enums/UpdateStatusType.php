<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Zaman çizelgesindeki tek bir güncellemenin tipini temsil eder.
 *
 * Proje genel durumundan (ProjectStatus) ayrı tutulur: bir proje "devam ederken"
 * hem tamamlanmış adımlar hem de engeller aynı timeline'da yer alabilir.
 */
enum UpdateStatusType: string
{
    case Completed = 'completed';
    case InProgress = 'in_progress';
    case Blocked = 'blocked';
    case Info = 'info';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Tamamlandı',
            self::InProgress => 'Devam Ediyor',
            self::Blocked => 'Engellendi',
            self::Info => 'Bilgilendirme',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Completed => 'green',
            self::InProgress => 'yellow',
            self::Blocked => 'red',
            self::Info => 'blue',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Completed => 'fa-solid fa-circle-check',
            self::InProgress => 'fa-solid fa-clock',
            self::Blocked => 'fa-solid fa-ban',
            self::Info => 'fa-solid fa-circle-info',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Completed => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-200 dark:ring-emerald-400/20',
            self::InProgress => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-400/20',
            self::Blocked => 'bg-red-50 text-red-800 ring-red-600/20 dark:bg-red-500/10 dark:text-red-200 dark:ring-red-400/20',
            self::Info => 'bg-blue-50 text-blue-800 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-200 dark:ring-blue-400/20',
        };
    }

    public function iconClasses(): string
    {
        return match ($this) {
            self::Completed => 'bg-emerald-500 text-white ring-emerald-100 dark:ring-slate-900',
            self::InProgress => 'bg-amber-400 text-slate-900 ring-amber-100 dark:ring-slate-900',
            self::Blocked => 'bg-red-500 text-white ring-red-100 dark:ring-slate-900',
            self::Info => 'bg-blue-500 text-white ring-blue-100 dark:ring-slate-900',
        };
    }

    public function cardClasses(): string
    {
        return match ($this) {
            self::Completed => 'border-emerald-200 bg-emerald-50/80 text-slate-900 dark:border-emerald-500/25 dark:bg-emerald-500/10 dark:text-slate-100',
            self::InProgress => 'border-amber-200 bg-amber-50/80 text-slate-900 dark:border-amber-500/25 dark:bg-amber-500/10 dark:text-slate-100',
            self::Blocked => 'border-red-200 bg-red-50/80 text-slate-900 dark:border-red-500/25 dark:bg-red-500/10 dark:text-slate-100',
            self::Info => 'border-blue-200 bg-blue-50/80 text-slate-900 dark:border-blue-500/25 dark:bg-blue-500/10 dark:text-slate-100',
        };
    }
}
