<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'fantasy_name',
        'organization_type',
        'cnpj',
        'municipal_registration',
        'cep',
        'address',
        'city',
        'state',
        'neighborhood',
        'number',
        'complement',
        'email',
        'phone',
        'phone_number',
        'observation',
        'agency',
        'account',
        'pix',
        'organization_type_id',
        'responsible_id',
        'bank_id',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function responsible()
    {
        return $this->belongsTo(Responsible::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
