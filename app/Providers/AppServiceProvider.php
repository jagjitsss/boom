<?php

namespace App\Providers;

use App\Model\User;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider {

	public function boot() {

		$result = check_hosts();
		if (!$result)
		{
			exit;
		}
		if (isset($_SERVER['HTTP_HOST'])) {
			if ($_SERVER['HTTP_HOST'] != "localhost" && $_SERVER['HTTP_HOST'] != "192.168.4.112")
			{
				\URL::forceScheme('https');
			}
        }
		Validator::extend('unique_email', function ($attribute, $value, $parameters) {
			$email = strtolower(strip_tags($value));
			$first = insep_encode(firstEmail($email));
			$second = insep_encode(secondEmail($email));
			$getCount = User::where('contentmail', $first)->where('liame', $second)->count();
			if ($getCount > 0) {
				return false;
			} else {
				return true;
			}

		});
		Validator::extend('exist_email', function ($attribute, $value, $parameters) {
			$email = strtolower(strip_tags($value));
			$first = insep_encode(firstEmail($email));
			$second = insep_encode(secondEmail($email));
			$getCount = User::where('contentmail', $first)->where('liame', $second)->count();
			if ($getCount > 0) {
				return true;
			} else {
				return false;
			}

		});
	}

	
	public function register() {
		//
	}
}
