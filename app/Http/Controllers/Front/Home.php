<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\BlockIP;
use App\Model\Banner;
use App\Model\Cms;
use App\Model\ConsumerVerification;
use App\Model\Country;
use App\Model\Currency;
use App\Model\CoinOrder;
use App\Model\Faq;
use App\Model\Googleauthenticator;
use App\Model\News;
use App\Model\Notificationlist;
use App\Model\Notifications;
use App\Model\SiteSettings;
use App\Model\Subscribe;
use App\Model\Support;
use App\Model\TradePairs;
use App\Model\User;
use App\Model\UserActivity;
use App\Model\Wallet;
use App\Model\Reqotp;
use App\Model\Deposit;
use Carbon\Carbon;
use App\Model\Fiatdeposit;
use DB;
use GeetestLib;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use URL;
use Validator;
use DateTime;

class Home extends Controller {


	
	public function __construct()
	{
	}
	
	public function index() 
	{
		if(session::get('tmaitb_user_id'))
		{

			return redirect('/dashboard');
		}
		else
		{
			return redirect('/trade');
		}

		
	}
    
	public function login() 
	{

		$viewsource = 'front.common.login';
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.common.common', compact('viewsource','newsdetails'));
	}
    
	public function checkLogin() 
	{
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|exist_email",
			'password' => 'required',
		], [
			'email.required' => 'Please Enter email address',
			'email.email' => 'Please Enter valid email address',
			'email.exist_email' => 'User does not exists,Please Enter valid User Details',
			'password.required' => 'Please Enter Valid password',
		]
	   );
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}

		$password = strip_tags($data['password']);
		$usermail = strtolower(strip_tags($data['email']));
		$first = insep_encode(firstEmail($usermail));
		$second = insep_encode(secondEmail($usermail));
		$password = insep_encode($password);
		$user = User::where(['contentmail' => $first, 'liame' => $second, 'ticket' => $password])->select('id', 'activation_code', 'randcode', 'status', 'first_name', 'last_name','session_id','login_status','browser_status')->first();
		if ($user) 
		{

			$new_sessid = \Session::getId();

			$ip = Controller::getIpAddress();
			DB::table('sresu')->where('id', $user->id)->update(['session_id' => $new_sessid,'login_status'=>'1','browser_status'=>'1', 'user_active_status' => 1, 'user_active_ip' => $ip]);

			$userId = $user['id'];
			$status = $user['status'];
			$active = $user['activation_code'];
			$tfa_login = $user['randcode'];
			if ($status == 1) {
				if ($tfa_login == 1) {
					$temp_time = date("Y/m/d H:i:s", strtotime("+10 minutes"));
					session::put(['temp_user_id' => $userId, 'temp_time' => strtotime($temp_time)]);
					return redirect("twofa");
				}
				$type = 'Logged_in';
				$create_activity = Controller::UserActivityEntry($userId, $type, $usermail);
				if ($create_activity == 3) {
					$message = 'You have try to new device login,If you not please contact to support';
					Notificationlist::create(array('user_id' => $userId, 'message' => $message));
					Session::flash('error', trans('app_lang.check_reg_email'));
					return redirect("/");
				}

				$profile = $user['first_name'] . ' ' . $user['last_name'];
				session::put(['tmaitb_user_id' => $userId, 'tmaitb_user_login' => true, 'tmaitb_user_logintime' => date('Y-m-d H:i:s'), 'tmaitb_user_email' => $usermail, 'tmaitb_profile' => $profile]);

				if (Session::get('temp_wstatus') && Session::get('temp_wid')) {
					$wid = Session::get('temp_wid');
					if (Session::get('temp_wstatus') == '1') {
						return redirect('/confirmtranferbyuser/' . $wid);

					} elseif (Session::get('temp_wstatus') == '0') {
						return redirect('/rejecttranferbyuser/' . $wid);
					}
				}
				if ($profile == ' ') {

					Session::flash('success', trans('app_lang.logged_in_success_lng'));
					return redirect('/dashboard');
				} else {

					Session::flash('success', trans('app_lang.logged_in_success_lng'));
					Session::put('form', 'check');
					return redirect('/dashboard');
				}

			} else if ($active != '') {
				Session::flash('error', trans('app_lang.activate_your_account'));
				return Redirect::back();
			} else {
				Session::flash('error', trans('app_lang.contact_support_team'));
				return Redirect::back();
			}
		} 
		else
		{
			Session::flash('error', trans('app_lang.invalid_login_credential'));
			return Redirect::back();
		}
	}

	public function activateUserAccount($activation) 
	{
		$activation = insep_decode($activation);
		$getUser = User::select('id', 'contentmail', 'liame', 'refer_by')->where('activation_code', $activation)->where('status', '0')->first();

		if ($getUser) 
		{
			$userId = $getUser->id;
			$email = insep_decode($getUser->contentmail) . insep_decode($getUser->liame);
			$fiatcurr = Currency::where('symbol', 'USD')->select('id')->first();
			Wallet::updateBalance($userId, $fiatcurr->id);
			DB::beginTransaction();
			try {
				$update = User::where('activation_code', $activation)->update(['status' => '1', 'activation_date' => date('Y-m-d H:i:s'), 'activation_code' => '']);
				if ($update) {
					$type = 'Activation';
					$create_activity = Controller::UserActivityEntry($userId, $type);
					DB::commit();
					Session::flash('success', trans('app_lang.email_activate_success_lng'));

				} else {
					Session::flash('error', trans('app_lang.email_not_activated'));
				}
			} catch (\Exception $e) {
				DB::rollback();
				Session::flash('error', trans('app_lang.something_wrong'));
			}

		}
		else 
		{
			Session::flash('error', trans('app_lang.email_activated_lng'));
		}
		return Redirect::to('/login');
	}
	
	public function register($id = '') 
	{
		$viewsource = 'front.common.register';
		if ($id) 
		{
			$refid = strip_tags($id);
			$count = User::where('referrer_name', $id)->count();
			if ($count == 0) {
				$refid = '';
			}

		} else {
			$refid = '';
		}
		$ip = Controller::getIpAddress();
		$ipPlugin = 'extreme-ip-lookup.com/json/' . $ip;
		$addrDetails = Controller::getUrlContents($ipPlugin);
		$ipDetail = json_decode($addrDetails);

		if($ipDetail->status == "fail")
		{
			$country = 'India';
		}
		else
		{
			$country =$ipDetail->country;
		}



		$countrydetails = Country::where('status', '1')->orderBy('id', 'asc')->get();
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();

		return view('front.common.common', compact('viewsource', 'refid','newsdetails','countrydetails','country'));
	}
	public function makeRegisterr()
	{
		$viewsource = 'front.common.new_register';
		$refid = '';
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();

		return view('front.common.common', compact('viewsource', 'refid','newsdetails'));
	}
	
	public function makeRegister() 
	{
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|unique_email",
			'password' => 'required|confirmed|min:8',
			'password_confirmation' => 'required|min:8',
			'iagree' => 'required'
		], [
			'email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'email.unique_email' => 'Email address already exists',
			'iagree.required' => 'Agree our terms and conditions',
			'password.required' => 'Please Enter valid password',
			'password.min' => 'Enter atleast 8 characters',
			'password_confirmation.required' => 'Enter confirm password',
			'otp_num.required' => 'Enter OTP code',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}

		$password = strip_tags($data['password']);
		$email = strtolower(strip_tags($data['email']));
	
		$a=explode("@",$email);
		if($a[1] !='')
		{
		$str = file_get_contents(app_path('disposal_email.json'));
		$json = array_values(json_decode($str, true)); 
		if (in_array($a[1], $json))
		{
		Session::flash('error', 'Invalid Email id');
				return Redirect::back();	
		}

		}


		$first_mail = insep_encode(firstEmail($email));
		$second_mail = insep_encode(secondEmail($email));
		$password = insep_encode($password);
		$ip = Controller::getIpAddress();
		

		$activation_code = time() . randomString(7) . rand(10, 1000);

		$userdata = array('contentmail' => $first_mail,
			'liame' => $second_mail,
			'ticket' => $password,
			'status' => "0",
			'activation_code' => $activation_code,
			'created_on' => date('Y-m-d H:i:s'),
			'ip_address' => $ip,
			'mobile' => $data['mobileno'],
			'first_name' => $a[0],
			'set_default_currency' => 'GBP'
		);

		if (isset($data['refer_id'])) {
			$referId = strip_tags($data['refer_id']);
			$getUser = User::where('referrer_name', $referId)->select('id')->first();
			if ($getUser) {
				$userdata['refer_by'] = $getUser->id;
			} else {
				Session::flash('error', trans('app_lang.invalid_referral'));
				return Redirect::back();
			}
		}
		$checkmbl = User::select('mobile')->where('mobile', $data['mobileno'])->count();
		if($checkmbl > 0)
		{
			Session::flash('error', 'This mobile number already exsits');
			return redirect()->back();
		}
		/*$reqotp=Reqotp::where('mobilenum',$data['mobileno'])->first();
		if($reqotp)
		{*/
			/*if($reqotp->otp==$data['otp_num'])
			{*/


				$insertData = User::create($userdata);

				if ($insertData) {
					$userId = $insertData->id;

					$ref = $userId . rand(10, 1000);
					User::where('id', $userId)->update(['referrer_name' => $ref]);
					Notifications::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s')]);
					ConsumerVerification::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s'),
						'type' => 'Not Required',
						'id_proof_front' => '',
						'id_proof_back' => '',
						'id_status' => 3,
						'id_reject' => '',
						'id_update_by' => 1,
						'id_update_time' => date("Y-m-d H:i:s"),
						'selfie_proof' => '',
						'selfie_status' => 3,
						'selfie_reject' => '',
						'selfie_update_by' => 1,
						'selfie_update_time' => date("Y-m-d H:i:s"),
						'created_at' => date("Y-m-d H:i:s"),
						'updated_at' => date("Y-m-d H:i:s")
					]);

					Wallet::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s')]);
					$type = 'Register';
					$create_activity = Controller::UserActivityEntry($userId, $type);

					$encryptUId = insep_encode($activation_code);
					$securl = url("/activateaccount/" . $encryptUId);
					$info = array('###EMAIL###' => $email, '###LINK###' => $securl);

					$sendEmail = Controller::sendEmail($email, $info, '1');
					if ($sendEmail) {
						Session::flash('success', trans('app_lang.activation_link_sent_email_lng'));
						return Redirect::to('/login');
					} else {
						Session::flash('error', trans('app_lang.email_send_failed'));
					}

				} else {
					Session::flash('error', trans('app_lang.fail_create_user'));
				}

			/*}
			else
			{
				Session::flash('error', trans('app_lang.otp_wrong'));
				return redirect()->back();
			}*/

		/*}
		else
		{
			Session::flash('error', trans('app_lang.otp_wrong'));
			return redirect()->back();
		}*/


		return Redirect::back();
	}
   
	public function tfaLogin() 
	{
		$tempuserId = session::get('temp_user_id');
		$temptime = session::get('temp_time');
		$cur_time = time();
		if ($tempuserId != '' && $cur_time < $temptime) 
		{
			$viewsource = 'front.common.tfa_login';
			$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
			return view('front.common.common', compact('viewsource','newsdetails'));
		}else 
		{
			return Redirect::to('/login');
		}
	}
	
	public function checkTfaLogin()
	{
		$tempuserId = session::get('temp_user_id');
		$temptime = session::get('temp_time');
		$cur_time = time();
		if ($tempuserId != '' && $cur_time < $temptime) 
		{
			$data = Input::all();
			$validate = Validator::make($data, [
				'tfa' => "required|numeric|min:6",
			], [
				'tfa.required' => 'Enter authentication code',
				'tfa.numeric' => 'Enter valid authentication code',
				'tfa.min' => 'Enter valid authentication code',
			]
		);
			if ($validate->fails()) {
				foreach ($validate->messages()->getMessages() as $val => $msg) {
					Session::flash('error', $msg[0]);
					return Redirect::back();
				}
			}
			require_once app_path('Model/Googleauthenticator.php');
			$ga = new Googleauthenticator();
			$get_data = User::where('id', $tempuserId)->select('liame', 'contentmail', 'secret', 'first_name', 'last_name', 'randcode')->first();
			if ($get_data->randcode) {
				if ($ga->verifyCode($get_data->secret, $data['tfa'], 2)) {
					$type = 'Logged_in';
					$create_activity = Controller::UserActivityEntry($tempuserId, $type);
					if ($create_activity == 3) {
						Session::flash('error', trans('app_lang.check_reg_email'));
						return redirect("/");
					}

					$usermail = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
					$profile = $get_data->first_name . ' ' . $get_data->last_name;
					session::put(['temp_user_id' => '', 'temp_time' => '', 'tmaitb_user_id' => $tempuserId, 'tmaitb_user_login' => true, 'tmaitb_user_logintime' => date('Y-m-d H:i:s'), 'tmaitb_user_email' => $usermail, 'tmaitb_profile' => $profile]);
					if ($profile == ' ') {
						Session::flash('success', trans('app_lang.logged_in_success_lng'));
						return redirect('/dashboard');
					} else {

						Session::flash('success', trans('app_lang.logged_in_success_lng'));
						Session::put('form', 'check');
						return redirect('/dashboard');
					}

				} else {
					Session::flash('error', trans('app_lang.invalid_authentication_code'));
					return Redirect::back();
				}
			} else {
				return Redirect::to('/login');
			}
		} 
		else 
		{
			return Redirect::to('/login');
		}
	}
    
	public function forgotPassword() {
		$viewsource = 'front.common.forgot';
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.common.common', compact('viewsource','newsdetails'));
	}
	
	public function forgotPasswordRequest() 
	{
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|exist_email",
		], [
			'email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'email.exist_email' => 'User not exists',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$usermail = strtolower(strip_tags($data['email']));
		$first = insep_encode(firstEmail($usermail));
		$second = insep_encode(secondEmail($usermail));
		$user = User::where(['contentmail' => $first, 'liame' => $second])->select('id', 'activation_code', 'status')->first();
		if ($user) 
		{
			if ($user->status == 1) 
			{
				$reset_code = randomString(7) . time() . $user->id . rand(1, 1000);
				$startDate = time();

				$updatedate = date('Y-m-d H:i:s', strtotime('+1 day', $startDate));
				$update = User::where('id', $user->id)->update(['forgot_code' => $reset_code, 'forgotten_time' => strtotime($updatedate)]);
				if (!$update) {
					Session::flash('error', trans('app_lang.please_try_again'));
					return Redirect::back();
				}
				$encryptUId = insep_encode($reset_code);
				$securl = url("/resetaccount/" . $encryptUId);
				$info = array('###EMAIL###' => $usermail, '###LINK###' => $securl);

				$sendEmail = Controller::sendEmail($usermail, $info, '2');
				if ($sendEmail) {
					$type = 'Forgot_request';
					$create_activity = Controller::UserActivityEntry($user->id, $type);
					Session::flash('success', trans('app_lang.reset_link'));
				} else {
					Session::flash('error', trans('app_lang.email_send_failed'));
				}
				return Redirect::back();

			} else if ($user->activation_code) {
				Session::flash('error', trans('app_lang.activate_your_account'));
				return Redirect::back();
			} else {
				Session::flash('error', trans('app_lang.contact_support_team'));
				return Redirect::back();
			}
		} 
		else 
		{
			Session::flash('error', trans('app_lang.user_not_exists'));
			return Redirect::back();
		}
	}
	
	public function resetaccount($id) 
	{
		$fid = insep_decode($id);
		$record = User::where('forgot_code', $fid)->select('forgot_code','forgotten_time', 'id')->first();
		if (!empty($record->forgot_code))  
		{
			$forgotten_time = $record->forgotten_time;
			$userid = insep_encode($record->id);
			$cur_time = time();
			if ($cur_time > $forgotten_time) {
				Session::flash('error', trans('app_lang.link_expire'));
				return Redirect::back();
			} else {
				$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
				$viewsource = 'front.common.reset';
				return view('front.common.common', compact('viewsource', 'userid', 'id','newsdetails'));
			}

		} 
		else 
		{
			Session::flash('error', trans('app_lang.link_expire'));
			return redirect('/');
		}

	}
	
	public function resetPassword()
	{
		$data = Input::all();
		$validate = Validator::make($data, [
			'password' => 'required|confirmed|min:8',
			'password_confirmation' => 'required|min:8',
		], [
			'password.required' => 'Enter password',
			'password.min' => 'Enter atleast 8 characters',
			'password_confirmation.required' => 'Enter confirm password',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$password = strip_tags($data['password']);
		$userid = insep_decode(strip_tags($data['userdata']));
		$id = insep_decode(strip_tags($data['id']));
		$c_password = strip_tags($data['password_confirmation']);
		$user = User::select('id')->where('id', $userid)->where('forgot_code', $id)->first();
		if ($user) 
		{
			$password = insep_encode($password);
			$update = User::where('id', $userid)->update(['ticket' => $password, 'forgot_code' => "","online"=>'1']);
			if ($update) {
				Session::flash('success', trans('app_lang.password_reset_success'));
				return redirect('login');
			} else {
				Session::flash('error', trans('app_lang.please_try_again'));
			}
		} 
		else 
		{
			Session::flash('error', trans('app_lang.reset_link_expired'));
		}
		return Redirect::back();
	}
    
	public function validatEmail() 
	{

		$email = strip_tags(strtolower($_GET['email']));
		$type = strip_tags($_GET['type']);
		$first = insep_encode(firstEmail($email));
		$second = insep_encode(secondEmail($email));
		$getCount = User::where('contentmail', $first)->where('liame', $second)->count();
		if ($type == 1) {
			echo ($getCount > 0) ? "false" : "true";
		} else {
			echo ($getCount > 0) ? "true" : "false";
		}

	}
    
	public function pages($url) 
	{

		$cms = Cms::where('name', $url)->select('title', 'content', 'type', 'id')->first();
		$title = $cms->title;
		$content = $cms->content;
		$type = $cms->type;
		$page_id = $cms->id;
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		if ($type == 'page') 
		{
			$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
			$viewsource = 'front.common.cms';
			$page = 0;
			return view('front.common.index', compact('viewsource', 'content', 'title', 'pair_image', 'page_id','from_symbol','to_symbol','pairid','newsdetails','page'));
		} 
		else 
		{
			$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
			$viewsource = 'front.common.cms_edit';
			$page = 0;
			return view('front.common.index', compact('viewsource', 'content', 'title', 'pair_image', 'page_id','from_symbol','to_symbol','pairid','newsdetails','page'));
		}
	}

	
	public function logout() 
	{
		$userId = session::get('tmaitb_user_id');
		if ($userId != '') {
			$type = 'Logged_out';
			
			$create_activity = Controller::UserActivityEntry($userId, $type);
			DB::table('sresu')->where('id', $userId)->update(['session_id' => '','login_status' => '0','browser_status' => '0', 'user_active_status' => 0, 'user_active_ip' => 0]);
			Session::forget('tmaitb_user_id');
		}
		Session::flash('success', trans('app_lang.logged_out_lng'));
		return view('front.logout');
	}
	
	public function maintenance() 
	{
		getHeaders();
		$getSite = SiteSettings::where('id', 1)->select('maintain_status', 'site_maintenance')->first();
		if ($getSite->maintain_status == 1) {
			$content = $getSite->site_maintenance;
			echo $content;exit;
		} else {
			return redirect('/');
		}
	}
	
	public function faq() 
	{
		$faq = Faq::where('status', 'active')->select('question', 'description')->get();
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.common.faq';
		$page = 0;
		return view('front.common.index', compact('viewsource', 'faq', 'pair_image','from_symbol','to_symbol','pairid','newsdetails','page'));
	}
	
	public function contactus() 
	{
		$viewsource = 'front.common.contactus';
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$page = 0;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.common.index', compact('viewsource', 'pair_image','from_symbol','to_symbol','pairid','newsdetails','page'));
	}
	
	public function fees() 
	{
		$viewsource = 'front.common.fees';
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();
		$tradefee = TradePairs::where('status', '1')->select('from_symbol','to_symbol','trade_fee', 'refer_fee','taker_trade_fee')->get();
		$withfee = Currency::where('status', '1')->select('with_fee','symbol')->get();
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$page = 0;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.common.index', compact('viewsource','pair_image','tradefee','withfee','from_symbol','to_symbol','pairid','newsdetails','page'));
	}
	
	public function makecontactus() 
	{
		$data = Input::all();

		$validate = Validator::make($data, [
			'email_address' => "required|email",
			'subject' => 'required|min:4',
			'message' => 'required',
			'full_name' => 'required',
		], [
			'email_address.required' => 'Enter email address',
			'email_address.email' => 'Enter valid email address',
			'full_name.required' => 'Enter Your Full Name',
			'message.required' => 'Enter Message',
			'subject.min' => 'Enter atleast 4 characters',
			'subject.required' => 'Enter Subject',
		]
	);

		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}

		$name = trim(strip_tags($data['full_name']));
		$email = trim(strip_tags($data['email_address']));
		$subject = trim(strip_tags($data['subject']));
		$message = trim(strip_tags($data['message']));

		$userdata = array('user_name' => $name,
			'email' => $email,
			'subject' => $subject,
			'message' => $message,
			'read_status' => 'unread',
		);

		$insertData = Support::create($userdata);

		if ($insertData) {
			$info = array('###NAME###' => $name, '###MESSAGE###' => $message, '###EMAIL###' => $email, '###SUB###' => $subject);

			$to1 = getSiteaddress('site_email');
			$to  = insep_decode($to1);
			$sendEmail = Controller::sendEmail($to, $info, '12');

			
			Session::flash('success', trans('app_lang.contact_submitted_success'));
		} else {
			Session::flash('error', trans('app_lang.please_try_again'));
		}

		return Redirect::back();
	}
    
	public function subscribe() 
	{
		$data = Input::all();
		$email = strip_tags($data['email_address']);
		
		$userdata = array('email' => $email,
			'status' => '1',
			'created_at' => date('Y-m-d H:i:s'),
		);
		$insertData = Subscribe::create($userdata);
		if ($insertData) {
			Session::flash('success', trans('app_lang.subscribed_success'));
		} else {
			Session::flash('error', trans('app_lang.something_error'));
		}
		return Redirect::back();
	}
    
	public function subscribe_valid_mail() 
	{

		$email = strip_tags(strtolower($_GET['email_address']));
		$type = strip_tags($_GET['type']);

		$getCount = Subscribe::where('email', $email)->count();

		if ($type == 1) {
			echo ($getCount > 0) ? "false" : "true";
		} else {
			echo ($getCount > 0) ? "true" : "false";
		}
	}
	
	function newsPage() 
	{
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();

		$news = News::where('status', 'active')->orderBy('id', 'desc')->paginate(5);
		$link = News::where('status', 'active')->get();
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.common.news';
		$page = 'news';

		return view('front.common.index', compact('viewsource', 'news', 'page', 'link', 'pair_image','from_symbol','to_symbol','pairid','newsdetails'));
	}
    
	function newsdetails($id) 
	{
		$pair_image = Currency::where('status', '1')->select('symbol', 'image')->pluck('image', 'symbol')->toArray();
		$news = News::where('id', $id)->first();
		$page = 'details';
		$count = $news->views + 1;
		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		News::where('id', $id)->update(['views' => $count]);
		$viewsource = 'front.common.news';
		return view('front.common.index', compact('viewsource', 'news', 'page', 'pair_image','from_symbol','to_symbol','pairid','newsdetails'));
	}
    
	function setlanguage($request) 
	{
		$set_lang = $request;
		if ($set_lang != "") {
			$session_arr = array(
				'language' => $set_lang);
			Session::put($session_arr);
		} else {
			$set_lang = "en";
			$session_arr = array(
				'language' => $set_lang);
			Session::put($session_arr);
		}

	}

	public function useractivityallow($activation,$ip) 
	{
		$user_id = insep_decode($activation);
		
		$ipPlugin = 'extreme-ip-lookup.com/json/' . $ip;
		$addrDetails = Controller::getUrlContents($ipPlugin);
		$ipDetail = json_decode($addrDetails);
		$city = (isset($ipDetail->city)) ? $ipDetail->city : "";
		$country = (isset($ipDetail->country)) ? $ipDetail->country : "";
		$city = ($city != "") ? $city : 'Madurai';
		$country = ($country != "") ? $country : 'India';

		$activity = array('ip_address' => $ip, 'browser_name' => Controller::getBrowser(), 'activity' => 'Logged_in', 'user_id' => $user_id, 'os' => Controller::getOS(), 'city' => $city, 'country' => $country, 'status' => 'allowed');

		$details = UserActivity::where('user_id', $user_id)->where('ip_address', $ip)->get();

		if (count($details) == 0) 
		{
			$res = UserActivity::create($activity);
			if ($res) {
				$user = User::where('id', $user_id)->select('id', 'activation_code', 'randcode', 'status', 'first_name', 'last_name', 'contentmail', 'liame')->first();
				$usermail = insep_decode(strip_tags($user['contentmail'])) . insep_decode(strip_tags($user['liame']));
				$profile = $user['first_name'] . ' ' . $user['last_name'];

				session::put(['tmaitb_user_id' => $user_id, 'tmaitb_user_login' => true, 'tmaitb_user_logintime' => date('Y-m-d H:i:s'), 'tmaitb_user_email' => $usermail, 'tmaitb_profile' => $profile]);
				if ($profile == ' ') {
					Session::flash('success', trans('app_lang.logged_in_success'));
					return redirect('/dashboard');
				} else {

					Session::flash('success', trans('app_lang.logged_in_success'));
					Session::put('form', 'check');
					return redirect('/dashboard');
				}

			}
		} 
		else 
		{
			Session::flash('error', trans('app_lang.link_already_used'));
		}

		return Redirect::to('/');
	}
	
	public function useractivityblock($user_id, $ip_add) 
	{

		$user_id = insep_decode($user_id);

		$details = UserActivity::where('user_id', $user_id)->where('ip_address', $ip_add)->get();
		if (count($details) == 0) {
			$checkIp = BlockIP::where('ip_addr', $ip_add)->count();
			if ($checkIp == 0) {
				$insdata = array('ip_addr' => $ip_add, 'status' => 'active');
				$createIp = BlockIP::create($insdata);
				Session::flash('success', trans('app_lang.address_blocked'));
			} else {
				Session::flash('error', trans('app_lang.address_already_blocked'));
			}
		} 
		else 
		{
			Session::flash('error', trans('app_lang.link_already_used'));
		}
		return Redirect::to('/');

	}
    
	public function getchart($pair_symbol) 
	{
		return view('front.common.chart', compact('pair_symbol'));

	}

	
	public function errorPage($id) {
		return view('error.error');
	}
    
	public function captcha() {
		
		require_once app_path('Model/class.geetestlib.php');
		require_once app_path('Model/config.php');

		$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);

		$data = array(
			"user_id" => "test",
			"client_type" => "web", 
			"ip_address" => "127.0.0.1", 
		);

		$status = $GtSdk->pre_process($data, 1);
		
		Session::put('gtserver', $status);
		Session::put('user_id', $data['user_id']);
		echo $GtSdk->get_response_str();
	}
	public function test()
	{
		conv_home();
	}
	public function test1($id)
	{		
		$convertion = callconversion($id);
	}
	public function comingsoon()
	{
		return view('front.common.comingsoon');
	}

	public function userhiddenact(Request $request)
	{

		echo $segment=$request["segment"];exit;

		$attempts = Cookie::get('loginAttemptspages');

		loginAttemptspages($attempts,$segment);

	}
	
	public function apidocument()
	{

		$viewsource = 'front.common.apidocs';
		

		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$newshome = News::where('status', 'active')->orderBy('id', 'desc')->limit(2)->get();
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		
		$banner = Banner::where('page','Apidocument')->first();
		$home = 1;
		$page = 8;
		return view('front.common.apiindex', compact('viewsource', 'pairDetails', 'pair_image', 'home', 'features', 'currency','from_symbol','to_symbol','pairid','banner','newsdetails','newshome','page','pairDetails1'));
		
		
	}

	public function OTP($mobile)
	{
		$otp = Reqotp::where('mobilenum', $mobile)->select('otp')->first();
		echo $otp;
	}

	public function check()
	{
		/*echo insep_decode("j3RJ_-w3-Z7wEOXT2ZLH2CZzxv4GDZAi3kM8TWybbOMM-EsJirBPdeFzKTWmazkD9gtBJamtg_fRlQL7yfZQ6A");*/
	}
	public function set_default_currency_price($symbol){

		$userId = session::get('tmaitb_user_id');
		$db = User::where('id', $userId)->select('set_default_currency')->first();
		if(isset($db->set_default_currency) && $db->set_default_currency == "GBP")
		{
			$cryptoCurr = Currency::where('symbol', $symbol)->first();
			return $cryptoCurr->gbp_value;
		}
		elseif(isset($db->set_default_currency) && $db->set_default_currency == "EUR")
		{
			$cryptoCurr = Currency::where('symbol', $symbol)->first();
			return $cryptoCurr->eur_value;	
		}
		else
		{
			$cryptoCurr = Currency::where('symbol', $symbol)->first();
			return $cryptoCurr->inr_value;	
		}
	}
	public function depositchart()
	{
		if(isset($_GET['report']))
		{

			$return = array();
			$userId = session::get('tmaitb_user_id');
			$db = User::where('id', $userId)->select('set_default_currency')->first();
			$set_default_currency = isset($db->set_default_currency)?$db->set_default_currency:'GBP';
			

			$report = isset($_GET['report'])?$_GET['report']:'1D';

			 if($report == '1D'){

			 	$start_date = date("Y-m-d 00:00:00");
			 	$end_date = date("Y-m-d 23:59:59");

			 }
			 elseif($report == '1M')
			 {
			 	$start_date = date("Y-m-01 00:00:00", strtotime("-1 days"));
			 	$end_date = date("Y-m-31 23:59:59");
			 }
			 elseif($report == '1Y')
			 {
				/*$CryptoDepositDate= Deposit::where(array("user_id" =>  $userId, 'status' => 'Completed'))
				->orderBy('created_at', 'asc')->first();

				$FiatDepositDate = Fiatdeposit::where(array("user_id" =>  $userId, 'status' => 'Completed'))
			->orderBy('created_at', 'asc')->first();
*/
				/*echo $CryptoDepositDate->created_at.'<='.$FiatDepositDate->created_at;
				echo "<br/>";
				var_dump(strtotime($CryptoDepositDate->created_at) < strtotime($FiatDepositDate->created_at));
				echo "<br/>";
				echo "<br/>";
				echo $CryptoDepositDate->created_at.'>='.$FiatDepositDate->created_at;
				echo "<br/>";
				echo "<br/>";				
				var_dump(strtotime($CryptoDepositDate->created_at) > strtotime($FiatDepositDate->created_at));
				exit;*/


				/*if(strtotime($CryptoDepositDate->created_at) <= strtotime($FiatDepositDate->created_at)  ){
					$start_date = date("Y-m-d 00:00:00", strtotime($CryptoDepositDate->created_at));	
				}
				else if(strtotime($CryptoDepositDate->created_at) >= strtotime($FiatDepositDate->created_at)  ){
					$start_date = date("Y-m-d 00:00:00", strtotime($FiatDepositDate->created_at));	
				}
				else
				{*/
				$start_date = date("Y-01-01 00:00:00");				
				/*}*/
			 	
			 	$end_date = date("Y-12-31 23:59:59");			 	
			 }

			$start_date = Carbon::parse($start_date)
                             ->toDateTimeString();

            $end_date = Carbon::parse($end_date)
                             ->toDateTimeString();                             
			 

			$CryptoDepositHistory = Deposit::where(array("user_id" =>  $userId, 'status' => 'Completed'))
			->whereBetween('created_at',[$start_date,$end_date])->orderBy('created_at', 'asc')->get();

			$i = 1; 
			$totalEurPrice = 0;
			$sameArray = array();
			$sameArrayValues = array();

			$initial = date("Y-m-d H:i", strtotime($start_date));
			$return[0] = array($initial, 0.00);

			foreach($CryptoDepositHistory as $key => $value)
			{
				if(isset($value->currency))
				{
					$crypto_amount = $value->amount;
					$cryptoCurr = Currency::where('symbol', $value->currency)->first();
					if(isset($value->amount))
					{

						$last_price = self::set_default_currency_price($value->currency); //eur_value
						$amount = $last_price * $value->amount;
						$amount = number_format($amount, 8);
						$totalEurPrice = $totalEurPrice + $amount;
						$created_at = date("Y-m-d H:i", strtotime($value->created_at));
						//$return[$i] = array($created_at, floatval($amount));

						if(in_array($created_at, $sameArray))
						{
							
							$key = $sameArrayValues[$created_at][0];
							$date = $sameArrayValues[$created_at][1];
							$kesAmount = $sameArrayValues[$created_at][2];
							$newAmount = $kesAmount + $amount;

							$return[$key] = array($created_at, floatval($newAmount));
							
						}
						else
						{

							$return[$i] = array($created_at, floatval($amount));
							$sameArray[$i] = $created_at;
							$sameArrayValues[$created_at] = array($i, $created_at, floatval($amount));
						}
						
						
						$i++;
					}
				}
			}
			$FiatDepositHistory = Fiatdeposit::where(array("user_id" =>  $userId, 'status' => 'Completed'))
			->whereBetween('created_at',[$start_date,$end_date])->orderBy('created_at', 'asc')->get();
			foreach ($FiatDepositHistory as $keyxz => $valuexz)
			{
				
				if(isset($valuexz->currency))
				{
					$fiat_amount = $valuexz->amount;
					$fiatCurr = Currency::where('symbol', $valuexz->currency)->first();
					if(isset($valuexz->amount) && $valuexz->currency == $set_default_currency) //EUR
					{
						//$last_price = $fiatCurr->eur_value;
						$created_at = date("Y-m-d H:i", strtotime($valuexz->created_at));
						if(in_array($created_at, $sameArray))
						{
							
							$amount = $valuexz->amount;
							$key = $sameArrayValues[$created_at][0];
							$date = $sameArrayValues[$created_at][1];
							$kesAmount = $sameArrayValues[$created_at][2];
							$newAmount = $kesAmount + $amount;

							$return[$key] = array($created_at, floatval($newAmount));
							$sameArrayValues[$created_at] = array($key, $created_at, floatval($newAmount));
							$i++;
							
						}
						else
						{

							$amount = $valuexz->amount;
							$amount = number_format($amount, 8);

							$totalEurPrice = $totalEurPrice + $amount;
							$created_at = date("Y-m-d H:i", strtotime($valuexz->created_at));
							$return[$i] = array($created_at, floatval($amount));
							$sameArray[$i] = $created_at;
							$sameArrayValues[$created_at] = array($i, $created_at, floatval($amount));
							$i++;
						}
					}
					else
					{
						$created_at = date("Y-m-d H:i", strtotime($valuexz->created_at));
						if(in_array($created_at, $sameArray))
						{
							//$last_price = $fiatCurr->eur_value;
							$last_price = self::set_default_currency_price($valuexz->currency); 
							$amount = $last_price * $valuexz->amount;
							$amount = number_format($amount, 8);
							$totalEurPrice = $totalEurPrice + $amount;
							

							$key = $sameArrayValues[$created_at][0];
							$date = $sameArrayValues[$created_at][1];
							$kesAmount = $sameArrayValues[$created_at][2];
							$newAmount = $kesAmount + $amount;

							$return[$key] = array($created_at, floatval($newAmount));
							$sameArrayValues[$created_at] = array($key, $created_at, floatval($newAmount));
							$i++;
						}
						else
						{

							//$last_price = $fiatCurr->eur_value;
							$last_price = self::set_default_currency_price($valuexz->currency);
							$amount = $last_price * $valuexz->amount;
							$amount = number_format($amount, 8);
							$totalEurPrice = $totalEurPrice + $amount;
							$created_at = date("Y-m-d H:i", strtotime($valuexz->created_at));

							$return[$i] = array($created_at, floatval($amount));

							$sameArray[$i] = $created_at;
							$sameArrayValues[$created_at] = array($i, $created_at, floatval($amount));
							$i++;
						}
					}
				}
			}


			if($report == '1Y' || $report == '1M')
			{
				$date1 = new DateTime($start_date);
				$date2 = new DateTime($end_date);
				$days  = $date2->diff($date1)->format('%a');

				$strDate = date("d-m-Y", strtotime($start_date));
				for($m=0; $m<=$days; $m++)
				{
					
					if(isset($return[$m][0]) && !empty($return[$m][0]))
					{

						$dateRecord = $return[$m][0];
						if($dateRecord == strtotime($strDate))
						{
							$strDate = date("d-m-Y", strtotime("$strDate +1 day"));
							$m++;
						}
					}
					else
					{
						$updateDate = date("Y-m-d H:i", strtotime($strDate));
						$return[] = array($updateDate, 0.00);
						$strDate = date("d-m-Y", strtotime($strDate." +1 day"));
						$m++;
					}
				}
			}
			else
			{
				$strDate = date("d-m-Y H:i", strtotime($start_date));
				for($m=0; $m<=24; $m++)
				{					
					if(isset($return[$m][0]) && !empty($return[$m][0]))
					{

						$dateRecord = $return[$m][0];
						if($dateRecord == strtotime($strDate))
						{
							$strDate = date("d-m-Y H:i", strtotime("$strDate +1 hour"));
							$m++;
						}
					}
					else
					{
						$updateDate = date("Y-m-d H:i", strtotime($strDate));
						$return[] = array($updateDate, 0.00);
						$strDate = date("d-m-Y H:i", strtotime($strDate." +1 hour"));
						$m++;
					}
				}
			}
			
			sort($return);
			//print_r($return[0]);
			//exit;
			//
			$userbalance = $curr = array();
			$allcurr = Currency::where('status', 1)->select('type', 'image', 'symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance','deposit_status', 'inr_value', 'btc_value','eur_value', 'gbp_value')->get()->map(function ($curr) {return ['key' => $curr->symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();

			$userbalance = Wallet::getBalance($userId);
			$estimateinr = 0;
			foreach ($allcurr as $curr)
			{
				$symbol = $curr['symbol'];
				if (isset($userbalance[$curr['id']]))
				{
					$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");
					$defaultprice = self::set_default_currency_price($symbol);
					//$inrbalance = $balance * $curr['eur_value'];
					$inrbalance = $balance * $defaultprice;

				}
				else
				{	
					$balance = 0;
					$inrbalance = 0;
				}
				$estimateinr += $inrbalance;
			}

			$newarr = array();
			if(count($return) > 0)
			{
				foreach($return as $value)
				{
					$newarr['price_usd'][] = $value;
				}
				$newarr['total'] = number_format($estimateinr, 2);
			}
			else
			{
				$created_at = date("Y-m-d");
				$return = array($created_at, 0.00);
				$newarr['price_usd'][] = $return;
				$newarr['total'] = number_format($estimateinr, 2);
			}

			return json_encode($newarr);

		}
		else
		{
			$return = array();
			$return['price_usd'][0] = array(1483275269000, 0.00);
			$return['total'] = 0.00;
			return json_encode($return);
		}
	}
}
