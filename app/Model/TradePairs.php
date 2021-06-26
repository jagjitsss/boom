<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TradePairs extends Model
{
    protected $table = 'sriap_edart';

    protected $guarded = [];

    //To get all pairs to display in drop down
    public static function getFullPairs() {
    	return TradePairs::select('id','from_symbol','to_symbol','trade_fee','min_price')->where('status',1)->get();
    }
}
