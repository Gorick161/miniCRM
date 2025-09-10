<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Demo-User + Rolle
    $user = \App\Models\User::factory()->create([
        'name' => 'Demo User',
        'email' => 'demo@example.com',
        'password' => bcrypt('password'),
    ]);
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    $user->assignRole('admin');

    // Pipeline + Stages
    $pipeline = \App\Models\Pipeline::create(['name' => 'Standard Sales']);
    $stages = collect(['Lead','Qualified','Proposal','Negotiation','Won'])
        ->map(fn($name, $i) => \App\Models\Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => $name,
            'position' => $i,
        ]));

    // Companies + Contacts
    $companies = \App\Models\Company::factory()->count(10)->create([
        'owner_id' => $user->id
    ]);
    $companies->each(function($company){
        \App\Models\Contact::factory()->count(rand(1,3))->create([
            'company_id' => $company->id
        ]);
    });

    // Deals
    foreach(range(1,20) as $i){
        $company = $companies->random();
        $stage = $stages->random();
        \App\Models\Deal::factory()->create([
            'pipeline_id' => $pipeline->id,
            'stage_id' => $stage->id,
            'company_id' => $company->id,
            'owner_id' => $user->id,
            'currency' => 'EUR',
        ]);
    }
}
}
