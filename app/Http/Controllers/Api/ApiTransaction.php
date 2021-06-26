<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Sats;
use App\Model\CoinAddress;
use App\Model\Currency;
use App\Model\Deposit;
use App\Model\Fiatdeposit;
use App\Model\Googleauthenticator;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Withdraw;
use App\Model\Fiatwithdraw;
use App\Model\Tokens;
use DB;
use Illuminate\Support\Facades\Input;
use URL;
use Validator;

class ApiTransaction extends Controller {
	public function __construct() {

	}
	
	public function withdraw() {
		$data = Input::all();
		$validate = Validator::make($data, ['amount' => "required", 'currency' => "required", 'address' => "required",'remark' => "required",'device_type' => "required"], ['amount.required' => 'Enter amount', 'currency.required' => 'Choose currency', 'address.required' => 'Enter address','remark.required' => 'Enter remark','device_type.required' => 'Enter device type']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		} else {
			$user_id = $data['user_id'];
			$currency = trim(strip_tags($data['currency']));
			$address = trim(strip_tags($data['address']));
			$amount = trim(strip_tags($data['amount']));
			$tag = trim(strip_tags($data['remark']));
			$device_type = trim(strip_tags($data['device_type']));
			$get_data = DB::table('sresu')
			->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $user_id)
			->select('first_name', 'last_name', 'verified_status', 'randcode', 'id_status', 'selfie_status', 'liame', 'contentmail', 'secret')->first();
			if ($get_data->id_status != 3 || $get_data->selfie_status != 3) {
				$data = array('status' => '0', 'message' => 'Please complete your KYC verification');
				echo json_encode($data);
				exit;
			}
			$curr = Currency::where('symbol', $currency)->select('id', 'min_withdraw', 'with_fee', 'withdarw_status','ERC20')->first();			
			if ($curr) {
				$id = $curr->id;
				if ($curr->withdarw_status == 1) {
					$userbalance = Wallet::getBalance($user_id, $id);					
					
					$fee_per = $curr->with_fee;					
					if($curr->ERC20=='1'){
						$getdetails         = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();

						$decimalval=$getdetails->decimalval;
						if ($decimalval==0) {
							if ( strpos( $amount, "." ) !== false ) {
								$message = "Please don't enter decimal value for withdraw for this token".$currency. " only integer values accepted";
								$data = array('status' => '0', 'message' => $message);
								echo json_encode($data);
								exit;
							}else{
								$fee_amt = $fee_per;
								$fee_amtt =number_format((float) $fee_per, 8, '.', '');
							}
						} else {
							$fee_amt = ($fee_per * $amount) / 100;
							$fee_amtt =number_format((float) $fee_amt, 8, '.', '');
						}
					} else {
						$fee_amt = ($fee_per * $amount) / 100;
						$fee_amtt =number_format((float) $fee_amt, 8, '.', '');
					}
					$transfer_amount = $amount - $fee_amt;
					$transfer_amountt =number_format((float) $transfer_amount, 8, '.', '');

					if ($amount < $curr->min_withdraw) {
						$data = array('status' => '0', 'message' => 'Please enter withdraw amount greater than minimum amount');
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					} else if ($amount > $userbalance) {
						$data = array('status' => '0', 'message' => 'Please enter amount less than your balance amount');
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					} else {
						if ($get_data->randcode) {
							if (isset($data['tfa'])) {
								require_once app_path('Model/Googleauthenticator.php');
								$ga = new Googleauthenticator();

								if (!$ga->verifyCode($get_data->secret, $data['tfa'], 2)) {
									$data = array('status' => '0', 'message' => 'Invalid 2FA code');
									echo json_encode($data, JSON_FORCE_OBJECT);
									exit;
								}
							} else {
								$data = array('status' => '2', 'message' => '2FA is required');
								echo json_encode($data, JSON_FORCE_OBJECT);
								exit;
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
							$sendEmail = Controller::sendEmail($email, $info, '9',$device_type);
							if ($sendEmail) {
								$message = 'You have added a withdraw request for -' . $amount . ' ' . $currency;
								Controller::siteNotification($message, $user_id);
								$data = array('status' => '1', 'message' => 'Withdraw request placed successfully! Please confirm your email
										');
								echo json_encode($data, JSON_FORCE_OBJECT);
								exit;
							} else {
								$data = array('status' => '0', 'message' => 'Email sending failed!');
								echo json_encode($data, JSON_FORCE_OBJECT);
								exit;
							}
						} else {
							$data = array('status' => '0', 'message' => 'Please try again');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
						
					}
				} else {
					$data = array('status' => '0', 'message' => 'Withdraw disbled');
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
			}
		}
	}
	
	function resend_request_email() {
		$data = Input::all();
		$user_id = $data['user_id'];
		$validate = Validator::make($data, [
			'user_id' => 'required',
			'withdraw_id' => 'required',
			'device_type' => 'required']);

		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}

		$device_type = $data['device_type'];

		if (isset($data['withdraw_id'])) {
			$withdraw_id = $data['withdraw_id'];
			$id = insep_decode($withdraw_id);
			$rec = Withdraw::where('id', $id)->where('user_id', $user_id)->select('currency', 'amount', 'address', 'transfer_amount', 'fee_amt', 'status')->first();
			if ($rec) {
				if ($rec->status == 'Pending') {
					$code = time() . '112' . $user_id . rand(99, 99999);
					$encryptUId = insep_encode($code);
					$result = Withdraw::where('id', $id)->update(array('confirm_code' => $code));
					if ($result) {
						$securl = url("/confirmtranferbyuser/" . $encryptUId);
						$rsecurl = url("/rejecttranferbyuser/" . $encryptUId);
						$get_data = User::where('id', $user_id)->select('first_name', 'last_name', 'liame', 'contentmail')->first();
						$name = $get_data->first_name . ' ' . $get_data->last_name;
						$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
						$transfer_amount = $rec->transfer_amount;
						$amount = $rec->amount;
						$currency = $rec->currency;
						$fee_amt = $rec->fee_amt;
						$address = $rec->address;
						$info = array('###TRANSFER###' => $transfer_amount, '###CUR###' => $currency, '###AMOUNT###' => $amount, '###ADDR###' => $address, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl, '###FEE###' => $fee_amt, '###USER###' => $name);

						$sendEmail = Controller::sendEmail($email, $info, '9', $device_type);
						$message = 'You have requested a withdraw resend link';
						Controller::siteNotification($message, $user_id);
						$data = array('status' => '1', 'message' => 'Withdraw mail resend');
					} else {
						$data = array('status' => '0', 'message' => 'Withdraw link already used');
					}
				} else {
					$data = array('status' => '0', 'message' => 'Withdraw link already used');
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request');
			}
		} else {
			$data = array('status' => '0', 'message' => 'withdraw id required');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
  	
	function fiat_resend_request_email() {
		$data = Input::all();
		$user_id = $data['user_id'];
		$validate = Validator::make($data, [
			'user_id' => 'required',
			'withdraw_id' => 'required',
			'device_type' => 'required']);

		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data);
				exit;
			}
		}

		$device_type = $data['device_type'];

		if (isset($data['withdraw_id'])) {
			$withdraw_id = $data['withdraw_id'];
			$id = insep_decode($withdraw_id);
			$rec = Fiatwithdraw::where('id', $id)->where('user_id', $user_id)->select('currency', 'amount','given_amount', 'fee_amt', 'status')->first();
			if ($rec) {
				if ($rec->status == 'Pending') {
					$code = time() . '112' . $user_id . rand(99, 99999);
					$encryptUId = insep_encode($code);
					$result = Fiatwithdraw::where('id', $id)->update(array('confirm_code' => $code));
					if ($result) {
						$securl = url("/confirmwithdrawbyuser/" . $encryptUId);
						$rsecurl = url("/rejectwithdrawbyuser/" . $encryptUId);
						$get_data = User::where('id', $user_id)->select('first_name', 'last_name', 'liame', 'contentmail')->first();
						$name = $get_data->first_name . ' ' . $get_data->last_name;
						$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
						$transfer_amount = $rec->given_amount;
						$amount = $rec->amount;
						$currency = $rec->currency;
						$fee_amt = $rec->fee_amt;
						$address = $rec->address;
						$info = array('###TRANSFER###' => $transfer_amount, '###CUR###' => $currency, '###AMOUNT###' => $amount, '###ADDR###' => $address, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl, '###FEE###' => $fee_amt, '###USER###' => $name);
						$sendEmail = Controller::sendEmail($email, $info, '28', $device_type);
						$message = 'You have requested a withdraw resend link';
						Controller::siteNotification($message, $user_id);
						$data = array('status' => '1', 'message' => 'Withdraw mail resend');
					} else {
						$data = array('status' => '0', 'message' => 'Withdraw link already used');
					}
				} else {
					$data = array('status' => '0', 'message' => 'Withdraw link already used');
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request');
			}
		} else {
			$data = array('status' => '0', 'message' => 'withdraw id required');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
	
	function cancel_withdraw_request() {
		$data = Input::all();
		$user_id = $data['user_id'];
		if (isset($data['withdraw_id'])) {
			$withdraw_id = $data['withdraw_id'];
			$id = insep_decode($withdraw_id);
			$rec = Withdraw::where('id', $id)->where('user_id', $user_id)->where('is_flag', 0)->select('currency', 'amount', 'address', 'transfer_amount', 'fee_amt', 'status')->first();
			if ($rec) {
				if ($rec->status == 'Pending') {
					$currency = $rec->currency;
					$currecny_detail = Currency::where('symbol', $currency)->select('id')->first();
					if ($currecny_detail) {
						$cur_id = $currecny_detail->id;
						$amount = $rec->amount;
						$balance = Wallet::getBalance($user_id, $cur_id);
						$update_balance = $balance + $amount;
						$remarks = 'Withdraw cancelled ' . $amount . ' ' . $currency;
						$result = DB::transaction(function () use ($id, $user_id, $cur_id, $update_balance, $remarks) {

							Withdraw::where('id', $id)->update(array('status' => 'Cancelled', 'remarks' => $remarks));
							return Wallet::updateBalance($user_id, $cur_id, $update_balance);
						});
						if ($result) {
							$message = 'You have cancelled your withdraw request for -' . $amount . ' ' . $currency;
							Controller::siteNotification($message, $user_id);
							$data = array('status' => '1', 'message' => 'Withdraw cancelled');
						}
					} else {
						$data = array('status' => '0', 'message' => 'Invalid request');
					}
				} else {
					$data = array('status' => '0', 'message' => 'Withdraw link already used');
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request');
			}
		} else {
			$data = array('status' => '0', 'message' => 'withdraw id required');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
	
	function cancel_fiat_withdraw_request() {
		$data = Input::all();
		$user_id = $data['user_id'];
		if (isset($data['withdraw_id'])) {
			$withdraw_id = $data['withdraw_id'];
			$id = insep_decode($withdraw_id);
			$rec = Fiatwithdraw::where('id', $id)->where('user_id', $user_id)->where('is_flag', 0)->select('currency', 'amount', 'given_amount', 'fee_amt', 'status')->first();
			if ($rec) {
				if ($rec->status == 'Pending') {
					$currency = $rec->currency;
					$currecny_detail = Currency::where('symbol', $currency)->select('id')->first();
					if ($currecny_detail) {
						$cur_id = $currecny_detail->id;
						$amount = $rec->amount;
						$balance = Wallet::getBalance($user_id, $cur_id);
						$update_balance = $balance + $amount;
						$remarks = 'Withdraw cancelled ' . $amount . ' ' . $currency;
						$result = DB::transaction(function () use ($id, $user_id, $cur_id, $update_balance, $remarks) {

							Fiatwithdraw::where('id', $id)->update(array('status' => 'Cancelled', 'remarks' => $remarks));
							return Wallet::updateBalance($user_id, $cur_id, $update_balance);
						});
						if ($result) {
							$message = 'You have cancelled your withdraw request for -' . $amount . ' ' . $currency;
							Controller::siteNotification($message, $user_id);
							$data = array('status' => '1', 'message' => 'Withdraw cancelled');
						}
					} else {
						$data = array('status' => '0', 'message' => 'Invalid request');
					}
				} else {
					$data = array('status' => '0', 'message' => 'Withdraw link already used');
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request');
			}
		} else {
			$data = array('status' => '0', 'message' => 'withdraw id required');
		}
		echo json_encode($data, JSON_FORCE_OBJECT);
		exit;
	}
	
	public function withdraw_history() {
		$data = Input::all();
		$id = $data['user_id'];
		$data = $orders = array();
		$withdraw = Withdraw::where('user_id', $id);
		$withdraw_count = $withdraw->count();
		if ($withdraw_count) {
			$withdraw = $withdraw->select('updated_at', 'created_at','address', 'transaction_id', 'currency', 'amount', 'status', 'fee_amt', 'id');
			$orders = $withdraw->orderBy('id', 'Desc')->get()->toArray();
		}
		$his = array();
		$his1 = array();
		if ($withdraw_count) {
			foreach ($orders as $r) {
				$tx = $r['transaction_id'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");
				array_push($his, array(
					'id' => insep_encode($r['id']),
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'fee' => $fee_amt,
					'address' => $r['address'],
					'datetime' => $r['updated_at'],
					'status' => $r['status'],
					'type' => 'crypto',
				));
			}
			self::array_sort_by_column($his, 'datetime');
		} else {
			array_merge($his1, array());
		}

		$fiat_withdraw = Fiatwithdraw::where('user_id', $id);
		$fiat_withdraw_count = $fiat_withdraw->count();
		if ($fiat_withdraw_count) {
			$fiat_withdraw = $fiat_withdraw->select('updated_at', 'created_at','given_amount', 'transaction_id','currency', 'currency_id', 'amount', 'status', 'fee_amt', 'id');
			$orders1 = $fiat_withdraw->orderBy('id', 'Desc')->get()->toArray();
		}
		
		if ($fiat_withdraw_count) {
			foreach ($orders1 as $r) {
				$tx = $r['transaction_id'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");

				array_push($his, array(
					'id' => insep_encode($r['id']),
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'fee' => $fee_amt,
					'transfer_amount'=>$r['given_amount'],
					'datetime' => $r['updated_at'],
					'status' => $r['status'],
					'type' => 'fiat',
				));
			}
			self::array_sort_by_column($his, 'datetime');
		} else {
			array_merge($his1, array());
		}
		if(!empty($his)) {
			$history = $his;
		}
		else {
			$history = $his1;
		}		
		echo json_encode(array('status' => '1', 'Withdraw_history' => $history));
	}
    
	public function deposit_history() {

		$data = Input::all();
		$id = $data['user_id'];
		$data = $orders = array();
		$deposit = Deposit::where('user_id', $id);
		$deposit_count = $deposit->count();
		if ($deposit_count) {
			$deposit = $deposit->select('updated_at','created_at', 'address', 'transaction_id', 'currency', 'amount', 'status');
			$orders = $deposit->orderBy('id', 'Desc')->get()->toArray();
		}
		$his = array();
		$his1 = array();
		if ($deposit_count) {
			foreach ($orders as $r) {
				$tx = $r['transaction_id'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$status = $r['status'];
				array_push($his, array(
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'address' => $r['address'],
					'datetime' => $r['updated_at'],
					'status' => $status,
					'type' => 'crypto',
				));
			}
			self::array_sort_by_column($his, 'datetime');
		} else {
			array_merge($his1, array());
		}

		$url = URL::to('/') . "/public/images/deposit_proof/";
		$data = $orders1 = array();
		$fiat_deposit = Fiatdeposit::where('user_id', $id);

		$fiat_deposit_count = $fiat_deposit->count();
		if ($fiat_deposit_count) {
			$fiat_deposit = $fiat_deposit->select('updated_at','created_at', 'proof', 'referencenum', 'currency', 'amount', 'status','payment_method');
			$orders1 = $fiat_deposit->orderBy('id', 'Desc')->get()->toArray();
		}
	
		if ($fiat_deposit_count) {
			foreach ($orders1 as $r) {
				$tx = $r['referencenum'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$status = $r['status'];
				array_push($his, array(
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'proof' => $url.$r['proof'],
					'datetime' => $r['updated_at'],
					'status' => $status,
					'type' => 'fiat',
				));
			}
			self::array_sort_by_column($his, 'datetime');
		} else {
			array_merge($his1, array());
		}	
		if(!empty($his)) {
			$history = $his;
		}
		else {
			$history = $his1;
		}
		echo json_encode(array('status' => '1', 'Deposit_history' => $history));
	}	

	
	public function coin_details() {
		$data = Input::all();
		$id = $data['user_id'];
		$all_cur = $userbalance = $curr = array();
		$allcurr = Currency::where('status', 1)->select('symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance', 'image')->get()->toArray();
		$userbalance = Wallet::getBalance($id);
		foreach ($allcurr as $curr) {
			$symbol = $curr['symbol'];
			$all_cur['symbol'] = $symbol;
			$all_cur['name'] = $curr['name'];
			$all_cur['min_withdraw'] = $curr['min_withdraw'];
			$all_cur['max_withdraw'] = $curr['max_withdraw'];
			$all_cur['with_fee'] = $curr['with_fee'];
			$all_cur['image'] = $curr['image'];
			$all_cur['withdarw_content'] = $curr['withdarw_content'];
			$all_cur['withdraw_maintenance'] = $curr['withdraw_maintenance'];
			$all_cur['status'] = $curr['withdarw_status'] ? 'Enabled' : 'Disabled';
			$inorders = inorders($symbol, $id);
			$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_withdraw'];
			$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");

			if (isset($userbalance[$curr['id']])) {
				$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");
			} else {
				$balance = 0;
			}
			$all_cur['inorders'] = $inorders;
			$all_cur['balance'] = $balance;
			$all_cur['total'] = $inorders + $balance;
			$all_data[] = $all_cur;
		}
		$mdata["status"] = 1;
		$mdata["data"] = $all_data;
		echo json_encode($mdata);
	}

	public function deposit() {
		$data = Input::all();
		$id = $data['user_id'];
		if (isset($data['currency'])) {
			$currency = $data['currency'];
			$currency = trim(strip_tags($currency));
			
			$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('alert_deposit', 'deposit_status', 'deposit_content', 'deposit_maintenance', 'alert_message', 'alert_checkbox_content', 'image', 'name', 'symbol', 'id')->first();
			if ($currecny_details) {
				if ($currecny_details->deposit_status == '1') {
					if ($currecny_details->alert_deposit == '1') {
						$address_array = self::get_address($currency, $id);
						$userbalance = Wallet::getBalance($id);
						$inorders = inorders($currecny_details->symbol, $id);
						$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_withdraw'];

						if (isset($userbalance[$currecny_details->id])) {
							$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$currecny_details->id]), '0'), ".");
						} else {
							$balance = 0;
						}

						$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");

						
						$data = array('type' => 'warning', 'warning' => $currecny_details->alert_message, 'checkbox_message' => $currecny_details->alert_checkbox_content, 'coin_image' => $currecny_details->image, 'coin_name' => $currecny_details->name, 'symbol' => $currecny_details->symbol, 'qr_image' => 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . insep_decode($address_array['address']), 'inorder' => $inorders, 'total_balance' => $balance + $inorders,
							'available_balance' => $balance);

						echo json_encode(array('status' => '1', 'data' => $data));exit;
					} else {
						$address_array = self::get_address($currency, $id);
						if ($address_array) {
							$tag = '';

							$coin_Address = insep_decode($address_array['address']);

							$userbalance = Wallet::getBalance($id);
							$inorders = inorders($currecny_details->symbol, $id);
							$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_withdraw'];

							if (isset($userbalance[$currecny_details->id])) {
								$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$currecny_details->id]), '0'), ".");
							} else {
								$balance = 0;
							}

							$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");
							$data = array(
								'type' => 'deposit',
								'deposit_content' => $currecny_details->deposit_content,
								'address' => $coin_Address,
								'tag' => $tag,
								'coin_image' => $currecny_details->image,
						
								'coin_name' => $currecny_details->name,
								'symbol' => $currecny_details->symbol,
								'qr_image' => 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . $coin_Address,
								'inorder' => $inorders,
								'total_balance' => $balance + $inorders,
								'available_balance' => $balance);

							echo json_encode(array('status' => '1', 'data' => $data));exit;
						} else {
							echo json_encode(array('status' => '0', 'message' => 'Please try again'));exit;
						}
					}
				} else {
					$userbalance = Wallet::getBalance($id);
					$inorders = inorders($currecny_details->symbol, $id);
					$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_withdraw'];

					if (isset($userbalance[$currecny_details->id])) {
						$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$currecny_details->id]), '0'), ".");
					} else {
						$balance = 0;
					}

					$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");

				
					$data = array('type' => 'maintenance', 'maintenance' => $currecny_details->deposit_maintenance, 'coin_name' => $currecny_details->name, 'coin_image' => $currecny_details->image, 'symbol' => $currecny_details->symbol, 'inorder' => $inorders, 'total_balance' => $balance + $inorders, 'available_balance' => $balance);
					echo json_encode(array('status' => '1', 'data' => $data));exit;
				}
			} else {
				echo json_encode(array('status' => '0', 'message' => 'Please try again'));exit;
			}
		} else {
			echo json_encode(array('status' => '0', 'message' => 'currency required'));exit;
		}
	}

	public function accept_deposit() {
		$data = Input::all();
		$id = $data['user_id'];
		if (isset($data['currency'])) {
			$currency = $data['currency'];
			$currency = trim(strip_tags($currency));
			$address_array = self::get_address($currency, $id);
			if ($address_array) {
				$tag = '';
				
				$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('deposit_content', 'name', 'symbol', 'image')->first();
				$data = array('type' => 'deposit', 'address' => insep_decode($address_array['address']), 'tag' => $tag, 'deposit_content' => $currecny_details->deposit_content, 'coin_image' => $currecny_details->image, 'coin_name' => $currecny_details->name, 'symbol' => $currecny_details->symbol, 'qr_image' => 'https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=' . insep_decode($address_array['address']));
				echo json_encode(array('status' => '1', 'data' => $data));exit;
			} else {
				echo json_encode(array('status' => '0', 'message' => 'Please try again'));exit;
			}
		}
		echo json_encode(array('status' => '0', 'data' => 'currency required'));exit;
	}
	public function get_address($currency, $id) {
		$currency = trim(strip_tags($currency));
		$user = CoinAddress::where(['user_id' => $id, 'currency' => $currency])->select('address', 'tag')->first();
		if ($user) {
			return $user->toArray();
		} else {
			$coins = new Sats;
			$address = $coins->generateAddress($currency);
			if ($address) {
				
					$tag_enc = '';
					$address_encrypt = insep_encode($address);
				
				$address_array = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => $currency];
				$result = CoinAddress::Create($address_array);
				if ($result) {
					$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
					return $address_array;
				}
			}
		}
		return false;
	}
	public function fiatdeposit() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'bankid' => "required",
			'currency' => "required",
			'depositamount' => 'required',
			'transaction_id' => 'required',
			'payment' => 'required',
			'proof' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$account = strip_tags($data['bankid']);
		$currency = strip_tags($data['currency']);
		$currencyid = getCurrencyid($currency);		
		$depositamount = strip_tags($data['depositamount']);
		$refno = strip_tags($data['transaction_id']);
		$payment = strip_tags($data['payment']);
		$filename = strip_tags($data['proof']);
		if($depositamount > 0)
		{
			$update_arr = ['user_id' => $id, 'payment_method' => $payment, 'currency_id' => $currencyid, 'currency' => $currency, 'amount' => $depositamount, 'referencenum' => $refno, 'bankid' => $account, 'status' => 'Pending','proof' => $filename];
			
			$update = Fiatdeposit::create($update_arr);
			if ($update) {
				echo json_encode(array('status' => '1', 'message' => 'Deposit request placed successfully'));exit;
			} else {
				echo json_encode(array('status' => '0', 'message' => 'Please try again!'));exit;
			}
		}
		else 
		{
			echo json_encode(array('status' => '0', 'message' => 'Please enter deposit amount greater than zero'));exit;
		}
	}
	public function fiatwithdraw() {
		$data = Input::all();
		$id = $data['user_id'];
		$validate = Validator::make($data, [
			'bankid' => "required",
			'currency' => "required",
			'withdrawamount' => 'required',
			'device_type' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$get_data = DB::table('sresu')
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'verified_status', 'randcode', 'id_status', 'selfie_status', 'liame', 'contentmail', 'secret')->first();
		if ($get_data->id_status != 3 || $get_data->selfie_status != 3) {
			$data = array('status' => '0', 'message' => 'Please verify your kyc');
			echo json_encode($data, JSON_FORCE_OBJECT);
			exit;
		}
		$bank = strip_tags($data['bankid']);
		$currencysym = strip_tags($data['currency']);
		$currencyid = getCurrencyid($currencysym);
		$withdrawamount = strip_tags($data['withdrawamount']);
		$device_type = strip_tags($data['device_type']);
		$fiatcurrency = Currency::where('id', $currencyid)->select('min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status','id')->first();
		$min_withdraw = $fiatcurrency->min_withdraw;
		$max_withdraw = $fiatcurrency->max_withdraw;
		$with_fee = $fiatcurrency->with_fee;

		if ($fiatcurrency) {
			if ($fiatcurrency->withdarw_status == 1) {
				$balance = getBalance($id, $fiatcurrency->id);
				$fee = $withdrawamount * $with_fee / 100;
				$givenamount = $withdrawamount - $fee;
				
				if($balance > 0){
					if ($withdrawamount < $min_withdraw) {
						$data = array('status' => '0', 'message' => 'Please enter withdraw amount greater than minimum amount');
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					} else if ($withdrawamount > $max_withdraw) {
						$data = array('status' => '0', 'message' => 'Please enter withdraw amount less than maximum amount');
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					} else if ($withdrawamount > $balance) {
						$data = array('status' => '0', 'message' => 'Please enter amount less than your balance amount');
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					} else {
						if ($get_data->randcode) {
							if (isset($data['tfa'])) {
								require_once app_path('Model/Googleauthenticator.php');
								$ga = new Googleauthenticator();
								if (!$ga->verifyCode($get_data->secret, $data['tfa'], 2)) {
									$data = array('status' => '0', 'message' => 'Please enter correct code');
									echo json_encode($data, JSON_FORCE_OBJECT);
									exit;
								}
							} else {
								$data = array('status' => '0', 'message' => '2FA is required');
								echo json_encode($data, JSON_FORCE_OBJECT);
								exit;
							}
						}
						$currency = getCurrencysymbol($fiatcurrency->id);
						$code = time() . $id . rand(99, 99999);
						$encryptUId = insep_encode($code);
						$expire = strtotime('+1 day', time());
						$remarks = 'withdraw ' . $currency . ' ' . $withdrawamount;
						$update_arr = array('user_id' => $id, 'bankid' => $bank, 'currency_id' => $fiatcurrency->id, 'currency' => $currency, 'amount' => $withdrawamount, 'fee_amt' => $fee, 'fee_per' => $with_fee, 'given_amount' => $givenamount, 'ip_addr' => $_SERVER['REMOTE_ADDR'], 'confirm_code' => $code, 'expire' => $expire, 'status' => 'Pending', 'remarks' => $remarks);
						
						$update = Fiatwithdraw::create($update_arr);
						if ($update) {
							$bal = Wallet::getBalance($id, $fiatcurrency->id);
							$update_balance = $bal - $withdrawamount;
							$balupdate = Wallet::updateBalance($id, $currencyid, $update_balance);

							$useremail = getUserEmail($id);
							$name = getUserName($id);

							$securl = url("/confirmwithdrawbyuser/" . $encryptUId);
							$rsecurl = url("/rejectwithdrawbyuser/" . $encryptUId);

							$info = array('###CUR###' => $currency, '###AMOUNT###' => $withdrawamount, '###TRANSFER###' => $givenamount, '###FEE###' => $fee, '###USER###' => $name, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl);

							$sendEmail = Controller::sendEmail($useremail, $info, '28',$device_type);
							$data = array('status' => '1', 'message' => 'Withdraw request placed successfully! Please confirm your email');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						} else {
							$data = array('status' => '0', 'message' => 'Please try again!');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
					}
				}else{
					$data = array('status' => '0', 'message' => 'Insufficient balance');
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
			} else {
				$data = array('status' => '0', 'message' => 'Withdraw disbled');
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		} else {
			$data = array('status' => '0', 'message' => 'Currency not found!');
			echo json_encode($data, JSON_FORCE_OBJECT);
			exit;
		}
	}
	public function fiat_deposit_history() {
		$data = Input::all();
		$id = $data['user_id'];
		$token = $data['token'];
		$url = URL::to('/') . "/public/images/deposit_proof/";
		$data = $orders = array();
		$deposit = Fiatdeposit::where('user_id', $id);

		$deposit_count = $deposit->count();
		if ($deposit_count) {
			$deposit = $deposit->select('updated_at', 'proof', 'referencenum', 'currency', 'amount', 'status','payment_method');
			$orders = $deposit->orderBy('id', 'Desc')->get()->toArray();
		}
		$data = array();
		if ($deposit_count) {
			foreach ($orders as $r) {
				$tx = $r['referencenum'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$status = $r['status'];
				array_push($data, array(
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'proof' => $url.$r['proof'],
					'datetime' => $r['updated_at'],
					'status' => $status,
				));
			}
			echo json_encode(array('status' => '1', 'data' => $data));
		} else {

			echo json_encode(array('status' => '1', 'data' => array()));
		}
	}
	public function fiat_withdraw_history() {
		$data = Input::all();
		$id = $data['user_id'];
		$data = $orders = array();
		$withdraw = Fiatwithdraw::where('user_id', $id);
		$withdraw_count = $withdraw->count();
		if ($withdraw_count) {
			$withdraw = $withdraw->select('updated_at', 'given_amount', 'transaction_id', 'currency_id','currency', 'amount', 'status', 'fee_amt', 'id');
			$orders = $withdraw->orderBy('id', 'Desc')->get()->toArray();
		}
		$data = array();
		if ($withdraw_count) {
			foreach ($orders as $r) {
				$tx = $r['transaction_id'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");

				array_push($data, array(
					'id' => insep_encode($r['id']),
					'currency' => $r['currency'],
					'transaction_id' => $tx,
					'amount' => $amount,
					'fee' => $fee_amt,
					'transfer_amount' => $r['given_amount'],
					'datetime' => $r['updated_at'],
					'status' => $r['status'],
				));
			}
			echo json_encode(array('status' => '1', 'data' => $data));
		} else {
			echo json_encode(array('status' => '1', 'data' => array()));
		}
	}
	public function get_depositcoins() {
		$data = Input::all();
		$id = $data['user_id'];
		$currency = trim(strip_tags($data['currency']));
		$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('alert_deposit', 'deposit_status', 'deposit_content', 'deposit_maintenance', 'alert_message', 'alert_checkbox_content')->first();
		if ($currecny_details) {			
			if ($currecny_details->deposit_status == '1') {
				if ($currecny_details->alert_deposit == '1') {
					$data = array('type' => 'warning', 'deposit_content' => $currecny_details->deposit_content, 'msg' => $currecny_details->alert_message, 'msg1' => $currecny_details->alert_checkbox_content);
				} else {
					$address_array = self::get_addresscoin($currency,$id);
					if ($address_array) {
						$tag = '';						
						$qrcode = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . insep_decode($address_array['address']);
						$contentview =  getcurrencynotice('content','0','en',$currency);						
						$data = array('type' => 'deposit', 'deposit_content' => $contentview, 'address' => insep_decode($address_array['address']), 'tag' => $tag,'qrcode' => $qrcode);
						echo json_encode(array('status' => '1', 'data' => $data));
					} else {
						$data = array('type' => 'failed');
						echo json_encode(array('status' => '0', 'data' => $data));
					}
				}
			} else {
				$data = array('type' => 'maintenance', 'msg' => $currecny_details->deposit_maintenance);
				echo json_encode(array('status' => '0', 'data' => $data));
			}
		} else {
			$data = array('type' => 'failed Currency details not available');
			echo json_encode(array('status' => '0', 'data' => $data));
		}		
	}

	public function get_addresscoin($currency,$id) {		
		$currency = trim(strip_tags($currency));
		$user = CoinAddress::where(['user_id' => $id, 'currency' => $currency])->select('address', 'tag')->first();
		if ($user) {
			return $user->toArray();
		} else {
			$coins = new Sats;
			$address = $coins->generateAddress($currency,$id);
			if ($address) {
				$tag_enc = '';
				
					$address_encrypt = insep_encode($address);
					$address_array = ['user_id' => $id, 'address' => $address_encrypt, 'tag' => $tag_enc, 'created_at' => date('Y-m-d H:i:s'), 'currency' => $currency];
					$result = CoinAddress::Create($address_array);
					if ($result) {
						$address_array = array('address' => $address_encrypt, 'tag' => $tag_enc);
						return $address_array;
					}
					
			}
		}
		return false;
	}

	
	function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
		$reference_array = array();
		foreach($array as $key => $row) {
			$reference_array[$key] = $row[$column];
		}
		array_multisort($reference_array, $direction, $array);
	}
}
