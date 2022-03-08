<?php

namespace Database\Factories;

use App\Models\Occurrence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class OccurrenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'occurrence_id'=>$this->faker->uuid(),
            'start_time'=>now(),
            'duration'=>$this->faker->randomDigit(),
            'status'=>'available'

        ];
    }
}
