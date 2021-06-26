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
use App\Model\Wallet;
use App\Model\News;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;

use URL;
use Validator;

class Trade extends Controller {
    
	public function index() 
	{
		$pairs = TradePairs::select('from_symbol', 'to_symbol')->where('status', 1)->first();
		$pairs = $pairs->to_symbol . '_' . $pairs->from_symbol;
		return redirect('/trade/' . $pairs);

	}
	
	public function trade($pair_symbol) 
	{
		$id = session::get('tmaitb_user_id');
		if ($id) {
			$user = User::where('id', $id)->select('profile')->first();
		} else {
			$user = '';
		}

		$viewsource = 'front.trade.trade';
		$pairs = TradePairs::getFullPairs();
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$page = 2;
		return view('front.trade.index', compact('viewsource', 'user', 'pairs', 'pair_symbol', 'page','newsdetails'));

	}
	
	public function getPairData($pair) 
	{
		$splitPair = explode('_', $pair);
		$firstCurr = strtoupper(strip_tags(trim($splitPair[1])));
		$secondCurr = strtoupper(strip_tags(trim($splitPair[0])));
		$pairDetails = TradePairs::where('from_symbol', $firstCurr)->where('to_symbol', $secondCurr)->select('id', 'min_price', 'max_price', 'trade_fee', 'taker_trade_fee', 'last_price', 'min_amt', 'from_symbol_id', 'to_symbol_id')->first();
		if ($pairDetails) 
		{
			$pairId = $pairDetails->id;
			$result['pair_id'] = $pairId;
			$result['from_cur'] = $firstCurr;
			$result['to_cur'] = $secondCurr;
			$result['from_bal'] = 0;
			$result['to_bal'] = 0;
			$result['pair'] = $secondCurr . '/' . $firstCurr;
			$No_trade_his = trans('app_lang.no_trade_history');
			$No_open_ord = trans('app_lang.no_open_order_available');
			$result['my_orders'] = '0';
			$result['min_amt'] = $pairDetails->min_amt;
			$result['min_price'] = $pairDetails->min_price;
			$result['max_price'] = $pairDetails->max_price;
			$result['trade_fee'] = $pairDetails->trade_fee;
			$result['taker_trade_fee'] = $pairDetails->taker_trade_fee;
			$result['last_price'] = $pairDetails->last_price;
			$inr_value = Currency::where('symbol', $firstCurr)->select('inr_value')->first()->inr_value;
			$result['usd_val'] = $inr_value;
			$result['buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy');
			$result['limit_buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy',16);
			$result['sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell');
			$result['limit_sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell',15);
			$result['market_orders'] = self::getallFilledOrders($pairId);
			$result['open_orders'] = '0';
			$result['stop_orders'] = '0';
			$userId = session('tmaitb_user_id');
			if ($userId != "") {
				$getBalance1 = Wallet::getBalance($userId, $pairDetails->from_symbol_id);
				$getBalance2 = Wallet::getBalance($userId, $pairDetails->to_symbol_id);
				$result['from_bal'] = $getBalance1;
				$result['to_bal'] = $getBalance2;
				$result['my_orders'] = self::getMyTradeHistory($pairId, $userId);

				$result['open_orders'] = self::getActiveOrders($pairId, $userId);
				$result['stop_orders'] = self::getStopOrders($pairId, $userId);

			}
			$result['trade_data'] = getTradeData($pairId, $firstCurr, $secondCurr);
			$result['price_range'] = priceRange($pairId);			

			$response = array('status' => 'success', 'result' => $result);
		} 
		else 
		{
			$response = array('status' => 'fail');
		}
		echo json_encode($response);
	}
	
	function showUserBalance() 
	{
		$id = session::get('tmaitb_user_id');
		$all_cur = self::get_balance();
		$user = DB::table('sresu')
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('profile', 'verified_status', 'randcode', 'id_status', 'selfie_status')->first();
		return view('front.trade.wallet', compact('all_cur','user'));
    }
    
