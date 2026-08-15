<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Support\Money;

test('tahsilat yılları listelenir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['title' => 'Kurumsal Site']);

    Payment::factory()->for($project)->create([
        'amount' => 15000,
        'paid_on' => '2026-03-15',
        'note' => 'Kapora',
    ]);

    $this->actingAs($user)
        ->get(route('admin.earnings.index', ['year' => 2026]))
        ->assertOk()
        ->assertSee('Mart')
        ->assertSee(Money::format('15000.00'), false);
});

test('ay detayında tahsilat satırları görünür', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['title' => 'Kurumsal Site']);

    Payment::factory()->for($project)->create([
        'amount' => 15000,
        'paid_on' => '2026-03-15',
        'note' => 'Kapora',
    ]);

    Payment::factory()->for($project)->create([
        'amount' => 8000,
        'paid_on' => '2026-04-02',
        'note' => 'Nisan taksiti',
    ]);

    $this->actingAs($user)
        ->get(route('admin.earnings.show', ['year' => 2026, 'month' => '03']))
        ->assertOk()
        ->assertSee('Kurumsal Site')
        ->assertSee('Kapora')
        ->assertSee(Money::format('15000.00'), false)
        ->assertDontSee('Nisan taksiti');
});

test('geçersiz ay 404 döner', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/earnings/2026/13')
        ->assertNotFound();
});

test('giriş yapmayan tahsilat sayfasını göremez', function () {
    $this->get(route('admin.earnings.index'))
        ->assertRedirect(route('login'));
});
