<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Sats;
use App\Model\CoinAddress;
use App\Model\CoinProfit;
use App\Model\Currency;
use App\Model\Googleauthenticator;
use App\Model\Wallet;
use App\Model\Withdraw;
use App\Model\Tokens;
use Config;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use URL;
use Validator;

class Transactions extends Controller {
	public function __construct() {

	}

	public function get_address($currency) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}

		$id = session::get('tmaitb_user_id');
		$currency = trim(strip_tags($currency));
		$user = CoinAddress::where(['user_id' => $id, 'currency' => $currency])->select('address', 'tag')->first();
		if ($user) {
			return $user->toArray();
		} 
		else 
		{	
			if($currency =="BTC" || $currency =="ETH" || $currency =="BCH" )
			{
				$coins = new Sats;
				$address = $coins->generateAddress($currency,$id);
			}
			elseif($currency =="BoomCoin")
			{
				
				$cmc_url = getSocketUrl()."/generateNewTokenAddress";
                $response = files_get_content($cmc_url);
                                
                if(isset($response->data))
                {
                	$data = $response->data;
                    $address = trim(strtolower($data->address));
                    $privateKey = $data->privateKey;
                }
			}
			if (isset($address))
			{
				$tag_enc = '';

				if($currency =="BTC")
				{
					$address_encrypt = insep_encode($address);
					$address_arraybtc = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'BTC'];
					$result = CoinAddress::Create($address_arraybtc);

					if($result) 
					{
						$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
						return $address_array;
					}

				}
				if($currency =="BCH")
				{
					$address_encrypt = insep_encode($address);
					$address_arraybtc = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'BCH'];
					$result = CoinAddress::Create($address_arraybtc);

					if($result) 
					{
						$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
						return $address_array;
					}

				}
				else if($currency =="BoomCoin")
				{
					$address_encrypt = insep_encode($address);
					$privateKeyencrypt = insep_encode($privateKey);
					$address_arrayown = ['user_id' => $id, 'address' => $address_encrypt, 'secret' => $privateKeyencrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'BoomCoin'];
					$result = CoinAddress::Create($address_arrayown);

				}
				else
				{
					$address_encrypt = insep_encode($address);
					$address_arrayeth = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'ETH'];
					$result = CoinAddress::Create($address_arrayeth);

				}

				
				
				if (isset($result)) 
				{
					$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
					return $address_array;
				}
				
	        }
		}
		return false;
	}
	
