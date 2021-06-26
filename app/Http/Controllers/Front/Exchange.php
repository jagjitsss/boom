<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Audit;
use App\Model\CoinOrder;
use App\Model\Currency;
use App\Model\OrderTemp;
use App\Model\TradeModel;
use App\Model\TradePairs;
use App\Model\User;
use App\Model\Banner;
use App\Model\ExchangePairs;
use App\Model\ExchangeModel;
use App\Model\Wallet;
use App\Model\News;
use App\Model\CoinProfit;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;

use URL;
use Validator;

class Exchange extends Controller {
    
	public function index() 
	{
	$wcwr =  session::get('tmaitb_user_id');

	if($wcwr)
	{
		$type=1;
	}
	else
	{
		$type=0;
	}

	$wallet = Wallet::where('user_id',$wcwr)->first();
	$viewsource = 'front.exchange.exchange';
	$coinPairs = array();		

	$buyexchangepairs = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->map(function ($curr) {return ['key' => $curr->from_symbol.$curr->to_symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();

		$sellexchangepairs = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->map(function ($curr) {return ['key' => $curr->to_symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();	

		$exchangepair = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->toArray();
		$results = self::arraygroupBy($exchangepair,'to_symbol');
	$currency = Currency::where('status', '1')->select('image', 'symbol', 'name', 'inr_value')->orderBy('symbol', 'asc')->get()->toArray();
	$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
	$from_symbol = $tradepairs->from_symbol;
	$to_symbol = $tradepairs->to_symbol;
	$pairid = $tradepairs->id;
	$newshome = News::where('status', 'active')->orderBy('id', 'desc')->limit(2)->get();
	$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
	$banner = Banner::select('image_url','url')->where('status','1')->where('page','Home')->get();

	$home = 1;
	$page = 0;
		return view('front.common.index', compact('viewsource',  'home', 'features', 'currency','from_symbol','to_symbol','pairid','banner','newsdetails','newshome','page','buyexchangepairs','sellexchangepairs','wcwr','wallet','results','type'));
	}	


	public function makeexchange()
	{
		
		
		$sta = 1;

		if($sta) {
			$id = session::get('tmaitb_user_id');
			if($id) {
				$data = Input::all();

				$extype = $data['extype'];

				$trigger['bitcoiva_id'] =	$id;
				$trigger['type'] =	$extype;
				

				
				$validate = Validator::make($data, [
					'from_currency' => "required",
					'to_currency' => "required",
					'amount' => "required|numeric",
				], [
					'from_currency.required' => 'Choose From Currency',
					'to_currency.required' => 'Choose To Currency',
					'amount.required' => 'Enter amount',
					'amount.numeric' => 'Enter valid amount',
				]);

				if ($validate->fails()) {
					
					foreach ($validate->messages()->getMessages() as $val => $msg) {
					
						$response = array('status' => '0', 'result' =>  $msg[0]);
						echo json_encode($response);exit;
					}
				}



				if($extype == 'buy')
				{
					$bal_symbol = $from_symbol = $data['from_currency'];
					$to_symbol = $data['to_currency'];
					$amount = $data['amount'];
				

				} else {
					$bal_symbol = $from_symbol = $data['from_currency'];
					$to_symbol = $data['to_currency'];
					$amount = $data['from_currency_amt'];
					
				}


				if($amount < 0) {

					$response = array('status' => '0', 'result' => 'Enter valid amount');
					echo json_encode($response);exit;
				}

				$digit = ($from_symbol == 'USD') ? 2 : 8;
				$digit1 = ($to_symbol == 'USD' ) ? 2 : 8;
				
				
				
				if($extype == 'buy') {
					$exchangepair = ExchangePairs::where('from_symbol',$from_symbol)->where('to_symbol',$to_symbol)->select('last_price', 'trade_fee', 'from_symbol_id', 'to_symbol_id', 'min_amt', 'max_amt')->first();
				} else {
					$exchangepair = ExchangePairs::where('from_symbol',$to_symbol)->where('to_symbol',$from_symbol)->select('last_price', 'trade_fee', 'from_symbol_id', 'to_symbol_id', 'min_amt', 'max_amt')->first();
				}




				if($extype == 'buy') {
					$last_price = $exchangepair->last_price;
					$last_price = number_format($last_price, $digit1, '.', '');
				} else {
					$price = $exchangepair->last_price;
					if($price > 0) {
						$last_price = 1 / $price;
					} else {
						$last_price = 0;
					}
					$last_price =  number_format($last_price, $digit1, '.', '');
				}
				
				
				if($last_price == 0) {
					$response = array('status' => '0', 'result' => 'Please try again later');
					echo json_encode($response);exit;
				}

				
				$trade_fee = $exchangepair->trade_fee;

				$from_symbol_id = $exchangepair->from_symbol_id;

				$min_amt = $exchangepair->min_amt;
				$max_amt = $exchangepair->max_amt;

				$total = $amount * $last_price;
				
				
				$fees = $total * $trade_fee / 100;
				

				$final = $total - $fees;
				
				$curid = Currency::where('symbol', $bal_symbol)->first()->id;
				$balance = Wallet::getBalance($id, $curid);
				
				if($amount > $balance)
				{
					$response = array('status' => '0', 'result' => 'You have insufficient balance');
					echo json_encode($response);exit;
				}

				if($amount < $min_amt)
				{
					$response = array('status' => '0', 'result' => 'Enter amount more than minimum value');
					echo json_encode($response);exit;
				}

				if($amount > $max_amt)
				{
					$response = array('status' => '0', 'result' => 'Enter amount less than maximum value');
					echo json_encode($response);exit;
				}
				$updateBal = $balance - $amount;				
				$update = Wallet::updateBalance($id, $curid, $updateBal);
				
				if($update)
				{
					$newdate = date('Y-m-d H:i:s');

					$create = array(
						'user_id' => $id,
						'from_symbol' => $from_symbol,
						'to_symbol' => $to_symbol,
						'type' => $extype,
						'amount' => $amount,
						'fees' => $fees,
						'fee_per' => $trade_fee,
						'total' => $final,
						'status' => 'pending',
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'created_at' => date('Y-m-d H:i:s'),
						'expired_at' => date('Y-m-d H:i:s'),
					);

					$result = ExchangeModel::create($create);
					$lastid = insep_encode($result->id);

					/********* Exchange Automated ************/
					$userexchange = ExchangeModel::where(['id' => $result->id])->first();
					if ($userexchange)
					{
						if ($userexchange->status == "pending")
						{
							$currency = $userexchange->to_symbol;

							$total = $userexchange->total;
							$amount = $userexchange->amount;
							$fee_amt = $userexchange->fees;
							$user_id = $userexchange->user_id;

							$curid = Currency::where('symbol', $currency)->first()->id;

							$balance = Wallet::getBalance($user_id, $curid);

							$updateBal = $balance + $total;
							$update = Wallet::updateBalance($user_id, $curid, $updateBal);
							$updateexchange = ExchangeModel::where('id', $result->id)->update(['status' => 'Completed']);
							if ($updateexchange)
							{
									$profitData = array(
										'user_id' => $userexchange->user_id,
										'theftAmount' => $userexchange->fees,
										'theftCurrency' => $userexchange->to_symbol,
										'Type' => 'exchange Profit',
									);
									$query = CoinProfit::create($profitData);

									//$getSiteDetails = Controller::getSitedetails();
									//$admin = $getSiteDetails->admin_redirect;
									//$link1 = env('DOMAIN_URL').$admin."/viewUserexchange/".$lastid;
									$link1 = url('funds');
									if($extype == 'buy')
									{
										$info = array('###AMOUNT###' => number_format($amount,2)." ". $from_symbol, '###FEES###' => number_format($fees, 8)." ". $to_symbol, '###TOTAL###' => number_format($final, 8)." ". $to_symbol, '###LINK1###' => $link1,  '###NAME###' => getUserName($id), '###TYPE###' => $extype);
									}
									else
									{
										$info = array('###AMOUNT###' => number_format($amount,8)." ". $from_symbol, '###FEES###' => number_format($fees, 2)." ". $to_symbol, '###TOTAL###' => number_format($final, 2)." ". $to_symbol, '###LINK1###' => $link1,  '###NAME###' => getUserName($id), '###TYPE###' => $extype);
									}

									/*$toemail1 = $getSiteDetails->site_email;
									$toemail = insep_decode($toemail1);*/
									$toemail = getUserEmail($userexchange->user_id);
									$sendEmail = Controller::sendEmail($toemail, $info, '34');
									$bcc = '1';
									if($sendEmail)
									{
										Session::flash('success', 'Your transaction completed successfully.');
									$response = array('status' => '1', 'result' => 'Your transaction completed successfully');
									}
									else
									{
										$response = array('status' => '0', 'result' => 'Your transaction completed successfully.');
									}
									echo json_encode($response);exit;
							}
						}
						else
						{
							Session::flash('error', 'Unable to process this transaction');
							$response = array('status' => '1', 'result' => 'Unable to process this transaction');
							echo json_encode($response);exit;
						}
					}
					else
					{
						Session::flash('error', 'Unable to process this transaction');
						$response = array('status' => '1', 'result' => 'Unable to process this transaction');
						echo json_encode($response);exit;
					}

					/********* Exchange Automated ************/
				}
				else
				{
					$response = array('status' => '0', 'result' => 'Pleasess try again later');
					echo json_encode($response);exit;
				}
			}
			else
			{
				$response = array('status' => '0', 'result' => 'Please login to continue');
				echo json_encode($response);exit;
			}
		}
	}

	public function arraygroupBy($array, $key) {
		$return = array();
		foreach($array as $val) {
			$return[$val[$key]][$val['from_symbol']] = $val;
		}
		return $return;
	}


	
	public function exchange_history() {
		$id = session::get('tmaitb_user_id');
		if($id) {
			$totalrecords = intval(Input::get('totalrecords'));
			$draw = Input::get('draw');
			$start = Input::get('start');
			$length = Input::get('length');
			$sorttype = Input::get('order');
			$sort_col = $sorttype['0']['column'];
			$sort_type = $sorttype['0']['dir'];
			$search = Input::get('search');
			$from_date = Input::get('from');
			$to_date = Input::get('to');
			$search = $search['value'];

			if ($sort_col == '1') {
				$sort_col = 'created_at';
			} else if ($sort_col == '2') {
				$sort_col = 'type';
			} else if ($sort_col == '3') {
				$sort_col = 'from_symbol';
			} else if ($sort_col == '2') {
				$sort_col = 'to_symbol';
			} else if ($sort_col == '3') {
				$sort_col = 'amount';
			} else if ($sort_col == '4') {
				$sort_col = 'fees';
			} else if ($sort_col == '5') {
				$sort_col = 'total';
			} else if ($sort_col == '6') {
				$sort_col = 'status';
			} else {
				$sort_col = "id";
			}
			if ($sort_type == 'asc') {
				$sort_type = 'desc';
			} else {
				$sort_type = 'asc';
			}

			$data = $orders = array();
			$exchange = ExchangeModel::where('user_id', $id);
			if ($search != '') {
				$exchange = $exchange->where(function ($q) use ($search) {
					$q->where('from_symbol', 'like', '%' . $search . '%')->orWhere('to_symbol', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%')->orWhere('fees', 'like', '%' . $search . '%')->orWhere('total', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%')->orWhere('type', 'like', '%' . $search . '%')->orWhere('created_at', 'like', '%' . $search . '%');}
				);
			}

			if ($from_date) {
				$exchange = $exchange->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($from_date)));
			}

			if ($to_date) {
				$exchange = $exchange->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($to_date)));
			}

			$exchange_count = $exchange->count();
			if ($exchange_count) {

				$exchange = $exchange->select('from_symbol', 'to_symbol', 'amount', 'fees', 'total', 'status', 'created_at','id','type', 'expired_at', 'user_id');

				$orders = $exchange->skip($start)->take($length)->orderBy($sort_col, $sort_type)->get()->toArray();
			}
			$data = array();
			$no = $start + 1;

			if ($exchange_count) 
			{
				foreach ($orders as $r) {
					if($r['status'] == 'Completed') {
						$completed = URL::to('public/images/tick.png');
						$status = '<img src="'.$completed.'" alt="Completed" title="Completed"> Completed';
					} else if($r['status'] == 'pending') {
						$pending = URL::to('public/images/pending.png');
						$status = '<img src="'.$pending.'" alt="Pending" title="Pending"> Pending';
					} else {
						$cancel = URL::to('public/images/cancel.png');
						$status = '<img src="'.$cancel.'" alt="Cancelled" title="Cancelled"> Cancelled';
					}

					$digits = ($r['from_symbol'] == 'USD') ? 2 : 8;
					$digits1 = ($r['to_symbol'] == 'USD') ? 2 : 8;

					$amount =number_format($r['amount'], $digits, '.', '');
					$fees =number_format($r['fees'], $digits1, '.', '');
					$total =number_format($r['total'], $digits1, '.', '');

			
					array_push($data, array(
						$no,
						$r['created_at'],
						ucfirst($r['type']),
						$r['from_symbol'],
						$r['to_symbol'],
						$amount." ".$r['from_symbol'],
						$fees." ".$r['to_symbol'],
						$total." ".$r['to_symbol'],
						$status,
						
					));
					$no++;
				}
				echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $exchange_count, 'recordsFiltered' => $exchange_count, 'data' => $data));
			} 
			else 
			{

				echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $exchange_count, 'recordsFiltered' => $exchange_count, 'data' => array()));
			}
		}
	}


