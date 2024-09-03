<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
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
        'origin',
        'observation',
        'agency',
        'account',
        'pix',
        'financial_observation',
        'organization_type_id',
        'responsible_id',
        'bank_id',
    ];

    protected $table = 'suppliers';

    public function organizationType()
    {
        return $this->belongsTo(
            OrganizationType::class,
            'organization_type_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(CategorySupplier::class);
    }

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
        return $this->belongsToMany(Event::class);
    }
}
