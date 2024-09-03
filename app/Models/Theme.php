<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'speaker_id',
        'theme_id'
    ];

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }
    
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
