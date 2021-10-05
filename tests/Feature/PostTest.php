<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_see_their_tweets()
    {
        $user = User::factory()->create();
        Post::factory(5)->create(["user_id" => $user->id]);
        $this->be($user);
        $response = $this->get('/posts');
        $post = Post::first();

        $response->assertSee($post->body);
    }

    public function test_user_can_post_new_tweet()
    {
        $user = User::factory()->create();
        $this->be($user);

        $post = Post::factory()->make();
        $response = $this->post('/posts', [
            'body' => $post->body
        ]);
        $response->assertRedirect('/posts');
    }

    public function test_user_can_see_their_new_tweet()
    {
        $user = User::factory()->create();
        $this->be($user);

        $post = Post::factory()->make();
        $this->post('/posts', [
            'body' => $post->body
        ]);
        $response = $this->get('/posts');
        $response->assertSee($post->body);
    }

    public function test_user_can_see_following_user_tweet()
    {
        $user = User::factory()->create();
        $this->be($user);

        // create user posts
        Post::factory(3)->create(['user_id' => $user->id]);

        // create following user
        $followingUser = User::factory()->create();
        // config relationship
        $uf = new UserFollower;
        $uf->user_id = $followingUser->id;
        $uf->follower_id = $user->id;
        $uf->save();

        // create following user tweet
        $posts = Post::factory(3)->create(['user_id' => $followingUser->id]);

        $response = $this->get('/posts');
        $response->assertSee($posts->first()->body);
    }

    public function test_user_cannot_see_not_following_user_tweet()
    {
        $user = User::factory()->create();
        $this->be($user);

        // create user posts
        Post::factory(3)->create(['user_id' => $user->id]);

        // create following user
        $followingUser = User::factory()->create();

        // create following user tweet
        $posts = Post::factory(3)->create(['user_id' => $followingUser->id]);

        $response = $this->get('/posts');
        $response->assertDontSee($posts->first()->body);
    }
}
