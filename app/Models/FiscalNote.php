<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalNote extends Model
{
    use HasFactory;

    protected $table = 'fiscal_notes';

    protected $fillable = [
        'filename',
        'paid',
        'event_id',
        'payment_term',
        'notification_date',
        'observation'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sendedEmail(){
        return $this->hasMany(sendedEmail::class);
    }
}
