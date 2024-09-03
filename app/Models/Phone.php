<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = ['phone_number', 'responsible_id'];

    public function responsible()
    {
        return $this->belongsTo(Responsible::class);
    }
}
