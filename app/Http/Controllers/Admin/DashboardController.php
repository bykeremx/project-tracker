<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use Illuminate\View\View;

/**
 * Özet sayfası. withCount ile N+1 oluşmaz; sayımlar tek aggregasyon sorgusudur.
 * clients/projects PK üzerinden COUNT(*) çalışır (full scan kabul edilebilir,
 * satır sayısı yönetim panelinde küçüktür).
 */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'clientCount' => Client::query()->count(),
            'projectCount' => Project::query()->count(),
            'inProgressCount' => Project::query()->where('status', ProjectStatus::InProgress)->count(),
            'completedCount' => Project::query()->where('status', ProjectStatus::Completed)->count(),
            'recentProjects' => Project::query()
                ->with('client:id,name')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
