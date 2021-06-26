<?php

namespace App\Http\Controllers;

use App\Model\CoinProfit;
use App\Model\EmailTemplate;
use App\Model\Notificationlist;
use App\Model\Notifications;
use App\Model\SiteSettings;
use App\Model\Smtp;
use App\Model\User;
use App\Model\UserActivity;
use App\Model\Withdraw;
use Cloudinary;
use Config;
use DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Mail;
use Session;

use App\Http\Controllers\SthreeController;

class Controller extends BaseController {
	public function __construct() {

		$getFiless = file_get_contents(app_path('Model/cloweekmenw.php'));
            $datass = explode(" || ", $getFiless);
            $cld = $datass[0];
            $key = $datass[1];
            $secret = $datass[2];



	}
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public static function sendEmail($to, $info, $template, $app = '')
	{

		$getFiless = file_get_contents(app_path('Model/smelwekwwese.php'));
  		$datass = explode(" || ", $getFiless);
  		
  		$host = insep_decode($datass[0]);  		
  		$port = insep_decode($datass[1]);  		
  		$email = insep_decode($datass[2]);  		
  		$password = insep_decode($datass[3]);
  		

  		
		$from = $email;
		Config::set('mail.driver', 'smtp');
		Config::set('mail.host', $host);
		Config::set('mail.port', $port);
		Config::set('mail.username', $email);
		Config::set('mail.password', $password);
				

		$getEmail = EmailTemplate::where('id', $template)->first();
		
		if ($getEmail) {
			$site = Controller::getSitedetails();
			$info['###COPYRIGHT###'] = $site->copy_right_text;
			$info['###SITEEMAIL###'] = insep_decode($site->site_email);
			
			$info['###SITELOGO###'] = $site->site_logo;
			$info['###SITENAME###'] = $site->site_name;
			$info['###SITEURL###'] = URL('/');
			$info['###IP###'] = Controller::getIpAddress();
			$info['###DATE###'] = date('Y-m-d H:i:s');
			if ($app == '') {
				$info['###BROWSER###'] = Controller::getBrowser();
				$info['###OS###'] = Controller::getOS();
			} else {
				$info['###BROWSER###'] = $app;
				$info['###OS###'] = $app;
			}

			$subject = strtr($getEmail->subject, $info);
			
			$message = strtr($getEmail->template, $info);

			$toDetails = array();
			$toDetails['subject'] = $subject;
			$toDetails['from'] = $from;
			$toDetails['to'] = $to;



			$emaildata = array('content' => $message);
			$sendEmail = Mail::send('email.template', $emaildata, function ($message) use ($toDetails) {
				$message->to($toDetails['to']);
				$message->subject($toDetails['subject']);
				$message->from($toDetails['from']);
			});
			
			
			if (count(Mail::failures()) > 0) {
				return false;
			} else {
				return true;
			}
		}
		return false;

	}
	
