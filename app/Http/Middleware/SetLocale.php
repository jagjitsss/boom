<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Session;

class SetLocale {
    public function handle(Request $request, Closure $next) {
        if (Session::has('language')) {
            $language = Session::get('language');
            App::setLocale($language);
            if ($language == 'en') {
                $language = Session::put('language_id', '1');
            } else { $language = Session::put('language_id', '2');}
        } else {
            $language = 'en';
            $language = Session::put('language_id', '1');
            App::setLocale('en');
        }
        return $next($request);
    }
}