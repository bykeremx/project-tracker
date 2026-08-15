<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Services\EarningsService;
use Illuminate\View\View;

/**
 * Özet sayfası. withCount ile N+1 oluşmaz; sayımlar tek aggregasyon sorgusudur.
 * clients/projects PK üzerinden COUNT(*) çalışır (full scan kabul edilebilir,
 * satır sayısı yönetim panelinde küçüktür).
 */
class DashboardController extends Controller
{
    public function __invoke(EarningsService $earnings): View
    {
        $hour = (int) now()->format('G');
        $greeting = match (true) {
            $hour < 12 => 'Günaydın',
            $hour < 18 => 'İyi günler',
            default => 'İyi akşamlar',
        };

        $monthlyBreakdown = $earnings->monthlyBreakdown();
        $chartMonths = array_reverse($monthlyBreakdown);
        $chartMax = max([
            1,
            ...array_map(fn (array $row): float => (float) $row['total'], $chartMonths),
        ]);

        return view('admin.dashboard', [
            'greeting' => $greeting,
            'clientCount' => Client::query()->count(),
            'projectCount' => Project::query()->count(),
            'adminCount' => User::query()->count(),
            'inProgressCount' => Project::query()->where('status', ProjectStatus::InProgress)->count(),
            'completedCount' => Project::query()->where('status', ProjectStatus::Completed)->count(),
            'monthEarned' => $earnings->monthTotal(),
            'yearEarned' => $earnings->yearTotal(),
            'outstandingTotal' => $earnings->outstandingTotal(),
            'monthlyBreakdown' => $monthlyBreakdown,
            'chartMonths' => $chartMonths,
            'chartMax' => $chartMax,
            'recentProjects' => Project::query()
                ->with('client:id,name')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
