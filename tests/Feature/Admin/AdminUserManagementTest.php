<?php

declare(strict_types=1);

use App\Models\User;

test('yönetici listesi görüntülenir', function () {
    $user = User::factory()->create(['name' => 'Mevcut Yönetici']);

    $this->actingAs($user)
        ->get(route('admin.admins.index'))
        ->assertOk()
        ->assertSee('Mevcut Yönetici')
        ->assertSee('Yeni yönetici');
});

test('yeni yönetici eklenebilir', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.admins.store'), [
            'name' => 'Selin Kaya',
            'email' => 'selin@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ])
        ->assertRedirect(route('admin.admins.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'Selin Kaya',
        'email' => 'selin@example.com',
    ]);
});

test('aynı e-posta ile yönetici eklenemez', function () {
    $user = User::factory()->create(['email' => 'admin@example.com']);

    $this->actingAs($user)
        ->post(route('admin.admins.store'), [
            'name' => 'Kopya',
            'email' => 'admin@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ])
        ->assertSessionHasErrors('email');
});

test('yönetici güncellenebilir', function () {
    $actor = User::factory()->create();
    $admin = User::factory()->create(['name' => 'Eski Ad']);

    $this->actingAs($actor)
        ->put(route('admin.admins.update', $admin), [
            'name' => 'Yeni Ad',
            'email' => $admin->email,
        ])
        ->assertRedirect(route('admin.admins.index'));

    expect($admin->fresh()->name)->toBe('Yeni Ad');
});

test('başka yönetici silinebilir', function () {
    $actor = User::factory()->create();
    $admin = User::factory()->create();

    $this->actingAs($actor)
        ->delete(route('admin.admins.destroy', $admin))
        ->assertRedirect(route('admin.admins.index'));

    $this->assertDatabaseMissing('users', ['id' => $admin->id]);
});

test('kendi yönetici kaydı silinemez', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.admins.destroy', $user))
        ->assertForbidden();

    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

test('giriş yapmayan yönetici ekleyemez', function () {
    $this->post(route('admin.admins.store'), [
        'name' => 'Yetkisiz',
        'email' => 'yetkisiz@example.com',
        'password' => 'password12',
        'password_confirmation' => 'password12',
    ])->assertRedirect(route('login'));
});
