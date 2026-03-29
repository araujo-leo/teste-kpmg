<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->sentence(3),
            'code' => strtoupper($this->faker->unique()->lexify('???-####')),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed', 'paused']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'manager_id' => User::factory(),
            'starts_at' => $this->faker->date(),
        ];
    }
}
