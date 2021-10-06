<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::factory()->create();
        return [
            'user_id' => $post->user_id,
            'post_id' => $post->id,
            'body' => $this->faker->paragraph(2),
            'stars' => $this->faker->numberBetween(0, 1000),
            'published_at' => $this->faker->dateTimeBetween($post->published_at, 'now')
        ];
    }
}
