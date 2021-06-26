<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;

class checkUser {

	public function handle($request, Closure $next) {
		$user_id = session::get('tmaitb_user_id');

		if (profilename_check($user_id) == ' ') {
			Session::flash('error', 'Please fill your profile details to continue!');

			return Redirect::to('/dashboard');
		}
		return $next($request);
	}
}
