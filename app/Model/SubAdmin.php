<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubAdmin extends Model {
	protected $table = 'nimda_bus';

	protected $guarded = [];

	public static $faqRule = array(
		'question' => 'required',
		'description' => 'required',
	);

	public static $newsRule = array(
		'title' => 'required',
		'content' => 'required',
	);

	public static $depositRule = array(
		'confirm_code' => 'required',
		'reason' => 'required',
		'actual_amount' => 'required',
	);

	public static $cmsRule = array(
		'title' => 'required',
		'content' => 'required',
	);

	public static $bannerRule = array(
		'title' => 'required',
		'Banner_Image' => 'mimes:jpeg,jpg,png',
		//'content' => 'required',dimensions:width=251,height=128
	);

	public static $bannerimageRule = array(
		'title' => 'required',
		'Banner_Image' => 'mimes:jpeg,jpg,png|dimensions:width=1200,height=60',
		//'content' => 'required',dimensions:width=251,height=128
	);

	public static $emailRule = array(
		'name' => 'required',
		'subject' => 'required',
		'template' => 'required',
	);

	public static $metaRule = array(
		'title' => 'required',
		'meta_keywords' => 'required',
		'meta_description' => 'required',
	);

	public static $profileRule = array(
		'admin_username' => 'required',
		'admin_phno' => 'required',
		'admin_address' => 'required',
		'admin_city' => 'required',
		'admin_state' => 'required',
		'admin_postal' => 'required',
		'country' => 'required',
	);

	public static $pwdRule = array(
		'current_pwd' => 'required',
		'new_pwd' => 'required|min:8',
		'confirm_pwd' => 'required|min:8',
	);

	public static $adminemailRule = array(
		'email_id' => 'required|email',
	);

	public static $adminPatternRule = array(
		'old_pattern_code' => 'required',
		'pattern_code' => 'required|confirmed',
		'pattern_code_confirmation' => 'required',
	);

	public static $siteRule = array(
		'site_name' => 'required',
		'admin_redirect' => 'required',
		'contact_no' => 'required',
		'contact_address' => 'required',
		'city' => 'required',
		'country' => 'required',
		'copy_right_text' => 'required',
		'maintain_status' => 'required',
		'site_maintenance' => 'required',
	);

	public static $addSubadminRule = array(
		'username' => 'required',
		'email_addr' => 'required',
		'pattern_code' => 'required',
		'permission' => 'required',
	);

	public static $bankRule = array(
		'bank_name' => 'required',
		'acc_name' => 'required',
		'acc_number' => array('required', 'numeric'),
		'bank_code' => 'required',
		'bank_branch' => 'required',
		'status' => 'required',
	);

	// rules for sms updating in admin start here
	public static $smsRule = array(
		'name' => 'required',
		'number' => array('required', 'numeric'),
	);
	// rules for sms updating in admin end here

	public static $tradeFeeRule = array(
		'from_amt' => 'required',
		'to_amt' => 'required',
		'fee' => 'required',
	);
	public static $newsletterRule = array(
		'subject' => 'required',
		'template' => 'required',
	);

	public static $tradePairRule = array(
		'min_price' => 'required',
		'trade_fee' => 'required',
		'min_amt' => 'required',
	);
	public static $tradePairRule1 = array(
		'trade_fee' => 'required',
		'min_amt' => 'required',
	);
	//fetch particular profile details
	public static function getProfile($id) {
		$profilePicture['admin'] = SubAdmin::where('id', $id)
			->select('profile', 'username')->first();
		return $profilePicture;
	}
    //fetch admin url details
	public static function getAdminUrl() {
		$getUrl = SiteSettings::where('id', 1)->select('admin_redirect')->first();
		return $getUrl->admin_redirect;
	}
    // fetch permission details for subadmin
	public static function getPermission($id) {
		$permission = SubAdmin::where('id', $id)->select('permission')->first();
		return $permission->permission;
	}
    //fetch subadmin permission allowed 
	public static function getAllowed($id) {
		$permission = SubAdmin::where('id', $id)->select('allowed')->first();
		return $permission->allowed;
	}
    //get notification count
	public static function getNotificationCount() {
		$count = AdminNotification::where('status', 'unread')->count();
		return $count;
	}
    //get admin notification
	public static function getAdminNotifcation() {
		$result = AdminNotification::orderBy('id', 'desc')->get();
		if ($result->isEmpty()) {
			return "";
		} else {
			return $result;
		}
	}
     //get time difference
	public static function getTimeAgo($date_time) {
		$date2 = date_create(date('Y-m-d H:i:s'));
		$date1 = date_create($date_time);
		$diff = date_diff($date1, $date2);
		$left = '0 sec ago';

		if ($date1 < $date2) {
			if ($diff->s != 0) {
				$left = $diff->s . ' sec ago';
			}

			if ($diff->i != 0) {
				$left = $diff->i . ' mins ago';
			}

			if ($diff->h != 0) {
				$left = $diff->h . ' hours ago';
			}

			if ($diff->d != 0) {
				$left = $diff->d . ' days ago';
			}

			if ($diff->m != 0) {
				$left = $diff->m . ' months ago';
			}

			if ($diff->y != 0) {
				$left = $diff->y . ' years ago';
			}

		}
		return $left;
	}

}
