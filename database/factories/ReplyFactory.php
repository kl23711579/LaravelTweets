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
        return [
            'user_id' => $this->faker->randomNumber(),
            'post_id' => $this->faker->randomNumber(),
            'body' => $this->faker->paragraph(2),
            'published_at' => $this->faker->dateTimeBetween()
        ];
    }

//    public function withUserId($userId = 1)
//    {
//
//    }
}
