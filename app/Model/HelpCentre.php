<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HelpCentre extends Model
{
    protected $table = 'ertnec_pleh';

    protected $guarded = [];
    
    public static $addRule = array(
		'category' => 'required',
		'description' => 'required',
		'subject' => 'required',
		'file' => 'mimes:jpeg,jpg,png|max:10000' // max 10000kb
	);
}
