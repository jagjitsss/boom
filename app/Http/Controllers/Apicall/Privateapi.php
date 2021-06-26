<?php
namespace App\Http\Controllers\Apicall;

use App\Http\Controllers\Controller;
use App\Model\CoinOrder;
use App\Model\OrderTemp;
use App\Model\TradeModel;
use App\Model\TradePairs;
use App\Model\Currency;
use App\Model\User;
use App\Model\Wallet;
use DB;
use URL;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Http\Request;
use App\Model\Deposit;
use App\Model\Fiatdeposit;
use App\Model\Withdraw;
use App\Model\Fiatwithdraw;
use App\Model\CoinAddress;
use App\Http\Controllers\Front\Sats;

class Privateapi extends Controller {
	public function __construct() {

	}

	public function withdraw() {
		$data = Input::all();
		$validate = Validator::make($data, [
			'api_key' => "required",
			'api_secret' => "required",
			'amount' => "required",
			'currency' => "required",
			'to_address' => "required",
			'remark' => "required"
		],
		[
			'api_key.required' => 'Invalid key details',
			'api_secret.required' => 'Invalid key details',
			'amount.required' => 'Enter amount',
			'currency.required' => 'Choose currency',
			'to_address.required' => 'Enter address',
			'remark.required' => 'Enter remark'
		]);
		if ($validate->fails())
		{
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		} else {
			
			$currency = trim(strip_tags($data['currency']));
			$address = trim(strip_tags($data['to_address']));
			$amount = trim(strip_tags($data['amount']));
			$tag = trim(strip_tags($data['remark']));

			$api_key = trim(strip_tags($data['api_key']));
			$api_secret = trim(strip_tags($data['api_secret']));			
			$device_type = 'API Service';
			

			$getUserDetails = User::where(array('api_key' => $api_key, 'api_secret' => $api_secret))->first();
			
			if(isset($getUserDetails->id) && $getUserDetails->id > 0)
			{

				$user_id = $getUserDetails->id;	

				$get_data = DB::table('sresu')
				->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $user_id)
				->select('first_name', 'last_name', 'verified_status', 'randcode', 'id_status', 'selfie_status', 'liame', 'contentmail', 'secret')->first();
				
				$curr = Currency::where('symbol', $currency)->select('id', 'min_withdraw', 'with_fee', 'withdarw_status','ERC20')->first();			
				if($curr)
				{
					$id = $curr->id;
					if ($curr->withdarw_status == 1) {
						$userbalance = Wallet::getBalance($user_id, $id);					
						
						$fee_per = $curr->with_fee;					
						$fee_amt = ($fee_per * $amount) / 100;
						$fee_amtt =number_format((float) $fee_amt, 8, '.', '');
						
						$transfer_amount = $amount - $fee_amt;
						$transfer_amountt =number_format((float) $transfer_amount, 8, '.', '');

						if ($amount < $curr->min_withdraw)
						{
							$data = array('status' => '0', 'message' => 'Please enter withdraw amount greater than minimum amount');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
						else if($amount > $userbalance)
						{
							$data = array('status' => '0', 'message' => 'Please enter amount less than your balance amount');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
						else
						{

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

							if(isset($result))
							{
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
									$data = array('status' => '1', 'message' => 'Withdraw request placed successfully! Please confirm your email');
									echo json_encode($data, JSON_FORCE_OBJECT);
									exit;
								} else {
									$data = array('status' => '0', 'message' => 'Withdraw request placed successfully, Email sending failed!');
									echo json_encode($data, JSON_FORCE_OBJECT);
									exit;
								}
							}
							else
							{
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
			else
			{
				$data = array('status' => '0', 'message' => 'No users found');
				echo json_encode($data);
				exit;
			}
		}
	}

	public function get_depositcoins_api()
	{

		
		$data = Input::all();
		//

		$api_key = trim(strip_tags($data['api_key']));
		$api_secret = trim(strip_tags($data['api_secret']));
		$getUserDetails = User::where(array('api_key' => $api_key, 'api_secret' => $api_secret))->first();
		if(isset($getUserDetails->id) && $getUserDetails->id > 0)
		{


		$id = $getUserDetails->id;

		$currency = trim(strip_tags($data['currency']));
		$currecny_details = Currency::where(['symbol' => $currency, 'status' => 1])->select('alert_deposit', 'deposit_status', 'deposit_content', 'deposit_maintenance', 'alert_message', 'alert_checkbox_content')->first();

		if (isset($currecny_details) && count($currecny_details) > 0)
		{

			if ($currecny_details->deposit_status == '1') {
				if ($currecny_details->alert_deposit == '1') {
					$data = array('type' => 'warning', 'deposit_content' => $currecny_details->deposit_content, 'msg' => $currecny_details->alert_message, 'msg1' => $currecny_details->alert_checkbox_content);
				} else {
					$address_array = self::get_addresscoin($currency,$id);
					if ($address_array) {
						$tag = '';						
						$qrcode = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . insep_decode($address_array['address']);
						
						$data = array('status' => 1, 'deposit_address' => insep_decode($address_array['address']),'qrcode' => $qrcode);
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
		}
		else
		{
			$data = array('type' => 'failed Currency details not available');
			echo json_encode(array('status' => '0', 'data' => $data));
			exit;
		}
		}
		else
		{
			echo json_encode(array('status' => '0', 'msg' => 'No users found'));	
			exit;
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

	public function getaccountbalance(Request $request) {
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];

		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$balance_array = $balance = $all_result = array();
			$currency=Currency::where(['status' => 1])->get();
			foreach ($currency as $value) {
				$curid=$value->id;

				$balance[$value->symbol] = Wallet::getBalance($user->id,$curid); 
			}

			$mdata["status"] = 1;
			$mdata["data"] = $balance;
		}else{
			$user=User::select('id')->where(array('api_key'=>$apikey,'status'=>0))->first();
			if($user)
			{
				$mdata["data"] = "Please enable your apikey settings";
			}else{
				$mdata["data"] = "Invalid apikey";
			}

			$mdata["status"] = 0;

		}


		echo json_encode($mdata);exit;
	}



	public function getDeposithistory() {
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;
			$data = $orders = array();

			$deposit = Deposit::where('user_id', $id)->get();
			
			if (count($deposit)>0) {
				$deposit = Deposit::select('updated_at', 'address', 'transaction_id', 'currency', 'amount', 'status');
				$orders = $deposit->orderBy('id', 'Desc')->get()->toArray();
			}
			$his = array();
			if (count($deposit)>0) {
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
				array_push($his, array());
			}
			$url = URL::to('/') . "/public/images/deposit_proof/";
			$data = $orders1 = array();
			$fiat_deposit = Fiatdeposit::where('user_id', $id)->count();

			if (count($fiat_deposit)>0) {
				$fiat_deposit = Fiatdeposit::select('updated_at', 'proof', 'referencenum', 'currency', 'amount', 'status','payment_method');
				$orders1 = Fiatdeposit::where('user_id', $id)->orderBy('id', 'Desc')->get()->toArray();
			}
		

			$his1 = array();
			if (count($fiat_deposit)>0) {
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
				self::array_sort_by_column($his1, 'datetime');
			} else {
				array_push($his1, array());
			}
			
			$hist=array_merge($his,$his1);
			echo json_encode(array('status' => '1', 'Deposit_history' => $hist));
		}	
	}
  
	function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
		$reference_array = array();
		foreach($array as $key => $row) {
			$reference_array[$key] = $row[$column];
		}
		array_multisort($reference_array, $direction, $array);
	}

	public function getWithdrawhistory() {
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;
			$data = $orders = array();

			$withdraw =Withdraw::where('user_id', $id)->count();
			if (count($withdraw)>0) {
				$withdraw = Withdraw::select('updated_at', 'address', 'transaction_id', 'currency', 'amount', 'status', 'fee_amt', 'id');
				$orders = Withdraw::where('user_id', $id)->orderBy('id', 'Desc')->get()->toArray();
			}
			$his = array();
			if (count($withdraw)>0) {
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
				array_push($his, array());
			
			}

			$fiat_withdraw = Fiatwithdraw::where('user_id', $id)->count();

			if (count($fiat_withdraw)>0) {
				$fiat_withdraw = Fiatwithdraw::select('updated_at', 'given_amount', 'transaction_id','currency', 'currency_id', 'amount', 'status', 'fee_amt', 'id');
				$orders1 = Fiatwithdraw::where('user_id', $id)->orderBy('id', 'Desc')->get()->toArray();
			}
			$his1 = array();
			if (count($fiat_withdraw)>0) {
				foreach ($orders1 as $r) {
					$tx = $r['transaction_id'];
					$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
					$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");

					array_push($his1, array(
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
				self::array_sort_by_column($his1, 'datetime');
			} else {
				array_push($his1, array());

			}

			
			
			$hist=array_merge($his,$his1);
			echo json_encode(array('status' => '1', 'Withdraw_history' => $hist));
		}
	}

	function openBuyorders(){
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];

		$validate = Validator::make($data, [
			'pair' => 'required',
		], [
			'pair.required' => 'pair required',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$pair = $data['pair'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;

			$pair=explode("_",$pair);
			$from = $pair[0];
			$to = $pair[1];
			$where= array('from_symbol'=>$to,'to_symbol'=>$from);
			$pairDetails = TradePairs::select('id')->where($where)->first();
			if($pairDetails){
				$hist=self::getBuySellOrdersApi($pairDetails->id,'buy',$id);
				echo json_encode(array('status' => '1', 'openOrders' => $hist));
			}else{
				$hist ="Invalid Pairs";
				echo json_encode(array('status' => '0', 'openOrders' => $hist));
			}

		}
	}

		
	public static function getBuySellOrdersApi($pair, $type,$userid) {
		if ($type == 'sell') {
			$openOrders = CoinOrder::where('pair', $pair)->where('Type',$type)->where('user_id',$userid)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select(DB::raw('SUM(Amount) as amount'), 'id', 'Price', 'secondCurrency', 'firstCurrency', 'status')->orderBy('Price', 'asc')->groupBy('Price')->get();

		} else {
			$openOrders = CoinOrder::where('pair', $pair)->where('Type',$type)->where('user_id',$userid)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select(DB::raw('SUM(Amount) as amount'), 'id', 'Price', 'secondCurrency', 'firstCurrency', 'status')->orderBy('Price', 'desc')->groupBy('Price')->get();

		}
		$response = $result = array();
		$activeAmount_var = 0;
		$totalapiamount = 0;
		if (!$openOrders->isEmpty()) {
			foreach ($openOrders as $order) {
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->amount;
				$status = $order->status;
				if ($type == "buy") {
					$tempId = "buyorderId";
				} else {
					$tempId = "sellorderId";
				}
				$filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
				$filledAmount = ($filledAmount) ? $amount - $filledAmount : $amount;
				$total = $filledAmount * $price;
				$decimal = 8;				
				$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$totalapiamount = $totalapiamount + $filledAmount;
				$result['type'] = $type;
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['from_cur'] = $order->secondCurrency;
				$result['to_cur'] = $order->firstCurrency;
				$response[] = $result;
			}
		}
		return $response;
	}

	function openSellorders(){
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		
		$validate = Validator::make($data, [
			'pair' => 'required',
		], [
			'pair.required' => 'pair required',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$pair = $data['pair'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;

			$pair=explode("_",$pair);
			$from = $pair[0];
			$to = $pair[1];
			$where= array('from_symbol'=>$to,'to_symbol'=>$from);
			$pairDetails = TradePairs::select('id')->where($where)->first();
			if($pairDetails){
				$hist=self::getBuySellOrdersApi($pairDetails->id,'sell',$id);
				echo json_encode(array('status' => '1', 'openOrders' => $hist));
			}else{
				$hist ="Invalid Pairs";
				echo json_encode(array('status' => '0', 'openOrders' => $hist));
			}

		}
	}

	function getFilledOrder()
	{
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$validate = Validator::make($data, [
			'pair' => 'required',
		], [
			'pair.required' => 'pair required',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
		$pair = $data['pair'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;
			$pair=explode("_",$pair);
			$from = $pair[0];
			$to = $pair[1];
			$where= array('from_symbol'=>$to,'to_symbol'=>$from);
			$pairDetails = TradePairs::select('id')->where($where)->first();
			if($pairDetails){
				$hist=self::getFilledOrdersapi($pairDetails->id, $from, $to);

				echo json_encode(array('status' => '1', 'getfilledorders' => $hist));
			}else{
				$hist ="Invalid Pairs";
				echo json_encode(array('status' => '0', 'getfilledorders' => $hist));
			}
		}

	}
	
	public function getFilledOrdersapi($pairId, $firstCurrency, $secondCurrency) {
		$response = array();
		$result = array();
		$orders = OrderTemp::where('pair', $pairId)->where('cancel_id', NULL)->select('askPrice', 'filledAmount', 'updated_at', 'sellerUserId', 'buyerUserId','sell_fee','buy_fee','sellorderId','buyorderId')->orderBy('id', 'desc')->limit(40)->get();
		if (!$orders->isEmpty()) {
			$orders = $orders->toArray();
			$i = 0;
			$j = 0;
			$count = count($orders);
			foreach ($orders as $key => $order) {
				$k = $i + 1;
				$sellerUserId = $order['sellerUserId'];
				$sellorderId = $order['sellorderId'];
				$sellordertype = getordertype($sellorderId);
				$buyerUserId = $order['buyerUserId'];
				$buyorderId = $order['buyorderId'];
				$buyordertype = getordertype($buyorderId);
				$updated_at = $order['updated_at'];
				$sell_fee = $order['sell_fee'];
				$buy_fee = $order['buy_fee'];
				$decimal = 8;
				$filledAmount = rtrim(rtrim(sprintf('%.8F', $order['filledAmount']), '0'), ".");
				$activePrice = rtrim(rtrim(sprintf('%.8F', $order['askPrice']), '0'), ".");

				$total = $activePrice * $filledAmount;
				$total = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['datetime'] = date('H:i:s', strtotime($updated_at));
				$oldprice = 0;
				$keys = $key + 1;
				if ($count > $keys) {
					$oldprice = $orders[$keys]['askPrice'];
				}
				$result['type'] = 'increase';
				if ($oldprice > $activePrice) {
					$result['type'] = 'decrease';
				}
				$result['price'] = $activePrice;
				$result['amount'] = $filledAmount;
				$result['from_cur'] = $firstCurrency;
				$result['to_cur'] = $secondCurrency;
				$result['sell_fee'] = $sell_fee;
				$result['buy_fee'] = $buy_fee;
				$result['sellordertype'] =  $sellordertype;
				$result['buyordertype'] =  $buyordertype;				
				$response[] = $result;
			}

		}
		return $response;
	}

	function getCurrencybalance(){
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$validate = Validator::make($data, [
			'currency' => 'required',
		], [
			'currency.required' => 'currency required',
		]
	);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				$data = array('status' => '0', 'message' => $msg[0]);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}

		$name = $data['currency'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$id = $user->id;
			$currency=Currency::where(['status' => 1,'symbol'=>$name])->first();
			$curid=$currency->id;
			$balance=array();
			$mdata["currencyname"] = $currency->name;
			$mdata["Available balance"] =  Wallet::getBalance($user->id,$curid); 

			echo json_encode(array('status' => '1', 'data' => $mdata));
		}else{
			$hist ="Invalid Pairs";
			echo json_encode(array('status' => '0', 'data' => $hist));
		}

		
	}
	
	public function cancelOrder() {
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$user_id = $user->id;

			$validate = Validator::make($data, [
				'order_id' => 'required',
			], [
				'order_id.required' => 'Orderid required',
			]
		);
			
			$trade_id = $data['order_id'];
			$tradeId = insep_decode($trade_id);

			$buyorderId = $buyuserId = $sellorderId = $selluserId = 0;

			$order = CoinOrder::where('id', $tradeId)->where('user_id', $user_id)->whereIn('status', ['active', 'partially', 'stoporder'])->first();
			if ($order) {
				$userId = $order->user_id;
				$type = $order->Type;
				$activeAmount = $order->Amount;
				$orderId = $order->id;
				$total = $order->Total;
				$ordertype = $order->ordertype;
				
				$activePrice = $order->Price;
				$pair_id = $order->pair;
				
				$fcurrId = $order->firstCurrency;
				$scurrId = $order->secondCurrency;
				$pair_details = get_pair($pair_id);
				if ($pair_details) {
					$from_symbol = $pair_details->from_symbol_id;
					$to_symbol = $pair_details->to_symbol_id;
				} else {
					$data = array('status' => '0', 'message' => 'Invalid pair');
					echo json_encode($data);exit();
				}
				if (($type == "buy" || $type == "sell") && $ordertype != "market") {
					if ($type == 'buy') {
						$buyorderId = $orderId;
						$buyuserId = $userId;
						$filledAmount = TradeModel::checkOrdertemp($orderId, 'buyorderId');
						if ($filledAmount) {
							$filledAmount = $activeAmount - $filledAmount;
							
							$activeTotal = $filledAmount * $activePrice;
						} else {
							$filledAmount = $activeAmount;
							$activeTotal = $total;
						}
						$cur = $from_symbol;
						$secondbal = Wallet::getBalance($userId, $cur);
						$updateBal = $activeTotal + $secondbal;
						$remarks = $type . ' cancelled ' . $activeTotal . ' ' . $cur;
					} else {
						$sellorderId = $orderId;
						$selluserId = $userId;
						$filledAmount = TradeModel::checkOrdertemp($orderId, 'sellorderId');
						if ($filledAmount) {
							$filledAmount = $activeAmount - $filledAmount;
						} else {
							$filledAmount = $activeAmount;
						}
						$cur = $to_symbol;
						$firstbal = Wallet::getBalance($userId, $cur);
						$updateBal = $filledAmount + $firstbal;
						$remarks = $type . ' cancelled ' . $filledAmount . ' ' . $cur;
					}

					$tempData = array(
						'askAmount' => $activeAmount,
						'cancel_id' => $userId,
						'cancel_order' => $type,
						'firstCurrency' => $fcurrId,
						'secondCurrency' => $scurrId,
						'askPrice' => $activePrice,
						'filledAmount' => $filledAmount,
						'sellorderId' => $sellorderId,
						'sellerUserId' => $selluserId,
						'buyorderId' => $buyorderId,
						'buyerUserId' => $buyuserId,
						"pair" => $pair_id,
						"fee_per" => 0,
						"datetime" => date("Y-m-d H:i:s"),
					);
					$result = DB::transaction(function () use ($userId, $tempData, $updateBal, $cur, $orderId, $remarks) {
						OrderTemp::create($tempData);

						CoinOrder::where('id', $orderId)->update(['status' => 'cancelled', 'remarks' => $remarks]);
						Wallet::updateBalance($userId, $cur, $updateBal);
						return true;
					});
				} else {
					$data = array('status' => '0', 'message' => 'Invalid request');
					echo json_encode($data);exit();
				}
				if ($result != "") {
					$data = array('status' => '1','Cancel amount'=>$activeAmount,'Orderid'=>$trade_id,'message' => 'Order cancelled successfully');
					echo json_encode($data);exit();
				} else {
					$data = array('status' => '0', 'message' => 'Invalid request');
					echo json_encode($data);exit();
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request Orderid not found');
				echo json_encode($data);exit();
			}
			
		}
	}

	

	public function createOrder() {
		$data = Input::all();
		$apikey = $data['api_key'];
		$apisecret = $data['api_secret'];
		$user=User::select('id')->where(array('api_key'=>$apikey,'api_secret'=>$apisecret,'api_status'=>1))->first();
		if($user){
			$userId= $user->id;
			$validate = Validator::make($data, [
				'amount' => 'required|numeric',
				'order' => 'required',
				'type' => 'required',
				'pair' => 'required',
			], [
				'amount.required' => 'amount required',
				'amount.numeric' => 'enter valid amount',
				'order.required' => 'order type required',
				'type.required' => 'trade type required',
				'pair.required' => 'pair required',
			]
		);
			if ($validate->fails()) {
				foreach ($validate->messages()->getMessages() as $val => $msg) {
					$data = array('status' => '0', 'message' => $msg[0]);
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
			} else {
				$order = strip_tags($data['order']);
				if ($order != "market") {
					$validate = Validator::make($data, [
						'price' => 'required|numeric',
					], [
						'price.required' => 'price required',
						'price.numeric' => 'enter valid price',
					]
				);
					if ($validate->fails()) {
						foreach ($validate->messages()->getMessages() as $val => $msg) {
							$data = array('status' => '0', 'message' => $msg[0]);
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
					}
				}
				if ($order == 'stoporder') {
					$validate = Validator::make($data, [
						'stopprice' => 'required|numeric',
					], [
						'stopprice.required' => 'Enter stopprice',
						'stopprice.numeric' => 'Enter valid stopprice',
					]
				);
					if ($validate->fails()) {
						foreach ($validate->messages()->getMessages() as $val => $msg) {
							$data = array('status' => '0', 'message' => $msg[0]);
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						}
					}
				}
				$type = strip_tags($data['type']);
				$amount = strip_tags($data['amount']);
				$price = strip_tags($data['price']);
				$pairr = strip_tags($data['pair']);
				$pair=explode("_",$pairr);
				$from = $pair[0];
				$to = $pair[1];
				$where= array('from_symbol'=>$to,'to_symbol'=>$from);
				$pairDetails = TradePairs::select('id')->where($where)->first();
				if($pairDetails){
					$pair = $pairDetails->id;
					
				}
				
				$stopprice = 0;
				$getPair = TradePairs::where('id', $pair)->select('id', 'from_symbol_id', 'to_symbol_id', 'from_symbol', 'to_symbol', 'min_price', 'max_price', 'trade_fee', 'taker_trade_fee', 'min_amt', 'last_price')->first();
				if (!$getPair) {
					$data = array('status' => '0', 'message' => 'Invalid pair');
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
				$last_price = $getPair->last_price;
				$trade_percent = getPercent();
				$price_changes_pos = $last_price + ($last_price * $trade_percent / 100);
				$price_changes_neg = $last_price - ($last_price * $trade_percent / 100);
				if ($price_changes_pos < $price) {
					$data = array('status' => '0', 'message' => 'Enter price less than or equal to ' . $price_changes_pos);
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				} else if ($price_changes_neg > $price) {
					$data = array('status' => '0', 'message' => 'Enter price more than or equal to ' . $price_changes_neg);
					echo json_encode($data, JSON_FORCE_OBJECT);
					exit;
				}
				$pairId = $getPair->id;
				$firstCurr = $getPair->from_symbol;
				$secondCurr = $getPair->to_symbol;
				$firstCurr_id = $getPair->from_symbol_id;
				$secondCurr_id = $getPair->to_symbol_id;
				if ($type == 'buy') {

					$balance = Wallet::getBalance($userId, $firstCurr_id);
				} else {
					$balance = Wallet::getBalance($userId, $secondCurr_id);
				}
				$feePer = $getPair->trade_fee;
				$tfeePer = $getPair->taker_trade_fee;
				if ($amount < $getPair->min_amt) {
					$data = array('status' => '0', 'message' => 'Enter amount more than or equal to ' . $getPair->min_amt);
					echo json_encode($data, JSON_FORCE_OBJECT);exit;
				}
				if ($order != "market") {
					$minPrice = $getPair->min_price;
					$maxPrice = $getPair->max_price;
					if ($price < $minPrice) {
						$data = array('status' => '0', 'message' => 'Enter price more than or equal to ' . $minPrice);
						echo json_encode($data, JSON_FORCE_OBJECT);exit;
					}
				}
				if ($order == "stoporder") {
					$stopprice = strip_tags($data['stopprice']);
					$lastPrice = $getPair->last_price;
					if ($type == "buy") {
						if ($stopprice <= $lastPrice) {
							$data = array('status' => '0', 'message' => 'enter stopprice above ' . $lastPrice);
							echo json_encode($data, JSON_FORCE_OBJECT);exit;

						}
					} else {
						if ($stopprice >= $lastPrice) {
							$data = array('status' => '0', 'message' => 'enter stopprice below ' . $lastPrice);
							echo json_encode($data, JSON_FORCE_OBJECT);exit;
						}
					}
				}
				$total = $amount * $price;
				if ($type == "buy") {
					if ($total > $balance) {
						$data = array('status' => '0', 'message' => 'insufficient balance');
						echo json_encode($data, JSON_FORCE_OBJECT);exit;
					}
					$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice, '2');
				} elseif ($type == "sell") {
					if ($amount > $balance) {
						$data = array('status' => '0', 'message' => 'insufficient balance');
						echo json_encode($data, JSON_FORCE_OBJECT);exit;
					}
					$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice, '2');
				} else {
					$data = array('status' => '0', 'message' => 'invalid request');
					echo json_encode($data, JSON_FORCE_OBJECT);exit;
				}
			}
		}
	}

}
