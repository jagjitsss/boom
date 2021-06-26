<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;

class checkWalletSession {
	
	public function handle($request, Closure $next) {
		if (Session::get('walletId') == "") {
			$redirectUrl = "HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai";
			Session::flash('error', 'Please login to continue!');
			return Redirect::to($redirectUrl);
		}
		return $next($request);
	}
}
