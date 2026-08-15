<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'title' => fake()->sentence(3),
            'access_token' => Str::random(64),
            'status' => ProjectStatus::InProgress,
            'start_date' => now()->subDays(10)->toDateString(),
            'target_completion_date' => now()->addDays(20)->toDateString(),
            'actual_completion_date' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (): array => [
            'status' => ProjectStatus::Completed,
            'actual_completion_date' => now()->toDateString(),
        ]);
    }

    public function onHold(): static
    {
        return $this->state(fn (): array => [
            'status' => ProjectStatus::OnHold,
        ]);
    }
}
