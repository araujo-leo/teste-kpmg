<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'resolved']),
            'description' => $this->faker->paragraph(),
            'attachment_path' => null,
        ];
    }
}
