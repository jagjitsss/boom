<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use App\Model\SiteSettings;

class checkAdminSession
{

    public function handle($request, Closure $next)
    {
        if(Session::get('adminId') == "") {
            $getUrl = SiteSettings::where('id',1)->select('admin_redirect')->first();
            $redirectUrl = $getUrl->admin_redirect;
            Session::flash('error','Please login to continue!');
            return Redirect::to($redirectUrl);
        }
        return $next($request);
    }
}
