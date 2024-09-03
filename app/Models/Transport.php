<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'event_id'];

    protected $searchableFields = ['*'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function transportDetail()
    {
        return $this->hasOne(TransportDetail::class);
    }
}
