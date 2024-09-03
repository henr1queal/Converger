<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportBusBetweenCity extends Model
{
    use HasFactory;

    protected $table = 'transport_bus_between_city';
    public $timestamps = false;

    protected $fillable = [
        'company',
        'number',
        'seat',
        'origin_city',
        'destiny_city',
        'trip_date',
        'start_boarding',
        'arrival_forecast',
        'ticket_buyer',
        'phone_buyer',
        'observation',
        'type',
        'event_id'
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
