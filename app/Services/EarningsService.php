<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Carbon;

/**
 * Kazanç özetleri tahsilat satırlarından hesaplanır; ayrı ay tablosu yoktur.
 */
final class EarningsService
{
    public function monthTotal(?Carbon $month = null): string
    {
        $month ??= now();

        $total = Payment::query()
            ->whereBetween('paid_on', [
                $month->copy()->startOfMonth()->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ])
            ->sum('amount');

        return $this->normalize($total);
    }

    public function yearTotal(?int $year = null): string
    {
        $year ??= (int) now()->year;

        $total = Payment::query()
            ->whereBetween('paid_on', [
                Carbon::create($year, 1,1)?->toDateString(), // 2026 'nın ilk günü ()
                Carbon::create($year, 12, 31)?->toDateString(), // 2026 'nın son günü (31 aralık 2026)
            ])
            ->sum('amount');

        return $this->normalize($total);
    }

    public function outstandingTotal(): string
    {
        $remaining = Project::query()
            ->whereNotNull('agreed_budget')
            ->withSum('payments', 'amount')
            ->get(['id', 'agreed_budget'])
            ->sum(fn (Project $project): float => max(
                (float) $project->agreed_budget - (float) $project->collectedAmount(),
                0,
            ));

        return $this->normalize($remaining);
    }

    /**
     * @return list<array{key: string, year: int, month: string, label: string, total: string}>
     */
    public function monthlyBreakdown(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $totals = Payment::query()
            ->where('paid_on', '>=', $start->toDateString())
            ->get(['paid_on', 'amount'])
            ->groupBy(fn (Payment $payment): string => $payment->paid_on->format('Y-m'))
            ->map(fn ($group): string => $this->normalize($group->sum('amount')));

        $rows = [];

        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');

            $rows[] = [
                'key' => $key,
                'year' => (int) $month->format('Y'),
                'month' => $month->format('m'),
                'label' => $month->translatedFormat('F Y'),
                'total' => $totals[$key] ?? '0.00',
            ];
        }

        return array_reverse($rows);
    }

    /**
     * @return list<int>
     */
    public function availableYears(): array
    {
        $current = (int) now()->year;
        $minDate = Payment::query()->min('paid_on');

        if ($minDate === null) {
            return [$current];
        }

        $start = (int) Carbon::parse((string) $minDate)->year;
        $maxDate = Payment::query()->max('paid_on');
        $end = max((int) Carbon::parse((string) $maxDate)->year, $current);

        return range($end, $start);
    }

    /**
     * @return list<array{year: int, month: string, label: string, total: string, count: int}>
     */
    public function yearMonths(int $year): array
    {
        $start = Carbon::create($year, 1, 1) ?? now()->setYear($year)->startOfYear();
        $end = $start->copy()->endOfYear();

        $grouped = Payment::query()
            ->whereBetween('paid_on', [$start->toDateString(), $end->toDateString()])
            ->get(['paid_on', 'amount'])
            ->groupBy(fn (Payment $payment): string => $payment->paid_on->format('m'));

        $rows = [];

        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $month = Carbon::create($year, $monthNumber, 1) ?? $start->copy()->month($monthNumber);
            $key = $month->format('m');
            $group = $grouped->get($key, collect());

            $rows[] = [
                'year' => $year,
                'month' => $key,
                'label' => $month->translatedFormat('F'),
                'total' => $this->normalize($group->sum('amount')),
                'count' => $group->count(),
            ];
        }

        return $rows;
    }

    public function resolveMonth(int $year, int $month): Carbon
    {
        abort_unless($year >= 2000 && $year <= 2100 && $month >= 1 && $month <= 12, 404);

        return Carbon::create($year, $month, 1)?->startOfMonth() ?? abort(404);
    }

    private function normalize(string|int|float $amount): string
    {
        return number_format((float) $amount, 2, '.', '');
    }
}
