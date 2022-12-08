<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    // cast the fields to appropriate type
    protected $casts = [
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    protected $fillable = [
        'destination',
        'text',
        'timestamp',
        'processed',
        'processed_at'
    ];

    /**
    * The "booted" method of the model.
    *
    * @return void
    */
    protected static function booted()
    {
        static::created(function ($message) {
            // Create the uuid upon message creation
            $uuid =  Str::orderedUuid();
            $message->uuid = $uuid;
            $message->save();

            // create a batch if not exists
            Batch::firstOrCreate([
                'destination' => $message->destination
            ]);
        });
    }
}
