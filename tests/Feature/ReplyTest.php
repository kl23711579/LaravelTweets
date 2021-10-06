<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\UserFollower;
use Database\Factories\ReplyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_users_can_post_reply_in_their_tweet()
    {
        $user = User::factory()->create();

        // create user posts
        $post = Post::factory()->create(['user_id' => $user->id]);

        // post reply
        $reponse = $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertRedirect('/posts/'.$post->id);
    }

    public function test_users_can_post_reply_in_following_user_tweet()
    {
        $user = User::factory()->create();

        // create user posts
        Post::factory()->create(['user_id' => $user->id]);

        // create following user
        $followingUser = User::factory()->create();
        // config relationship
        $uf = new UserFollower;
        $uf->user_id = $followingUser->id;
        $uf->follower_id = $user->id;
        $uf->save();

        // create following user tweet
        $post = Post::factory()->create(['user_id' => $followingUser->id]);

        // post reply
        $reponse = $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertRedirect('/posts/'.$post->id);
    }

    public function test_users_cannot_post_reply_in_not_following_user_tweet()
    {
        $user = User::factory()->create();

        // create user posts
        Post::factory()->create(['user_id' => $user->id]);

        // create following user
        $notFollowingUser = User::factory()->create();

        // create following user tweet
        $post = Post::factory()->create(['user_id' => $notFollowingUser->id]);

        // post reply
        $reponse = $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertStatus(404);
    }

    public function test_users_cannot_post_reply_in_nonexist_tweet()
    {
        $user = User::factory()->create();

        // post reply
        $reponse = $this->actingAs($user)->post('/posts/1/replies', [
            'user_id' => $user->id,
            'post_id' => 1,
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertStatus(404);
    }
}
