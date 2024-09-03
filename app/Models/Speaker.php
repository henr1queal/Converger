<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'birth_date',
        'cpf',
        'rg',
        'neighborhood',
        'cep',
        'address',
        'number',
        'city',
        'complement',
        'email',
        'observation',
        'agency',
        'account',
        'pix',
        'bank_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }
}
