<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UpdateStatusType;
use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectUpdate>
 */
class ProjectUpdateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status_type' => fake()->randomElement(UpdateStatusType::cases()),
            'is_public' => true,
        ];
    }

    public function private(): static
    {
        return $this->state(fn (): array => [
            'is_public' => false,
            'title' => 'İç not: '.fake()->sentence(3),
        ]);
    }
}
