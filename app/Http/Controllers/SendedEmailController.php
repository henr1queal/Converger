<?php

namespace App\Http\Controllers;

use App\Models\SendedEmail;
use Illuminate\Http\Request;

class SendedEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sendEmailNotificationToUser($id)
    {
        $send = new SendedEmail();
        $send->user_id = Auth()->id();
        $send->fiscal_note_id = $id;
        $send->save(); 
    }    
}
