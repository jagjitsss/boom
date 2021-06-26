<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email() {
      $data = array('name'=>"Virat Gandhi");
   	
   	  Config::set('mail.driver', 'smtp');
		Config::set('mail.host', '');
		Config::set('mail.port', 587);
		Config::set('mail.username', '');
		Config::set('mail.password', '');
		Config::set('mail.encryption', 'ssl');


      Mail::send(['text'=>'mail'], $data, function($message)
      {
         $message->to('', 'Tutorials Point')->subject('Laravel Basic Testing Mail');
         $message->from('','Virat Gandhi');
      });
      
   }
   
}