<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'theme',
        'place',
        'observation',
        'total_price',
        'converger_price',
        'speaker_price',
        'partner_price',
        'note_emission_date',
        'payment_term',
        'receipt_date',
        'received',
        'speaker_payment_date',
        'financial_observation',
        'user_id',
        'customer_id',
        'event_type_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'note_emission_date' => 'date',
        'payment_term' => 'date',
        'receipt_date' => 'date',
        'received' => 'boolean',
        'speaker_payment_date' => 'date',
    ];

    public function fiscalNote()
    {
        return $this->hasOne(FiscalNote::class);
    }

    public function hosting()
    {
        return $this->hasOne(Hosting::class);
    }

    public function transportTaxi()
    {
        return $this->hasMany(TransportTaxi::class);
    }

    public function transportUber()
    {
        return $this->hasMany(TransportUber::class);
    }

    public function transportBusBetweenCity()
    {
        return $this->hasMany(TransportBusBetweenCity::class);
    }

    public function transportBusSameCity()
    {
        return $this->hasMany(TransportBusSameCity::class);
    }

    public function transportPlane()
    {
        return $this->hasMany(TransportPlane::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)->withPivot('observation', 'price', 'paid');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class)->withPivot('observation', 'price', 'paid');
    }
    
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class);
    }
}
