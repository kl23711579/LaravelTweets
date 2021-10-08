<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Reply;
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
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertStatus(404);
    }

    public function test_users_cannot_post_reply_in_nonexist_tweet()
    {
        $user = User::factory()->create();

        // post reply
        $reponse = $this->actingAs($user)->post('/posts/1/replies', [
            'body' => $this->faker->paragraph(2)
        ]);

        $reponse->assertStatus(404);
    }

    public function test_users_can_see_reply()
    {
        $user = User::factory()->create();

        // create user posts
        $post = Post::factory()->create(['user_id' => $user->id]);

        // create reply
        $reply = Reply::factory()->make([
            'body' => $this->faker->paragraph(2)
        ]);

        // post reply
        $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'body' => $reply->body
        ]);

        $reponse = $this->actingAs($user)->get('/posts/' . $post->id);

        $reponse->assertSee($reply->body);

    }

    public function test_users_can_see_their_reply_in_following_user_tweet()
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
        $post = Post::factory()->create(['user_id' => $followingUser->id]);

        // create reply
        $reply = Reply::factory()->make([
            'body' => $this->faker->paragraph(2)
        ]);
        $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'body' => $reply->body
        ]);

        $reponse = $this->actingAs($user)->get('/posts/' . $post->id);

        $reponse->assertSee($reply->body);

    }

    public function test_replies_are_arranged_in_the_order_of_new_to_old()
    {
        $user = User::factory()->create();

        // create user posts
        $post = Post::factory()->create(['user_id' => $user->id]);

        // first reply
        $firstReply = Reply::factory()->make([
            'body' => $this->faker->paragraph(2),
        ]);
        $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'body' => $firstReply->body
        ]);

        // second reply
        $secondReply = Reply::factory()->make([
            'body' => $this->faker->paragraph(2),
        ]);
        $this->actingAs($user)->post('/posts/'.$post->id.'/replies', [
            'body' => $secondReply->body
        ]);

        $reponse = $this->actingAs($user)->get('/posts/' . $post->id);

        $reponse->assertSeeInOrder([$firstReply->body, $secondReply->body]);
    }
}
