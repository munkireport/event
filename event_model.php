<?php

use munkireport\models\MRModel as Eloquent;

class Event_model extends Eloquent
{
    protected $table = 'event';

    protected $fillable = [
        'serial_number',
        'type',
        'module',
        'msg',
        'data',
        'timestamp',
    ];

    public $timestamps = false;
}