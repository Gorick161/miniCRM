<?php

namespace Database\Factories;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition(): array
    {
        return [
            'pipeline_id' => null, // Seeder
            'stage_id'    => null, // Seeder 
            'company_id'  => null, // Seeder 
            'owner_id'    => null, // Seeder 
            'title'       => $this->faker->catchPhrase(),
            'value_cents' => $this->faker->numberBetween(5_000, 250_000),
            'currency'    => 'EUR',
            'probability' => $this->faker->numberBetween(5, 80),
            'status'      => 'open',
            'expected_close_date' => $this->faker->optional()->date(),
        ];
    }
}
