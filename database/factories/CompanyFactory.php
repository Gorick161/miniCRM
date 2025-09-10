<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'   => $this->faker->company(),
            'domain' => $this->faker->domainName(),
            'phone'  => $this->faker->phoneNumber(),
            'notes'  => $this->faker->optional()->paragraph(),
            'owner_id' => null, // will be setted in seeder
        ];
    }
}
