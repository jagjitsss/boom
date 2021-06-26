<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;

class checkGuest {

	public function handle($request, Closure $next) {
		if (Session::get('tmaitb_user_id') != "") {
			return Redirect::to('/dashboard');
		}
		return $next($request);
	}
}
