<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Project;
use App\Services\EarningsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Ay ay tahsilat defteri. Toplamlar payments.paid_on üzerinden hesaplanır.
 */
class EarningsController extends Controller
{
    public function index(Request $request, EarningsService $earnings): View
    {
        $this->authorize('viewAny', Project::class);

        $year = $request->integer('year') ?: (int) now()->year;

        abort_unless($year >= 2000 && $year <= 2100, 404);

        $years = $earnings->availableYears();

        if (! in_array($year, $years, true)) {
            $years[] = $year;
            rsort($years);
        }

        return view('admin.earnings.index', [
            'year' => $year,
            'years' => $years,
            'months' => $earnings->yearMonths($year),
            'yearTotal' => $earnings->yearTotal($year),
        ]);
    }

    public function show(int $year, int $month, EarningsService $earnings): View
    {
        $this->authorize('viewAny', Project::class);

        $current = $earnings->resolveMonth($year, $month);
        $previous = $current->copy()->subMonth();
        $next = $current->copy()->addMonth();

        $payments = Payment::query()
            ->with([
                'project:id,client_id,title',
                'project.client:id,name,company_name',
            ])
            ->whereBetween('paid_on', [
                $current->toDateString(),
                $current->copy()->endOfMonth()->toDateString(),
            ])
            ->orderByDesc('paid_on')
            ->orderByDesc('id')
            ->cursorPaginate(20)
            ->withQueryString();

        return view('admin.earnings.show', [
            'current' => $current,
            'previous' => $previous,
            'next' => $next,
            'total' => $earnings->monthTotal($current),
            'payments' => $payments,
        ]);
    }
}
