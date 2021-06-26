<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use Route;
use App\Model\SiteSettings;
use App\Model\SubAdmin;
use App\Model\AdminActivity;

class checkPermission
{

    public function handle($request, Closure $next)
    {
        if(Session::get('adminId') != "") {
            $id = Session::get('adminId');
            $admin = SubAdmin::where('id',$id)->select('permission','role')->first();
            if($admin->role == "admin") {
                return $next($request);
            } else if($admin->role == "subadmin") {
                $currentRoute = \Route::getCurrentRoute()->getActionName();
                $explodeRoute = explode('@', $currentRoute);
                $uri = $explodeRoute[1];
                $per = AdminActivity::checkPermission($uri);
                if($per == -1) {
                    return $next($request);
                }
                $permission = explode(',', strip_tags($admin->permission));
                if(in_array($per, $permission)) {
                    return $next($request);
                } else {
                    $getUrl = SiteSettings::where('id',1)->select('admin_redirect')->first();
                    $redirectUrl = $getUrl->admin_redirect;
                    Session::flash('error', 'Permission Denied!');
                    return Redirect::to($redirectUrl);
                }
            }
        }
    }
}
