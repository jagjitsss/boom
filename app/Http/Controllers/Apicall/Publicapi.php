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

class Publicapi extends Controller {
	public function __construct() {

	}
	
	public function getPairDetails() {
		$data = Input::all();
		$userId = 0;
		$balance_array = $balance = $all_result = array();
		$pairs = TradePairs::select('id', 'min_price', 'max_price', 'trade_fee', 'taker_trade_fee', 'last_price', 'min_amt', 'from_symbol_id', 'to_symbol_id', 'from_symbol', 'to_symbol','convertedeur')->where('status','1')->get();
		if ($pairs) {
			foreach ($pairs as $pairDetails) {
				$result = array();
				$pairId = $pairDetails->id;
				
					$favour ="";
				
					$firstCurr = $pairDetails->from_symbol;
					$secondCurr = $pairDetails->to_symbol;
			
				
				$result['first_currency'] =$secondCurr;
				$result['second_currency'] =$firstCurr; 
				$result['last_market_price'] = $pairDetails->last_price;
				$trade_data = getTradeData($pairId, $firstCurr, $secondCurr);
				
				$result['lowestaskprice'] = $trade_data['low'];
				$result['highestbidprice'] = $trade_data['high'];
				$result['volume'] = $trade_data['volume'];
				
				
				if ($userId != 0) {
					$getBalance1 = Wallet::getBalance($userId, $pairDetails->from_symbol_id);
					$getBalance2 = Wallet::getBalance($userId, $pairDetails->to_symbol_id);
					$result['first_currency'] = $getBalance2;
					$result['second_currency'] = $getBalance1;
					$balance[$firstCurr] = $getBalance1;
					$balance[$secondCurr] = $getBalance2;
				}

				$all_result[] = $result;
			}

			$url = URL::to('/') . "/public/images/admin_currency/";

			foreach ($balance as $key => $value) {
				$img = getCurrencyImage($key);
				$image = $url.$img;
				$name = getCurrencyname($key);
				$balance_array[] = array('symbol' => $key,'name'=>$name, 'balance' => $value, 'currency_image' => $image);
			}
			$mdata["status"] = 1;
			$mdata["data"] = $all_result;
			
			echo json_encode($mdata);exit;
		} else {
			$response = array('status' => '0', 'data' => 'no pairs');
			echo json_encode($response);exit;
		}
	}
	
