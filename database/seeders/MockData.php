<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Post;

class MockData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = User::all();
        $users->map(fn($user) => Post::factory(5)->create(['user_id' => $user->id]));
    }

}
