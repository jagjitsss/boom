<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'larrefer';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'refered_by');
	}
}
