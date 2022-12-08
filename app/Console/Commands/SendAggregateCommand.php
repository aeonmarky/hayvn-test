<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Artisan command that sends the aggregated
 * messages to destination aggregated messages
 * endpoint
 *
 * Class SendAggregateCommand
 * @package App\Console\Commands
 */
class SendAggregateCommand extends Command
{
    /**
     * The aggregated message payload
     *
     * @var array
     */
    private $_payload = [];

    /**
     * The batch that has been processed
     *
     * @var array
     */
    private $_batches = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hayvn:sendaggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // init the batch content array
        $content['batches'] = [];

        // start transaction
        DB::beginTransaction();

        // Get the messages that are not yet processed
        $messages = Message::where('processed', false)->cursor();

        // get all unprocessed messages
        foreach ($messages as $message) {
            // get the batch data
            $batch = Batch::where('destination', $message->destination)->first();

            // if no batch found, we continue and move on to next record
            if (empty($batch)) {
                continue;
            }

            // check the batch/destination if ready for processing after env('AGGREGATED_MESSSAGE_DELAY_IN_SECS')
            if (!empty($batch->last_processed_at)
                && Carbon::parse($batch->last_processed_at)->diffInSeconds(now()) < env('AGGREGATED_MESSSAGE_DELAY_IN_SECS')) {
                continue;
            }

            // insert the contents of the batch
            $content['batches'][$message->destination][] = [
                    'text' => $message->text,
                    // convert the time to zulu format
                    'timestamp' => Carbon::parse($message->timestamp)->toIso8601ZuluString('millisecond')
            ];

            // temporary update the processed status and processing timestamp
            $message->processed = true;
            $message->processed_at = now();
            $message->save();

            // store the destination for batch processing update
            $this->_batches[] = $batch;
        }

        // pack the array payload
        collect($content['batches'])->each(function ($messages, $destination) {
            $this->_payload['batches'][] = [
                'destination' => $destination,
                'messages' => $messages
            ];
        });

        // json encode the array payload
        $payload = json_encode($this->_payload);

        // send the request to aggregate-messages endpoint
        $response = Http::withHeaders([
            "Content-type" => "application/json",
            "Accept" => "application/json",
        ])->withBody(
            $payload, 'application/json'
        )->post(env('AGGREGATED_MESSAGES_URI'));

        // check if request is a failure
        if ($response->successful() !== true) {
            // we rollback the status to unprocessed since request has failed
            DB::rollBack();
            return Command::FAILURE;
        }

        // update the processing time of the batch/destination
        foreach ($this->_batches as $batch) {
            $batch->last_processed_at = now();
            $batch->save();
        }

        // Request successful. We commit the DB transaction
        DB::commit();

        // return successful status code to console
        return Command::SUCCESS;
    }
}
