<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Suitmedia\Cloudflare\Tests\Supports\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @throws \Exception
     *
     * @return array
     */
    public function definition()
    {
        $time = random_int(1483203600, 1530378000);

        return [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph(2),
            'content'     => implode("\n<br />\n", $this->faker->paragraphs(10)),
            'created_at'  => '2016-01-01 00:00:00',
            'updated_at'  => date('Y-m-d H:i:s', $time),
        ];
    }
}
