<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\DeletePaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function store(
        StorePaymentRequest $request,
        Project $project,
        CreatePaymentAction $createPayment,
    ): RedirectResponse {
        $this->authorize('update', $project);

        $createPayment->execute($project, $request->validated());

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Tahsilat kaydedildi.');
    }

    public function destroy(
        Project $project,
        Payment $payment,
        DeletePaymentAction $deletePayment,
    ): RedirectResponse {
        $this->authorize('update', $project);

        abort_unless($payment->project_id === $project->id, 404);

        $deletePayment->execute($payment);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Tahsilat silindi.');
    }
}
