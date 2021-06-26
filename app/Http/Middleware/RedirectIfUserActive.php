<?php

namespace App\Http\Middleware;

use App\Model\User;
use Closure;
use Redirect;
use Session;

class RedirectIfUserActive {

	public function handle($request, Closure $next) {
		if (Session::get('tmaitb_user_id') != "") {
			$userId = Session::get('tmaitb_user_id');
			$getUser = User::where('id', $userId)->select('status')->first();
			if ($getUser->status != "1") {
				Session::flush();
				Session::flash('error', 'Your account has been deactivated by Admin!');
				return Redirect::to('/login');
			}
		}
		return $next($request);
	}
}
