<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Cms;
use App\Model\ConsumerVerification;
use App\Model\Country;
use App\Model\Faq;
use App\Model\Googleauthenticator;
use App\Model\News;
use App\Model\Notifications;
use App\Model\Subscribe;
use App\Model\Support;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Reqotp;
use App\Model\SiteSettings;
use Illuminate\Support\Facades\Input;
use URL;
use Validator;

class ApiHome extends Controller {
	public function __construct() {

	}
	
	
	public function registration() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|unique_email",
			'password' => 'required|confirmed|min:8',
			'password_confirmation' => 'required|min:8',
			'iagree' => 'required',
			'otp_num' => 'required',
		], [
			'email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'email.unique_email' => 'Email address already exists',
			'password.required' => 'Enter password',
			'password.min' => 'Enter atleast 8 characters',
			'password_confirmation.required' => 'Enter confirm password',
			'otp_num.required' => 'Enter OTP code',
		]
	    );
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$password = strip_tags($data['password']);
		$email = strtolower(strip_tags($data['email']));
		$first_mail = insep_encode(firstEmail($email));
		$second_mail = insep_encode(secondEmail($email));
		$password = insep_encode($password);

		$device_type = $data['device_type'];
		$ip = Controller::getIpAddress();
		$activation_code = time() . randomString(8) . rand(10, 1000);

		$a=explode("@",$email);

		$userdata = array(
			'contentmail' => $first_mail,
			'liame' => $second_mail,
			'ticket' => $password,
			'status' => "0",
			'is_site' => "0",
			'activation_code' => $activation_code,
			'created_on' => date('Y-m-d H:i:s'),
			'ip_address' => $ip,
			'mobile' => $data['mobileno'],
			'first_name' => $a[0],
			'set_default_currency' => 'GBP'

		);

		if (isset($data['refer_code'])) {
			$referId = strip_tags($data['refer_code']);
			$getUser = User::where('referrer_name', $referId)->select('id')->first();
			if ($getUser) {
				$userdata['refer_by'] = $getUser->id;
			} else {
				$data = array('status' => '0', 'message' => 'Invalid referral ID!');
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$checkmbl = User::select('mobile')->where('mobile', $data['mobileno'])->count();
		if($checkmbl > 0)
		{
			Session::flash('error', 'This mobile number already exsits');
			return redirect()->back();
		}
		$reqotp=Reqotp::where('mobilenum',$data['mobileno'])->first();
		if($reqotp)
		{
			if($reqotp->otp==$data['otp_num'])
			{
				$insertData = User::create($userdata);
				if ($insertData) {
					$userId = $insertData->id;
					$ref = $userId . rand(10, 1000);
					User::where('id', $userId)->update(['referrer_name' => $ref]);
					Notifications::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s')]);
					ConsumerVerification::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s')]);
					Wallet::create(['user_id' => $userId, 'created_at' => date('Y-m-d H:i:s')]);

					$encryptUId = insep_encode($activation_code);
					$securl = url("/activateaccount/" . $encryptUId);
					$info = array('###EMAIL###' => $email, '###LINK###' => $securl);

					$sendEmail = Controller::sendEmail($email, $info, '1', $device_type);
					if ($sendEmail) {
						$data = array('status' => '1', 'message' => 'Activation link sent to your registered email');

					} else {
						$data = array('status' => '0', 'message' => 'Email sending failed!');
					}

				} else {
					$data = array('status' => '0', 'message' => 'Failed to create user!');
				}
			}
			else {
				$data = array('status' => '0', 'message' => 'Your OTP is wrong');
			}
		}
		else {
			$data = array('status' => '0', 'message' => 'Your OTP is wrong');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
	}

	
	public function email_validation() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|unique_email",
		], [
			'email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'email.unique_email' => 'Email address already exists',
		]
	       );
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		echo json_encode(array('status' => '1', 'message' => 'success'), JSON_FORCE_OBJECT);
	}

	
	public function checkmobile() {
		$data = Input::all();
		if ($data) {
			$mobile_num= $data['mobile'];
			$check = User::select('mobile')->where('mobile', $mobile_num)->count();
			if ($check > 0) {
				$data = array('status' => '0', 'message' => 'Mobile number already exists');
			} else {
				$data = array('status' => '1', 'message' => 'True');
			}
		}
		echo json_encode($data);
	}

	
	public function login() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|exist_email",
			'password' => 'required',
			'device_type' => 'required',
		], [
			'email.required' => 'Enter email address',
			'device_type.required' => 'Device type required',
			'email.email' => 'Enter valid email address',
			'email.exist_email' => 'User not exists',
			'password.required' => 'Enter password',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}

		$password = strip_tags($data['password']);
		$usermail = strtolower(strip_tags($data['email']));
		$first = insep_encode(firstEmail($usermail));
		$second = insep_encode(secondEmail($usermail));
		$password = insep_encode($password);
		$user = User::where(['contentmail' => $first, 'liame' => $second, 'ticket' => $password])->select('id', 'activation_code', 'randcode', 'status', 'first_name', 'last_name', 'passcode')->first();
		if ($user) {
			$userId = $user['id'];
			
			$status = $user['status'];
			$active = $user['activation_code'];
			$passcode = $user['passcode'];
			$tfa_login = $user['randcode'];
			if ($status == 1) {
				if ($tfa_login == 1) {
					$data = array('status' => '2', 'message' => 'TFA required', 'user_id' => $userId);
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
				$device_type = strip_tags($data['device_type']);
				$type = 'Logged_in';
				$create_activity = Controller::UserActivityEntry($userId, $type, $usermail, 0, $device_type);

				if ($create_activity == 3) {
					$data = array('status' => '0', 'message' => 'You have try to new device login,If you not please contact to support');

				} else {
					$token = $userId . time() . rand(10, 100);
					$update = User::where('id', $userId)->update(['token' => $token]);
					if ($update) {

						$data = array('status' => '1', 'message' => 'Logged in successfully', 'user_id' => $userId, 'token' => $token, 'passcode' => $passcode);
					} else {
						$data = array('status' => '0', 'message' => 'Please try again');
					}
				}
			} else if ($active != '') {
				$data = array('status' => '0', 'message' => 'Please activate your account!');
			} else {
				$data = array('status' => '0', 'message' => 'Please contact support team, Your account deactivated!');
			}
		} else {
			$data = array('status' => '0', 'message' => 'Invalid login credentials');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;

	}

	
	public function tfaLogin() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'tfa' => "required|numeric|min:6",
			'user_id' => "required|numeric",
			'device_type' => 'required',
		], [
			'user_id.required' => 'Enter userid',
			'device_type.required' => 'Device type required',
			'user_id.numeric' => 'Enter valid userid',
			'tfa.required' => 'Enter authentication code',
			'tfa.numeric' => 'Enter valid authentication code',
			'tfa.min' => 'Enter valid authentication code',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}

		require_once app_path('Model/Googleauthenticator.php');
		$ga = new Googleauthenticator();
		$tempuserId = strip_tags($data['user_id']);
		$device_type = strip_tags($data['device_type']);
		$get_data = User::where('id', $tempuserId)->select('liame', 'contentmail', 'secret', 'randcode','passcode')->first();
		$usermail = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
		$passcode = $get_data->passcode;

		if ($get_data->randcode) {
			if ($ga->verifyCode($get_data->secret, $data['tfa'], 2)) {
				$type = 'Logged_in';
				$create_activity = Controller::UserActivityEntry($tempuserId, $type, $usermail, 0, $device_type);
				if ($create_activity == 3) {
					$data = array('status' => '0', 'message' => 'Check your registered email on confirmation');
				} else {
					$token = $tempuserId . time() . rand(10, 100);
					$update = User::where('id', $tempuserId)->update(['token' => $token]);
					if ($update) {
						$data = array('status' => '1', 'message' => 'Logged in successfully', 'user_id' => $tempuserId, 'token' => $token,'passcode' => $passcode);
					} else {
						$data = array('status' => '0', 'message' => 'Please try again');
					}
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid authentication code!');
			}
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function forgotPasswordRequest() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|exist_email",
			'device_type' => 'required',
		], [
			'device_type.required' => 'device type required',
			'email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'email.exist_email' => 'User not exists',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$usermail = strtolower(strip_tags($data['email']));
		$first = insep_encode(firstEmail($usermail));
		$second = insep_encode(secondEmail($usermail));
		$user = User::where(['contentmail' => $first, 'liame' => $second])->select('id', 'activation_code', 'status')->first();
		if ($user) {
			if ($user->status == 1) {
				$reset_code = randomString(7) . time() . $user->id . rand(1, 1000);
				$startDate = time();
				$device_type = strip_tags($data['device_type']);
				$updatedate = date('Y-m-d H:i:s', strtotime('+1 day', $startDate));
				$update = User::where('id', $user->id)->update(['forgot_code' => $reset_code, 'forgotten_time' => strtotime($updatedate)]);
				if (!$update) {
					$data = array('status' => '0', 'message' => 'Please try again');
				} else {
					$encryptUId = insep_encode($reset_code);
					$securl = url("/resetaccount/" . $encryptUId);
					$info = array('###EMAIL###' => $usermail, '###LINK###' => $securl);

					$sendEmail = Controller::sendEmail($usermail, $info, '2', $device_type);
					if ($sendEmail) {
						$type = 'Forgot_request';
						$create_activity = Controller::UserActivityEntry($user->id, $type, '', 0, $device_type);
						$data = array('status' => '1', 'message' => 'Reset link sent to your email id');
					} else {
						$data = array('status' => '0', 'message' => 'Email sending failed!');
					}
				}

			} else if ($user->activation_code) {
				$data = array('status' => '0', 'message' => 'Please activate your account!');
			} else {
				$data = array('status' => '0', 'message' => 'Please contact support team, Your account deactivated!');
			}
		} else {
			$data = array('status' => '0', 'message' => 'User not exists');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
	
	public function getCountries() {
		$country = Country::where('status', 1)->select('country_name', 'id','phonecode')->get();
		foreach ($country as $value) {
			$title = $value['id'];
			$content = $value['country_name'];
			$phonecode = $value['phonecode'];
			
			$list = array('id' => $title, 'country_name' => $content, 'phonecode' => $phonecode);
			$ne_list[] = $list;
		}
		$mdata["status"] = 1;
		$mdata["countries"] = $ne_list;
		echo json_encode($mdata);
		exit;
	}

	
	public function cms_pages() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'page_id' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}
		$page = $data['page_id'];
		$cms = Cms::where('id', $page)->select('title', 'content', 'type', 'id', 'name')->first();

		$mdata["status"] = 1;
		$mdata["title"] = $cms->title;
		$mdata["type"] = $cms->type;
		$mdata["page_name"] = $cms->name;
		$mdata["content"] = $cms->content;
		echo json_encode($mdata);die;
	}

	
	public function faq() {
		$faq = Faq::where('status', 'active')->select('question', 'description')->get();
		foreach ($faq as $value) {
			$questions = $value['question'];
			$description = $value['description'];

			$list = array('questions' => $questions, 'description' => $description);
			$fa_list[] = $list;
		}
		$mdata["status"] = 1;
		$mdata["data"] = $fa_list;
		echo json_encode($mdata);
		exit;
	}

	
	public function contact_us() {
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
		]);

		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
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
			$to = getSiteaddress('site_email');
			$sendEmail = Controller::sendEmail(insep_decode($to), $info, '12');
			$data = array('status' => '1', 'message' => 'Contact request submitted successfully!');
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data);exit;
	}

	
	public function news() {
		$news = News::where('status', 'active')->select('title', 'content', 'updated_at', 'id')->get();
		foreach ($news as $value) {
			$title = $value['title'];
			$content = $value['content'];
			$d = strtotime($value['updated_at']);
			$date = date("d/m/Y h:i:s A", $d);
			$list = array('news_title' => $title, 'updated_date' => $date, 'news_content' => $content);
			$ne_list[] = $list;
		}
		$mdata["status"] = 1;
		$mdata["data"] = $ne_list;
		echo json_encode($mdata);
		exit;
	}

	
	public function subscribe() {
		$data = Input::all();
		$email = strip_tags($data['email_address']);
		$validate = Validator::make($data, [
			'email_address' => 'required|email']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}
		$userdata = array('email' => $email,
			'status' => '1',
			'created_at' => date('Y-m-d H:i:s'),
		);
		$exist = Subscribe::where('email', $email)->select('id')->first();
		if ($exist) {
			$data = array('status' => '0', 'message' => 'You have already subscribed');
			echo json_encode($data);
			exit;
		}
		$insertData = Subscribe::create($userdata);
		if ($insertData) {
			$data = array('status' => '1', 'message' => 'You have subscribed successfully');
		} else {
			$data = array('status' => '0', 'message' => 'Something error occured');
		}
		echo json_encode($data);
		exit;
	}

	
	public function contact_address() {
		$email = getSiteaddress('site_email');
		$data = array('status' => '1', 'phone_number' => getSiteaddress('contact_number'), 'location' => getSiteaddress('contact_address') . ',' . getSiteaddress('city') . ',' . getSiteaddress('country'), 'contact_email' => insep_decode($email));
		echo json_encode($data);
		exit;
	}

	
	public function forgot_passcode() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'email' => "required|email|exist_email",
			'user_id' => 'required',
		], [
			'email.required' => 'Enter email address',
			'user_id.required' => 'Enter user_id',
			'email.email' => 'Enter valid email address',
			'email.exist_email' => 'User not exists',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}
		$userid = strip_tags($data['user_id']);
		$usermail = strtolower(strip_tags($data['email']));
		$first = insep_encode(firstEmail($usermail));
		$second = insep_encode(secondEmail($usermail));
		$user = User::where(['contentmail' => $first, 'liame' => $second])->select('id', 'passcode', 'status')->first();
		if ($user->id != $userid) {
			$data = array('status' => '0', 'message' => 'Invalid credentials');
			echo json_encode($data, JSON_FORCE_OBJECT);
			exit;
		}
		if ($user) {
			if ($user->status == 1) {
				$passcode = $user->passcode;
				$info = array('###EMAIL###' => $usermail, '###PSD###' => $passcode);
				$sendEmail = Controller::sendEmail($usermail, $info, '14');
				if ($sendEmail) {
					$type = 'Forgot_request';
					$create_activity = Controller::UserActivityEntry($user->id, $type, '', 0);
					$data = array('status' => '1', 'message' => 'Passcode sent to your email id');
				} else {
					$data = array('status' => '0', 'message' => 'Email sending failed!');
				}
			} else if ($user->activation_code) {
				$data = array('status' => '0', 'message' => 'Please activate your account!');
			} else {
				$data = array('status' => '0', 'message' => 'Please contact support team, Your account deactivated!');
			}
		} else {
			$data = array('status' => '0', 'message' => 'User not exists');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
		
	
	public function sendotpreg() {
		$data = Input::all();
		if ($data) {
			$num = $data['mobile'];
			$code = $data['country'];
			$ddd = User::where('mobile', $num)->count();
			if ($ddd == '0') {
				$rand = '123456';

				if ($rand) {
					$get = Reqotp::where('mobilenum', $num)->delete();

					$ins = Reqotp::insert(array('mobilenum' => $num, 'otp' => $rand, 'status' => '0', 'created_date' => date('Y-m-d H:i:s'), 'expire_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'))));
					$pho = '+' . $num;
					self::sendsms($num,$code,$rand);
					return true;
				} else {
					return '0';
					
				}
			}
			else
			{
				$data = array('status' => '1', 'message' => 'Mobile number already exixts');
				echo json_encode($data);
				exit;
			}
		} 
		else {
			return '0';
		}
	}

	

	function sendsms($mobile, $code, $message) {
		$otp = $message;
		$message = 'Your OTP code is' . $message;
		$coin_info = SiteSettings::where('id', 1)->select('site_name', 'smsapikey')->first();
		$curl = curl_init();
		$sender = 'Bit2eExchange';
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms?country".$code,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{ \"sender\": \"".$sender."\", \"route\": \"4\", \"country\": \"".$code."\", \"sms\": [ { \"message\": \"".$message."\", \"to\": [ \"".$mobile."\" ] } ] }",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_HTTPHEADER => array(
		    "authkey:".$coin_info->smsapikey,
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  $data = array('status' => '0', 'message' => 'Please try again');
		} else {
		  $data = array('status' => '1', 'message' => 'OTP sent successfully','otp'=>$otp);
		}
		echo json_encode($data);
		exit;
	}

	
	public function checkotp() {
		$data = Input::all();
		if ($data) {
			$otpcode = $data['otp_num'];
			$mobilenum = $data['mobileno'];
			$check = Reqotp::select('otp')->where('mobilenum', $mobilenum)->first();
			if ($check->otp == $otpcode) {
				echo "true";
			} else {
				echo "false";
			}
		}
	}
}