	function showadvancUserBalance() 
	{
		$id = session::get('tmaitb_user_id');
		$user = User::where('id', $id)->select('profile', 'randcode')->first();
		$response = '';
		$all_cur = $userbalance = $curr = array();

		$allcurr = Currency::where('status', 1)->select('image', 'symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance')->get()->map(function ($curr) {return ['key' => $curr->symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();

		$userbalance = Wallet::getBalance($id);
		foreach ($allcurr as $curr) 
		{
			$symbol = $curr['symbol'];
			$src = URL::to('/')."/public/images/admin_currency/".$curr['image'];
			$inorders = inorders($symbol, $id);
			$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_crypto_withdraw'] + $inorders['inorder_fiat_withdraw'];
			$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");
			if (isset($userbalance[$curr['id']])) {
				$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");
			} else {
				$balance = 0;
			}
			$total = $inorders + $balance;
			if ($id) {
				$response .= '<tr><td class=""><img class="portlet-table-cc-icon mr-2" src="' . $src . '"> ' . $symbol . '</td><td id="' . $symbol . '_bal">' . $balance . '</td><td class="' . $symbol . '"balance">' . $total . '</td><td class="text-center ' . $symbol . '"balance">' . $inorders . '</td></tr>';

			}
			else
			{
				$response .= '<tr><td class=""><img class="portlet-table-cc-icon mr-2" src="' . $src . '"> ' . $symbol . '</td><td id="' . $symbol . '_bal">-</td><td class="' . $symbol . '"balance">-</td><td class="text-center ' . $symbol . '"balance">-</td></tr>';
			}
		}

		echo $response;

	}
	
	function get_balance() 
	{
		$userId = session('tmaitb_user_id');

		$all_cur = $userbalance = $curr = array();
		$allcurr = Currency::where('status', 1)->select('image', 'symbol', 'id')->get();
		if ($userId) {
			$userbalance = Wallet::getBalance($userId);
		}
		foreach ($allcurr as $key => $value) {
			$curr['balance'] = isset($userbalance[$value->id]) ? $userbalance[$value->id] : 0;
			$curr['symbol'] = $value->symbol;
			$curr['image'] = $value->image;
			array_push($all_cur, $curr);
		}

		return $all_cur;
	}

	public static function getBuySellOrders($pair, $type , $limit = '') 
	{
		if ($type == 'sell') {
			if($limit != "")
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'asc')->limit($limit)->get();
			}
			else
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'asc')->get();
			}

		} else {
			if($limit != "")
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->limit($limit)->get();
			}
			else
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->get();
			}

		}
		$result = $response = $responses = array();
		$activeAmount_var = 0;
		if ($type == "buy") {
			$tempId = "buyorderId";
			$className = 'class="posVal"';
		} else {
			$tempId = "sellorderId";
			$className = 'class="negVal"';
		}
		if (!$openOrders->isEmpty()) 
		{
			foreach ($openOrders as $order) 
			{
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->amount;
				$status = $order->status;
				$filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
				$filledAmount = ($filledAmount) ? $amount - $filledAmount : $amount;
				if (isset($responses[$price])) {
					$old_amount = $responses[$price]['amount'];
					$old_amount += $filledAmount;
					$total = $old_amount * $price;
					$responses[$price]['amount'] = $old_amount;
					$responses[$price]['total'] = $total;
				} else {

					$total = $filledAmount * $price;
					$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
					$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
					$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
					$result['cls'] = '';
					$responses[$price] = $result;
				}

			}
			foreach ($responses as $key => $value) {
				$response[] = $value;
			}
        } 
		else 
		{
			$response = '0';
		}
		return $response;
	}

	
	public static function getStopOrders($pairId, $userId) 
	{
		$orders = CoinOrder::where('pair', $pairId)->where('user_id', $userId)->where('status', 'stoporder')->select('id', 'Price', 'Amount', 'firstCurrency', 'secondCurrency', 'Type', 'stopprice', 'ordertype', 'updated_at')->orderBy('id', 'desc')->get();
		$response = array();
		if (!$orders->isEmpty()) 
		{
			foreach ($orders as $order) 
			{
				$type = $order->Type;
				$orderId = $order->id;
				$price = $order->Price;
				$stopprice = $order->stopprice;
				$amount = $order->Amount;
				$status = $order->status;
				$filledAmount = $amount;
				$total = $filledAmount * $price;
				$decimal = 8;

				$order_ty_txt = trans('app_lang.stoporder_tab');

				$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['stopprice'] = rtrim(rtrim(sprintf('%.8F', $stopprice), '0'), ".");
				$result['type'] = $type;
				$result['ordertype'] = $order_ty_txt;

				$result['datetime'] = date('Y-m-d H:i:s', strtotime($order->updated_at));
				$result['id'] = insep_encode($orderId);
				$response[] = $result;
			}
		} 
		else 
		{
			$response = 0;
		}
		return $response;
	}

	
	public static function getActiveOrders($pairId, $userId) 
	{
		$openOrders = CoinOrder::where('pair', $pairId)->where('user_id', $userId)->whereIn('status', ['active', 'partially'])->select('id', 'Price', 'Amount', 'firstCurrency', 'secondCurrency', 'Type', 'ordertype', 'updated_at')->orderBy('id', 'desc')->get();
		$response = array();
		if (!$openOrders->isEmpty()) 
		{
			foreach ($openOrders as $order) 
			{
				$type = $order->Type;
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->Amount;
				$status = $order->status;
				
				$tempId = ($type == "buy") ? "buyorderId" : "sellorderId";
				$filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
				$filledAmount = ($filledAmount) ? $amount - $filledAmount : $amount;
				$total = $filledAmount * $price;
				
				$decimal = 8;
				if ($type == "buy") {
					
					$className = 'class="posVal"';
					$type_val = trans('app_lang.buy_tab');
				} else {
					
					$className = 'class="negVal"';
					$type_val = trans('app_lang.sell_tab');
				}

				if ($order->ordertype == 'market') {
					$order_ty_text = trans('app_lang.market_tab');
				} else if ($order->ordertype == 'limit') {
					$order_ty_text = trans('app_lang.limit_tab');
				} else if ($order->ordertype == 'stoporder') {
					$order_ty_text = trans('app_lang.stoporder_tab');
				}
				else {
					$order_ty_text ="";
				}

				$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
				$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
				$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				$result['datetime'] = date('Y-m-d H:i:s', strtotime($order->updated_at));
				$result['id'] = insep_encode($orderId);
				$result['type'] = ucfirst($type_val);
				$result['ordertype'] = ucfirst($order_ty_text);
				$response[] = $result;
			}
		} 
		else 
		{
			$response = 0;
		}
		return $response;
	}
	
	public function cancelOrder(Request $request) 
	{
		$userId = session::get('tmaitb_user_id');
		if ($userId == '') {
			echo "Session expired!";exit();
		}
		$result = "";
		$buyorderId = $buyuserId = $sellorderId = $selluserId = 0;
		$data = $request->all();
		$tradeId = $cancel_id = $data['tradeid'];
		$tradeId = insep_decode($tradeId);
		$order = CoinOrder::where('id', $tradeId)->whereIn('status', ['active', 'partially', 'stoporder'])->first();
		if ($order) 
		{
			$userId = $order->user_id;
			$type = $order->Type;
			$activeAmount = $order->Amount;
			$orderId = $order->id;
			$total = $order->Total;
			$ordertype = $order->ordertype;
			$status = $order->status;
			$activePrice = $order->Price;
			$pair_id = $order->pair;

			$fcurrId = $order->firstCurrency;
			$scurrId = $order->secondCurrency;
			$pair_details = get_pair($pair_id);
			if ($pair_details) {
				$from_symbol = $pair_details->from_symbol_id;
				$to_symbol = $pair_details->to_symbol_id;
			} else {
				echo "Invalid pair!";exit();
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
					$remarks = $type . ' cancelled ' . $activeTotal . ' ' . $cur;

					$updateBal = $activeTotal + $secondbal;
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
					$remarks = $type . ' cancelled ' . $filledAmount . ' ' . $cur;

					$updateBal = $filledAmount + $firstbal;
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
				echo "Invalid request!";exit();
			}
			if ($result != "") {
				$response = array('orders' => 0, 'type' => 0, 'existing_type' => $type, 'new_array' => 0, 'existing_array' => array('0' => array('price' => $activePrice, 'amount' => $filledAmount)));
				if ($ordertype == 'stoporder' && ($status == 'active' || $status == 'partially')) {
					$ordertype = 'limit';
				}
				$data = array('status' => 'success', 'message' => 'order cancelled', 'response' => $response, 'cancel_id' => $cancel_id, 'datetime' => date('Y-m-d H:i'), 'ordertype' => $ordertype);
				echo json_encode($data, JSON_FORCE_OBJECT);
			} else {
				echo "Failed to update!";
			}
		} 
		else {
			echo "Invalid request!";
		}
	}

	
	public static function getMyTradeHistory($pairId, $userId) 
	{
		$response = "";
		$orders = DB::table('redor_nioc as CO')->where(['CO.user_id' => $userId, 'CO.pair' => $pairId])->whereIn('CO.status', ['partially', 'filled', 'cancelled'])->leftjoin('pmetredor AS A', 'A.buyorderId', '=', 'CO.id')->leftjoin('pmetredor AS B', 'B.sellorderId', '=', 'CO.id')->select('CO.Type as Type', 'CO.id as id', 'CO.Price as Price', 'CO.Amount as Amount', 'CO.status as status', 'CO.ordertype as ordertype', 'CO.Fee as Fee', 'CO.secondCurrency as secondCurrency', 'CO.updated_at as updated_at', 'A.buyorderId as buyorderId', 'B.sellorderId as sellorderId', 'A.cancel_id as buyCancel', 'B.cancel_id as sellCancel', 'A.filledAmount as buyFilled', 'B.filledAmount as sellFilled', 'A.askPrice as buyPrice', 'B.askPrice as sellPrice', 'A.updated_at as buyUpdate', 'B.updated_at as sellUpdate')->orderBy('CO.updated_at', 'desc')->limit(20)->get()->toArray();

		if (!empty($orders)) 
		{
			foreach ($orders as $order) 
			{
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
				if ($filledAmount > 0) {
					$response['total'] = $filledAmount * $askPrice;
					$response['type'] = ucfirst($type);
					$response['status'] = ($cancelId != "") ? 'Cancelled' : 'Filled';
					$response['ordertype'] = ucfirst($ordertype);

					$response['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
					$response['price'] = rtrim(rtrim(sprintf('%.8F', $askPrice), '0'), ".");
					$response['fees'] = rtrim(rtrim(sprintf('%.8F', $feeAmount), '0'), ".");
					$response['total'] = rtrim(rtrim(sprintf('%.8F', $response['total']), '0'), ".");
					$response['datetime'] = date('Y-m-d H:i:s', strtotime($updatedAt));

					$result_array[] = $response;
				}
			}
		} 
		else 
		{
			
			$result_array = '0';
		}
		return $result_array;
	}
	
	function createOrder(Request $request) 
	{
		$userId = session::get('tmaitb_user_id');
		if ($userId == '') {
			echo "Session expired!";exit();
		}
		$data = $request->all();
		$validate = Validator::make($data, [
			'amount' => 'required|numeric',
			'order' => 'required',
			'type' => 'required',
			'pair' => 'required',
		], [
			'amount.required' => trans('app_lang.enter_amount'),
			'amount.numeric' => trans('app_lang.enter_amount_valid'),
			'order.required' => trans('app_lang.trade_type_required'),
			'type.required' => trans('app_lang.order_type_require'),
			'pair.required' => trans('app_lang.trade_pair_required'),
		]
	    );
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				echo $msg[0];exit;
			}
		} else {
			$order = strip_tags($data['order']);
			if ($order != "market") {
				$validate = Validator::make($data, [
					'price' => 'required|numeric',
				], [
					'price.required' => trans('app_lang.enter_price'),
					'price.numeric' => trans('app_lang.valid_price_enter'),
				]
			);
				if ($validate->fails()) {
					foreach ($validate->messages()->getMessages() as $val => $msg) {
						$data = array('status' => 'error', 'message' => $msg[0]);
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				}
			}
			if ($order == 'stoporder') {
				$validate = Validator::make($data, [
					'stopprice' => 'required|numeric',
				], [
					'stopprice.required' => trans('app_lang.enter_price'),
					'stopprice.numeric' => trans('app_lang.stop_valid_price'),
				]
			);
				if ($validate->fails()) {
					foreach ($validate->messages()->getMessages() as $val => $msg) {
						$data = array('status' => 'error', 'message' => $msg[0]);
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
				$data = array('status' => 'error', 'message' => "Invalid pair");
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

			$feePer = $getPair->trade_fee;
			$tfeePer = $getPair->taker_trade_fee;
			if ($amount < $getPair->min_amt) {
				$data = array('status' => 'error', 'message' => trans('app_lang.enter_amount_more_than') . $getPair->min_amt);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
			if ($order == "stoporder") {
				$stopprice = strip_tags($data['stopprice']);
				$lastPrice = $getPair->last_price;
				if ($type == "buy") {
					if ($stopprice <= $lastPrice) {
						$data = array('status' => 'error', 'message' => trans('app_lang.enter_stop_price_above') . $lastPrice);
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				} else {
					if ($stopprice >= $lastPrice) {
						$data = array('status' => 'error', 'message' => trans('app_lang.enter_stop_price_below') . $lastPrice);
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				}
			}
			$total = $amount * $price;
			
			if ($type == "buy") {

				if ($order != "market") {
					if ($total > $balance) {
						$data = array('status' => 'error', 'message' => trans('app_lang.insufficient_bal'));
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				}
				else
				{
					if($balance <= 0)
					{
						$data = array('status' => 'error', 'message' => trans('app_lang.insufficient_bal'));
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				}
				$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice);
			} elseif ($type == "sell") {
				if ($order != "market") {
					if ($amount > $balance) {
						$data = array('status' => 'error', 'message' => trans('app_lang.insufficient_bal'));
						echo json_encode($data, JSON_FORCE_OBJECT);
						exit;
					}
				}
				$result = TradeModel::createOrder($userId, $amount, $price, $feePer, $tfeePer, $type, $order, $firstCurr, $secondCurr, $pairId, $balance, $firstCurr_id, $secondCurr_id, $stopprice);
			} else {
				$data = array('status' => 'error', 'message' => trans('app_lang.invalid_request'));
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			}
		}
	}

	public function coinPairs($type = '',$pairid = '') 
	{
		$coinPairs = array();
		$fav = $favValues = $btcValues = $ethValues = $usnValues = "";

		$pairDetails = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");

		$id = session::get('tmaitb_user_id');
		if ($id) {
			$get_fav = User::where('id', $id)->select('fav_pairs')->first();
			$get_fav = $get_fav->fav_pairs;
			$fav = explode(',', $get_fav);
		}

		foreach ($pairDetails as $pairs) {
			$all_active_pairs = array();
			$pairdetails = '';
			$fromSymbol = $pairs->from_symbol;
			$toSymbol = $pairs->to_symbol;
			$forUrl = $toSymbol . '_' . $fromSymbol;
			$forName = $toSymbol . '/' . $fromSymbol;
			
			$lastId = "id=last_price_" . $forUrl;
			$changeId = "id=change_" . $forUrl;
			$volumeId = "id=volume_" . $forUrl;
			$activeId = "id=active_pair_" . $forUrl;
			$activeCls = "all_active_pairs active_pair_" . $forUrl;
			$activeCls = "class='" . $activeCls . "'";
			
			$lastPrice = number_format($pairs->last_price, 8, '.', ',');


			$yesterPrice = $pairs->yesterday_price == '' ? 0 : $pairs->yesterday_price;
			$high_price = $pairs->high_price == '' ? 0 : $pairs->high_price;
			$low_price = $pairs->low_price == '' ? 0 : $pairs->low_price;

			$fiat = "EUR";
			$convertionnew = getconvertionprice($fromSymbol,$toSymbol);
			$convertionPrice = $convertionnew == '' ? 0 : rtrim(rtrim(sprintf('%.4F', $convertionnew), '0'), ".");
			if($fromSymbol != "EUR")
			{
				$convertprice = $convertionPrice;
			}
			else
			{
				$convertprice = $convertionPrice;
			}
			$convert_price = number_format($convertprice, 2, '.', ',');

			$convertvalue = $lastPrice.' / '.$convert_price." ".$fiat;
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
			$changePer = $arrow . number_format($changePer, 2, '.', ',') . '%';
		
			$volume = ($pairs->volume == null) ? "0.00" : number_format($pairs->volume, 2, '.', ',');
			$favClass = 0;

			$fav_id = insep_encode($pairs->id);
			if($pairid == $pairs->id)
			{
				$style = "background-color: #F2F2FC;";
			}
			else
			{
				$style = "";
			}
			$url = URL::to('/') . "/public/assets/images/copy_img.png";
			$url1 = URL::to('/') . "/public/assets/images/copy_ho.png";
			$url3 = URL::to('/') . "/public/images/admin_currency/".getCurrencyImage($toSymbol);
			$tradeurl = URL::to('/trade') ."/". $forUrl;
			switch ($toSymbol) {
				case 'BTC':
				if ($fav) {
					if (in_array($pairs->id, $fav)) {
						if ($type == '1') {
							$favValues .= '<tr class="fav' . $fav_id . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
						} else if ($type == '2') {
							$favValues .= '<tr class="fav' . $fav_id . '" style="'.$style.'"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="'.$url3.'"> <span class="bold">'.$toSymbol.'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice. '</span> / <span class="light">'.$convertionnew.'</span> <span class ="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume." ". $fromSymbol.'</td><td class="text-center td_copy" onclick="tradePairChange(\'' . $forUrl . '\')"><img title="Open in a new window" class="copy_img" src="'.$url .'"><img title="Open in a new window" class="copy_ho" src="'.$url1 .'"></td></tr>';
						} else {
							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
						}
						$favClass = 1;
					}
				}
				if ($favClass) {
					$fav_txt = '<i class="fa fa-fw fa-star"></i>';
				} else {
					$fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
				}

				if ($type == '1') {

					$btcValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
				} elseif ($type == '2') {

					$btcValues .= '<tr style="cursor:pointer; '. $style .'  " ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="'.$url3.'"> <span class="bold">'.$toSymbol.'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice. '</span> / <span class="light">'.$convertionnew.'</span> <span class="light">'.$fiat.'</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td class="text-right" onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume ." ".$fromSymbol. '</td><td class="text-center td_copy"><a href = "'.$tradeurl.'" target="_blank"><img title="Open in a new window" class="copy_img" src="'.$url .'" ><img title="Open in a new window" class="copy_ho" src="'.$url1 .'"></a></td></tr>';
				} else {
					$btcValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')" >' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
				}
				break;
				
				case 'BoomCoin':
				if ($fav) {
					if (in_array($pairs->id, $fav)) {
						if ($type == '1') {
							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';

						} elseif ($type == '2') {
							$favValues .= '<tr class="fav' . $fav_id . '" style="'.$style.'"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="'.$url3.'"> <span class="bold">'.$toSymbol.'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice .'</span> / <span class="light">'.$convertionnew.'</span> <span class ="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume ." ".$fromSymbol. '</td></tr>';

						} else {
							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';

						}
						$favClass = 1;

					}
				}
				if ($favClass) {
					$fav_txt = '<i class="fa fa-fw fa-star"></i>';
				} else {
					$fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
				}

				if ($type == '1') {
					$usnValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
				} elseif ($type == '2') {
					$usnValues .= '<tr style="cursor:pointer; '.$style.'" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="'.$url3.'"> <span class="bold">' . $toSymbol .'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">'.$convertionnew.'</span> <span class="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td>' . $volume ." ".$fromSymbol. '</td></tr>';
				} else {
					$usnValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
				}
				break;
				case 'BCH':
				if ($fav) {
					if (in_array($pairs->id, $fav)) {
						if ($type == '1') {
							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';

						} elseif ($type == '2') {
							$favValues .= '<tr class="fav' . $fav_id . '" style="'.$style.'"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="'.$url3.'"> <span class="bold">'.$toSymbol.'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice .'</span> / <span class="light">'.$convertionnew.'</span> <span class ="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume ." ".$fromSymbol. '</td></tr>';

						} else {
							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';

						}
						$favClass = 1;

					}
				}
				if ($favClass) {
					$fav_txt = '<i class="fa fa-fw fa-star"></i>';
				} else {
					$fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
				}

				if ($type == '1') {
					$usnValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
				} elseif ($type == '2') {
					$usnValues .= '<tr style="cursor:pointer; '.$style.'" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="'.$url3.'"> <span class="bold">' . $toSymbol .'/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">'.$convertionnew.'</span> <span class="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td>' . $volume ." ".$fromSymbol. '</td></tr>';
				} else {
					$usnValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
				}
				break;
				case 'ETH':
				if ($fav) {
					if (in_array($pairs->id, $fav)) {
						if ($type == '1') {
							$favValues .= '<tr class="fav' . $fav_id . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';

						} elseif ($type == '2') {
							$favValues .= '<tr class="fav' . $fav_id . '" style="'.$style.'"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="'.$url3.'"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">'.$convert_price.'</span> <span class ="light">'.$fiat.'</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume ." ".$fromSymbol. '</td></tr>';

						} else {

							$favValues .= '<tr class="fav' . $fav_id . '"><td onclick="favPair(this,\'' . $fav_id . '\')" class="portlet-star-cnt"><i class="fa fa-fw fa-star"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
						}
						$favClass = 1;

					}
				}
				if ($favClass) {
					$fav_txt = '<i class="fa fa-fw fa-star"></i>';
				} else {
					$fav_txt = '<i class="fa fa-fw fa-star-o"></i>';
				}

				if ($type == '1') {

					$ethValues .= '<tr style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td></tr>';
				} elseif ($type == '2') {

					$ethValues .= '<tr style="cursor:pointer;'.$style.'" ' . $activeId . ' ' . $activeCls . '><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')">' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><img height="20" width="20" src="'.$url3.'"> <span class="bold">' . $toSymbol . '/</span><span class="light">' . $fromSymbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">'.$convert_price.'</span> <span class="light">'.$fiat.'</span></td><td><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume ." ".$fromSymbol. '</td></tr>';
				} else {
					$ethValues .= '<tr style="cursor:pointer;"><td class="tab-' . $fav_id . ' portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')" >' . $fav_txt . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '>' . $forName . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '>' . $lastPrice . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . '</td></tr>';
				}
				break;

			}
		}
		$No_record_fou = trans('app_lang.no_data_found');
		if ($btcValues == '') {
			$btcValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
		}

		if ($ethValues == '') {
			$ethValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
		}

		if ($usnValues == '') {
			$usnValues = '<div style="text-align:center;"><span>' . $No_record_fou . '</span></div>';
		}

		if ($favValues == '') {
			$favValues = '<div style="text-align:center;" class="tet no-fav"><span>' . $No_record_fou . '</span></div>';
		}

		$coinPairs['BTC'] = $btcValues;
		$coinPairs['ETH'] = $ethValues;
		$coinPairs['Fav'] = $favValues;
		echo json_encode($coinPairs, true);
	}
	
	public function chart($coin, $type) {

		if ($type == "symbols") {
			echo '{"name":"' . $coin . '","exchange-traded":"BoomCoin","exchange-listed":"BoomCoin","timezone":"America/New_York","minmov":1,"minmov2":0,"pointvalue":1,"session":"24x7","has_intraday":true,"has_no_volume":false,"description":"' . $coin . '","type":"stock","supported_resolutions":["1","5","15","30","60","D","2D","3D","W","3W","M","6M"],"pricescale":100000000,"volume_precision":8,"ticker":"' . $coin . '"}';
			exit();
		}
		if ($type == "config") {
			echo '{"supports_search":true,"supports_group_request":false,"supports_marks":true,"supports_timescale_marks":true,"supports_time":true,"exchanges":[{"value":"","name":"All Exchanges","desc":""},{"value":"BoomCoin","name":"BoomCoin","desc":"BoomCoin"}],"symbols_types":[{"name":"All types","value":""},{"name":"Stock","value":"stock"},{"name":"Index","value":"index"}],"supported_resolutions":["1","5","15","30","60","D","2D","3D","W","3W","M","6M"]}';
			exit();
		}
		if ($type == "history") {
			$from = $_GET['from'];
			$to = $_GET['to'];
			$exp = explode('_', $coin);
			$firstCurr = strtoupper($exp[0]);
			$secondCurr = strtoupper($exp[1]);
			$pairId = TradePairs::where(['to_symbol' => $firstCurr, 'from_symbol' => $secondCurr,'status'=> 1])->select('id')->first()->id;
			$res = TradeModel::chartData($pairId, $from, $to);
			echo $res;
		}
	}


    
	function advance_trade() {
		$pairs = TradePairs::select('from_symbol', 'to_symbol')->where('status', 1)->first();
		$pairs = $pairs->to_symbol . '_' . $pairs->from_symbol;
		return redirect('/advance_trade/' . $pairs);

	}
	
	function advance_trade_pair($pair_symbol) {
		$id = session::get('tmaitb_user_id');
		if ($id) {
			$user = User::where('id', $id)->select('profile')->first();
		} else {
			$user = '';
		}

		$pairs = TradePairs::getFullPairs();
		$page = 2;
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.advance_trade.trade-advanced', compact('pair_symbol', 'user', 'pairs','newsdetails','page'));

	}
	
	function favoritePair() 
	{
		$id = session::get('tmaitb_user_id');
		if ($id) {
			$data = Input::all();
			$fav_array = array();
			$pair_id = trim(strip_tags($data['pair_id']));
			$pair_id = insep_decode($pair_id);
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
				echo 'success';
			} else {
				echo 'failed to updated';
			}
		}

	}
	
	public function getallFilledOrders($pairId) 
	{
		$response = array();
		$result = array();
		$orders = OrderTemp::where('pair', $pairId)->where('cancel_id', NULL)->select('askPrice', 'filledAmount', 'updated_at', 'sellerUserId', 'buyerUserId', 'sellorderId', 'buyorderId')->orderBy('id', 'desc')->limit(40)->get();
		if (!$orders->isEmpty()) {
			$i = 0;
			$j = 0;
			foreach ($orders as $order) {
				$k = $i + 1;
				$sellerUserId = $order->sellerUserId;
				$buyerUserId = $order->buyerUserId;
				$sellorderId = $order->sellorderId;
				$buyorderId = $order->buyorderId;
				$updated_at = $order->updated_at;
				$decimal = 8;
				$filledAmount = rtrim(rtrim(sprintf('%.8F', $order->filledAmount), '0'), ".");
				$activePrice = rtrim(rtrim(sprintf('%.8F', $order->askPrice), '0'), ".");
				if ($filledAmount > 0) {
					$total = $activePrice * $filledAmount;
					$total = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
					$result['datetime'] = date('H:i:s', strtotime($updated_at));
					$result['price'] = $activePrice;
					$result['amount'] = $filledAmount;
					$result['sellorderId'] = $sellorderId;
					$result['buyorderId'] = $buyorderId;
					$response[] = $result;
				}
			}

		}
		return $response;
	}
	
	public function getPairDataadvance($pair) 
	{

		$splitPair = explode('_', $pair);
		$firstCurr = strtoupper(strip_tags(trim($splitPair[1])));
		$secondCurr = strtoupper(strip_tags(trim($splitPair[0])));
		$pairDetails = TradePairs::where('from_symbol', $firstCurr)->where('to_symbol', $secondCurr)->select('id', 'min_price', 'max_price', 'trade_fee', 'taker_trade_fee', 'last_price', 'min_amt', 'from_symbol_id', 'to_symbol_id')->first();
		if ($pairDetails) {
			$pairId = $pairDetails->id;
			$result['pair_id'] = $pairId;
			$result['from_cur'] = $firstCurr;
			$result['to_cur'] = $secondCurr;
			$result['from_bal'] = 0;
			$result['to_bal'] = 0;
			$result['pair'] = $secondCurr . '/' . $firstCurr;
			$No_trade_his = trans('app_lang.no_trade_history');
			$No_open_ord = trans('app_lang.no_open_order_available');
			$result['my_orders'] = '0';
			$result['min_amt'] = $pairDetails->min_amt;
			$result['min_price'] = $pairDetails->min_price;
			$result['max_price'] = $pairDetails->max_price;
			$result['trade_fee'] = $pairDetails->trade_fee;
			$result['taker_trade_fee'] = $pairDetails->taker_trade_fee;
			$result['last_price'] = $pairDetails->last_price;
			$inr_value = Currency::where('symbol', $firstCurr)->select('inr_value')->first()->inr_value;
			$result['usd_val'] = $inr_value;
			$result['buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy');
			$result['limit_buy_orders'] = self::getadvanceBuySellOrders($pairId, 'buy',6);
			$result['sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell');
			$result['limit_sell_orders'] = self::getadvanceBuySellOrders($pairId, 'sell',6);
			$result['sell_orderss'] = self::getadvanceBuySellOrders($pairId, 'sell');
			$result['limit_sell_orderss'] = self::getadvanceBuySellOrders($pairId, 'sell',6);
			$result['market_orders'] = self::getallFilledOrders($pairId);
			$result['open_orders'] = '0';
			$result['stop_orders'] = '0';
			$userId = session('tmaitb_user_id');
			if ($userId != "") {
				$getBalance1 = Wallet::getBalance($userId, $pairDetails->from_symbol_id);
				$getBalance2 = Wallet::getBalance($userId, $pairDetails->to_symbol_id);
				$result['from_bal'] = $getBalance1;
				$result['to_bal'] = $getBalance2;
				$result['my_orders'] = self::getMyTradeHistory($pairId, $userId);

				$result['open_orders'] = self::getActiveOrders($pairId, $userId);
				$result['stop_orders'] = self::getStopOrders($pairId, $userId);

			}
			$result['trade_data'] = getTradeData($pairId, $firstCurr, $secondCurr);
			$result['price_range'] = priceRange($pairId);

			$response = array('status' => 'success', 'result' => $result);
		} else {
			$response = array('status' => 'fail');
		}
		echo json_encode($response);
	}
	
	public static function getadvanceBuySellOrders($pair, $type, $limit = '') 
	{
		if ($type == 'sell') {
			if($limit != '')
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->limit($limit)->get();
			}
			else
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'asc')->get();
			}

		} else {
			if($limit != '')
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->limit($limit)->get();
			}
			else
			{
				$openOrders = CoinOrder::where('pair', $pair)->where('Type', $type)->whereIn('ordertype', ['limit', 'stoporder'])->whereIn('status', ['active', 'partially'])->select('amount', 'id', 'Price', 'secondCurrency', 'status', 'order_token')->orderBy('Price', 'desc')->get();
			}

		}
		$result = $response = $responses = array();
		$activeAmount_var = 0;
		if ($type == "buy") {
			$tempId = "buyorderId";
			$className = 'class="posVal"';
		} else {
			$tempId = "sellorderId";
			$className = 'class="negVal"';
		}
		if (!$openOrders->isEmpty()) {
			foreach ($openOrders as $order) {
				$orderId = $order->id;
				$price = $order->Price;
				$amount = $order->amount;
				$status = $order->status;
				$filledAmount = TradeModel::checkOrdertemp($orderId, $tempId);
				$filledAmount = ($filledAmount) ? $amount - $filledAmount : $amount;
				if (isset($responses[$price])) {
					$old_amount = $responses[$price]['amount'];
					$old_amount += $filledAmount;
					$total = $old_amount * $price;
					$responses[$price]['amount'] = $old_amount;
					$responses[$price]['total'] = $total;
				} else {

					$total = $filledAmount * $price;
					$result['amount'] = rtrim(rtrim(sprintf('%.8F', $filledAmount), '0'), ".");
					$result['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
					$result['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
					$result['cls'] = '';
					$responses[$price] = $result;
				}

			}
			foreach ($responses as $key => $value) {
				$response[] = $value;
			}

		} else {
			$response = '0';
		}
		return $response;
	}

}
