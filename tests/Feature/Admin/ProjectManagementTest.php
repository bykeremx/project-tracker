<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Enums\UpdateStatusType;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;

test('yeni projede 64 karakterlik benzersiz token üretilir', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'client_id' => $client->id,
            'title' => 'Yeni Site',
            'start_date' => '2026-01-10',
            'target_completion_date' => '2026-03-10',
        ])
        ->assertRedirect();

    $project = Project::query()->first();

    expect($project)->not->toBeNull()
        ->and($project->access_token)->toHaveLength(64)
        ->and($project->status)->toBe(ProjectStatus::InProgress);
});

test('bitiş tarihi başlangıçtan önce olamaz', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'client_id' => $client->id,
            'title' => 'Hatalı Tarih',
            'start_date' => '2026-03-10',
            'target_completion_date' => '2026-01-10',
        ])
        ->assertSessionHasErrors('target_completion_date');
});

test('proje tamamlandı işaretlenince gerçek bitiş tarihi yazılır', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.projects.status', $project), [
            'status' => ProjectStatus::Completed->value,
        ])
        ->assertRedirect(route('admin.projects.show', $project));

    $project->refresh();

    expect($project->status)->toBe(ProjectStatus::Completed)
        ->and($project->actual_completion_date)->not->toBeNull();
});

test('proje beklemeye alınabilir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.projects.status', $project), [
            'status' => ProjectStatus::OnHold->value,
        ]);

    expect($project->fresh()->status)->toBe(ProjectStatus::OnHold);
});

test('projeye güncelleme eklenebilir', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.updates.store', $project), [
            'title' => 'API geliştirildi',
            'description' => 'Kimlik doğrulama tamamlandı.',
            'status_type' => UpdateStatusType::Completed->value,
            'is_public' => '1',
        ])
        ->assertRedirect(route('admin.projects.show', $project));

    $this->assertDatabaseHas('project_updates', [
        'project_id' => $project->id,
        'title' => 'API geliştirildi',
        'is_public' => 1,
    ]);
});

test('token formdan gönderilse bile sistem kendi tokenını üretir', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.projects.store'), [
            'client_id' => $client->id,
            'title' => 'Güvenli Token',
            'start_date' => '2026-01-10',
            'target_completion_date' => '2026-03-10',
            'access_token' => str_repeat('a', 64),
        ]);

    $project = Project::query()->first();

    expect($project->access_token)->not->toBe(str_repeat('a', 64));
});
