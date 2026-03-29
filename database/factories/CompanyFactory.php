<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'corporate_name' => $this->faker->company() . ' LTDA',
            'user_id' => User::factory(),
        ];
    }
}
