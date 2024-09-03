<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'account',
        'observation',
        'phone_number',
        'email',
        'phone',
        'state',
        'organization_type',
        'cnpj',
        'municipal_registration',
        'cep',
        'address',
        'number',
        'neighborhood',
        'city',
        'complement',
        'responsible_id',
        'organization_type_id',
        'bank_id',
        'pix',
        'agency',
        'origin',
        'financial_observation'        
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function organizationType()
    {
        return $this->belongsTo(
            OrganizationType::class,
            'organization_type_id'
        );
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function responsible()
    {
        return $this->belongsTo(Responsible::class);
    }
}
