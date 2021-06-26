<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Addcoin;
use App\Model\AdminNotification;
use App\Model\AdminBankwire;
use App\Model\Bankwire;
use App\Model\CoinAddress;
use App\Model\CoinProfit;
use App\Model\ConsumerVerification;
use App\Model\Currency;
use App\Model\Googleauthenticator;
use App\Model\HelpCentre;
use App\Model\HelpIssue;
use App\Model\Notificationlist;
use App\Model\Notifications;
use App\Model\SiteSettings;
use App\Model\TradePairs;
use App\Model\User;
use App\Model\VerificationType;
use App\Model\Wallet;
use DB;
use Illuminate\Support\Facades\Input;
use Session;
use URL;
use Validator;

class ApiUsers extends Controller {
	public function __construct() {

	}

	
	public function viewProfile() {
		$data = Input::all();
		$id = $data['user_id'];
		$url = URL::to('/') . "/public/images/profile/";
		$profile = User::where('id', $id)->select('first_name', 'last_name', 'mobile', 'gender', 'dob', 'contentmail', 'liame', 'city', 'state', 'country', 'address1', 'address2', 'profile', 'pincode')->first();
		if ($profile) {
			$profile = $profile->toArray();
			
			$profile['profile'] = $profile['profile'] ? $profile['profile'] : '';
			$profile['profileurl'] = $profile['profile'] ? $profile['profile'] : '';
			$profile['first_name'] = $profile['first_name'] ? $profile['first_name'] : '';
			$profile['last_name'] = $profile['last_name'] ? $profile['last_name'] : '';
			$profile['mobile'] = $profile['mobile'] ? $profile['mobile'] : '';
			$profile['gender'] = $profile['gender'] ? $profile['gender'] : '';
			$profile['dob'] = $profile['dob'] ? $profile['dob'] : '';
			$profile['city'] = $profile['city'] ? $profile['city'] : '';
			$profile['state'] = $profile['state'] ? $profile['state'] : '';
			$profile['country'] = $profile['country'] ? $profile['country'] : '';
			$profile['address1'] = $profile['address1'] ? $profile['address1'] : '';
			$profile['address2'] = $profile['address2'] ? $profile['address2'] : '';
			$profile['pincode'] = $profile['pincode'] ? $profile['pincode'] : '';
			$profile['email'] = insep_decode($profile['contentmail']) . insep_decode($profile['liame']);
			unset($profile['contentmail']);
			unset($profile['liame']);
			$data = array('status' => '1', 'profile' => $profile);
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function updateUserPassword() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'oldpassword' => 'required|min:8',
			'password' => 'required|confirmed|min:8',
			'password_confirmation' => 'required|min:8',
		], [
			'oldpassword.required' => 'Enter old password',
			'password.required' => 'Enter password',
			'password.min' => 'Enter atleast 8 characters',
			'password_confirmation.required' => 'Enter confirm password',
		]
		);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$id = $data['user_id'];
		$oldpassword = strip_tags(insep_encode($data['oldpassword']));
		$validpssword = User::where('id', $id)->where('ticket', $oldpassword)->select('contentmail', 'liame')->first();
		if (!$validpssword) {
			$data = array('status' => '0', 'message' => 'Incorrect current password');

		} else {
			if (strip_tags($data['oldpassword']) == strip_tags($data['password'])) {
				$data = array('status' => '0', 'message' => 'Dont enter old password as new password');
			} else {
				$password = strip_tags(insep_encode($data['password']));
				$update = User::where('id', $id)->update(['ticket' => $password]);
				if ($update) {
					$message = 'You have changed your password';
					$email = insep_decode($validpssword->contentmail) . insep_decode($validpssword->liame);
					Controller::siteNotification($message, $id, 'change_password', $email);
					$data = array('status' => '1', 'message' => 'Password changed successfully');
				} else {
					$data = array('status' => '0', 'message' => 'Please try again');
				}
			}
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function profile_update() {
		$data = Input::all();
		$id = strip_tags($data['user_id']);
		$validate = Validator::make($data, [
			'first_name' => "required|min:3|max:15",
			'last_name' => "required|min:3|max:15",
			'dob' => 'required',
			'address1' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'pincode' => 'required',
			'profile' => 'required',
			'mobile' => "required|numeric|min:3"]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}

		if (isset($data['address2'])) {
			$address2 = $data['address2'];
		} else {
			$address2 = '';
		}

		$first_name = strip_tags($data['first_name']);
		$last_name = strip_tags($data['last_name']);
		$dob = strip_tags($data['dob']);
		$gender = strip_tags($data['gender']);
		$address1 = strip_tags($data['address1']);
		$city = strip_tags($data['city']);
		$state = strip_tags($data['state']);
		$country = strip_tags($data['country']);
		$pincode = strip_tags($data['pincode']);
		$mobile = strip_tags($data['mobile']);
		$profile = strip_tags($data['profile']);

		$update_arr = ['first_name' => $first_name, 'last_name' => $last_name, 'dob' => $dob, 'gender' => $gender, 'address1' => $address1, 'address2' => $address2, 'city' => $city, 'state' => $state, 'country' => $country, 'pincode' => $pincode, 'mobile' => $mobile, 'profile' => $profile];

		$update = User::where('id', $id)->update($update_arr);
		if ($update) {
			$message = 'You have updated your profile details';

			Controller::siteNotification($message, $id);
			session::put(['tmaitb_profile' => $first_name . ' ' . $last_name]);
			$data = array('status' => '1', 'message' => 'Profile updated successfully');
		} else {
			$data = array('status' => '0', 'message' => 'Password change has been fail');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function user_details() {
		$data = Input::all();
		$id = $data['user_id'];
		$url = URL::to('/') . "/public/images/profile/";
		$profile = DB::table('sresu')
			->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
			->select('first_name', 'last_name', 'contentmail', 'liame', 'randcode', 'secret', 'verified_status', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject', 'mobile', 'profile', 'referrer_name')->first();
		if ($profile) {
			
			$details['email'] = insep_decode($profile->contentmail) . insep_decode($profile->liame);
			if ($profile->randcode == 1) {
				$details['tfa_status'] = 'enable';
			} else {
				$details['tfa_status'] = 'disable';
			}
			$details['tfa_secreat'] = $profile->secret;
			$details['first_name'] = $profile->first_name ? $profile->first_name : '';
			$details['last_name'] = $profile->last_name ? $profile->last_name : '';
			$details['mobile'] = $profile->mobile ? $profile->mobile : '';
			$details['profile'] = $profile->profile ? $profile->profile : '';
			$details['profileurl'] = $profile->profile ? $url.$profile->profile : '';
			$details['referral_id'] = $profile->referrer_name ? $profile->referrer_name : '';

			if ($profile->id_status == 2 || $profile->selfie_status == 2) {
				$details['kyc_userstatus'] = 'Rejected';
			} else if ($profile->id_status == 1 || $profile->selfie_status == 1) {
				$details['kyc_userstatus'] = 'Pending';
			} else if ($profile->verified_status == 0) {
				$details['kyc_userstatus'] = 'Unverified';
			} else {
				$details['kyc_userstatus'] = 'Verified';
			}

			if ($details['first_name'] == '' || $details['last_name'] == '') {
				$details['profile_status'] = 'UnVerified';
			} else {
				$details['profile_status'] = 'Verified';
			}

			$getUrl = SiteSettings::where('id', 1)->select('new_coin_fee', 'new_coin_fee_status')->first();
			$coin_fees = $getUrl->new_coin_fee;
			$details['new_coin_fee'] = $coin_fees;

			$details['unread_noty_count'] = notification_list_web($id);
			
			$bankwire[] = Bankwire::where('user_id',$id)->first();
			$details['userbankdetails']= $bankwire;
			
			$adminbankwire[] = AdminBankwire::where('status',1)->first();
			$details['adminbankdetails']= $adminbankwire;
			
			$new[] = $details;
			$data = array('status' => '1', 'profile' => $details);
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		
		echo json_encode($data);
		exit;
	}

	
	public function TFA_get_key() {
		$data = Input::all();
		$id = $data['user_id'];
		$profile = User::where('id', $id)->select('first_name', 'last_name', 'mobile', 'gender', 'dob', 'contentmail', 'liame', 'city', 'state', 'country', 'address1', 'address2', 'profile', 'randcode')->first();
		if ($profile->randcode == 1) {
			$status = 'enable';
		} else {
			$status = 'disable';
		}

		require_once app_path('Model/Googleauthenticator.php');
		$ga = new Googleauthenticator();
		$secret = $ga->createSecret();
		$tfa_url = $ga->getQRCodeGoogleUrl('BoomCoin', $secret);

		$data = array('status' => '1', 'tfasecret' => $secret, 'tfasecretqr' => $tfa_url, 'tfa_status' => $status);
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function tfa_update() {
		$data = Input::all();
		$id = $data['user_id'];

		$validate = Validator::make($data, [
			'onecode' => 'required|numeric|min:6',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}

		if (isset($data['secret'])) {
			$sec_key = $data['secret'];
		} else {
			$sec_key = '';
		}

		$get_data = User::where('id', $id)->select('secret', 'randcode')->first();
		
		if ($get_data->secret && $get_data->randcode == 1) {
			$secret = $get_data->secret;
		} else {
			$secret = $sec_key;
		}
		$code = $data['onecode'];

		require_once app_path('Model/Googleauthenticator.php');
		$ga = new Googleauthenticator();

		if ($ga->verifyCode($secret, $code, 2)) {
			if ($get_data->randcode) {
				$status = 'deactivated';
				$update = array('randcode' => 0, 'secret' => '');
			} else {
				$status = 'activated';
				$update = array('randcode' => 1, 'secret' => $secret);
			}
			$result = User::where('id', $id)->update($update);
			if ($result) {
				$message = 'You have ' . $status . ' 2FA status';
				Controller::siteNotification($message, $id, 'tfa');
				$data = array('status' => '1', 'message' => '2FA code ' . $status . ' successfully');
			} else {
				$data = array('status' => '0', 'message' => 'Please try again');}
		} else {
			$data = array('status' => '0', 'message' => 'Invalid 2FA code');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function kyc_update() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'proof1' => 'required',
			'proof2' => 'required',
			'proof3' => 'required',
		]);

		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$update_arr = array();
		$verification = ConsumerVerification::where('user_id', $id)->select('id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status')->first();

		if ($verification->id_status == 0 || $verification->id_status == 2) {
			if ($data['proof1'] && $data['proof2']) {
				$update_arr['id_proof_front'] = $data['proof1'];
				$update_arr['id_proof_back'] = $data['proof2'];
				$update_arr['id_status'] = 1;
			} else {
				$data = array('status' => '0', 'message' => 'Front side proof is required');
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		if ($verification->selfie_status == 0 || $verification->selfie_status == 2) {
			if ($data['proof3'] == '') {
				$data = array('status' => '0', 'message' => 'Selfie proof is required');
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			} else {
				$update_arr['selfie_status'] = 1;
				$update_arr['selfie_proof'] = $data['proof3'];
			}
		}
		$update_arr['type'] = $data['type'];
		$update = ConsumerVerification::where('user_id', $id)->update($update_arr);
		if ($update) {
			$message = 'You have updated your kyc details';
			Controller::siteNotification($message, $id);
			$data = array('status' => '1', 'message' => 'KYC updated successfully');
		} else {
			$data = array('status' => '1', 'message' => 'Please Try again');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function kyc_types()
	{
		$country = VerificationType::select('category', 'id')->get();
		foreach ($country as $value) {
			$title = $value['id'];
			$content = $value['category'];
			
			$list = array('id' => $title, 'category' => $content);
			$ne_list[] = $list;
		}

		$mdata["status"] = 1;
		$mdata["kyc_types"] = $ne_list;
		echo json_encode($mdata);
		exit;
	}

	
	public function kyc_details() {
		$data = Input::all();
		$id = $data['user_id'];
		$url = URL::to('/') . "/public/images/kyc/";
		$profile = DB::table('sresu')
			->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
			->select('first_name', 'last_name', 'contentmail', 'liame', 'verified_status', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject','type')->first();

		if ($profile) {
			
			$details['email'] = insep_decode($profile->contentmail) . insep_decode($profile->liame);

			if ($profile->id_status == 2 || $profile->selfie_status == 2) {
				$details['kyc_common_status'] = 'Rejected';
			} else if ($profile->id_status == 1 || $profile->selfie_status == 1) {
				$details['kyc_common_status'] = 'Pending';
			} else if ($profile->verified_status == 0) {
				$details['kyc_common_status'] = 'Unverified';
			} else {
				$details['kyc_common_status'] = 'Verified';
			}
			$details['user_kyc_type'] = $profile->type;
			$details['proof1'] = $profile->id_proof_front ? $profile->id_proof_front : '';
			$details['proof2'] = $profile->id_proof_back ? $profile->id_proof_back : '';
			$details['proof3'] = $profile->selfie_proof ? $profile->selfie_proof : '';

			$details['proof1_url'] = $profile->id_proof_front ? $profile->id_proof_front : '';
			$details['proof2_url'] = $profile->id_proof_back ? $profile->id_proof_back : '';
			$details['proof3_url'] = $profile->selfie_proof ? $profile->selfie_proof : '';
		

			if ($profile->id_status == 0) {
				$details['proof1_status'] = 'Unverified';
				$details['proof2_status'] = 'Unverified';
			} else if ($profile->id_status == 1) {
				$details['proof1_status'] = 'Pending';
				$details['proof2_status'] = 'Pending';
			} else if ($profile->id_status == 2) {
				$details['proof1_status'] = 'Rejected';
				$details['proof2_status'] = 'Rejected';
				$details['id_proof_reject_reason'] = $profile->id_reject;
			} else if ($profile->id_status == 3) {
				$details['proof1_status'] = 'Verified';
				$details['proof2_status'] = 'Verified';
			}

			if ($profile->selfie_status == 0) {$details['proof3_status'] = 'Unverified';} else if ($profile->selfie_status == 1) {$details['proof3_status'] = 'Pending';} else if ($profile->selfie_status == 2) {
				$details['proof3_status'] = 'Rejected';
				$details['selfie_proof_reject_reason'] = $profile->selfie_reject;
			} else if ($profile->selfie_status == 3) {$details['proof3_status'] = 'Verified';}

			$kyc_types = VerificationType::get();
			foreach ($kyc_types as $value) {
				$kycid = $value['id'];
				$kyctype = $value['category'];

				$list = array('id' => $kycid, 'category' => $kyctype);
				$ne_list[] = $list;
			}
			$data = array('status' => '1', 'user_kyc_details' => $details,'kyc_types' => $ne_list);
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function dashboard() {
		$data = Input::all();
		$id = $data['user_id'];
		callconversion($id);
		$overview = $wallet_bal = array();
		$user = User::where('id', $id)->select('profile')->first();
		$url = URL::to('/') . "/public/images/admin_currency/";
		$all_cur = $userbalance = $curr = array();
		
		$allcurr = Currency::where('status', 1)->select('image', 'symbol', 'id', 'type', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance', 'status', 'created_at', 'min_deposit', 'max_deposit')->get();
		$total_btc = number_format(totalconversion($id,'BTC'),8);
		$total_eur = number_format(totalconversion($id,'EUR'),2);
		
		$userbalance = Wallet::getBalance($id);
		foreach ($allcurr as $curr) {
			$inorders = inorders($curr['symbol'], $id);

			$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_crypto_withdraw'] + $inorders['inorder_fiat_withdraw'];
			$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");
			$user_balance = isset($userbalance[$curr['id']]) ? $userbalance[$curr['id']] : 0;
			$balance = rtrim(rtrim(sprintf('%.8F', $user_balance), '0'), ".");
			$user = CoinAddress::where(['user_id' => $id, 'currency' => $curr['symbol']])->select('address', 'tag')->first();
			$coin_address = $coin_qr_url = '';
			if ($user) {
				$coin_address = insep_decode($user['address']);
				$coin_qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $user['address'];
			}

			$total_balance = $balance + $inorders;

			$btcbalance = singlecurrencyconversion($id,'BTC',$curr['id']);
			$btc_bal = number_format($btcbalance, 8, '.', '');
			$eurbalance =singlecurrencyconversion($id,'EUR',$curr['id']);
			$eur_bal = number_format($eurbalance, 2, '.', '');	

			$img = $url.$curr['image'];
			$overview = array('total_balance' => $total_balance, 'balance' => $balance, 'inorders' => $inorders, 'name' => $curr['name'], 'symbol' => $curr['symbol'],'type' => $curr['type'], 'max_withdraw_limit' => $curr['max_withdraw'], 'min_withdraw_limit' => $curr['min_withdraw'], 'withdraw_fees' => $curr['with_fee'], 'coin_address' => $coin_address, 'coin_qr_url' => $coin_qr_url, 'image' => $img,'BTC_conversion'=>$btc_bal,'EUR_conversion'=>$eur_bal);

			$wallet_bal[] = $overview;
		}
		$mdata["status"] = 1;
		$mdata["data"] = $wallet_bal;
		$mdata["Total_BTC_conversion"] = $total_btc;
		$mdata["Total_EUR_conversion"] = $total_eur;
		echo json_encode($mdata);
		exit;
	}

	
	public function support_categories() {
		$category = HelpIssue::where('language_code', 'en')->select('category', 'id')->get();
		$category_list = array();
		foreach ($category as $key => $value) {
			$result['id'] = $value->id;
			$result['category'] = $value->category;

			$category_list[] = $result;
		}
		$datas['status'] = '1';
		$datas['category'] = $category_list;
		echo json_encode($datas);
		exit;
	}

	
	public function add_ticket() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'category' => 'required',
			'subject' => 'required',
			'message' => 'required',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		} else {
			$data_arr = array('user_id' => $id, 'category' => strip_tags($data['category']), 'subject' => $data['subject'], 'message' => $data['message'], 'status' => 'unread', 'ticket_status' => 'active');

			if (isset($data['file'])) {
				$data_arr['image'] = $data['file'];
			}

			$create = HelpCentre::create($data_arr);
			if ($create) {
				$helpId = $create->id;
				HelpCentre::where('id', $helpId)->update(['reference_no' => $helpId]);
				$message = 'You have added support ticket TKT-' . $helpId;
				Controller::siteNotification($message, $id);
				$data = array('status' => '1', 'message' => 'Ticket added successfully');
			} else {
				$data = array('status' => '0', 'message' => 'Please try again');
			}
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}

	
	public function ticket_list() {
		$data = Input::all();
		$id = $data['user_id'];
		$data = array();
		
		$data['status'] = '1';
		
		$data['tickets'] = self::tickets($id);
		
		echo json_encode($data);
		exit;
	}

	
	public static function active_tickets($id) {
		$tickets_active = HelpCentre::where('ticket_status', 'active')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$url = URL::to('/') . "/public/images/support/";
		$active_list = array();
		if ($tickets_active) {
			foreach ($tickets_active as $item) {
				$d = strtotime($item['updated_at']);
				$date = date("M d, Y h:i A", $d);

				$list['ticket_id'] = $item['reference_no'];
				$list['created_date'] =$date;
				$list['subject'] = $item['subject'];
				$list['message'] = $item['message'];
				$list['ticket_image'] = $item['image'];
				$list['ticket_image_url'] = $item['image'];
				$list['ticket_status'] = $item['ticket_status'];
				$list['category'] = getsupportcategory($item['reference_no']);

				$active_list[] = $list;
			}
		}
		return $active_list;
	}

	
	public static function inactive_tickets($id) {
		$url = URL::to('/') . "/public/images/support/";
		$tickets_inactive = HelpCentre::where('ticket_status', 'close')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
	
		$inactive_list = array();
		if ($tickets_inactive) {

			foreach ($tickets_inactive as $item) {
				

				$list['ticket_id'] = $item['reference_no'];
				$list['created_date'] = $item['updated_at'];
				$list['subject'] = $item['subject'];
				$list['message'] = $item['message'];
				$list['ticket_image'] = $item['image'];
				$list['ticket_image_url'] = $item['image'];
				$list['ticket_status'] = $item['ticket_status'];
				$list['category'] = getsupportcategory($item['reference_no']);

				$inactive_list[] = $list;
			}
		}

		return $inactive_list;
	}

	
	public static function tickets($id) {
		$url = URL::to('/') . "/public/images/support/";
		$tickets = HelpCentre::where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$inactive_list = array();
		if ($tickets) {

			foreach ($tickets as $item) {
				
				$list['ticket_id'] = $item['reference_no'];
				$list['created_date'] = $item['updated_at'];
				$list['subject'] = $item['subject'];
				$list['message'] = $item['message'];
				$list['ticket_image'] = $item['image'];
				$list['ticket_image_url'] = $item['image'];
				$list['ticket_status'] = $item['ticket_status'];
				$list['category'] = getsupportcategory($item['reference_no']);

				$inactive_list[] = $list;
			}
		}

		return $inactive_list;
	}

	
	public function update_coin() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'coin_name' => "required|min:3|max:20",
			'coin_symbol' => "required|min:3|max:20",
			'coin_type' => "required",
			'coin_website' => 'required|url',
			'coin_chat' => 'required|url',
			'coin_git' => 'required|url',
			'coin_explorer' => 'required|url']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}

		
		$getUrl = SiteSettings::where('id', 1)->select('new_coin_fee', 'new_coin_fee_status')->first();
		$status = $getUrl->new_coin_fee_status;

		if ($status == 1) {
			$fees = $getUrl->new_coin_fee;
			$firstbal = Wallet::getBalance($id, 1);
			if ($firstbal < $fees) {
				$data = array('status' => '0', 'message' => "You don't have enough minimum balance to add new coin");
				echo json_encode($data);
				exit;
			}
			$debit_balance = $firstbal - $fees;
			Wallet::updateBalance($id, 1, $debit_balance);
			$theftdata = array(
				'user_id' => $id,
				'theftAmount' => $fees,
				'theftCurrency' => 'BTC',
				'type' => 'New coin fees',
			);
			CoinProfit::create($theftdata);
		}

		$coin_name = strip_tags($data['coin_name']);
		$coin_symbol = strip_tags($data['coin_symbol']);
		$coin_type = strip_tags($data['coin_type']);
		$coin_website = strip_tags($data['coin_website']);
		$coin_chat = strip_tags($data['coin_chat']);
		$coin_git = strip_tags($data['coin_git']);
		$coin_explorer = strip_tags($data['coin_explorer']);

		$update_arr = ['user_id' => $id, 'coin_name' => $coin_name, 'coin_symbol' => $coin_symbol, 'coin_type' => $coin_type, 'coin_website' => $coin_website, 'coin_chat' => $coin_chat, 'coin_git' => $coin_git, 'coin_explorer' => $coin_explorer];

		if (isset($data['coin_logo'])) {
			$update_arr['image'] = strip_tags($data['coin_logo']);
		}

		$update = Addcoin::create($update_arr);
		if ($update) {
			$get_data = User::where('id', $id)->select('liame', 'contentmail')->first();
			$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
			$update_id = $update->id;
			$adminNotify['admin_id'] = 1;
			$adminNotify['doc_id'] = $update_id;
			$adminNotify['type'] = "Coin";
			$adminNotify['message'] = $email . ' has added a new coin ' . $coin_name;
			$adminNotify['status'] = "unread";
			AdminNotification::create($adminNotify);
			$data = array('status' => '1', 'message' => 'Coin added successfully');
		} else {
			$data = array('status' => '1', 'message' => 'Please try again');
		}
		echo json_encode($data);
		exit;
	}

	
	public function coin_type() {
		$data = Input::all();
		$id = $data['user_id'];
		$datas['status'] = 1;
		$datas['type'] = ['Bitcoin RPC Interface', 'ERC20 Token', 'Monero RPC Interface', 'Non-Monero Cryptonote', 'Other Token'];
		echo json_encode($datas);exit;
	}

	
	public function mail_notification() {
		$data = Input::all();
		$id = $data['user_id'];

		
		$validate = Validator::make($data, [
			'type' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}

		$my_array = array('device' => 'new_device_login', '2fa' => 'tfa', 'password' => 'change_password', 'trade' => 'trade');
		$type = $data['type'];
		$update = $my_array[$type];
		$get_data = Notifications::where('user_id', $id)->select($update)->first();
		if ($get_data[$update] == 1) {
			$update_value = 0;
		} else {
			$update_value = 1;
		}
		$update_alert = Notifications::where('user_id', $id)->update([$update => $update_value]);
		if ($update_alert) {
			$data = array('status' => '1', 'message' => 'Notification updated successfully');
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data);
		exit;
	}

	
	public function notification_list() {
		$data = Input::all();
		$id = $data['user_id'];
		$notification = Notificationlist::where('user_id', $id)->orderBy('id', 'desc')->paginate(50);
		if ($notification) {
			foreach ($notification as $val) {
				$list['message'] = $val['message'];
				$list['updated_at'] = $val['updated_at'];
				$records[] = $list;

			}
			echo json_encode(array('status' => '1', 'data' => $records));
		} else {
			echo json_encode(array('status' => '1', 'data' => 'No records found'));
		}
		die;
	}

	
	public function email_status() {
		$data = Input::all();
		$id = $data['user_id'];
		$profile = DB::table('sresu')
			->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
			->select('trade', 'tfa', 'change_password', 'new_device_login', 'contentmail', 'liame')->first();

		if ($profile) {
			$details['email'] = insep_decode($profile->contentmail) . insep_decode($profile->liame);
			if ($profile->change_password == 1) {
				$details['change_password'] = 'enable';
			} else {
				$details['change_password'] = 'disable';
			}

			if ($profile->new_device_login == 1) {
				$details['new_device_login'] = 'enable';
			} else {
				$details['new_device_login'] = 'disable';
			}

			if ($profile->tfa == 1) {
				$details['tfa'] = 'enable';
			} else {
				$details['tfa'] = 'disable';
			}

			if ($profile->trade == 1) {
				$details['trade'] = 'enable';
			} else {
				$details['trade'] = 'disable';
			}

			$data = array('status' => '1', 'data' => $details);
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data);
		exit;
	}

	
	public function logout() {
		$data = Input::all();
		$id = $data['user_id'];
		$device_type = $data['device_type'];

		$type = 'Logged_out';
		$create_activity = Controller::UserActivityEntry($id, $type, '', 0, $device_type);
		$update = User::where('id', $id)->update(['token' => '','login_status'=>'0','browser_status'=>'0','session_id'=>'']);

		if ($update) {
			$data = array('status' => '1', 'message' => 'Logged out successfully');
		} else {
			$data = array('status' => '0', 'message' => 'Please try again');
		}
		echo json_encode($data);
		exit;
	}

	
	public function passcode() {
		$data = Input::all();
		$id = $data['user_id'];
		$token = $data['token'];
		$key = $data['key'];
		$user_passcode = $data['old_passcode'];
		$validate = Validator::make($data, [
			'passcode' => "required|min:4",
			'user_id' => 'required',
			'key' => "required",
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}

		$passcode = strip_tags($data['passcode']);
		if ($key == 0) {
			$update = User::where('id', $id)->where('token', $token)->update(['passcode' => $passcode]);
			if ($update) {
				$message = 'You have updated your Passcode';
				Controller::siteNotification($message, $id);
				$data = array('status' => '1', 'message' => 'Passcode created successfully');
			} else {
				$data = array('status' => '0', 'message' => 'Please try again');
			}
		} else if ($key == 2) {
			$old_passcode = User::select('passcode')->where('id', $id)->where('token', $token)->first()->passcode;
			if($old_passcode == $user_passcode)	{
				$update = User::where('id', $id)->where('token', $token)->update(['passcode' => $passcode]);

				if ($update) {
					$message = 'You have updated your Passcode';
					Controller::siteNotification($message, $id);
					$data = array('status' => '1', 'message' => 'Passcode updated successfully');
				} else {
					$data = array('status' => '0', 'message' => 'Please try again');
				}
			} else {
				$data = array('status' => '0', 'message' => 'Wrong old passcode');
			}
		} else {
			$login = User::where('id', $id)->where('passcode', $passcode)->select('token', 'status')->first();
			if ($login) {
				if ($login->status == 1) {
					$data = array('status' => '1', 'message' => 'Authentication successfully', 'token' => $login->token);
					echo json_encode($data);
					exit;
				} else if ($login->status == 0) {
					$data = array('status' => '0', 'message' => 'Your account deactivated! please contact support team');
					echo json_encode($data);
					exit;
				} else {
					$data = array('status' => '0', 'message' => 'Invalid passcode');
					echo json_encode($data);
					exit;
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid passcode');
				echo json_encode($data);
				exit;
			}
		}
		echo json_encode($data);
		exit;
	}

	
	public function checkpasscode() {
		$data = Input::all();
		if ($data) {
			$passcode= $data['passcode'];
			$id= $data['user_id'];
			$check = User::select('passcode')->where('id', $id)->first();
			$pass =  $check->passcode;
			if ($pass ==  $passcode) {
				echo "true";
			} else {
				echo "false";
			}
		}
	}

	
	public function ticket_details() {
		$data = Input::all();
		$id = $data['user_id'];
		if (!isset($data['ticket_id'])) {
			$data = array('status' => '0', 'message' => 'ticket id required');
			echo json_encode($data);
			exit;
		}
		$refId = $data['ticket_id'];

		$query = HelpCentre::where('reference_no', $refId)->orderBy('id', 'asc')->get();
		$profile = User::where('id', $id)->select('profile')->first()->profile;
		$data = compact('query', 'profile');
		$url = URL::to('/') . "/public/images/profile/";
		$url1 = URL::to('/') . "/public/images/support/";
		foreach ($query as $value) {
			$profil = $value->admin_name ? asset('/') . ('public/assets/images/profile-pic.png') : $profile;
			$name = $value->admin_name ? $value->admin_name : getUserName($id);
			$is_admin = $value->admin_name ? '1' : '0';
			
			$date = $value->created_at;
			$message = $value->message;
			$imgs = $value->image ? $value->image : '';

			$category = $query[0]->category;
			$details = array('profile_picture' => $profil, 'name' => $name, 'created_at' => $date, 'message' => $message, 'is_admin' => $is_admin, 'ticket_image' => $imgs, 'category' => $category);

			$tickets[] = $details;
		}
		$mdata["status"] = 1;
		$mdata["data"] = $tickets;
		echo json_encode($mdata);
		exit;
	}

	
	public function close_ticket() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'close_ticket' => "required",
			'ticket_id' => "required",
		], [
			'close_ticket.required' => 'Close ticket is required',
			'ticket_id.required' => 'Ticket id is required',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'data' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		} 
		else {
			$ticket_no = strip_tags($data['ticket_id']);
			$status = $data['close_ticket'];
			if($status == 'close' || $status == 'Close') {
				$result = HelpCentre::where('reference_no', $ticket_no)->update(['status' => 'read', 'ticket_status' => 'close']);
				$message = 'You have closed your ticket TKT-' . $ticket_no;
				$mdata["status"] = 1;
				$mdata["message"] = $message;
			} else {
				$mdata["status"] = 0;
				$mdata["message"] = "Please try again";
			}
		}
		echo json_encode($mdata);exit;
	}

	
	public function edit_support() {
		$data = Input::all();
		$id = $data['user_id'];
		$comment = $data['comment'];

		$validate = Validator::make($data, [
			'comment' => "required|min:4",
			'edit_ref_no' => "required",
		], [
			'comment.required' => 'comment is required',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'data' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		} else {
			$ticket_no = strip_tags($data['edit_ref_no']);
			$data_arr = array('user_id' => $id, 'message' => $data['comment'], 'status' => 'unread', 'ticket_status' => 'active', 'reference_no' => $ticket_no);

			if (isset($data['file'])) {
				$data_arr['image'] = $data['file'];
			}
			if (isset($data['close_ticket'])) {
				$data_arr['ticket_status'] = 'close';
			}
			$create = HelpCentre::create($data_arr);
			if ($create) {
				if (isset($data['close_ticket'])) {
					$result = HelpCentre::where('reference_no', $ticket_no)->update(['status' => 'read', 'ticket_status' => 'close']);
				}
				$message = 'You have updated your ticket details TKT-' . $ticket_no;
				Controller::siteNotification($message, $id);
				$mdata["status"] = 1;
				$mdata["message"] = $message;
			} else {
				$mdata["status"] = 0;
				$mdata["message"] = "Please try again";
			}
			echo json_encode($mdata);
		}
		exit;
	}

	
	public function add_favourites() {
		$data = Input::all();
		$id = $data['user_id'];
		$pair_id = trim(strip_tags($data['pair_id']));

		$validate = Validator::make($data, [
			'pair_id' => "required",
		], [
			'pair_id.required' => 'Pair Id is required',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}
		if ($id) {
			$fav_array = array();
			$get_fav = User::where('id', $id)->select('fav_pairs')->first();
			$fav_pairs = $get_fav->fav_pairs;
			if ($fav_pairs) {
				$fav_array = explode(',', $fav_pairs);
			}

			if (in_array($pair_id, $fav_array)) {
				if (($key = array_search($pair_id, $fav_array)) !== false) {
					unset($fav_array[$key]);
				}
				$fav_pairs = implode(',', $fav_array);
			} else {
				if ($fav_pairs) {
					$fav_pairs .= ',' . $pair_id;
				} else {
					$fav_pairs = $pair_id;
				}
			}
			$update = User::where('id', $id)->update(array('fav_pairs' => $fav_pairs));
			if ($update) {
				$data = array('status' => '1', 'message' => 'success');
			} else {
				$data = array('status' => '0', 'message' => 'failed to updated');
			}
		}
		echo json_encode($data);
		exit;
	}

	
	public function Fav_list() {
		$data = Input::all();
		$id = $data['user_id'];
		$token = $data['token'];
		$fav = '';
		if ($id) {
			$get_fav = User::where('id', $id)->select('fav_pairs')->first();
			$get_fav = $get_fav->fav_pairs;
			$fav = explode(',', $get_fav);
		}

		$balance_array = $btcValues = $all_result = array();
	

		$pairs = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");
		if ($pairs) {
			foreach ($pairs as $pairs) {
				$result = array();
				$pairId = $pairs->id;

				$fromSymbol = $pairs->from_symbol;
				$toSymbol = $pairs->to_symbol;

				$lastPrice = number_format($pairs->last_price, 8, '.', ',');

				$yesterPrice = $pairs->yesterday_price == '' ? 0 : $pairs->yesterday_price;
				$high_price = $pairs->high_price == '' ? 0 : $pairs->high_price;
				$low_price = $pairs->low_price == '' ? 0 : $pairs->low_price;


				$fiat = "EUR";
				$convertion = getconvertionprice($fromSymbol,$fiat);
				$convertionPrice = $convertion == '' ? 0 : rtrim(rtrim(sprintf('%.4F', $convertion), '0'), ".");

				$convertprice = $lastPrice * $convertionPrice;

				$convert_price = $convertprice == '' ? 0 : rtrim(rtrim(sprintf('%.2F', $convertprice), '0'), ".");

				$clsName = "class=text-success";
				if ($yesterPrice <= 0) {
					$changePer = 0;
					$arrow = "";
				} else {
					$changePrice = ($lastPrice - $yesterPrice) / $yesterPrice;
					$changePer = $changePrice * 100;
					if (($lastPrice >= $yesterPrice)) {
						$clsName = "class=text-success";
						$arrow = "+";
					} else {
						$clsName = "class=text-danger";
						$arrow = "";
					}
				}
				$decimal = 8;
				$changePer = $arrow . number_format($changePer, 2, '.', ',');
				$volume = ($pairs->volume == null) ? "0.00" : number_format($pairs->volume, 2, '.', ',');
				$url = URL::to('/') . "/public/images/admin_currency/";
				$image = getCurrencyImage($toSymbol);
				$image1 = getCurrencyImage($fromSymbol);
				if ($id != 0) {
					if ($fav) {
						if (in_array($pairId, $fav)) {
							$Favourites = array(

								'Id'=> $pairId,
								'From_Symbol'=> $toSymbol,
								'FromImage'=>$image,
								'Image_url'=>$url.$image,
								'To_Symbol'=> $fromSymbol,
								'ToImage'=>$image,
								'ToImage_url'=>$url.$image1,
								'Last_Price'=>$lastPrice,
								'Convert_Price'=>$convertion,
								'Change_Percentage'=>$changePer,
								'High_Price'=>$high_price,
								'Low_Price'=>$low_price,
								'Volume'=>$volume,
								'Arrow'=>$arrow,
							);
							array_push($btcValues, $Favourites);
						}
					}
				}
			}
			$mdata["status"] = 1;
			$mdata["market"] = $btcValues;
			echo json_encode($mdata);exit;
		} else {
			$response = array('status' => '0', 'data' => 'no Favourites pairs');
			echo json_encode($response);exit;
		}

	}
	public function market_list() {
		$data = Input::all();		
		if (isset($data['user_id']) && isset($data['token'])) {
			$id = $data['user_id'];
			$token = $data['token'];
			$balance_array = $btcValues = $all_result = array();
			$pairs = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");

			if ($pairs) {
				foreach ($pairs as $pairs) {
					$result = array();
					$pairId = $pairs->id;

					$fromSymbol = $pairs->from_symbol;
					$toSymbol = $pairs->to_symbol;

					$lastPrice = number_format($pairs->last_price, 8, '.', ',');

					$yesterPrice = $pairs->yesterday_price == '' ? 0 : $pairs->yesterday_price;
					$high_price = $pairs->high_price == '' ? 0 : $pairs->high_price;
					$low_price = $pairs->low_price == '' ? 0 : $pairs->low_price;


					$fiat = "EUR";
					$convertion = getconvertionprice($fromSymbol,$fiat);
					$convertionPrice = $convertion == '' ? 0 : rtrim(rtrim(sprintf('%.4F', $convertion), '0'), ".");
					if($fromSymbol != "EUR") {
						$convertprice = $convertionPrice;
					} else {
						$convertprice = $convertionPrice;
					}
					$convert_price = number_format($convertprice, 2, '.', ',');
					$clsName = "class=text-success";
					if ($yesterPrice <= 0) {
						$changePer = 0;
						$arrow = "";
					} else {
						$changePrice = ($lastPrice - $yesterPrice) / $yesterPrice;
						$changePer = $changePrice * 100;
						if (($lastPrice >= $yesterPrice)) {
							$clsName = "class=text-success";
							$arrow = "+";
						} else {
							$clsName = "class=text-danger";
							$arrow = "";
						}
					}
					$decimal = 8;
					$changePer = $arrow . number_format($changePer, 2, '.', ',');
					$volume = ($pairs->volume == null) ? "0.00" : number_format($pairs->volume, 2, '.', ',');
					$url = URL::to('/') . "/public/images/admin_currency/";
					$image = getCurrencyImage($toSymbol);
					$image1 = getCurrencyImage($fromSymbol);
					$market = array(
						'Id'=>$pairId,
						'Image'=>$image,
						'Image_url'=>$url.$image,
						'From_Symbol'=> $toSymbol,
						'To_Symbol'=> $fromSymbol,
						'ToImage'=>$image1,
						'ToImage_url'=>$url.$image1,
						'Last_Price'=>$lastPrice,
						'Convert_Price'=>$convertion,
						'Change_Percentage'=>$changePer,
						'High_Price'=>$high_price,
						'Low_Price'=>$low_price,
						'Volume'=>$volume,
						'Arrow'=>$arrow,
					);
					array_push($btcValues, $market);		
				}
				$mdata["status"] = 1;
				$mdata['market'] = $btcValues;
				echo json_encode($mdata);exit;
			} else {
				$response = array('status' => '0', 'data' => 'No Market pairs');
				echo json_encode($response);exit;
			}
		}
		else
		{
			$balance_array = $btcValues = $all_result = array();
			$pairs = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");
			foreach ($pairs as $pairs) {
				$result = array();
				$pairId = $pairs->id;
				$fromSymbol = $pairs->from_symbol;
				$toSymbol = $pairs->to_symbol;
				$lastPrice = number_format($pairs->last_price, 8, '.', ',');
				$yesterPrice = $pairs->yesterday_price == '' ? 0 : $pairs->yesterday_price;
				$high_price = $pairs->high_price == '' ? 0 : $pairs->high_price;
				$low_price = $pairs->low_price == '' ? 0 : $pairs->low_price;
				$fiat = "EUR";
				$convertion = getconvertionprice($fromSymbol,$fiat);
				$convertionPrice = $convertion == '' ? 0 : rtrim(rtrim(sprintf('%.4F', $convertion), '0'), ".");
				if($fromSymbol != "EUR") {
					$convertprice = $convertionPrice;
				} else {
					$convertprice = $convertionPrice;
				}
				$convert_price = number_format($convertprice, 2, '.', ',');
				$clsName = "class=text-success";
				if ($yesterPrice <= 0) {
					$changePer = 0;
					$arrow = "";
				} else {
					$changePrice = ($lastPrice - $yesterPrice) / $yesterPrice;
					$changePer = $changePrice * 100;
					if (($lastPrice >= $yesterPrice)) {
						$clsName = "class=text-success";
						$arrow = "+";
					} else {
						$clsName = "class=text-danger";
						$arrow = "";
					}
				}
				$decimal = 8;
				$changePer = $arrow . number_format($changePer, 2, '.', ',');
				$volume = ($pairs->volume == null) ? "0.00" : number_format($pairs->volume, 2, '.', ',');
				$url = URL::to('/') . "/public/images/admin_currency/";
				$image = getCurrencyImage($toSymbol);
				$image1 = getCurrencyImage($fromSymbol);
				$market = array(
					'Id'=>$pairId,
					'Image'=>$image,
					'Image_url'=>$url.$image,
					'From_Symbol'=> $toSymbol,
					'To_Symbol'=> $fromSymbol,
					'ToImage'=>$image1,
					'ToImage_url'=>$url.$image1,
					'Last_Price'=>$lastPrice,
					'Convert_Price'=>$convertion,
					'Change_Percentage'=>$changePer,
					'High_Price'=>$high_price,
					'Low_Price'=>$low_price,
					'Volume'=>$volume,
					'Arrow'=>$arrow,
				);
				array_push($btcValues, $market);		
			}
			$mdata["status"] = 1;
			$mdata['market'] = $btcValues;
			echo json_encode($mdata);exit;
		}
	}
	
	
	public function referral_request() {
		$data = Input::all();
		$id = $data['user_id'];

		$validate = Validator::make($data, [
			'referral_email' => 'required|email|unique_email',
		],
			[
				'referral_email.required' => 'Enter email address',
				'email.email' => 'Enter valid email address',
				'referral_email.unique_email' => 'Email address already registered',
			]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}
		$referrer_name = User::where('id', $id)->select('referrer_name')->first()->referrer_name;
		$securl = url("/register/" . $referrer_name);
		$info = array('###EMAIL###' => $data['referral_email'], '###LINK###' => $securl);

		$sendEmail = Controller::sendEmail($data['referral_email'], $info, '5');
		if ($sendEmail) {
			$data = array('status' => '1', 'message' => 'Invitation sent successfully');
		} else {
			$data = array('status' => '1', 'message' => 'Please try again');
		}
		echo json_encode($data);
		exit;
	}

	
	public function referral() {
		$data = Input::all();
		$id = $data['user_id'];
		$referrer_name = User::where('id', $id)->select('referrer_name')->first()->referrer_name;
		$ref['refer_url'] = url("/register/" . $referrer_name);
		
		$sort_col = 'id';
		$sort_type = 'desc';

		$results = DB::select("SELECT `users`.`id`,`users`.`activation_code`,`users`.`status`,`users`.`liame`,`users`.`contentmail`,`users`.`referrer_name`, `referral`.`currency`,`referral`.`commision`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  right join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  GROUP BY `users`.`id`  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type);

		$data = array();
		$no = 1;

		if ($results) {
			foreach ($results as $r) {
				$email = insep_decode($r->contentmail) . insep_decode($r->liame);
				$status = $r->activation_code ? 'Registered' : ($r->status == 1 ? 'Active' : 'Deactive');
				$securl = url("/register/" . $r->referrer_name);
				array_push($data, array(
					'no' => $no,
					'id' => $email,
					'referrer_link' => $securl,
					'referrer_name' => getUserName($id),
					'status' => $status,
				));
				$no++;
			}
			$ref['referral_list'] = $data;
		} else {
			
			$ref['referral_list'] = array();
		}

		
		$sort_col = 'updated_at';
		$sort_type = 'desc';

		$results = DB::select("SELECT `users`.`liame`,`users`.`contentmail`, `referral`.`currency`,`referral`.`commision`,`referral`.`status`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  left join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type);

		$data1 = array();
		$no = 1;
		if ($results) {
			foreach ($results as $r) {
				$id = insep_decode($r->contentmail) . insep_decode($r->liame);
				$currency = $r->currency;
				$commision = $r->commision . ' ' . $currency;
				$datetime = $r->updated_at;
				array_push($data1, array(
					'no' => $no,
					'id' => $id,
					'commision' => $commision,
					'currency' => $currency,
					'datetime' => $datetime,
				));
				$no++;
			}			
			$ref['referral_history'] = $data1;
		} else {			
			$ref['referral_history'] = array();
		}		
		echo json_encode($ref);
		exit;
	}

	
	public function referral_list() {

		$data = Input::all();
		$id = $data['user_id'];

		$sort_col = 'id';
		$sort_type = 'desc';

		$results = DB::select("SELECT `users`.`id`,`users`.`activation_code`,`users`.`status`,`users`.`liame`,`users`.`contentmail`,`users`.`referrer_name`, `referral`.`currency`,`referral`.`commision`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  right join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  GROUP BY `users`.`id`  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type);

		$data = array();
		$no = 1;

		if ($results) {
			foreach ($results as $r) {
				$email = insep_decode($r->contentmail) . insep_decode($r->liame);
				$status = $r->activation_code ? 'Registered' : ($r->status == 1 ? 'Active' : 'Deactive');
				$securl = url("/register/" . $r->referrer_name);
				array_push($data, array(
					'no' => $no,
					'id' => $email,
					'referrer_link' => $securl,
					'referrer_name' => getUserName($id),
					'status' => $status,
				));
				$no++;
			}
			echo json_encode(array('status' => '1', 'data' => $data));
		} else {
			echo json_encode(array('status' => '1', 'data' => array()));
		}
	}

	
	public function referalHistory() {
		$data = Input::all();
		$id = $data['user_id'];
		$sort_col = 'updated_at';
		$sort_type = 'desc';

		$results = DB::select("SELECT `users`.`liame`,`users`.`contentmail`, `referral`.`currency`,`referral`.`commision`,`referral`.`status`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  left join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type);

		$data = array();
		$no = 1;
		if ($results) {
			foreach ($results as $r) {
				$id = insep_decode($r->contentmail) . insep_decode($r->liame);
				$currency = $r->currency;
				$commision = $r->commision . ' ' . $currency;
				$datetime = $r->updated_at;
				array_push($data, array(
					'no' => $no,
					'id' => $id,
					'commision' => $commision,
					'currency' => $currency,
					'datetime' => $datetime,
				));
				$no++;
			}
			echo json_encode(array('status' => '1', 'data' => $data));
		} else {
			echo json_encode(array('status' => '1', 'data' => array()));
		}
	}

	
	public function updatebankwire() {	
		$data = Input::all();		
		$validate = Validator::make($data, [
			'accountholdername' => "required",
			'accountnumber' => "required",
			'swift' => 'required',
			'bankname' => 'required',
			'bankaddress' => 'required',
			'routing' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$id = $data['user_id'];
		$check = Bankwire::where('user_id', $id)->count();

		$accholdername = strip_tags($data['accountholdername']);
		$accno = strip_tags($data['accountnumber']);
		$swift = strip_tags($data['swift']);
		$routing = strip_tags($data['routing']);
		$bankname = strip_tags($data['bankname']);
		$bankaddress = strip_tags($data['bankaddress']);

		$insert_arr = ['user_id' => $id, 'accountholdername' => $accholdername, 'accountno' => $accno, 'swift' => $swift, 'routingno' => $routing, 'bankname' => $bankname, 'bankaddress' => $bankaddress];

		$update_arr = ['accountholdername' => $accholdername, 'accountno' => $accno, 'swift' => $swift, 'routingno' => $routing, 'bankname' => $bankname, 'bankaddress' => $bankaddress];

		if($check > 0) {
			$update = Bankwire::where('user_id',$id)->update($update_arr);
			$data = array('status' => '1', 'message' => 'Bankwire details updated Successfully');
		} else {				
			$insert = Bankwire::create($insert_arr);
			$data = array('status' => '1', 'message' => 'Bankwire details added Successfully');
		}	
		echo json_encode($data);exit;
	}

	
	public function bankwire() {
		$data = Input::all();
		$id = $data['user_id'];
		$bankwire = Bankwire::where('user_id', $id)->first();
		$bank[] = $bankwire;
		if(!empty($bank)) {
			$mdata['status'] = 1;
			$mdata['bankwire'] = $bank;
		} else {
			$mdata['status'] = 0;
			$mdata['bankwire'] = array();
		}
		echo json_encode($mdata);exit;
	}

	
	public function upload_image() {
		$data = Input::all();
		$id = $data['user_id'];
		$type = $data['type'];
		$token = $data['token'];
		$randcode = randomcode(5);
		$filename = "BoomCoin" . $id . $randcode . $_FILES['file']['name'];
		if($type == 'profile') {
			$path = $data['file']->move(public_path('/images/profile/'), $filename);
		} if($type == 'kyc') {
			$path = $data['file']->move(public_path('/images/kyc/'), $filename);
		} if($type == 'support') {
			$path = $data['file']->move(public_path('/images/support/'), $filename);
		} if($type == 'deposit') {
			$path = $data['file']->move(public_path('/images/deposit_proof/'), $filename);
		} if($type == 'addcoin') {
			$path = $data['file']->move(public_path('/images/admin_currency/'), $filename);
		}
		echo json_encode(array('status' => '1', 'path' => $filename));exit;
	}

	
	public function singleCurrency() {
		$data = Input::all();
		$id = $data['user_id'];
		$symbol = $data['symbol'];
		
		$overview = $wallet_bal = array();
		$user = User::where('id', $id)->select('profile')->first();
		$url = URL::to('/') . "/public/images/admin_currency/";
		$all_cur = $userbalance = $curr = array();

		$curr = Currency::where('status', 1)->where('symbol', $symbol)->select('image', 'symbol', 'id', 'type', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance', 'status', 'created_at', 'min_deposit', 'max_deposit')->first();
		$total_btc = number_format(totalconversion($id,'BTC'),8);
		$total_eur = number_format(totalconversion($id,'EUR'),2);
		
		$userbalance = Wallet::getBalance($id);
		
		$inorders = inorders($symbol, $id);

		$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_crypto_withdraw'] + $inorders['inorder_fiat_withdraw'];
		$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");
		$user_balance = isset($userbalance[$curr['id']]) ? $userbalance[$curr['id']] : 0;
		$balance = rtrim(rtrim(sprintf('%.8F', $user_balance), '0'), ".");
		$user = CoinAddress::where(['user_id' => $id, 'currency' => $symbol])->select('address', 'tag')->first();
		$coin_address = $coin_qr_url = '';
		if ($user) {
			$coin_address = insep_decode($user['address']);
			$coin_qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $user['address'];
		}
		$total_balance = $balance + $inorders;
		$btcbalance = singlecurrencyconversion($id,'BTC',$curr['id']);
		$btc_bal = number_format($btcbalance, 8, '.', '');
		$eurbalance =singlecurrencyconversion($id,'EUR',$curr['id']);
		$eur_bal = number_format($eurbalance, 2, '.', '');

		$img = $url.$curr['image'];
		$overview = array('total_balance' => $total_balance, 'balance' => $balance, 'inorders' => $inorders, 'name' => $curr['name'], 'symbol' => $curr['symbol'],'type' => $curr['type'], 'max_withdraw_limit' => $curr['max_withdraw'], 'min_withdraw_limit' => $curr['min_withdraw'], 'withdraw_fees' => $curr['with_fee'], 'coin_address' => $coin_address, 'coin_qr_url' => $coin_qr_url, 'image' => $img,'BTC_conversion'=>$btc_bal,'EUR_conversion'=>$eur_bal);
		$wallet_bal = $overview;
		
		$mdata["status"] = 1;
		$mdata["data"] = $wallet_bal;
		$mdata["Total_BTC_conversion"] = $total_btc;
		$mdata["Total_EUR_conversion"] = $total_eur;
		echo json_encode($mdata);
		exit;
	}

	
	public function getdashboardconversion() {
		$data = Input::all();
		$id = $data['user_id'];		
		
		$total_btc = number_format(totalconversion($id,'BTC'),8);
		$total_eur = number_format(totalconversion($id,'EUR'),2);		
		$userbalance = Wallet::getBalance($id);		
		
		$mdata["status"] = 1;
		$mdata["Total_BTC_conversion"] = $total_btc;
		$mdata["Total_EUR_conversion"] = $total_eur;
		echo json_encode($mdata);
		exit;
	}
}
