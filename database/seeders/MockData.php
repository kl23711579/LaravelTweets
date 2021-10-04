<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserFollower;
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

        $users->map(function ($user) {
            Post::factory(5)->create(['user_id' => $user->id]);
        });

        $users->map(function($user) use ($users) {
            foreach($users as $u) {
                if($user->id !== $u->id) {
                    if(rand(0,1)){
                        UserFollower::create([
                            "user_id" => $user->id,
                            "follower_id" => $u->id
                        ]);
                    }
                }
            }
        });
    }

}
