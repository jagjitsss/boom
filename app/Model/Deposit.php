<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Deposit extends Model
{
    protected $table = 'tisoped';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}

}
