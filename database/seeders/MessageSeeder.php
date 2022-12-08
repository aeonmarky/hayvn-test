<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Iterations for aggregated message testing
     *
     * @var int
     */
    private int $_iterations = 60;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Run test for seeder
        for($iteration = 0; $iteration <= $this->_iterations; $iteration++) {
            echo "Iteration: " . $iteration . "\r\n";
            // Create test data using factory
            Message::factory(rand(5, 15))->create();
            // add delay
            sleep(rand(1, 2));
        }

    }
}
