<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    protected $table = 'event_types';

    public function event()
    {
        return $this->hasOne(Event::class);
    }
}
