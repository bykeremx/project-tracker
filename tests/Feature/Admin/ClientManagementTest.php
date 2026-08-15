<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Project;
use App\Models\User;

test('müşteri listesinden projeye gidilir', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['name' => 'Deneme Müşteri']);
    $project = Project::factory()->for($client)->create();

    $this->actingAs($user)
        ->get(route('admin.clients.index'))
        ->assertOk()
        ->assertSee('Projeye git')
        ->assertSee(route('admin.projects.show', $project), false);
});

test('yönetici müşteri oluşturabilir', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.clients.store'), [
            'name' => 'Ayşe Demir',
            'email' => 'ayse@example.com',
            'company_name' => 'Demir Ltd.',
        ])
        ->assertRedirect(route('admin.clients.index'));

    $this->assertDatabaseHas('clients', [
        'name' => 'Ayşe Demir',
        'email' => 'ayse@example.com',
        'company_name' => 'Demir Ltd.',
    ]);
});

test('müşteri adı zorunludur', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.clients.store'), [
            'name' => '',
        ])
        ->assertSessionHasErrors('name');
});

test('yönetici müşteriyi güncelleyebilir', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['name' => 'Eski Ad']);

    $this->actingAs($user)
        ->put(route('admin.clients.update', $client), [
            'name' => 'Yeni Ad',
            'email' => $client->email,
            'company_name' => $client->company_name,
        ])
        ->assertRedirect(route('admin.clients.index'));

    expect($client->fresh()->name)->toBe('Yeni Ad');
});

test('müşteri silinince bağlı projeler de silinir', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $project = Project::factory()->for($client)->create();

    $this->actingAs($user)
        ->delete(route('admin.clients.destroy', $client))
        ->assertRedirect(route('admin.clients.index'));

    $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

test('müşteri oluşturma dakikada 20 istekle sınırlanır', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    for ($i = 0; $i < 20; $i++) {
        $this->post(route('admin.clients.store'), [
            'name' => "Müşteri {$i}",
        ])->assertRedirect(route('admin.clients.index'));
    }

    $this->post(route('admin.clients.store'), [
        'name' => 'Fazla istek',
    ])->assertStatus(429);
});
