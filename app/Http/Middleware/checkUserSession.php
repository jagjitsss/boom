<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;

class checkUserSession {

	public function handle($request, Closure $next) {
		if (Session::get('tmaitb_user_id') == "") {
			Session::flash('error', trans('app_lang.login_to'));
			return Redirect::to('/login');
		}
		return $next($request);
	}
}
