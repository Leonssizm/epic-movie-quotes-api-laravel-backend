<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title'   => [
				'en' => $this->faker->sentence(),
				'ka' => fake('ka_GE')->realText(10),
			],
			'release_year'=> $this->faker->dateTimeBetween('-4 year', 'now'),
			'director'    => [
				'en'=> $this->faker->name(),
				'ka'=> fake('ka_GE')->name(),
			],
			'description' => [
				'en' => $this->faker->sentence(),
				'ka' => fake('ka_GE')->realText(10),
			],
			'user_id'     => User::factory(),
		];
	}
}
