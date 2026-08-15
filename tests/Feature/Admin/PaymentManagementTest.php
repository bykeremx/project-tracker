<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Support\Money;

test('proje anlaşılan bütçe ile oluşturulabilir', function () {
    $user = User::factory()->create();
    $projectData = Project::factory()->make();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'client_id' => $projectData->client_id,
            'title' => 'Bütçeli Site',
            'start_date' => '2026-01-10',
            'target_completion_date' => '2026-03-10',
            'agreed_budget' => '45000.50',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('projects', [
        'title' => 'Bütçeli Site',
        'agreed_budget' => 45000.50,
    ]);
});

test('projeye tahsilat eklenebilir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['agreed_budget' => 40000]);

    $this->actingAs($user)
        ->post(route('admin.projects.payments.store', $project), [
            'amount' => '15000.00',
            'paid_on' => '2026-03-15',
            'note' => 'Kapora',
        ])
        ->assertRedirect(route('admin.projects.show', $project));

    $this->assertDatabaseHas('payments', [
        'project_id' => $project->id,
        'amount' => 15000,
        'note' => 'Kapora',
    ]);

    expect($project->fresh()->collectedAmount())->toBe('15000.00')
        ->and($project->fresh()->remainingAmount())->toBe('25000.00');
});

test('sıfır tutarlı tahsilat reddedilir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.payments.store', $project), [
            'amount' => '0',
            'paid_on' => '2026-03-15',
        ])
        ->assertSessionHasErrors('amount');
});

test('tahsilat silinebilir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $payment = Payment::factory()->for($project)->create([
        'amount' => 5000,
        'note' => 'Silinecek kayıt',
    ]);

    $this->actingAs($user)
        ->delete(route('admin.projects.payments.destroy', [$project, $payment]))
        ->assertRedirect(route('admin.projects.show', $project));

    $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
});

test('başka projeye ait tahsilat silinemez', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $otherPayment = Payment::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.projects.payments.destroy', [$project, $otherPayment]))
        ->assertNotFound();
});

test('özet sayfası bu ayın tahsilatını gösterir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['agreed_budget' => 40000]);

    Payment::factory()->for($project)->create([
        'amount' => 15000,
        'paid_on' => now()->toDateString(),
    ]);

    Payment::factory()->for($project)->create([
        'amount' => 8000,
        'paid_on' => now()->subMonth()->toDateString(),
    ]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee(Money::format('15000.00'), false)
        ->assertSee(Money::format('23000.00'), false);
});
