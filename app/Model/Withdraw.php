<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'wardhtiw';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}
}
