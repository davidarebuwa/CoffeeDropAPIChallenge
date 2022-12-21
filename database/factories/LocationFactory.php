<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postcode' => $this->faker->postcode,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'times' => [
                'opening_times' => [
                    'monday' => $this->faker->time,
                    'tuesday' => $this->faker->time,
                    'wednesday' => $this->faker->time,
                    'thursday' => $this->faker->time,
                    'friday' => $this->faker->time,
                    'saturday' => $this->faker->time,
                    'sunday' => $this->faker->time,
                ],
                'closing_times' => [
                    'monday' => $this->faker->time,
                    'tuesday' => $this->faker->time,
                    'wednesday' => $this->faker->time,
                    'thursday' => $this->faker->time,
                    'friday' => $this->faker->time,
                    'saturday' => $this->faker->time,
                    'sunday' => $this->faker->time,
                ]
            ]
        ];
    }
}