public function paynetservice()
	{

		$id = session::get('tmaitb_user_id');			
		if($id)
		{
			$data = Input::all();
			$validate = Validator::make($data, [
				'pay_net_service' => "required",
				'pay_net_amt' => "required|numeric",
				'pay_net_currency_hidden' => "required",
			], [
				'pay_net_service.required' => 'Choose Payment Serivce',
				'pay_net_amt.required' => 'Enter Amount'
			]);

			if ($validate->fails())
			{
				
				foreach ($validate->messages()->getMessages() as $val => $msg)
				{
				
					$response = array('status' => '0', 'result' =>  $msg[0]);
					echo json_encode($response);exit;
				}
			}

						

			if(isset($data['pay_net_amt']) 
				&& $data['pay_net_amt'] > 0 
				&& isset($data['pay_net_service']) 
				&& $data['pay_net_service'] == 'paypal'
			)
			{



				$transaction_id = rand(9999999, 99999999999999);
				$transaction_id = insep_encode($transaction_id);

				$post_data = array(
					'amount' => $data['pay_net_amt'],
					'business' => 'boompay01_api1@gmail.com',
					'item_name' => "Deposit",
					'item_number' => $transaction_id,
					'no_shipping' => 0,
					'currency_code' => $data['pay_net_currency_hidden'],
					'notify_url' => url('paypal/notify'),
					'cancel_return' => url('paypal/cancel'),
					'return' => url('paypal/return'),
					'cmd' => '_xclick',
					'paynow' => true,
					'paypal_url' => "https://www.sandbox.PayPal.com/cgi-bin/webscr"
				);

				

				self::curlConnectToPayPal($post_data);
				exit;

			}
			echo json_encode(array('status' => true));
			exit;
		}

	}

	public function curlConnectToPayPal($post_data){


		$url = url('/').'/public/XNUVPAYNEXTS/index.php';   
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
		curl_setopt($ch, CURLOPT_URL, $url);   
		$res = curl_exec($ch);   
		
		$post = [
		    'username' => 'user1',
		    'password' => 'passuser1',
		    'gender'   => 1,
		];

		$ch = curl_init(url('/').'/public/XNUVPAYNEXTS');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		
		$response = curl_exec($ch);

		
		curl_close($ch);

		exit;


		foreach($post_data as $key => $value)
		{
		    $post_items[] = $key . '=' . $value;
		}
		$post_string = implode ('&', $post_items);
		$curl_connection = curl_init(url('/').'/public/XNUVPAYNEXTS/index.php');
		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl_connection, CURLOPT_USERAGENT, 
		  "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
		curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

		$result = curl_exec($curl_connection);
		curl_error($curl_connection);
		
		curl_close($curl_connection);

		echo json_encode($result);
		exit;
	}
}
