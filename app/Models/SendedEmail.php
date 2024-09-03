<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendedEmail extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'sended_emails';

    protected $fillable = [
        'fiscal_note_id',
        'user_id',
    ];

    public function fiscalNote(){
        return $this->belongsTo(FiscalNote::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
