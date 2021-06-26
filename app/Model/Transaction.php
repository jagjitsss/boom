<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}
}
