<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       

        User::factory()->create([
            'first_name' => 'Mohammad',
            'last_name' => 'Dehghanzadeh',
            'email' => 'nikted@info.com',
            'password' => 'secret',
            'role' => 'admin',
            'owner' => true,
        ]);

        User::factory()->create([
            'first_name' => 'sales',
            'last_name' => 'manager',
            'email' => 'sales_manager@nikted.com',
            'password' => 'secret',
            'role' => 'sales_manager',
            'owner' => true,
        ]);

        User::factory()->create([
            'first_name' => 'warehouse',
            'last_name' => 'manager',
            'email' => 'warehouse_manager@nikted.com',
            'password' => 'secret',
            'role' => 'warehouse_manager',
            'owner' => true,
        ]);

        User::factory()->create([
            'first_name' => 'logistics',
            'last_name' => 'manager',
            'email' => 'logistics_manager@nikted.com',
            'password' => 'secret',
            'role' => 'logistics_manager',
            'owner' => true,
        ]);

    
    }
}
