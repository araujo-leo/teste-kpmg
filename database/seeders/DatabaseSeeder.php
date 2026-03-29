<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $company = Company::factory()->create([
            'name' => 'Test Company',
            'corporate_name' => 'Test Company LTDA',
            'user_id' => $user->id,
            'email' => 'test@company.com',
        ]);

        $project = Project::factory()->create([
            'name' => 'Test Project',
            'company_id' => $company->id,
            'manager_id' => $user->id,
        ]);
    }
}
