<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Repositories\Zoom\Enum\MeetingType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid'=>$this->faker->uuid(),
            'id'=>$this->faker->randomDigit(),
            'host_id'=>$this->faker->randomDigit(),
            'host_email'=>$this->faker->email(),
            'topic'=>$this->faker->text(),
            'type'=>enum(MeetingType::RecurringMeetingFixedTime),
            'timezone'=>config('app.timezone'),
            'start_url'=>$this->faker->url(),
            'join_url'=>$this->faker->url(),
            'password'=>Hash::make($this->faker->password(6,8)),
            'encrypted_password'=>'Wk96MUNuampMUlNDQXRFUnRTaFU1QT09',
            'h323_password'=>$this->faker->randomDigit()
        ];
    }
}
