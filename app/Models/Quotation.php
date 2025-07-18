<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'total',
        'currency_id',
        'ages',
        'start_date',
        'end_date',
        'trip_length',
    ];

    protected $casts = [
        'ages' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'total' => 'decimal:2',
    ];
}