/*	public function get_address($currency) 
	{

		$id = session::get('tmaitb_user_id');
		$currency = trim(strip_tags($currency));
		$user = CoinAddress::where(['user_id' => $id, 'currency' => $currency])->select('address', 'tag')->first();
		if ($user) {
			return $user->toArray();
		} 
		else 
		{	
			
			$coins = new Sats;
			$address = $coins->generateAddress($currency,$id);

			if ($address) 
			{
				$tag_enc = '';

				if($currency =="BTC")
				{
					$address_encrypt = insep_encode($address);
					$address_arraybtc = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => $currency];
					$result2 = CoinAddress::Create($address_arraybtc);
					if ($result2) 
					{
					$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
					return $address_array;
					}

				}
				if($currency =="BCH")
				{
					$address = ltrim($address, "bitcoincash:");
					$address_encrypt = insep_encode($address);
					$address_arraybtc = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => $currency];
					$result2 = CoinAddress::Create($address_arraybtc);
					if ($result2) 
					{
					$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
					return $address_array;
					}

				}
				elseif($currency =="ETH")
				{
					$address_encrypt = insep_encode($address);
					$address_arrayeth = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'ETH'];

					$result = CoinAddress::Create($address_arrayeth);		
					
				}
				elseif($currency =="BoomCoin")
				{
					$address_encrypt = insep_encode($address);
					$address_arrayown = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => 'BoomCoin'];

					$result = CoinAddress::Create($address_arrayown);
				}

				if(isset($result))
				{
					$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
					return $address_array;
				}
	        }
		}
		return false;
	}*/
	
	public function get_coins($currency) 
	{
		$currency = trim(strip_tags($currency));
		$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('alert_deposit', 'deposit_status', 'deposit_content', 'deposit_maintenance', 'alert_message', 'alert_checkbox_content')->first();
		if ($currecny_details) 
		{
			
			if ($currecny_details->deposit_status == '1') 
			{
				if ($currecny_details->alert_deposit == '1') 
				{
					$data = array('type' => 'warning', 'deposit_content' => $currecny_details->deposit_content, 'msg' => $currecny_details->alert_message, 'msg1' => $currecny_details->alert_checkbox_content);
				} else 
				{


					$address_array = self::get_address($currency);
					
					if (isset($address_array))
					{
						$tag = '';
						
						$data = array('type' => 'deposit', 'deposit_content' => $currecny_details->deposit_content, 'address' => insep_decode($address_array['address']), 'tag' => $tag);
					} else 
					{
						$data = array('type' => 'failed');
					}
				}
			} else 
			{
				$data = array('type' => 'maintenance', 'msg' => $currecny_details->deposit_maintenance);
			}
		} 
		else 
		{
			$data = array('type' => 'failed');
		}
		$show_json = json_encode($data, JSON_FORCE_OBJECT);
		echo $show_json;
		exit;
	}
	
	public function get_tokens($currency) 
	{
		$currency = trim(strip_tags($currency));
		$curr = Currency::where('symbol', $currency)->select('id', 'min_withdraw', 'with_fee', 'withdarw_status','ERC20')->first();

		if ($curr) 
		{
			if($curr->ERC20=='1')
			{
				$getdetails         = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();

				$decimalval=$getdetails->decimalval;
				if ($decimalval==0) 
				{
					$data = array('type' => 'flat');

				}
				else
				{
					$data = array('type' => 'percentage');
				}

			}
			else
			{
				$data = array('type' => 'percentage');
			}

		}

		$show_json = json_encode($data, JSON_FORCE_OBJECT);
		echo $show_json;
		exit;
	}

	public function accept_alert($currency) 
	{
		$data = Input::all();
		$validate = Validator::make($data, ['iagree_coin' => "required"], ['iagree_coin.required' => 'Enter email address']);
		if ($validate->fails()) 
		{
			echo 'failed';exit;
		} else 
		{
			$currency = trim(strip_tags($currency));
			$address_array = self::get_address($currency);
			if ($address_array) 
			{
				$tag = '';

				$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('deposit_content')->first();
				$data = array('type' => 'deposit', 'address' => insep_decode($address_array['address']), 'tag' => $tag, 'deposit_content' => $currecny_details->deposit_content);
			} else 
			{
				$data = array('type' => 'failed');
			}
			echo json_encode($data);
			exit;
		}
	}
   
	function makeWithdraw() 
	{
		$data = Input::all();
		$validate = Validator::make($data, ['amount' => "required", 'withdrawname' => "required", 'address' => "required",'remark' => "required"], ['amount.required' => 'Enter amount', 'withdrawname.required' => 'Choose currency', 'address.required' => 'Enter address','remark.required' => 'Enter remark']);
		if ($validate->fails()) 
		{
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return redirect("funds?name=withdraw");
			}
		} 
		else 
		{
			if(Controller::checkUserSessionIp() == false){return redirect("logout");}

			$user_id = session::get('tmaitb_user_id');
			$currency = trim(strip_tags($data['withdrawname']));
			$address = trim(strip_tags($data['address']));
			$amount = trim(strip_tags($data['amount']));
			$tag = trim(strip_tags($data['remark']));
			$get_data = DB::table('sresu')
			->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $user_id)
			->select('first_name', 'last_name', 'verified_status', 'randcode', 'id_status', 'selfie_status', 'liame', 'contentmail', 'secret')->first();
			


			if ($get_data->randcode ==0)
			{
				Session::flash('error', trans('app_lang.verify_tfa'));
				return redirect("funds?name=withdraw");
			}


			$curr = Currency::where('symbol', $currency)->select('id', 'min_withdraw', 'with_fee', 'withdarw_status','ERC20')->first();
			
			if ($curr) 
			{
				$id = $curr->id;
				if ($curr->withdarw_status == 1) 
				{

					$userbalance = Wallet::getBalance($user_id, $id);
					$fee_per = $curr->with_fee;					
					if($curr->ERC20=='1')
					{
						$getdetails = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();

						$decimalval=$getdetails->decimalval;

						if($decimalval==0)
						{
							if(strpos($amount, "." ) !== false )
							{
								Session::flash('error', "Please don't enter decimal value for withdraw for this token".$currency. " only integer values accepted");
								return redirect("funds?name=withdraw");
							}
							else
							{
								$fee_amt = $fee_per;
								$fee_amtt =number_format((float) $fee_per, 8, '.', '');
							}

						}
						else
						{
							$fee_amt = ($fee_per * $amount) / 100;
							$fee_amtt =number_format((float) $fee_amt, 8, '.', '');
						}

					}else{

						$fee_amt = ($fee_per * $amount) / 100;
						$fee_amtt =number_format((float) $fee_amt, 8, '.', '');

					}


					$transfer_amount = $amount - $fee_amt;
					$transfer_amountt =number_format((float) $transfer_amount, 8, '.', '');

					if ($amount < $curr->min_withdraw) {
						Session::flash('error', trans('app_lang.enter_withdraw_amount_greater'));
						return redirect("funds?name=withdraw");
					} else if ($amount > $userbalance) {
						Session::flash('error', trans('app_lang.enter_amount_less_balance_amount'));
						return redirect("funds?name=withdraw");
					} else {


						if ($get_data->randcode) {
							if (isset($data['tfa'])) {
								require_once app_path('Model/Googleauthenticator.php');
								$ga = new Googleauthenticator();

								if (!$ga->verifyCode($get_data->secret, $data['tfa'], 2)) {
									Session::flash('error', trans('app_lang.invalid_2fa'));
									return redirect("funds?name=withdraw");
								}

							} else {
								Session::flash('error', trans('app_lang.2FA_required'));
								return redirect("funds?name=withdraw");
							}
						}

						$coins = new Sats;
							
						$code = time() . $id . $user_id . rand(99, 99999);
						$userbalance = Wallet::getBalance($user_id, $id);
						$update_balance = $userbalance - $amount;
						$remarks = 'withdraw ' . $currency . ' ' . $amount;
						$expire = strtotime('+1 day', time());
						$array_withdraw = array('amount' => $amount, 'address' => $address, 'transfer_amount' => $transfer_amountt, 'currency' => $currency, 'fee_amt' => $fee_amtt, 'fee_per' => $fee_per, 'user_id' => $user_id, 'ip_addr' => $_SERVER['REMOTE_ADDR'], 'confirm_code' => $code, 'tag' => $tag, 'expire' => $expire, 'status' => 'Pending', 'remarks' => $remarks);


						$result = DB::transaction(function () use ($array_withdraw, $user_id, $id, $update_balance) {

							Withdraw::create($array_withdraw);
							return Wallet::updateBalance($user_id, $id, $update_balance);
						});

						if ($result) {
							$encryptUId = insep_encode($code);
							$securl = url("/confirmtranferbyuser/" . $encryptUId);
							$rsecurl = url("/rejecttranferbyuser/" . $encryptUId);
							$name = $get_data->first_name . ' ' . $get_data->last_name;

							$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);




							$info = array('###TRANSFER###' => $transfer_amountt, '###CUR###' => $currency, '###AMOUNT###' => $amount, '###ADDR###' => $address, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl, '###FEE###' => $fee_amtt, '###USER###' => $name);

							$sendEmail = Controller::sendEmail($email, $info, '9');

							if ($sendEmail) {
								Session::flash('success', trans('app_lang.withdraw_sent_email_lng'));
								$message = 'You have added a withdraw request for -' . $amount . ' ' . $currency;
								Controller::siteNotification($message, $user_id);
								return Redirect::to('/funds');
							} else {
								Session::flash('error', trans('app_lang.email_send_failed'));
							}
						} 
						else 
						{
							Session::flash('error', trans('app_lang.please_try_again'));
							return redirect("funds?name=withdraw");
						}
					}
				} 
				else 
				{
					Session::flash('error', trans('app_lang.withdraw_disabled'));
					return redirect("funds?name=withdraw");
				}

			}
        }
	}
	
	public function confirmWithdrawProcess($id) 
	{
		$enc_id = $id;
		$id = insep_decode($id);
		return self::checkWithdrawalRequest($id, $enc_id, 1);

	}
	 
	public function RejectWithdrawProcess($id) 
	{
		$enc_id = $id;
		$id = insep_decode($id);
		return self::checkWithdrawalRequest($id, $enc_id, 0);
	}
    
	public function checkWithdrawalRequest($id, $enc_id, $status) 
	{
		$checkWithdraw = Withdraw::where('confirm_code', $id)->select('user_id')->first();
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$user_id = session::get('tmaitb_user_id');
		if ($user_id) 
		{
			if ($checkWithdraw) 
			{
				if ($checkWithdraw->user_id == $user_id) 
				{
					$checkWithdraw = Withdraw::where('confirm_code', $id)->select('transfer_amount', 'amount', 'currency', 'address', 'tag', 'expire', 'status', 'is_flag', 'id')->first();
					if ($checkWithdraw->status != 'Pending') {
						Session::flash('error', trans('app_lang.withdraw_link_used_lng'));
						return Redirect::to('/funds');
					} else if ($checkWithdraw->expire < time()) {
						Session::flash('error', trans('app_lang.withdraw_request_expired'));
						return redirect('/funds');
					} else {
						if ($status == 1) {
							if ($checkWithdraw->is_flag == 0) {
								$update = Withdraw::where('confirm_code', $id)->update(array('is_flag' => '1'));
								if ($update) {
									$transfer_amount = $checkWithdraw->transfer_amount;
									$currency = $checkWithdraw->currency;
									$address = $checkWithdraw->address;
									$tag = $checkWithdraw->tag;
									return self::completeWithdraw($id, $user_id, $transfer_amount, $currency, $address, $tag);
								}
							} else {
								Session::flash('error', trans('app_lang.please_try_again'));
								return Redirect::to('/funds');
							}
						} else if ($status == 0) {
							$amount = $checkWithdraw->amount;
							$currency = $checkWithdraw->currency;
							return self::rejectWithdraw($id, $user_id, $amount, $currency);
						} else {
							Session::flash('error', trans('app_lang.invalid_request'));
							return Redirect::to('/funds');
						}
					}
				} 
				else 
				{
					Session::flash('error', trans('app_lang.invalid_request'));
					return Redirect::to('/funds');
				}
			} 
			else 
			{
				Session::flash('error', trans('app_lang.invalid_request'));
				return Redirect::to('/funds');
			}
		} 
		else 
		{
			session::put(['temp_wstatus' => $status, 'temp_wid' => $enc_id]);
			Session::flash('error', trans('app_lang.login_confirm_withdraw_request'));

			return redirect("login");
		}
	}
	
	function completeWithdraw($confirm, $user_id, $transfer_amount, $currency, $address, $tag = '') 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$log_user_id = session::get('tmaitb_user_id');
		if ($log_user_id) 
		{
			if ($log_user_id == $user_id) 
			{

				$code = time() . $user_id . rand(999, 999999);
				Withdraw::where('confirm_code', $confirm)->update(array('status' => 'Processing', 'with_token' => $code));

				$withdraw_req = Withdraw::where('confirm_code', $confirm)->select('fee_amt', 'amount')->first();
				$email = session::get('tmaitb_user_email');
				$fee_amt = number_format((float) $withdraw_req->fee_amt, 8, '.', '');
				$name = session::get('tmaitb_profile');
				$encryptUId = insep_encode($code);
				$encryptUsId = insep_encode($user_id);
				$getSiteDetails = Controller::getSitedetails();
				
				$admin = $getSiteDetails->admin_redirect;
				$securl = env('DOMAIN_URL').$admin . "/confirmWithdraw/" . $encryptUId;
				$rsecurl = env('DOMAIN_URL').$admin . "/rejectWithdraw/" . $encryptUId.'/'.$encryptUsId;


				$info = array('###TRANSFER###' => $transfer_amount, '###CUR###' => $currency, '###AMOUNT###' => $withdraw_req->amount, '###ADDR###' => $address, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl, '###FEE###' => $fee_amt, '###USER###' => $email, '###NAME###' => $name);
				
				$toemail1= $getSiteDetails->site_email;
				$toemail = insep_decode($toemail1);
				

				$bcc = '1';
				$sendEmail = Controller::sendEmail($toemail, $info, '20');



				
				if ($sendEmail) {
					Session::flash('success', 'Withdraw request send to admin');

					return Redirect::to('/funds');

				} else {
					Session::flash('error', trans('app_lang.please_try_again'));
				}
				
			}
			return Redirect::to('/funds');
		}

	}
	
	function rejectWithdraw($confirm, $user_id, $amount, $currency) 
	{
		$log_user_id = session::get('tmaitb_user_id');
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		if ($log_user_id) 
		{
			if ($log_user_id == $user_id) 
			{
				$currecny_detail = Currency::where('symbol', $currency)->select('id')->first();
				if ($currecny_detail) {
					$cur_id = $currecny_detail->id;
					$array_withdraw = array('status' => '2');
					$balance = Wallet::getBalance($user_id, $cur_id);
					$update_balance = $balance + $amount;
					$remarks = 'withdraw request cancelled ' . $currency . ' ' . $amount;
					$result = DB::transaction(function () use ($confirm, $user_id, $cur_id, $update_balance, $remarks) {
						Withdraw::where('confirm_code', $confirm)->update(array('status' => 'Cancelled', 'remarks' => $remarks));
						return Wallet::updateBalance($user_id, $cur_id, $update_balance);
					});
					if ($result) {
						$message = 'You have cancelled your withdraw request for -' . $amount . ' ' . $currency;
						Controller::siteNotification($message, $log_user_id);
						Session::flash('success', trans('app_lang.withdraw_cancel_success_lng'));
						return Redirect::to('/funds');
					}
				}
			}
		}
		Session::flash('error', trans('app_lang.please_try_again'));
		return Redirect::to('/funds');
	}
}
