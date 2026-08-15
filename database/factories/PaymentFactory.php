<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'amount' => fake()->randomFloat(2, 1000, 25000),
            'paid_on' => now()->toDateString(),
            'note' => fake()->optional()->sentence(3),
        ];
    }
}
