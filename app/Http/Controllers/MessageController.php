<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAggregatedMessagesRequest;
use App\Http\Requests\AddMessageRequest;
use App\Models\AggregatedMessages;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Message Controller
 *
 * Class MessageController
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{

    /**
     * Add Message
     *
     * @param AddMessageRequest $request
     * @return array
     */
    public function addMessage(AddMessageRequest $request)
    {
        // Parse the date
        $date = Carbon::parse($request->timestamp)->toDateTimeString('millisecond');

        // create the message
        $message = Message::create([
            'destination' => $request->destination,
            'text' => $request->text,
            'timestamp' => $date
        ]);

        // return a response (200 OK)
        return [
            "uuid" => $message->uuid
        ];
    }

    /**
     * Test API for
     * Aggregated Messages
     *
     * @param AddAggregatedMessagesRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addAggregatedMessages(AddAggregatedMessagesRequest $request)
    {
        // start transaction
        DB::beginTransaction();

        try {
            // iterate the batch
            collect($request->batches)->each(function($batch)  {
                // iterate the messages and process it
                collect($batch['messages'])->each(function ($message) use ($batch) {
                    // Parse the date
                    $date = Carbon::parse($message['timestamp'])->toDateTimeString('millisecond');

                    // save messages
                    AggregatedMessages::create([
                        'destination' => $batch['destination'],
                        'text' => $message['text'],
                        'timestamp' => $date
                    ]);
                });
            });

            // commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // rollback all changes
            DB::rollBack();

            // we abort and send 5XX response to client
            return response([
                'errorCode' => 'FAILED_TO_PROCESS_BATCH',
                'message' => 'Failed to process messages',
            ], 500);
        }

        // return http status code 204
        return response(null, "204");
    }
}
