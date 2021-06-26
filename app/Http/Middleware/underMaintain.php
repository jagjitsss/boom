<?php

namespace App\Http\Middleware;

use App\Model\SiteSettings;
use Closure;
use Redirect;

class underMaintain {

	public function handle($request, Closure $next) {
		$status = SiteSettings::where('id', 1)->select('maintain_status')->first()->maintain_status;
		if ($status == "1") {
			return Redirect::to('site_under_maintenance');
		}
		return $next($request);
	}
}
