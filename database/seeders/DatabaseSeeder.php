<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Star;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Database\Seeder;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // default users
        User::create([
            'name' => "Frank",
            'twitter_id' => '2713709624',
            'nickname' => 'kl23711579',
            'email' => 'frank@frank.com',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => "Cristal",
            'twitter_id' => '2367020082',
            'nickname' => 'ls23711579',
            'email' => 'franklllsss@frank.com',
            'password' => bcrypt('password')
        ]);

        // create 10 new users
        User::factory(10)->create();

        $users = User::all();

        // each user have 5 post
        $users->map(function ($user) {
            $posts = Post::factory(5)->create(['user_id' => $user->id]);
            //each post create star
            $posts->map(function($post) {
                Star::create(['post_id' => $post->id]);
            });
        });

        // create follower relationship
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

        $faker = Factory::create();

        // create reply
        $users->map(function($user) use ($faker){
            // get following user
            $followingUser = $user->followings->pluck('id');

            // get self and following posts
            Post::where('user_id', $user->id)
                ->orWhereIn('user_id', $followingUser)
                ->get()
                ->map(function($post) use ($user, $faker){
                    // reply
                    for($i=0; $i<3; $i++) {
                        if(1) {
                            Reply::create([
                                'user_id' => $user->id,
                                'post_id' => $post->id,
                                'body' => $faker->paragraph(2),
                                'published_at' => $faker->dateTimeBetween($post->published_at, 'now')
                            ]);
                        }
                    }
                });
        });


    }
}
