<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatedMessages extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'destination',
        'text',
        'timestamp',
    ];
}
