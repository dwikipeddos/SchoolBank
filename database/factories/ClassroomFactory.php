<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'class' => $this->faker->randomElement(['X', 'XII', 'XI']),
            'name' => $this->faker->randomElement(['IPA', 'IPS']) . " " . $this->faker->numberBetween(1, 10),
            'year' => $this->faker->year(),
            'school_id' => School::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
