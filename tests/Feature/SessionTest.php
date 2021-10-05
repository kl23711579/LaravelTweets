<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee("Login With Twitter");
    }

    public function test_login_screen_cannot_be_rendered_when_user_login()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        // cannot see
        $response->assertDontSee("Login With Twitter");

        // redirect
        $response->assertRedirect('/posts');
    }

    public function test_user_can_see_logout_button_after_login()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/posts');

        $response->assertSee('Logout');
    }

    public function test_tweets_timeline_cannot_be_rendered_after_user_logout()
    {
        $user = User::factory()->create();
        $posts = Post::factory(5)->create();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertDontsee($posts->first()->body);
    }

    public function test_user_will_be_redirected_to_login_page_after_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
    }


}
