<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Test Destinations
     *
     * @var string[]
     */
    private $_destinations = [
        'Delectus qui possimus',
        'Nemo velit dolorum',
        'Et aut aut',
        'Quidem quo similique',
        'Voluptatem aspernatur et',
        'Oui qui quo',
    ];
    /**
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Set the fake values
        return [
            'destination' => $this->_destinations[rand(0, 5)],
            'text' => $this->faker->paragraph(),
            'timestamp' => Carbon::parse(now())->toDateTimeString('millisecond')
        ];
    }
}
