<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AddAggregatedMessagesDestinationDuplicateRule implements Rule
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
        // check for duplicate destination
        $destination = array_column($value, 'destination');
        if ($destination != array_unique($destination)) {
            return false;
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
        return 'Multiple batches contained the same destination';
    }
}
