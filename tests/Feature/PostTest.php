<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_their_tweets()
    {
        // create user
        $user = User::factory()->create();

        // create tweet
        $post = Post::factory()->make(["user_id" => $user->id]);

        // post
        $this->actingAs($user)->post('/posts', [
            'body' => $post->body
        ]);

        $response = $this->get('/posts');

        $response->assertSee($post->body);
    }

    public function test_user_can_post_new_tweet()
    {
        // create user
        $user = User::factory()->create();

        // create tweet
        $post = Post::factory()->make(["user_id" => $user->id]);

        // post tweet
        $response = $this->actingAs($user)->post('/posts', [
            'body' => $post->body
        ]);
        $response->assertRedirect('/posts');
    }

    public function test_user_can_see_following_user_tweet()
    {
        $user = User::factory()->create();

        // create following user
        $followingUser = User::factory()->create();
        // config relationship
        $uf = new UserFollower;
        $uf->user_id = $followingUser->id;
        $uf->follower_id = $user->id;
        $uf->save();

        // create following user tweet
        $post = Post::factory()->make(['user_id' => $followingUser->id]);

        // following user post tweet
        $this->actingAs($followingUser)->post('/posts', [
            'body' => $post->body
        ]);

        $response = $this->get('/posts');
        $response->assertSee($post->body);
    }

    public function test_user_cannot_see_not_following_user_tweet()
    {
        $user = User::factory()->create();

        // create not following user
        $notFollowingUser = User::factory()->create();

        // create following user tweet
        $post = Post::factory()->make(['user_id' => $notFollowingUser->id]);

        // not following user post tweet
        $this->actingAs($notFollowingUser)->post('/posts', [
            'body' => $post->body
        ]);

        $response = $this->actingAs($user)->get('/posts');
        $response->assertDontSee($post->body);
    }

    public function test_tweets_are_arranged_in_the_order_of_new_to_old()
    {
        $user = User::factory()->create();

        // create first tweet
        $firstTweet = Post::factory()->make(['user_id' => $user->id]);
        // post first tweet
        $this->actingAs($user)->post('/posts', [
            'body' => $firstTweet->body
        ]);

        // create second tweet
        $secondTweet = Post::factory()->make(['user_id' => $user->id]);
        // post second tweet
        $this->actingAs($user)->post('/posts', [
            'body' => $secondTweet->body
        ]);

        $response = $this->actingAs($user)->get('/posts');

        $response->assertSeeInOrder([$firstTweet->body, $secondTweet->body]);
    }

    public function test_user_tweet_thread_page_can_be_rendered()
    {
        $user = User::factory()->create();

        // create user posts
        $post = Post::factory()->make(['user_id' => $user->id]);
        // post tweet
        $this->actingAs($user)->post('/posts', [
            'body' => $post->body
        ]);

        $response = $this->actingAs($user)->get('/posts/1');

        $response->assertSeeInOrder([$post->body, 'Replys', 'Post']);
    }
    // Post::factory(3)->make()->sortByDesc('published_at')

    public function test_user_can_access_their_tweet_thread_page()
    {
        $user = User::factory()->create();

        // create user posts
        $post = Post::factory()->make(['user_id' => $user->id]);
        // post tweet
        $this->actingAs($user)->post('/posts', [
            'body' => $post->body
        ]);

        $response = $this->actingAs($user)->get('/posts/1');

        $response->assertStatus(200);
    }

    public function test_user_can_access_following_user_tweet_thread_page()
    {
        $user = User::factory()->create();

        // create following user
        $followingUser = User::factory()->create();
        // config relationship
        $uf = new UserFollower;
        $uf->user_id = $followingUser->id;
        $uf->follower_id = $user->id;
        $uf->save();

        // create following user tweet
        $tweet = Post::factory()->make(['user_id' => $followingUser->id]);

        // post tweet
        $this->actingAs($followingUser)->post('/posts', [
            'body' => $tweet->body
        ]);

        $response = $this->actingAs($user)->get('/posts/1');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_not_following_users_tweet_thread_page()
    {
        $user = User::factory()->create();

        // create following user
        $followingUser = User::factory()->create();

        // create following user tweet
        $tweet = Post::factory()->make(['user_id' => $followingUser->id]);

        // post tweet
        $this->actingAs($followingUser)->post('/posts', [
            'body' => $tweet->body
        ]);

        $response = $this->actingAs($user)->get('/posts/1');
        ;

        $response->assertStatus(404);
    }
}
