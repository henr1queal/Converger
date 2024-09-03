<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportPlane extends Model
{
    use HasFactory;
    
    protected $table = 'transport_plane';
    public $timestamps = false;

    protected $fillable = [
        'company',
        'number',
        'seat',
        'origin_airport',
        'destiny_airport',
        'trip_date',
        'start_boarding',
        'arrival_forecast',
        'ticket_buyer',
        'phone_buyer',
        'observation',
        'event_id',
        'type',
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