	public function createOrder() {
		$data = Input::all();
		$userId = $data['user_id'];
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
			$pair = strip_tags($data['pair']);
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
				$data = array('status' => 'error', 'message' => 'Enter price less than or equal to ' . $price_changes_pos);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			} else if ($price_changes_neg > $price) {
				$data = array('status' => 'error', 'message' => 'Enter price more than or equal to ' . $price_changes_neg);
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
				$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice, '1');
			} elseif ($type == "sell") {
				if ($amount > $balance) {
					$data = array('status' => '0', 'message' => 'insufficient balance');
					echo json_encode($data, JSON_FORCE_OBJECT);exit;
				}
				$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice, '1');
			} else {
				$data = array('status' => '0', 'message' => 'invalid request');
				echo json_encode($data, JSON_FORCE_OBJECT);exit;
			}
		}
	}
	
	public function getTradeHistory($pair) {
		$data = Input::all();
		$userId = 0;
		
		$result = array();
		if (isset($pair)) {

			$pair = $pair;
			$pair=explode("_",$pair);
			$from = $pair[0];
			$to = $pair[1];
			$where= array('from_symbol'=>$to,'to_symbol'=>$from);
			$pairDetails = TradePairs::where($where)->first();

		
			if ($pairDetails) {
				$result['pair'] = $pairDetails->id;
				$firstCurr = $pairDetails->from_symbol;
				$secondCurr = $pairDetails->to_symbol;
				$result['pair'] = $secondCurr . '_' . $firstCurr;

				
				$result['asks'] = self::getBuySellOrdersApi($pairDetails->id, 'buy');
				$result['bids'] = self::getBuySellOrdersApi($pairDetails->id, 'sell');
				
				
				$response = array('status' => '1', 'result' => $result);
			} else {
				$response = array('status' => '0', 'message' => 'invalid pairs');
			}
			echo json_encode($response);
			exit;
		} else {
			$data = array('status' => '0', 'message' => 'pair required');
			echo json_encode($data);
		}
	}
    
	public function tradehistory() {
		$data = Input::all();
		$userId = 0;
		if (isset($data['user_id']) && isset($data['token'])) {
			$token = $data['token'];
			$result = User::where(['id' => $data['user_id'], 'token' => $token])->count();
			if ($result) {
				$userId = $data['user_id'];
			}
		}
		$result = array();
		if (isset($data['pair'])) {
			$pairId = trim(strip_tags($data['pair']));
			$pairDetails = TradePairs::where('id', $pairId)->select('from_symbol', 'to_symbol')->first();
			if ($pairDetails) {
				$result['pair'] = $pairId;
				$result['from_cur'] = $firstCurr = $pairDetails->from_symbol;
				$result['to_cur'] = $secondCurr = $pairDetails->to_symbol;
				$result['pair'] = $secondCurr . '_' . $firstCurr;

				$result['transaction_history'] = $result['my_orders'] = array();
				$result['transaction_history'] = self::getFilledOrders($pairId, $firstCurr, $secondCurr);
				if ($userId != 0) {
					$result['my_orders'] = self::getapiMyTradeHistory($pairId, $userId);
				}
				$response = array('status' => '1', 'result' => $result);
			} else {
				$response = array('status' => '0', 'message' => 'invalid pair id');
			}
			echo json_encode($response);
			exit;
		} else {
			$data = array('status' => '0', 'message' => 'pair required');
			echo json_encode($data);
		}
	}
    
	public function myorders() {
		$data = Input::all();
		$userId = 0;
		if (isset($data['user_id']) && isset($data['token'])) {
			$token = $data['token'];
			$result = User::where(['id' => $data['user_id'], 'token' => $token])->count();
			if ($result) {
				$userId = $data['user_id'];
			}
		}
		$result = array();
		if (isset($data['pair'])) {
			$pairId = trim(strip_tags($data['pair']));
			$pairDetails = TradePairs::where('id', $pairId)->select('from_symbol', 'to_symbol')->first();
			if ($pairDetails) {
				$result['pair'] = $pairId;
				$result['from_cur'] = $firstCurr = $pairDetails->from_symbol;
				$result['to_cur'] = $secondCurr = $pairDetails->to_symbol;
				$result['pair'] = $secondCurr . '_' . $firstCurr;

				$result['open_orders'] = $result['stop_orders'] = array();
				if ($userId != 0) {
					$result['open_orders'] = self::getActiveOrders($pairId, $userId);
					$result['stop_orders'] = self::getStopOrders($pairId, $userId);
				}
				$response = array('status' => '1', 'result' => $result);
			} else {
				$response = array('status' => '0', 'message' => 'invalid pair id');
			}
			echo json_encode($response);
			exit;
		} else {
			$data = array('status' => '0', 'message' => 'pair required');
			echo json_encode($data);
		}
	}
	
	public static function getStopOrders($pairId, $userId) {
		$orders = CoinOrder::where('pair', $pairId)->where('user_id', $userId)->where('status', 'stoporder')->select('id', 'Price', 'Amount', 'firstCurrency', 'secondCurrency', 'Type', 'stopprice', 'ordertype', 'updated_at','maker_fee_per','taker_fee_per','Fee')->orderBy('id', 'desc')->get();
		$response = $result = array();
		if (!$orders->isEmpty()) {
			foreach ($orders as $order) {
				$type = $order->Type;
				$ordertype = $order->ordertype;
				$orderId = $order->id;
				$price = $order->Price;
				$stopprice = $order->stopprice;
				$amount = $order->Amount;
				$status = $order->status;
				$maker_fee_per =  $order->maker_fee_per;
				$taker_fee_per =  $order->taker_fee_per;
				$Fee =  $order->Fee;
				$filledAmount = $amount;
				$total = $filledAmount * $price;
				$decimal = 8;

				$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['datetime'] = date('Y-m-d H:i', strtotime($order->updated_at));
				$result['from_cur'] = $order->firstCurrency;
				$result['to_cur'] = $order->secondCurrency;
				$result['ordertype'] = $ordertype;
				$result['maker_fee_per'] = $maker_fee_per;
				$result['taker_fee_per'] = $taker_fee_per;
				$result['Fee'] = $Fee;
				$result['type'] = $type;
				$result['stopprice'] = $stopprice;
				$result['trade_id'] = insep_encode($orderId);
				$response[] = $result;
			}
		}
		return $response;
	}
	
	public static function getActiveOrders($pairId, $userId) {
		$openOrders = CoinOrder::where('pair', $pairId)->where('user_id', $userId)->whereIn('status', ['active', 'partially'])->select('id', 'Price', 'Amount', 'firstCurrency', 'secondCurrency', 'Type', 'ordertype', 'updated_at','maker_fee_per','taker_fee_per','Fee')->orderBy('id', 'desc')->get();
		$response = $result = array();
		if (!$openOrders->isEmpty()) {
			foreach ($openOrders as $order) {
				$type = $order->Type;
				$ordertype = $order->ordertype;
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->Amount;
				$status = $order->status;
				$maker_fee_per =  $order->maker_fee_per;
				$taker_fee_per =  $order->taker_fee_per;
				$Fee =  $order->Fee;
				$tempId = ($type == "buy") ? "buyorderId" : "sellorderId";
				$filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
				$filledAmount = ($filledAmount) ? $amount - $filledAmount : $amount;
				$total = $filledAmount * $price;
				$decimal = 8;
				$result['from_cur'] = $order->firstCurrency;
				$result['to_cur'] = $order->secondCurrency;

				$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['datetime'] = date('Y-m-d H:i', strtotime($order->updated_at));
				$result['type'] = $type;
				$result['ordertype'] = $ordertype;
				$result['maker_fee_per'] = $maker_fee_per;
				$result['taker_fee_per'] = $taker_fee_per;
				$result['Fee'] = $Fee;
				$result['trade_id'] = insep_encode($orderId);
				$response[] = $result;
			}
		}
		return $response;
	}
	
	public static function getapiMyTradeHistory($pairId, $userId) {
		$response = $result_array = array();
		$orders = DB::table('redor_nioc as CO')->where(['CO.user_id' => $userId, 'CO.pair' => $pairId])->whereIn('CO.status', ['partially', 'filled', 'cancelled'])->leftjoin('pmetredor AS A', 'A.buyorderId', '=', 'CO.id')->leftjoin('pmetredor AS B', 'B.sellorderId', '=', 'CO.id')->select('CO.Type as Type', 'CO.id as id', 'CO.Price as Price', 'CO.Amount as Amount', 'CO.status as status', 'CO.ordertype as ordertype', 'CO.Fee as Fee', 'CO.secondCurrency as secondCurrency', 'CO.firstCurrency as firstCurrency', 'CO.updated_at as updated_at', 'A.buyorderId as buyorderId', 'B.sellorderId as sellorderId', 'A.cancel_id as buyCancel', 'B.cancel_id as sellCancel', 'A.filledAmount as buyFilled', 'B.filledAmount as sellFilled', 'A.askPrice as buyPrice', 'B.askPrice as sellPrice', 'A.updated_at as buyUpdate', 'B.updated_at as sellUpdate')->orderBy('CO.updated_at', 'desc')->limit(20)->get()->toArray();

		if (!empty($orders)) {
			foreach ($orders as $order) {
				$type = $order->Type;
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->Amount;
				$status = $order->status;
				$ordertype = $order->ordertype;
				$feeAmount = $order->Fee;
				$updatedAt = $order->updated_at;
				$decimal = 8;
				if ($type == "buy") {
					$cancelId = $order->buyCancel;
					$filledAmount = $order->buyFilled;
					$askPrice = $order->buyPrice;
					$updatedAt = $order->buyUpdate;
				} else {
					$cancelId = $order->sellCancel;
					$filledAmount = $order->sellFilled;
					$askPrice = $order->sellPrice;
					$updatedAt = $order->sellUpdate;
				}
				$response['total'] = $filledAmount * $askPrice;
				$response['type'] = $type;
				$response['feeAmount'] = $feeAmount;
				$response['ordertype'] = $ordertype;
				$response['status'] = ($cancelId != "") ? 'cancelled' : 'filled';
				$response['from_cur'] = $order->firstCurrency;
				$response['to_cur'] = $order->secondCurrency;

				$response['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$response['price'] = rtrim(rtrim(sprintf('%.8F', $askPrice), '0'), ".");
				$response['total'] = rtrim(rtrim(sprintf('%.8F', $response['total']), '0'), ".");
				$response['datetime'] = date('Y-m-d H:i', strtotime($updatedAt));

				$result_array[] = $response;
			}

		}
		return $result_array;
	}
	
	public static function getBuySellOrdersApi($pair, $type) {
		if ($type == 'sell') {
			$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select(DB::raw('SUM(Amount) as amount'), 'id', 'Price', 'secondCurrency', 'firstCurrency', 'status')->orderBy('Price', 'asc')->groupBy('Price')->get();

		} else {
			$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select(DB::raw('SUM(Amount) as amount'), 'id', 'Price', 'secondCurrency', 'firstCurrency', 'status')->orderBy('Price', 'desc')->groupBy('Price')->get();

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
				$result['addamount'] = $totalapiamount;
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				
				$result['from_cur'] = $order->secondCurrency;

				$resultn= '['.$result['amount'].','.$result['price'].']';
				$response[] =$resultn;
			}
		}
		return $response;
	}
	
	public function getFilledOrders($pairId, $firstCurrency, $secondCurrency) {
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
				$result['datetime'] = $updated_at;
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
				$result['from_cur'] =$secondCurrency ;
				$result['to_cur'] = $firstCurrency;
				$result['sell_fee'] = $sell_fee;
				$result['buy_fee'] = $buy_fee;
				$result['sellordertype'] =  $sellordertype;
				$result['buyordertype'] =  $buyordertype;				
				$response[] = $result;
			}

		}
		return $response;
	}
	
	public function cancelTradeOrder() {
		$data = Input::all();
		$user_id = $data['user_id'];
		if (isset($data['trade_id'])) {

			$trade_id = $data['trade_id'];
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
					$data = array('status' => '1', 'message' => 'order cancelled');
					echo json_encode($data);exit();
				} else {
					$data = array('status' => '0', 'message' => 'Invalid request');
					echo json_encode($data);exit();
				}
			} else {
				$data = array('status' => '0', 'message' => 'Invalid request');
				echo json_encode($data);exit();
			}
		} else {
			$data = array('status' => '0', 'message' => 'trade id required');
			echo json_encode($data);
		}
	}
	
	public function tradechartview($pair)
	{
		$pair = $pair;
	    return view('front.trade.chart_app', compact('pair'));	   
	}
	
	public function tradechart_view()
	{	
		$from_cur = 'BTC';
		$to_cur =  'TYC';
		$pair = $from_cur."_".$to_cur;
		return view('front.trade.chart_app', compact('user_id', 'pair'));	   
	}
	
	public function getcurrencydetails(){
		
		$currecny_details = Currency::where(['status' => 1])->select('name', 'symbol','min_withdraw','max_withdraw','min_deposit','max_deposit')->get();
		if ($currecny_details) 
		{
			foreach ($currecny_details as $details) {
				$result = array();
				$result['currenycname'] = $details->name;
				$result['Symbol'] = $details->symbol;
				$result['min_withdrawlimit'] = $details->min_withdraw;
				$result['max_withdrawlimit'] = $details->max_withdraw;
				$response[] = $result;
			}
			$mdata["status"] = 1;
			$mdata["data"] = $response;
			echo json_encode($mdata); exit;
		}  
		else 
		{
			$response = array('type' => 'failed','message' => 'Invalid pairs');
			echo json_encode($response); 
		}
		
	}
	
	public function getMarketHistory($pair) {
		$data = Input::all();
		$result = array();
		if (isset($pair)) {
			$pair = $pair;
			$pair=explode("_",$pair);
			$from = $pair[0];
			$to = $pair[1];
			$where= array('from_symbol'=>$to,'to_symbol'=>$from);
			$pairDetails = TradePairs::where($where)->first();

			if ($pairDetails) {
				
				$firstCurr = $pairDetails->from_symbol;
				$secondCurr = $pairDetails->to_symbol;
				$result['pair'] = $secondCurr . '_' . $firstCurr;
				$result['transaction_history'] = self::getFilledOrders($pairDetails->id,$firstCurr, $secondCurr);
				
				
				$response = array('status' => '1', 'result' => $result);
			} else {
				$response = array('status' => '0', 'message' => 'invalid pairs');
			}
			echo json_encode($response);
			exit;
		} else {
			$data = array('status' => '0', 'message' => 'pair required');
			echo json_encode($data);
		}
	}

}
