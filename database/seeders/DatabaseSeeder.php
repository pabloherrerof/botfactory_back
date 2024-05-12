<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


       User::factory()->create([
            'name' => 'User 1',
            'email' => 'user1@botfactory.com',
            'password' => Hash::make("password1"),
        ]);

        User::factory()->create([
            'name' => 'User 2',
            'email' => 'user2@botfactory.com',
            'password' => Hash::make("password2"),
        ]);

        User::factory()->create([
            'name' => 'User 3',
            'email' => 'user3@botfactory.com',
            'password' => Hash::make("password3"),
        ]);

        Category::create([
            'name' => 'A',
        ]);

        Category::create([
            'name' => 'B',
        ]);

        Category::create([
            'name' => 'C',
        ]);

        Client::factory()->count(200)->create();
    }
}
