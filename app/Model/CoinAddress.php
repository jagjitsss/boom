<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CoinAddress extends Model
{
    protected $table = 'sserdda_nioc';

    protected $guarded = [];

    //associate with User
    public function user() {
	    return $this->belongsTo('App\Model\User', 'user_id');
	}
}
