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

        // create not following user
        $notFollowingUser = User::factory()->create();

        // create following user tweet
        $posts = Post::factory(3)->create(['user_id' => $notFollowingUser->id]);

        $response = $this->get('/posts');
        $response->assertDontSee($posts->first()->body);
    }

    public function test_tweets_are_arranged_in_the_order_of_new_to_old()
    {
        $user = User::factory()->create();

        // create following user
        $followingUser = User::factory()->create();
        // config relationship
        $uf = new UserFollower;
        $uf->user_id = $followingUser->id;
        $uf->follower_id = $user->id;
        $uf->save();

        // create tweets
        $posts = Post::factory()
                        ->count(6)
                        ->state(new Sequence(
                            ['user_id' => $user->id],
                            ['user_id' => $followingUser->id],
                        ))
                        ->create()
                        ->sortByDesc('published_at');

        $firstOrderPost = $posts->shift();
        $secondOrderPost = $posts->shift();

        $response = $this->actingAs($user)->get('/posts');

        $response->assertSeeInOrder([$firstOrderPost->body, $secondOrderPost->body]);
    }

    public function test_user_tweet_thread_page_can_be_rendered()
    {
        $user = User::factory()->create();

        // create user posts
        $posts = Post::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/posts/'.$posts->first()->id);

        $response->assertSeeInOrder([$posts->first()->body, 'Replys', 'Post']);
    }
    // Post::factory(3)->make()->sortByDesc('published_at')

    public function test_user_can_access_their_tweet_thread_page()
    {
        $user = User::factory()->create();

        // create user posts
        $posts = Post::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/posts/'.$posts->first()->id);

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
        $posts = Post::factory(3)->create(['user_id' => $followingUser->id]);

        $response = $this->actingAs($user)->get('/posts/'.$posts->first()->id);

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_not_following_users_tweet_thread_page()
    {
        $user = User::factory()->create();

        // create not following user
        $notFollowingUser = User::factory()->create();

        // create following user tweet
        $posts = Post::factory(3)->create(['user_id' => $notFollowingUser->id]);

        $response = $this->actingAs($user)->get('/posts/'.$posts->first()->id);

        $response->assertStatus(404);
    }
}
