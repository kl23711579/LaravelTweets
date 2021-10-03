<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Frank",
            'twitter_id' => '2713709624',
            'nickname' => 'kl23711579',
            'email' => 'frank@frank.com',
            'password' => bcrypt('password')
        ]);

        Post::factory(10)->create();


    }
}
