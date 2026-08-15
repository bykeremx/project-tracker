<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;

test('geçerli token ile müşteri sayfası açılır', function () {
    $project = Project::factory()->create(['title' => 'Canlı Proje']);
    $project->load('client');

    $this->get(route('status.show', $project->access_token))
        ->assertOk()
        ->assertSee('Canlı Proje')
        ->assertSee($project->client->name);
});

test('geçersiz token 404 döner', function () {
    $this->get('/status/'.str_repeat('x', 64))->assertNotFound();
});

test('müşteri yalnızca herkese açık güncellemeleri görür', function () {
    $project = Project::factory()->create();

    ProjectUpdate::factory()->for($project)->create([
        'title' => 'Yayına alındı',
        'is_public' => true,
    ]);

    ProjectUpdate::factory()->for($project)->private()->create([
        'title' => 'İç not gizli kalmalı',
    ]);

    $this->get(route('status.show', $project->access_token))
        ->assertOk()
        ->assertSee('Yayına alındı')
        ->assertDontSee('İç not gizli kalmalı');
});

test('müşteri ekranı yazma işlemi sunmaz', function () {
    $project = Project::factory()->create();

    $this->get(route('status.show', $project->access_token))
        ->assertOk()
        ->assertDontSee('Adımı kaydet')
        ->assertDontSee(route('admin.projects.updates.store', $project));
});

test('giriş yapmamış kullanıcı admin güncellemesi ekleyemez', function () {
    $project = Project::factory()->create();

    $this->post(route('admin.projects.updates.store', $project), [
        'title' => 'Yetkisiz',
        'status_type' => 'info',
    ])->assertRedirect(route('login'));
});

test('admin gizli notu kendi panelinde görür', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    ProjectUpdate::factory()->for($project)->private()->create([
        'title' => 'İç not gizli kalmalı',
    ]);

    $this->actingAs($user)
        ->get(route('admin.projects.show', $project))
        ->assertOk()
        ->assertSee('İç not gizli kalmalı');
});
