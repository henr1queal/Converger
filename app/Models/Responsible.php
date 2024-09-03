<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'full_name',
        'phone'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
