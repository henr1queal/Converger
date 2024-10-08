<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySupplier extends Model
{
    use HasFactory;

    protected $table = 'category_supplier';
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
