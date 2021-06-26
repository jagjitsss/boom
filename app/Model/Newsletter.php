<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter';

    protected $guarded = [];

    public static $countryRule = array(
        'content' => 'required',
        'subject' => 'required'
    );

    

}
