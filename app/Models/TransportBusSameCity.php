<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportBusSameCity extends Model
{
    use HasFactory;
    
    protected $table = 'transport_bus_same_city';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'observation',
        'event_id',
        'type',
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
