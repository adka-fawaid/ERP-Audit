<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrHist extends Model
{
    protected $table = 'tr_hist';

    protected $primaryKey = 'trans_number';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'trans_number',
        'user',
        'trans_type',
        'program',
        'trans_date',
        'trans_time',
        'location',
    ];

    protected $casts = [
        'trans_date' => 'date',
    ];
}