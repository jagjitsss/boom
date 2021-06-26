<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $table = 'faq_category';

    protected $guarded = [];

    //associte with faq
    public function faq() {
        return $this->hasMany('App\Model\Faq','category');
    }
}