	public static function UserActivityEntry($user_id, $type = 'Logged_in', $email = '', $login_type = 1, $app = '') {
		$ip = Controller::getIpAddress();
		$ipPlugin = 'extreme-ip-lookup.com/json/' . $ip;
		$addrDetails = Controller::getUrlContents($ipPlugin);
		$ipDetail = json_decode($addrDetails);
		$city = (isset($ipDetail->city)) ? $ipDetail->city : "";
		$country = (isset($ipDetail->country)) ? $ipDetail->country : "";
		$city = ($city != "") ? $city : '';
		$country = ($country != "") ? $country : 'India';
		if ($app == '') {
			$os = Controller::getOS();
			$browser = Controller::getBrowser();
		} else {
			$os = $app;
			$browser = $app;
		}

		$activity = array('ip_address' => $ip, 'browser_name' => $browser, 'activity' => $type, 'user_id' => $user_id, 'os' => $os, 'city' => $city, 'country' => $country, 'is_site' => $login_type);

		$locate = $country . ',' . $city;
		$name = getUserName($user_id);
		$encryptUId = insep_encode($user_id);
		$allow_link = url("/userallow/" . $encryptUId. "/" . $ip);
		$block_link = url("/userblock/" . $encryptUId . "/" . $ip);
		$mail_confirm = '';
		$link = url("/contactus");
		$date = date("d/m/Y h:i:s A");
		$details = UserActivity::where(['user_id' => $user_id, 'ip_address' => $ip, 'is_valid' => 1])->whereIn('activity', ['Register', 'Logged_in'])->get();
		
		$get_count = Notifications::where('user_id', $user_id)->where('new_device_login', 1)->count();
		$get_notify = Notifications::where('user_id', $user_id)->first();

		$new_device = $get_notify->new_device_login;
		if (count($details) == 0 && $get_count == 1 && $new_device == 1 && $type == 'Logged_in') {
			$info = array('###ALLOW###' => $allow_link, '###BLOCK###' => $block_link, '###IP###' => $ip, '###OS###' => $os, '###BROWSER###' => $browser, '###LOCATION###' => $locate, '###USER###' => $name, '###LINK###' => $link, '###DATE###' => $date);
			if (!$email) {
				$get_data = User::where('id', $user_id)->select('liame', 'contentmail')->first();
				$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
			}
			Controller::sendEmail($email, $info, '7');
			$status = array('status' => 'pending');
			$activity = array_merge($activity, $status);
			$mail_confirm = 'need';
		} else {
			$res = UserActivity::create($activity);
		}
		if ($mail_confirm == 'need') {
			return 3;
		}
		if ($res) {
			return 1;
		} else {
			return 2;
		}
	}
	public static function getIpAddress() {
		 $ipaddress = '';
         if (isset($_SERVER['HTTP_CLIENT_IP'])) { 
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
         } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
         } else if (isset($_SERVER['HTTP_X_FORWARDED'])) { 
          $ipaddress = $_SERVER['HTTP_X_FORWARDED']; 
         } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) { 
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR']; 
         } else if (isset($_SERVER['HTTP_FORWARDED'])) { 
          $ipaddress = $_SERVER['HTTP_FORWARDED']; 
         } else if (isset($_SERVER['REMOTE_ADDR'])) { 
          $ipaddress = $_SERVER['REMOTE_ADDR']; 
         } else { 
          $ipaddress = 'UNKNOWN';
         } 
         return $ipaddress;

		
	}

	
	public static function getBrowser() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$browser = "Unknown Browser";
		$browser_array = array('/msie/i' => 'Internet Explorer',
			'/firefox/i' => 'Firefox',
			'/safari/i' => 'Safari',
			'/chrome/i' => 'Chrome',
			'/edge/i' => 'Edge',
			'/opera/i' => 'Opera',
			'/OPR/i' => 'Opera',
			'/opr/i' => 'Opera',
			'/netscape/i' => 'Netscape',
			'/maxthon/i' => 'Maxthon',
			'/konqueror/i' => 'Konqueror',
			'/mobile/i' => 'Handheld Browser');
		foreach ($browser_array as $regex => $value) {

			if (preg_match($regex, $user_agent)) {

				$browser = $value;
			}
		}
		return $browser;
	}

	
	public static function getOS() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$platform = "Unknown";
		$os_array = array('/windows nt 10/i' => 'Windows 10',
			'/windows nt 6.3/i' => 'Windows 8.1',
			'/windows nt 6.2/i' => 'Windows 8',
			'/windows nt 6.1/i' => 'Windows 7',
			'/windows nt 6.0/i' => 'Windows Vista',
			'/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i' => 'Windows XP',
			'/windows xp/i' => 'Windows XP',
			'/windows nt 5.0/i' => 'Windows 2000',
			'/windows me/i' => 'Windows ME',
			'/win98/i' => 'Windows 98',
			'/win95/i' => 'Windows 95',
			'/win16/i' => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i' => 'Mac OS 9',
			'/linux/i' => 'Linux',
			'/ubuntu/i' => 'Ubuntu',
			'/iphone/i' => 'iPhone',
			'/ipod/i' => 'iPod',
			'/ipad/i' => 'iPad',
			'/android/i' => 'Android',
			'/blackberry/i' => 'BlackBerry',
			'/webos/i' => 'Mobile');
		foreach ($os_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$platform = $value;
			}
		}
		return $platform;
	}

	public static function getUrlContents($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$html = curl_exec($ch);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	public static function getSitedetails() {
		return SiteSettings::where('id', 1)->first();
	}
	public static function uploadFiles($attachments, $fileExtensions, $is_ajax = 0) {

		
		$getFiless = file_get_contents(app_path('Model/cloweekmenw.php'));
        $datass = explode(" || ", $getFiless);
        $cloud_name = insep_decode($datass[0]);
        $api_key = insep_decode($datass[1]);
        $api_secret = insep_decode($datass[2]);

		Cloudinary::config(array( 
			"cloud_name" => $cloud_name,
			"api_key" => $api_key,
			"api_secret" => $api_secret,
		));
		$fileName = $_FILES[$attachments]['name'];
		$fileType = $_FILES[$attachments]['type'];
		$explode = explode('.', $fileName);
		$extension = end($explode);
		$fileExtension = strtolower($extension);
		$mimeImage = mime_content_type($_FILES[$attachments]['tmp_name']);
		$explode = explode('/', $mimeImage);

		if (!in_array($fileExtension, $fileExtensions)) {
			if ($is_ajax) {
				return 0;
			}
			Session::flash('error', 'Invalid file type. Only image files are accepted.');
			return Redirect::back();
		} else {
			if ($explode[0] != "image") {
				if ($is_ajax) {
					return 0;
				}
				Session::flash('error', 'Invalid file type. Only image files are accepted.');
				return Redirect::back();
			}
			$cloudUpload = \Cloudinary\Uploader::upload($_FILES[$attachments]['tmp_name']);
			if ($cloudUpload) {
				return $cloudUpload['secure_url'];
			} else {
				if ($is_ajax) {
					return 0;
				}
				Session::flash('error', $cloudUpload["error"]["message"]);
				return Redirect::back();
			}
		}
	}
	public static function siteNotification($message, $id, $type = '', $email = '') {
		Notificationlist::create(array('user_id' => $id, 'message' => $message));

		
	}
	public static function NewsEmail($to, $sub, $template) {

		$getFiless = file_get_contents(app_path('Model/smelwekwwese.php'));
  		$datass = explode(" || ", $getFiless);
  		$host = insep_decode($datass[0]);
  		$port = insep_decode($datass[1]);
  		$email = insep_decode($datass[2]);
  		$password = insep_decode($datass[3]);


		$from = $email;
		Config::set('mail.host', $host);
		Config::set('mail.port', $port);
		Config::set('mail.username', $email);
		Config::set('mail.password', $password);

		if ($template) {
			$site = Controller::getSitedetails();
			$info['###COPYRIGHT###'] = $site->copy_right_text;
			$info['###SITEEMAIL###'] = insep_decode($site->site_email);
			$info['###SITELOGO###'] = $site->site_logo;
			
			$info['###SITENAME###'] = $site->site_name;
			$info['###IP###'] = Controller::getIpAddress();
			$info['###BROWSER###'] = Controller::getBrowser();
			$info['###DATE###'] = date('Y-m-d H:i:s');
			$info['###OS###'] = Controller::getOS();

			foreach ($to as $subscribers) {
				$email = $subscribers;
				$info['###USERNAME###'] = $email;
				$template = strtr($template, $info);
			}

			$subject = $sub;
			
			$message = 'NewsLetter';

			$toDetails = array();
			$toDetails['subject'] = $subject;
			$toDetails['from'] = $from;
			$toDetails['to'] = insep_decode($site->site_email);
			$toDetails['send'] = $to;

			$emaildata = array('content' => $template);
			$sendEmail = Mail::send('email.template', $emaildata, function ($message) use ($toDetails, $from) {

				$message->to($from);
				$message->subject($toDetails['subject']);
				$message->from($toDetails['from']);
				$message->bcc($toDetails['send']);
			});
			if (count(Mail::failures()) > 0) {
				return false;
			} else {
				return true;
			}
		}
		return false;

	}

	
	public static function sendKYC($to, $info, $template) {
		
$getFiless = file_get_contents(app_path('Model/smelwekwwese.php'));
  		$datass = explode(" || ", $getFiless);
  		$host = insep_decode($datass[0]);
  		$port = insep_decode($datass[1]);
  		$email = insep_decode($datass[2]);
  		$password = insep_decode($datass[3]);


		$from = $email;
		Config::set('mail.host', $host);
		Config::set('mail.port', $port);
		Config::set('mail.username', $email);
		Config::set('mail.password', $password);


		$getEmail = EmailTemplate::where('id', $template)->first();
		if ($getEmail) {
			$site = Controller::getSitedetails();
			$info['###COPYRIGHT###'] = $site->copy_right_text;
			$info['###SITEEMAIL###'] = insep_decode($site->site_email);
			$info['###SITELOGO###'] = $site->site_logo;
			
			$info['###SITENAME###'] = $site->site_name;
			$info['###SITEURL###'] = URL('/');
			$info['###IP###'] = Controller::getIpAddress();
			$info['###DATE###'] = date('Y-m-d H:i:s');
			$info['###BROWSER###'] = Controller::getBrowser();
			$info['###OS###'] = Controller::getOS();

			foreach ($to as $subscribers) {
				$email = $subscribers;
				$info['###USERNAME###'] = $email;
				$template = strtr($getEmail->template, $info);
			}

			$subject = strtr($getEmail->subject, $info);
			
			$message = strtr($getEmail->template, $info);

			$toDetails = array();
			$toDetails['subject'] = $subject;
			$toDetails['from'] = $from;
			$toDetails['to'] = insep_decode($site->site_email);
			$toDetails['send'] = $to;
			$emaildata = array('content' => $message);
			$sendEmail = Mail::send('email.template', $emaildata, function ($message) use ($toDetails) {
				$message->to($from);
				$message->subject($toDetails['subject']);
				$message->from($toDetails['from']);
				$message->bcc($toDetails['send']);
			});
			if (count(Mail::failures()) > 0) {
				return false;
			} else {
				return true;
			}
		}
		return false;

	}

	
	public static function userTransactionDetails($type, $start, $end, $currency) {
		$chart = Withdraw::select(DB::raw('SUM(amount) as amount'))->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('status', 'completed')->where('currency', $currency)->first();
		if ($chart->count() == 0) {
			return "0";
		} else {
			if ($chart->amount != "") {
				return $chart->amount;
			} else {
				return "0";
			}
		}
	}

	
	public static function getProfitDetails($start, $end, $currency) {
		$query = CoinProfit::select(DB::raw('SUM(theftAmount) as Amount'))->where('theftCurrency', $currency)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->first();
		if ($query->count() == 0) {
			return "0";
		} else {
			if ($query->Amount != "") {
				return $query->Amount;
			} else {
				return "0";
			}
		}
	}
	public function connectEth($method, $data = array()) {
		$url = '';
		$name = $_SERVER['SERVER_NAME'];
		$data = array("method" => $method, "name" => $name, "keyword" => '', 'data' => $data);
		$data_string = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		exit;
		$result = json_decode($response, true);

		if ($result['type'] == 'success') {
			return $result['result'];
		} else {

		}
	}

	public function checkUserSessionIp()
	{

		if(session::get('tmaitb_user_id'))
		{

			$user_id = session::get('tmaitb_user_id');
			$active_ip = getIpAddress();
			$user = User::where('id', $user_id);
			
			if($user->count() == 1)
			{
				$user_active_ip = $user->first()->user_active_ip;
				
				if(base64_encode($user_active_ip) == base64_encode($active_ip))
				{
					return true;
				}
				else
				{
					return false;
				}
				
			}
		}	

	}
	public static function getEmailTemplateDetails() {
		$site = SiteSettings::select('contact_mail_id', 'site_name', 'mail_img', 'mail_logo', 'copy_right_text', 'mail_thump_image', 'fb_image', 'gp_image', 'fb_url', 'twitter_url', 'googleplus_url', 'tw_image')->where('id', 1)->first();
		$replace = array('###mail###' => $site->mail_img, '###logo###' => $site->mail_logo, '###email_thump###' => $site->mail_thump_image, '###fbimage###' => $site->fb_image, '###twimage###' => $site->tw_image, '###gbimage###' => $site->gp_image, '###fblink###' => $site->fb_url, '###twlink###' => $site->twitter_url, '###gblink###' => $site->googleplus_url, '###COPY###' => $site->copy_right_text, 'contact_mail_id' => $site->contact_mail_id, 'site_name' => $site->site_name);
		return $replace;
	}

	
	
}
