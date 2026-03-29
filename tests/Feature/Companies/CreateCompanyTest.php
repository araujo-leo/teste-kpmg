<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Create a company', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/companies', [
        'name' => 'KPMG',
        'cnpj' => '12.345.678/0001-99',
        'email' => 'contact@kpmg.com',
        'corporate_name' => 'KPMG International Limited',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('companies', ['name' => 'KPMG']);
});

it('Create a company with no data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/companies', []);

    $response->assertSessionHasErrors(['name', 'cnpj', 'email']);
});
