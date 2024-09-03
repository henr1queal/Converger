<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'check_in',
        'check_out',
        'cep',
        'city',
        'address',
        'neighborhood',
        'number',
        'complement',
        'reference_point',
        'observation',
        'event_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'hostings';

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
