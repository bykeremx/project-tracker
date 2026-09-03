<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Kerem Mutlu',
            'email' => 'mkerem481@gmail.com',
            'password' => Hash::make('88226858Kk?'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
