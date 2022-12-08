<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AddAggregatedMessagesStructureRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // check the structure of the 'batches' field
        foreach($value as $destination) {
            if (!array_key_exists('destination', $destination)
            || !array_key_exists('messages', $destination)
            || empty($destination['messages'])) {
                return false;
            }

            // check message field structure
            foreach ($destination['messages'] as $message) {
                if (!array_key_exists('text', $message)
                || !array_key_exists('timestamp', $message)
                || empty($message['text'])
                || empty($message['timestamp'])) {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Failed to process messages: Malformed request payload';
    }
}
