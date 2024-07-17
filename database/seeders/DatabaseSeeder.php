<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1000)
            ->afterCreating(
                static fn ($user) => Order::factory(mt_rand(1, 5))
                    ->for($user)
                    ->create()
            )
            ->create();
    }
}
