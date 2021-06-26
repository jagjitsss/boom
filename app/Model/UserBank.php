<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    protected $table = 'user_bank_details';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}
}
