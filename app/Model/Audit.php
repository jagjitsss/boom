<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {
	protected $table = 'ecivres';
	public $timestamps = false;
	protected $guarded = [];

}
