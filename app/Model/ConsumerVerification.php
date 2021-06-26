<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConsumerVerification extends Model
{
    protected $table = 'noitacifirev';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}
}
