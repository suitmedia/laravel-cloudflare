<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Suitmedia\Cloudflare\Tests\Supports\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $time = random_int(1483203600, 1530378000);

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt(Str::random(12)),
            'remember_token' => Str::random(12),
            'created_at' => '2016-01-01 00:00:00',
            'updated_at' => date('Y-m-d H:i:s', $time),
        ];
    }
}
