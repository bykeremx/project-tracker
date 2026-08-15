<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Models\Payment;
use App\Models\Project;

final class CreatePaymentAction
{
    /**
     * @param  array{amount: numeric-string|int|float, paid_on: string, note?: string|null}  $data
     */
    public function execute(Project $project, array $data): Payment
    {
        return $project->payments()->create([
            'amount' => $data['amount'],
            'paid_on' => $data['paid_on'],
            'note' => $data['note'] ?? null,
        ]);
    }
}
