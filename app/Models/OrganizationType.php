<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    protected $table = 'organization_types';

    public function customers()
    {
        return $this->hasMany(Customer::class, 'organization_type_id');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'organization_type_id');
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class, 'organization_type_id');
    }

    public function partners()
    {
        return $this->hasMany(Partner::class, 'organization_type_id');
    }
}
