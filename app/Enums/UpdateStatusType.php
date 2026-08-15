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

    public function iconClasses(): string
    {
        return match ($this) {
            self::Completed => 'bg-emerald-500 ring-emerald-100',
            self::InProgress => 'bg-amber-400 ring-amber-100',
            self::Blocked => 'bg-red-500 ring-red-100',
            self::Info => 'bg-blue-500 ring-blue-100',
        };
    }

    public function cardClasses(): string
    {
        return match ($this) {
            self::Completed => 'border-emerald-100 bg-white',
            self::InProgress => 'border-amber-100 bg-white',
            self::Blocked => 'border-red-100 bg-white',
            self::Info => 'border-blue-100 bg-white',
        };
    }
}
