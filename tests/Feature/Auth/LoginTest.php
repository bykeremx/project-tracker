<?php

declare(strict_types=1);

use App\Models\User;

test('giriş sayfasında tema geçiş butonu vardır', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Tema değiştir', false)
        ->assertSee('Giriş yapın');
});

test('geçerli bilgilerle yönetici giriş yapabilir', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('hatalı şifre ile giriş reddedilir', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'yanlis',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('misafir admin paneline giremez', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('ana sayfa misafiri girişe yönlendirir', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('admin özetinde müşteri ve proje butonları vardır', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee(route('admin.clients.index'), false)
        ->assertSee(route('admin.projects.index'), false);
});
