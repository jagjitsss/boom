<?php
namespace App\Model;
use App\Model\OrderTemp;
use App\Model\Referral;
use App\Model\TradePairs;
use App\Model\Wallet;
use DB;
use Illuminate\Database\Eloquent\Model;
use Session;

class TradeModel extends Model {
	//create new sell or buy order
	public static function createOrder($userId, $amount, $price, $feePer, $feeTk, $type, $order, $firstCurr, $secondCurr, $pair, $balance, $firstCurr_id, $secondCurr_id, $stopprice = 0, $api = 0) {
		$status = ($order == "stoporder") ? "stoporder" : ($order == "market" ? "market" : "active");
		$total = $amount * $price;
		if ($type == "buy") {
			$updateBal = $balance - $total;
			$currency = $firstCurr_id;
			$remarks = 'buy ' . $secondCurr . ' for ' . $firstCurr . ' ' . $total;
		} else {
			$updateBal = $balance - $amount;
			$currency = $secondCurr_id;
			$remarks = 'sell ' . $secondCurr . ' ' . $amount;
		}
		$orderData = array(
			'user_id' => $userId,
			'Amount' => $amount,
			'Price' => $price,
			'stopprice' => $stopprice,
			'Type' => $type,
			'ordertype' => $order,
			'Fee' => '',
			'maker_fee_per' => $feePer,
			'taker_fee_per' => $feeTk,
			'trader' => 'user',
			'Total' => $total,
			'firstCurrency' => $firstCurr,
			'secondCurrency' => $secondCurr,
			'pair' => $pair,
			'order_token' => time() . randomString(8),
			'status' => $status,
			'remarks' => $remarks,
		);

		$result = DB::transaction(function () use ($orderData, $userId, $currency, $updateBal, $order, $type, $firstCurr, $secondCurr, $api) {

			if ($order != 'market') {

				$balUpdate = Wallet::updateBalance($userId, $currency, $updateBal);
				if (!$balUpdate) {
					return "Failed to update balance!";
				}
			} else {
				$oppType = ($type == "buy") ? "sell" : "buy";
				$checkOrder = self::checkActiveOrder($firstCurr, $secondCurr, $oppType, $userId);
				if ($checkOrder == 0) {
					if ($api == 1) {
						echo json_encode(array('status' => 0, 'message' => "No " . $oppType . " orders available!"));exit;
					} else {
						echo json_encode(array('status' => 'error', 'message' => "No " . $oppType . " orders available!"));exit;
					}
				}
			}
			return CoinOrder::create($orderData);
		});
		if ($result) {
			$lastId = $result->id;
			if ($order == 'market') {
				$query = TradeModel::market_mapping($lastId, $api);
			} elseif ($order == 'limit') {
				$time = time();

				$query = TradeModel::checkMapping($lastId, $pair, $type, $amount, $price, $userId, $order, $time,'',$api);
				
			}
			if ($api == 1) {
				if($type == 'buy')
				{
					$mess  = 'Your buy order placed';
				}
				else
				{
					$mess  = 'Your sell order placed';
				}
				$data = array('status' => '1', 'message' => $mess);
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			} else {
				if ($order == 'stoporder') {
					$query = array('orders' => '2', 'datetime' => date('Y-m-d H:i'), 'details' => insep_encode($lastId));
				}

				$data = array('status' => 'success', 'message' => 'order placed', 'response' => $query);
				echo json_encode($data, JSON_FORCE_OBJECT);
				// echo "success";
				exit;
			}
		} else {
			if ($api == 1 || $api==2) {
				$data = array('status' => '0', 'message' => 'Something went wrong!');
				echo json_encode($data, JSON_FORCE_OBJECT);
				exit;
			} else {
				echo 'Something went wrong!';exit;
			}
		}

	}
    // check if any active orders available
	public static function checkActiveOrder($first, $second, $type, $userId) 
	{
		return CoinOrder::where('firstCurrency', $first)->where('secondCurrency', $second)->whereIn('status', ['active', 'partially'])->where('Type', $type)->where('user_id', '!=', $userId)->count();
	}
	//new_array - inserting array - mapping for orders
	public static function checkMapping($res, $pairId, $type, $amount, $price, $userId, $ordertype, $time, $stopprice = 0,$api=0) 
	{
		$active['amount'] = rtrim(rtrim(sprintf('%.8F', $amount), '0'), ".");
		$active['price'] = rtrim(rtrim(sprintf('%.8F', $price), '0'), ".");
		$total = $amount * $price;
		$active['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
		$active['datetime'] = date('Y-m-d H:i', $time);
		$active['id'] = insep_encode($res);

		if ($type == "buy") {
			$type_val = trans('app_lang.buy_tab');
		} else {
			$type_val = trans('app_lang.sell_tab');
		}
		if ($ordertype == 'limit') {
			$order_ty_text = trans('app_lang.limit_tab');
		} else if ($ordertype == 'stoporder') {
			$order_ty_text = trans('app_lang.stoporder_tab');
		}

		$active['type'] = $type_val;
		$active['ordertype'] = $order_ty_text;
		$query = TradeModel::initialize_mapping($res, $pairId, $type,'','',$api);

		if (count($query) == 0 || $query == 0) {
			//create active order

			if ($ordertype == 'limit') {
				if($api==2){

					$data = array('active' => $active);

				}else{

					$data = array('orders' => 0, 'type' => $type, 'new_array' => array('0' => array('price' => $price, 'amount' => $amount)), 'stop' => 0, 'active' => $active);
				}
			} else {
				$active['stopprice'] = $stopprice;
				if($api==2){

					$data = array('active' => $active);

				}else{
					$data = array('orders' => 0, 'type' => $type, 'new_array' => array('0' => array('price' => $price, 'amount' => $amount)), 'active' => 0, 'stop' => $active);
				}
				
			}
			return $data;
		} else {
			if($api==2)
			{

				$active_order_array = $history = $partial_array = $history_data = array();
			//history
				if (isset($query['history'])) {
					foreach ($query['history'] as $value) {
						$history_data['amount'] = $value['filledAmount'];
						$history_data['price'] = $value['askPrice'];
						$history_data['datetime'] = $value['datetime'];
						$history[] = $history_data;
					}
				}
				$type_partial = ($type == 'sell') ? 'buy' : 'sell';
				if (isset($query['partial'])) {
				//to minus price
					if (isset($query['partial'][$type_partial])) {
						if (count($query['partial'][$type_partial])) {
							$active_order_array = $query['active_order'];
							foreach ($query['partial'][$type_partial] as $key => $value) {
								$partial['amount'] = $value['amount'];
								$partial['price'] = $value['price'];

								if ($partial['amount'] && $partial['price']) {
									$partial_array[] = $partial;
								}
							}
						}
					}
				
					if ($query['partial'][$type]['amount'] == 0) {

				} else {
					$amount = $query['partial'][$type]['amount'];
					$price = $query['partial'][$type]['price'];
				
					$data['active'] = $active;
					$total = $amount * $price;
					$data['active']['amount'] = rtrim(rtrim(sprintf('%.8F', $amount), "."), ".");
					$data['active']['price'] = rtrim(rtrim(sprintf('%.8F', $price), "."), ".");
					$data['active']['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				}

			}

           
			$data['active_order'] = is_array($active_order_array) ? $active_order_array : 0;


			return $data;


		}else{

			$active_order_array = $history = $partial_array = $history_data = array();
			
			if (isset($query['history'])) {
				foreach ($query['history'] as $value) {
					$history_data['amount'] = $value['filledAmount'];
					$history_data['price'] = $value['askPrice'];
					$history_data['datetime'] = $value['datetime'];
					$history[] = $history_data;
				}
			}
			$type_partial = ($type == 'sell') ? 'buy' : 'sell';
			if (isset($query['partial'])) {
			
				if (isset($query['partial'][$type_partial])) {
					if (count($query['partial'][$type_partial])) {
						$active_order_array = $query['active_order'];
						foreach ($query['partial'][$type_partial] as $key => $value) {
							$partial['amount'] = $value['amount'];
							$partial['price'] = $value['price'];

							if ($partial['amount'] && $partial['price']) {
								$partial_array[] = $partial;
							}
						}
					}
				}
			
				if ($query['partial'][$type]['amount'] == 0) {
					$data['new_array'] = '0';
					$data['active'] = '0';
				} else {
					$amount = $query['partial'][$type]['amount'];
					$price = $query['partial'][$type]['price'];
					$data['new_array'] = array('0' => array('amount' => $amount, 'price' => $price));
					$data['active'] = $active;
					$total = $amount * $price;
					$data['active']['amount'] = rtrim(rtrim(sprintf('%.8F', $amount), "."), ".");
					$data['active']['price'] = rtrim(rtrim(sprintf('%.8F', $price), "."), ".");
					$data['active']['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
				}

			}
			$data['last_price'] = $query['last_price'];
			$data['last_volume'] = $query['last_volume'];
			$data['type'] = $type;
			$data['orders'] = 1;
			$data['datetime'] = date('Y-m-d H:i');
			$data['existing_array'] = is_array($partial_array) ? $partial_array : 0;
			$data['existing_type'] = $type_partial;
			$data['active_order'] = is_array($active_order_array) ? $active_order_array : 0;
			$data['tradehistory'] = $history;
			$stop_orders = array();
			Session::forget('checkarr');
			if (isset($query['stop_orders'])) {
				$stop_orders['active'] = isset($query['stop_orders'][0]['active']) ? $query['stop_orders'][0]['active'] : 0;
				$stop_orders['active_values'] = isset($query['stop_orders'][0]['active_values']) ? $query['stop_orders'][0]['active_values'] : 0;
				foreach ($query['stop_orders'] as $stop_arr) {

					$filled = self::createArray($stop_arr['stop']);
					$stop_orders['filled'][] = $filled;
					if (isset($filled[0]['active_order'])) {
						$id = $filled[0]['active_order'][0]['id'];
						$id1 = $filled[0]['active_order'][1]['id'];
						if (isset($stop_orders['active_values'][$id])) {
							unset($stop_orders['active_values'][$id]);
						}
						if (isset($stop_orders['active_values'][$id1])) {
							unset($stop_orders['active_values'][$id1]);
						}
					}
				}

				$arr_stop = $stop_orders['filled'][0];
				$newarr1 = [];
				array_walk_recursive($arr_stop, function ($k, $val) use ($stop_orders) {
					global $newarr1;
					$text = substr($k, 0, 4);
					if ($text == 'rem_') {

						$id = substr($k, 4);
						$newarr1[] = $id;
						Session::push('checkarr', $newarr1);

					}

				});

				$checkAr = Session::get('checkarr');

				if (isset($checkAr[0])) {
					$arr = end($checkAr);
					foreach ($arr as $key => $value) {
						if (isset($stop_orders['active_values'][$value])) {
							unset($stop_orders['active_values'][$value]);
						}

					}
				}
				$data['stop_orders'] = $stop_orders;
			}

			return $data;

		}
	}
}
	
public static function initialize_mapping($res, $pairId, $stopArr = array(), $stop_call = 0, $recent_volume = 0,$api=0) 
{
	$stop_orders = $active_order_array = $buy_array = $sell_array = $partial_array = $filled_array = array();
	$last_volume = $last_price = 0;
	$buy = CoinOrder::where('id', $res)->whereIn('status', ['active', 'partially'])->first();
	if ($buy) {
		if ($buy->Type == 'buy') {
			$final = "";
			$buyorderId = $buy->id;
			$buyuserId = $buy->user_id;
			$buyPrice = $buy->Price;
			$buyOrertype = $buy->ordertype;
			$buyPrice = (float) $buyPrice;
			$buyAmount = (float) $buy->Amount;
			$pair = $buy->pair;
			$cur = get_pair($pair);
			$f_currId = $cur->to_symbol_id;
			$s_currId = $cur->from_symbol_id;
			$Total = $buy->Total;
			$Fee = $buy->Fee;
			$fcurrId = $buy->firstCurrency;
			$scurrId = $buy->secondCurrency;
			$buyordertype = $buy->ordertype;
			$buy_array = array('price' => $buyPrice, 'amount' => $buyAmount);
			$fetchsellRecords = TradeModel::getParticularsellorders($buyPrice, $buyuserId, $pair, $buyOrertype);
			if ($fetchsellRecords) {
				$k = 0;
				foreach ($fetchsellRecords as $sell) {
					$k++;
					$sellorderId = $sell->id;
					$selluserId = $sell->user_id;
					$sellPrice = $sell->Price;
					$sellOrdertype = $sell->ordertype;
					$sellAmount = $sell->Amount;
					$sellPrice = (float) $sellPrice;
					$sellAmount = (float) $sellAmount;
					$pair = $sell->pair;
					$sellstatus = $sell->status;
					$Total1 = $sell->Total;
					$Fee1 = $sell->Fee;
					$fee_per = $sell->fee_per;
					$fcurrId = $sell->firstCurrency;
					$scurrId = $sell->secondCurrency;
					$scurrId = $sell->secondCurrency;
					$sellSumamount = TradeModel::checkOrdertemp($sellorderId, 'sellorderId');
					if ($sellSumamount) {
						$approxiAmount = $sellAmount - $sellSumamount;
						$approxiAmount = number_format($approxiAmount, 8, '.', '');
					} else {
						$approxiAmount = $sellAmount;
					}
					$buySumamount = TradeModel::checkOrdertemp($buyorderId, 'buyorderId');
					if ($buySumamount) {
						$buySumamount = $buyAmount - $buySumamount;
						$buySumamount = number_format($buySumamount, 8, '.', '');
					} else {
						$buySumamount = $buyAmount;
					}
					if (trim($approxiAmount) >= trim($buySumamount)) {
						$amount = $buySumamount;
					} else {
						$amount = $approxiAmount;
					}
					if (trim($approxiAmount) != 0 && trim($buySumamount) != 0) {
						$date = date('Y-m-d');
						$time = date("H:i:s");
						$datetime = date("Y-m-d H:i:s");
						$data = array(
							'sellorderId' => $sellorderId,
							'sellerUserId' => $selluserId,
							'askAmount' => $sellAmount,
							'firstCurrency' => $fcurrId,
							'secondCurrency' => $scurrId,
							'askPrice' => $sellPrice,
							'filledAmount' => $amount,
							'buyorderId' => $buyorderId,
							'buyerUserId' => $buyuserId,
							'sellerStatus' => "inactive",
							'buyerStatus' => "inactive",
							"pair" => $pair,
							"fee_per" => 0,
							"datetime" => $datetime,
						);
						$inserted = OrderTemp::create($data);
						$buy_array['amount'] = $buy_array['amount'] - $amount;
						$last_price = $sellPrice;
						$last_volume = $recent_volume + ($amount * $sellPrice);
						$sellPrice_key = str_replace('.', '_', $sellPrice);
						if (isset($sell_array[$sellPrice_key])) {
							$amount_sell = $sell_array[$sellPrice_key]['amount'] + $amount;
							$sel_amount = number_format($amount_sell, 8, '.', '');
							$sell_array[$sellPrice_key]['amount'] = $sel_amount;
						} else {
							$sell_array[$sellPrice_key] = array('price' => $sellPrice, 'amount' => $amount);
						}
						$total = $amount * $sellPrice;
						$datetime1 = date('Y-m-d H:i');
						if ($stop_call == 1) {

							if($api==2){
								$active_order_array[] =  array('order_id' => insep_encode($buyorderId),'price' => $sellPrice, 'amount' => $amount,'type' => 'buy', 'ordertype' => $buyOrertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));
								$active_order_array[] = array('order_id' => insep_encode($sellorderId),'price' => $sellPrice, 'amount' => $amount,'type' => 'sell', 'ordertype' => $sellOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));

							}else{
								$update_id = insep_encode($buyuserId);

								$active_order_array[] = array($update_id => array('price' => $sellPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($buyorderId), 'type' => 'buy', 'ordertype' => $buyOrertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));

								$update_id = insep_encode($selluserId);
								
								$active_order_array[] = array($update_id => array('price' => $sellPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($sellorderId), 'type' => 'sell', 'ordertype' => $sellOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));
							}

						} else {
							$update_id = insep_encode($selluserId);
							if($api==2){
								$active_order_array[] = array('order_id' => insep_encode($sellorderId), 'amount' => $amount ,'price' => $sellPrice, 'type' => 'sell', 'ordertype' => $sellOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));

							}else{

								$active_order_array[] = array($update_id => array('price' => $sellPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($sellorderId), 'type' => 'sell', 'ordertype' => $sellOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));
							}
						}

						$filled_array[] = $data;
						TradePairs::where('id', $pair)->update(['last_price' => $sellPrice]);
						$theftprice = 0;
						if ($inserted) {
							if (trim($buyPrice) > trim($sellPrice)) {
								$price1 = $amount * $sellPrice;
								$price2 = $amount * $buyPrice;
								$theftprice = $price2 - $price1;
								$secondbal = Wallet::getBalance($buyuserId, $s_currId);
								$trade_fee = ($amount * $sellPrice) * $fee_per / 100;
								$remaining_balance = $secondbal + $theftprice + $trade_fee;
								Wallet::updateBalance($buyuserId, $s_currId, $remaining_balance);
							}
								
							if (trim($approxiAmount) == trim($amount)) {
								TradeModel::ordercompletetype($sellorderId, "sell", $inserted->id, $selluserId, $buyorderId);
							} else {
								TradeModel::orderpartialtype($sellorderId, "sell", $inserted->id, $buyorderId);
								$updatedata = array('status' => "partially");
								CoinOrder::where('id', $sellorderId)->update($updatedata);

							}
							if ((trim($approxiAmount) == trim($buySumamount)) || (trim($approxiAmount) > trim($buySumamount))) {
								TradeModel::ordercompletetype($buyorderId, "buy", $inserted->id, $buyuserId, $sellorderId);
								$buy_array['amount'] = 0;
							} else {
								TradeModel::orderpartialtype($buyorderId, "buy", $inserted->id, $sellorderId);
								$updatedata1 = array('status' => "partially");
								CoinOrder::where('id', $buyorderId)->update($updatedata1);

							}
							$stop_orders[] = TradeModel::checkStopOrder($pairId, $stopArr, $last_volume);
						}
					} else {
						break;
					}
				}
				$partial_array = array('buy' => $buy_array, 'sell' => $sell_array);
				$array['active_order'] = $active_order_array;
				$array['history'] = $filled_array;
				$array['last_price'] = $last_price;
				$array['stop_orders'] = $stop_orders;
				$array['partial'] = $partial_array;
				$array['last_volume'] = $last_volume;

				return $array;
			}
		} else if ($buy->Type == 'sell') {
			$sell = CoinOrder::where('id', $res)->first();
			$final = "";
			$sellorderId = $sell->id;
			$selluserId = $sell->user_id;
			$sellPrice = $sell->Price;
			$sellOrertype = $sell->ordertype;
			$sellPrice = (float) $sellPrice;
			$sellAmount = (float) $sell->Amount;
			$pair = $sell->pair;
			$cur = get_pair($pair);
			$f_currId = $cur->to_symbol_id;
			$s_currId = $cur->from_symbol_id;
			$Total1 = $sell->Total;
			$Fee1 = $sell->Fee;
			$fcurrId = $sell->firstCurrency;
			$scurrId = $sell->secondCurrency;
			$sell_array = array('price' => $sellPrice, 'amount' => $sellAmount);
			$fetchbuyRecords = TradeModel::getParticularbuyorders($sellPrice, $selluserId, $pair);
			if ($fetchbuyRecords) {
				$k = 0;
				foreach ($fetchbuyRecords as $buy) {
					$k++;
					$buyorderId = $buy->id;
					$buyuserId = $buy->user_id;
					$buyPrice = $buy->Price;
					$buyOrdertype = $buy->ordertype;
					$buyAmount = $buy->Amount;
					$buyPrice = (float) $buyPrice;
					$buyAmount = (float) $buy->Amount;
					$pair = $buy->pair;
					$buystatus = $buy->status;
					$Total = $buy->Total;
					$Fee = $buy->Fee;
					$fee_per = $buy->fee_per;
					$fcurrId = $buy->firstCurrency;
					$scurrId = $buy->secondCurrency;
					$ordertype = $buy->ordertype;
					$buySumamount = TradeModel::checkOrdertemp($buyorderId, 'buyorderId');
					if ($buySumamount) {
						$approxiAmount = $buyAmount - $buySumamount;
						$approxiAmount = number_format($approxiAmount, 8, '.', '');
					} else {
						$approxiAmount = $buyAmount;
					}
					$sellSumamount = TradeModel::checkOrdertemp($sellorderId, 'sellorderId');
					if ($sellSumamount) {
						$sellSumamount = $sellAmount - $sellSumamount;
						$sellSumamount = number_format($sellSumamount, 8, '.', '');
					} else {
						$sellSumamount = $sellAmount;
					}
					if (trim($approxiAmount) >= trim($sellSumamount)) {
						$amount = $sellSumamount;
					} else {
						$amount = $approxiAmount;
					}
					if (trim($approxiAmount) != 0 && trim($sellSumamount) != 0) {
						$date = date('Y-m-d');
						$time = date("H:i:s");
						$datetime = date("Y-m-d H:i:s");
						$data = array(
							'sellorderId' => $sellorderId,
							'sellerUserId' => $selluserId,
							'askAmount' => $sellAmount,
							'firstCurrency' => $fcurrId,
							'secondCurrency' => $scurrId,
							'askPrice' => $sellPrice,
							'filledAmount' => $amount,
							'buyorderId' => $buyorderId,
							'buyerUserId' => $buyuserId,
							'sellerStatus' => "inactive",
							'buyerStatus' => "inactive",
							"pair" => $pair,
							"fee_per" => 0,
							"datetime" => $datetime,
						);
						$inserted = OrderTemp::create($data);
						$last_price = $buyPrice;
						$last_volume = $recent_volume + ($amount * $buyPrice);
						$buyPrice_key = str_replace('.', '_', $buyPrice);
						if (isset($buy_array[$buyPrice_key])) {
							$buy_amt = $buy_array[$buyPrice_key]['amount'] + $amount;
							$b_amount = number_format($buy_amt, 8, '.', '');
							$buy_array[$buyPrice_key]['amount'] = $b_amount;
						} else {
							$buy_array[$buyPrice_key] = array('price' => $buyPrice, 'amount' => $amount);
						}
						$sell_array['amount'] = $sell_array['amount'] - $amount;
						$update_id = insep_encode($buyuserId);
						$total = $amount * $buyPrice;
						$datetime1 = date('Y-m-d H:i');
						if ($stop_call == 1) {

							if($api==2){

								$active_order_array[] = array('order_id' => insep_encode($sellorderId), 'price' => $buyPrice, 'amount' => $amount,  'type' => 'sell', 'ordertype' => $sellOrertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));
								$active_order_array[] =  array('order_id' => insep_encode($buyorderId),'price' => $buyPrice, 'amount' => $amount,'type' => 'buy', 'ordertype' => $buyOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));
							}else{
								$update_id = insep_encode($selluserId);
								$active_order_array[] = array($update_id => array('price' => $buyPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($sellorderId), 'type' => 'sell', 'ordertype' => $sellOrertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));
								$update_id = insep_encode($buyuserId);
								$active_order_array[] = array($update_id => array('price' => $buyPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($buyorderId), 'type' => 'buy', 'ordertype' => $buyOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));
							}



						} else {

							if($api==2){
								$active_order_array[] =  array('order_id' => insep_encode($buyorderId), 'price' => $buyPrice, 'amount' => $amount, 'type' => 'buy', 'ordertype' => $buyOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', ''));

							}else{
								$update_id = insep_encode($buyuserId);
								$active_order_array[] = array($update_id => array('price' => $buyPrice, 'amount' => $amount, 'user_id' => $update_id, 'order_id' => insep_encode($buyorderId), 'type' => 'buy', 'ordertype' => $buyOrdertype, 'datetime' => $datetime1, 'status' => 'Filled', 'total' => number_format($total, 8, '.', '')));
							}

						}

						$filled_array[] = $data;
						TradePairs::where('id', $pair)->update(['last_price' => $sellPrice]);
						$theftprice = 0;
						if ($inserted) {
							if (trim($sellPrice) < trim($buyPrice)) {
									
									TradePairs::where('id', $pair)->update(['last_price' => $buyPrice]);
									OrderTemp::where('id', $inserted->id)->update(['askPrice' => $buyPrice]);
								}
								
								if (trim($approxiAmount) == trim($amount)) {
									TradeModel::ordercompletetype($buyorderId, "buy", $inserted->id, $buyuserId, $sellorderId);
								} else {
									TradeModel::orderpartialtype($buyorderId, "buy", $inserted->id, $sellorderId);
									$updatedata1 = array('status' => "partially");
									CoinOrder::where('id', $buyorderId)->update($updatedata1);
								}
								if ((trim($approxiAmount) >= trim($sellSumamount))) {
									TradeModel::ordercompletetype($sellorderId, "sell", $inserted->id, $selluserId, $buyorderId);
									$sell_array['amount'] = 0;
								} else {
									TradeModel::orderpartialtype($sellorderId, "sell", $inserted->id, $buyorderId);
									$updatedata1 = array('status' => "partially");
									CoinOrder::where('id', $sellorderId)->update($updatedata1);
								}
								$stop_orders[] = TradeModel::checkStopOrder($pairId, $stopArr, $last_volume);
							}
						} else {
							break;
						}
					}
					$partial_array = array('buy' => $buy_array, 'sell' => $sell_array);
					$array['active_order'] = $active_order_array;
					$array['history'] = $filled_array;
					$array['partial'] = $partial_array;
					$array['last_volume'] = $last_volume;
					$array['last_price'] = $last_price;
					$array['stop_orders'] = $stop_orders;
					return $array;
				}
			}
		}
	}
	
	public static function checkStopOrder($pair, $stopArr = array(), $recent_volume = 0) 
	{
		$returnArr['active'] = $returnArr['active_values'] = $returnArr['stop'] = array();
		if (isset($stopArr['active']) && !empty($stopArr['active'])) {
			$returnArr['active'] = $stopArr['active'];
		}
		if (isset($stopArr['active_values']) && !empty($stopArr['active_values'])) {

			$returnArr['active_values'] = $stopArr['active_values'];

		}
		if (isset($stopArr['stop']) && !empty($stopArr['stop'])) {
			$returnArr['stop'] = $stopArr['stop'];
		}

		$pairDetails = TradePairs::where('id', $pair)->select('last_price')->first();
		$lowestaskprice = $highestbidprice = $pairDetails->last_price;
		$buy_array = $sell_array = array();
		$sellStopOrders = CoinOrder::where('stopprice', '>=', $highestbidprice)->where('pair', $pair)->where('Type', 'sell')->where('status', 'stoporder')->select('id', 'user_id', 'pair', 'stopprice', 'ordertype', 'price', 'amount');
		if ($sellStopOrders->count() > 0) {
			$sellStopOrders = $sellStopOrders->get();
			foreach ($sellStopOrders as $sellOrder) {
				$id = $sellOrder->id;
				$user_id = insep_encode($sellOrder->user_id);
				$enc_id = insep_encode($id);
				$pair = $sellOrder->pair;
				$stopprice = $sellOrder->stopprice;
				$ordertype = $sellOrder->ordertype;
				$price = $sellOrder->price;
				$amount = $sellOrder->amount;
				if ($ordertype == 'stoporder') {
					CoinOrder::where('id', $id)->update(['status' => 'active']);

					$returnArr['active'][$user_id][] = $enc_id;

					$returnArr['active_values'][$enc_id] = array('price' => $price, 'amount' => $amount, 'type' => 'sell', 'check_' . $enc_id => $amount);

					$returnArr['stop'][] = TradeModel::initialize_mapping($id, $pair, $returnArr, 1, $recent_volume);
				}
			}
		}
		$buyStopOrders = CoinOrder::where('stopprice', '<=', $lowestaskprice)->where('pair', $pair)->where('Type', 'buy')->where('status', 'stoporder')->select('id', 'pair', 'user_id', 'stopprice', 'ordertype', 'price', 'amount');
		if ($buyStopOrders->count() > 0) {
			$buyStopOrders = $buyStopOrders->get();
			foreach ($buyStopOrders as $buyOrder) {
				$id = $buyOrder->id;
				$user_id = insep_encode($buyOrder->user_id);
				$enc_id = insep_encode($id);
				$pair = $buyOrder->pair;
				$stopprice = $buyOrder->stopprice;
				$ordertype = $buyOrder->ordertype;
				$price = $buyOrder->price;
				$amount = $buyOrder->amount;
				if ($ordertype == 'stoporder') {
					CoinOrder::where('id', $id)->update(['status' => 'active']);
					$returnArr['active'][$user_id][] = $enc_id;

					$returnArr['active_values'][$enc_id] = array('price' => $price, 'amount' => $amount, 'type' => 'buy', 'check_' . $enc_id => $amount);

					$returnArr['stop'][] = TradeModel::initialize_mapping($id, $pair, $returnArr, 1, $recent_volume);
				}
			}
		}
		return $returnArr;
	}
	
	public static function getParticularsellorders($buyPrice, $buyuserId, $pair) 
	{
		$query = CoinOrder::where('pair', $pair)->where('user_id', '!=', $buyuserId)->where('Type', 'sell')->where('Price', '<=', $buyPrice)->whereIn('status', ['active', 'partially'])->orderBy('Price', 'asc')->get();
		if (!$query->isEmpty()) {
			return $query;
		} else {
			return false;
		}
	}
	
	public static function getParticularbuyorders($sellPrice, $selluserId, $pair) 
	{
		$query = CoinOrder::where('pair', $pair)->where('user_id', '!=', $selluserId)->where('Type', 'buy')->where('Price', '>=', $sellPrice)->whereIn('status', ['active', 'partially'])->orderBy('Price', 'desc');
		if ($query->count() >= 1) {
			return $query->get();
		} else {
			return false;
		}
	}
	
	public static function checkOrdertemp($id, $type) 
	{
		$query = OrderTemp::where($type, $id)->where('cancel_id', NULL)->select(DB::raw('SUM(filledAmount) as totalamount'));
		if ($query->count() >= 1) {
			$row = $query->first()->totalamount;
			return $row;
		} else {
			return false;
		}
	}
	
	public static function checkAskPrice($id, $type) 
	{
		$orders = OrderTemp::where($type, $id)->where('cancel_id', NULL)->select('askPrice', 'filledAmount')->get();
		$total = 0;
		if (!$orders->isEmpty()) {
			foreach ($orders as $order) {
				$total += $order->askPrice * $order->filledAmount;
			}
		}
		return $total;
	}
	
	public static function ordercompletetype($orderId, $type, $inserted, $userId, $another_id) 
	{
		TradeModel::removeOrder($orderId, $inserted, $another_id);
		if ($type == "buy") {
			$data = array('buyerStatus' => "active");
			OrderTemp::where('id', $inserted)->where('buyorderId', $orderId)->update($data);
		} else {
			$data = array('sellerStatus' => "active");
			OrderTemp::where('id', $inserted)->where('sellorderId', $orderId)->update($data);
		}
		return true;
	}
	
	public static function orderpartialtype($orderId, $type, $inserted, $another_id) 
	{
		TradeModel::partial_balanceupdate($orderId, $inserted, $another_id);
		return true;
	}
	
	public static function removeOrder($id, $inserted, $another_id) 
	{
		DB::enableQueryLog();
		$updatedata = array('status' => "filled");
		$query = CoinOrder::where('id', $id)->update($updatedata);
		if ($query) {
			$findfee_parm = self::find_fee_type($id, $another_id);

			$trade = CoinOrder::where('id', $id)->first();
			$tradetradeId = $trade->id;
			$tradeuserId = $trade->user_id;
			$tradePrice = $trade->Price;
			$tradeAmount = $trade->Amount;
			$tradeFee = $trade->Fee;
			$tradeType = $trade->Type;

			$orderType = $trade->ordertype;
			$tradeTotal = $trade->Total;
			$tradepair = $trade->pair;
			$orderDate = $trade->created_at;
			$orderTime = $trade->updated_at;
			$scurrId = $trade->firstCurrency;
			$fcurrId = $trade->secondCurrency;
			$cur = get_pair($tradepair);
			$f_currId = $cur->to_symbol_id;
			$s_currId = $cur->from_symbol_id;
			$order = OrderTemp::where('id', $inserted)->select('filledAmount', 'askPrice')->first();
			if ($tradeType == "buy") {
				$firstbal = Wallet::getBalance($tradeuserId, $f_currId);
				$filledAmt = $order->filledAmount;
				$askPrice = $order->askPrice;
				$feePer = $findfee_parm;
				$total = $filledAmt * $askPrice;
				$fees = $filledAmt * $feePer / 100;
				$filledAmt_with_fee = $filledAmt - $fees;
				$remaining_balance = $firstbal + $filledAmt_with_fee;
				$fee_cur = $f_currId;
				$fee_cur_name = $fcurrId;
				
				$result = DB::transaction(function () use ($tradeuserId, $f_currId, $remaining_balance, $inserted, $fees, $id) {
					Wallet::updateBalance($tradeuserId, $f_currId, $remaining_balance);
					OrderTemp::where('id', $inserted)->update(array('buy_fee' => $fees));
					CoinOrder::where('id', $id)->update(array('Fee' => $fees));
				});

			} elseif ($tradeType == "sell") {
				$secondbal = Wallet::getBalance($tradeuserId, $s_currId);
				$filledAmt = $order->filledAmount;
				$askPrice = $order->askPrice;
				$feePer = $findfee_parm;
				$total = $filledAmt * $askPrice;
				$fees = $total * $feePer / 100;
				$updateTotal = $total - $fees;
				$remaining_balance = $secondbal + $updateTotal;
				$fee_cur = $s_currId;
				$fee_cur_name = $scurrId;
				
				$result = DB::transaction(function () use ($tradeuserId, $s_currId, $remaining_balance, $inserted, $fees, $id) {
					Wallet::updateBalance($tradeuserId, $s_currId, $remaining_balance);
					OrderTemp::where('id', $inserted)->update(array('sell_fee' => $fees));
					CoinOrder::where('id', $id)->update(array('Fee' => $fees));
				});
			}
			$referAmount = 0;
			$referUserId = User::where('id', $tradeuserId)->select('refer_by')->first()->refer_by;
			if ($referUserId != "") {
				$referUserBal = Wallet::getBalance($referUserId, $fee_cur);
				$referFee = TradePairs::where('id', $tradepair)->select('refer_fee')->first()->refer_fee;
				$referAmount = ($fees * $referFee) / 100;
				$referAmount = number_format($referAmount, 8, '.', '');
				$referBal = $referUserBal + $referAmount;
				$remarks = 'referral commision ' . $referAmount . ' ' . $fee_cur_name;
				Referral::create(['user_id' => $tradeuserId, 'refered_by' => $referUserId, 'currency' => $fee_cur_name, 'commision' => $referAmount, 'created_at' => date('Y-m-d H:i:s'), 'remarks' => $remarks]);

				Wallet::updateBalance($referUserId, $fee_cur, $referBal);
			}
			$profitAmt = $fees - $referAmount;
			$theftdata = array(
				'user_id' => $tradeuserId,
				'theftAmount' => $profitAmt,
				'theftCurrency' => $fee_cur_name,
				'type' => ucfirst($tradeType) . ' Fees',
			);

			CoinProfit::create($theftdata);

			return true;
		} else {
			return false;
		}
	}
	// partial balance updation
	public static function partial_balanceupdate($id, $inserted, $another_id) 
	{
		$trade = CoinOrder::where('id', $id)->first();
		$findfee_parm = self::find_fee_type($id, $another_id);
		$userId = $trade->user_id;
		$tradetradeId = $trade->id;
		$fee_per = $trade->maker_fee_per;
		$Price = $trade->Price;
		$tradeType = $trade->Type;
		$tradeAmount = $trade->Amount;
		$tradeTotal = $trade->Total;
		$ordertype = $trade->ordertype;
		$tradepair = $trade->pair;
		$scurrId = $trade->firstCurrency;
		$fcurrId = $trade->secondCurrency;
		$cur = get_pair($tradepair);
		$f_currId = $cur->to_symbol_id;
		$s_currId = $cur->from_symbol_id;
		if ($tradeType == "buy") {
			$order = OrderTemp::where('id', $inserted)->select('filledAmount', 'askPrice')->first();
			$firstbal = Wallet::getBalance($userId, $f_currId);
			$filledAmt = $order->filledAmount;
			$askPrice = $order->askPrice;
			$feePer = $findfee_parm;
			$fee_cur = $f_currId;
			$fee_cur_name = $fcurrId;
			
			$total = $filledAmt * $askPrice;
			$fees = $filledAmt * ($feePer / 100);
			$filledAmt_with_fee = $filledAmt - $fees;
			$remaining_balance = $firstbal + $filledAmt_with_fee;
			DB::transaction(function () use ($userId, $f_currId, $remaining_balance, $inserted, $fees, $id) {
				Wallet::updateBalance($userId, $f_currId, $remaining_balance);
				OrderTemp::where('id', $inserted)->update(array('buy_fee' => $fees));
				CoinOrder::where('id', $id)->update(['Fee' => $fees]);
			});
		} else if ($tradeType == "sell") {
			$order = OrderTemp::where('id', $inserted)->select('filledAmount', 'askPrice', 'fee_per')->first();
			$secondbal = Wallet::getBalance($userId, $s_currId);

			$filledAmt = $order->filledAmount;
			$askPrice = $order->askPrice;
			$feePer = $findfee_parm;
			
			$fee_cur = $s_currId;
			$fee_cur_name = $scurrId;
			$total = $filledAmt * $askPrice;
			$fees = $total * ($feePer / 100);
			$updateTotal = $total - $fees;
			$remaining_balance = $secondbal + $updateTotal;
			DB::transaction(function () use ($userId, $s_currId, $remaining_balance, $inserted, $fees, $id) {
				Wallet::updateBalance($userId, $s_currId, $remaining_balance);
				OrderTemp::where('id', $inserted)->update(array('sell_fee' => $fees));
				CoinOrder::where('id', $id)->update(['Fee' => $fees]);
			});

		}
		$referAmount = 0;
		$referUserId = User::where('id', $userId)->select('refer_by')->first()->refer_by;
		if ($referUserId != "") {
			$referUserBal = Wallet::getBalance($referUserId, $fee_cur);
			$referFee = TradePairs::where('id', $tradepair)->select('refer_fee')->first()->refer_fee;
			$referAmount = ($fees * $referFee) / 100;
			$referAmount = number_format($referAmount, 8, '.', '');
			$referBal = $referUserBal + $referAmount;
			$remarks = 'referral commision ' . $referAmount . ' ' . $fee_cur_name;
			Referral::create(['user_id' => $userId, 'refered_by' => $referUserId, 'currency' => $fee_cur_name, 'commision' => $referAmount, 'created_at' => date('Y-m-d H:i:s'), 'remarks' => $remarks]);
			Wallet::updateBalance($referUserId, $fee_cur, $referBal);

		}
		$profitAmt = $fees - $referAmount;
		$theftdata = array(
			'user_id' => $userId,
			'theftAmount' => $profitAmt,
			'theftCurrency' => $fee_cur_name,
			'type' => ucfirst($tradeType) . ' Fees',
		);
		CoinProfit::create($theftdata);
		return true;
	}
    //get all market orders
	public static function getMarketOrders($userId, $first, $second, $type) 
	{
		if ($type == 'Buy') {
			$order_by = 'desc';
			$id = 'buyorderId';
		} else {
			$order_by = 'asc';
			$id = 'sellorderId';

		}
		$query = 'SELECT CO.id as id, CO.user_id as user_id, CO.Price as Price, CO.Amount as Amount, CO.Fee as Fee, CO.maker_fee_per as fee_per, CO.Total as Total, CO.status as status, SUM(OT.filledAmount) as filledAmount FROM tmaitb_redor_nioc as CO LEFT JOIN tmaitb_pmetredor as OT on OT.' . $id . ' = CO.id where CO.firstCurrency = "' . $first . '" and CO.secondCurrency = "' . $second . '" and CO.Type = "' . $type . '" and CO.status in ("active", "partially") and CO.user_id != ' . $userId . ' group by CO.id order by CO.Price asc';
		$result = DB::select(DB::raw($query));
		return (!is_null($result)) ? $result : false;
	}
    // get filled amount for buy and sell
	public static function checkFilledAmount($id, $type) 
	{
		if ($type == "buy") {
			$query = OrderTemp::where('buyorderId', $id)->select(DB::raw('SUM(filledAmount) as totalamount'))->first();
		} else {
			$query = OrderTemp::where('sellorderId', $id)->select(DB::raw('SUM(filledAmount) as totalamount'))->first();
		}
		return (!is_null($query)) ? $query->totalamount : false;
	}
	// Market Mapping
	public static function market_mapping($res, $api = 0) 
	{
		$buy = CoinOrder::where('id', $res)->first();
		if ($buy) {
			$pair_id = $buy->pair;
			$cur = get_pair($pair_id);
			$f_currId = $cur->to_symbol_id;
			$s_currId = $cur->from_symbol_id;
			if ($buy->Type == 'buy') {
				$final = "";
				$buyorderId = $buy->id;
				$buySumamount = TradeModel::checkOrdertemp($buyorderId, 'buyorderId');
				$buyAmount = (float) $buy->Amount;
				if ($buySumamount) {if (trim($buyAmount) == trim($buySumamount)) {return;}}
				$buyuserId = $buy->user_id;
				$buyPrice = lowestaskprice($pair_id);
				$buyOrertype = $buy->ordertype;
				$buyPrice = (float) $buyPrice;
				$pair = $buy->pair;
				$Total = $buy->Total;
				$Fee = $buy->Fee;

				$fetchsellRecords = TradeModel::getParticularsellorders($buyPrice, $buyuserId, $pair, $buyOrertype);
				if ($fetchsellRecords) {

					$k = 0;
					foreach ($fetchsellRecords as $sell) {
						$sBalance = Wallet::getBalance($buyuserId, $s_currId);
						$k++;
						$sellorderId = $sell->id;
						$selluserId = $sell->user_id;
						$sellPrice = $sell->Price;
						$sellOrdertype = $sell->ordertype;
						$sellAmount = $sell->Amount;
						$sellPrice = (float) $sellPrice;
						$sellAmount = (float) $sellAmount;
						$pair = $sell->pair;
						$sellstatus = $sell->status;
						$Total1 = $sell->Total;
						$Fee1 = $sell->Fee;
						$fee_per = $sell->fee_per;
						$fcurrId = $sell->firstCurrency;
						$scurrId = $sell->secondCurrency;
						$sellSumamount = TradeModel::checkOrdertemp($sellorderId, 'sellorderId');
						if ($sellSumamount) {
							$approxiAmount = $sellAmount - $sellSumamount;
							$approxiAmount = number_format($approxiAmount, 8, '.', '');
						} else {
							$approxiAmount = $sellAmount;
						}
						$buySumamount = $checkBuyTemp = TradeModel::checkOrdertemp($buyorderId, 'buyorderId');
						if ($buySumamount) {
							$buyFilledAmount = $buySumamount;
							$buySumamount = $buyAmount - $buyFilledAmount;
							$buySumamount = number_format($buySumamount, 8, '.', '');
						} else {
							$buyFilledAmount = 0;
							$buySumamount = $buyAmount;
						}
						if (trim($approxiAmount) >= trim($buySumamount)) {
							$amount = $buySumamount;
						} else {
							$amount = $approxiAmount;
						}
						$sellTotal = $amount * $sellPrice;
						$trade_fee = $sellTotal * $fee_per / 100;
						$matketTotal = $sellTotal + $trade_fee;
						
						if ($sBalance < $matketTotal) {
							$calFee = $sBalance * $fee_per / 100;
							$checkBal = $sBalance - $calFee;
							if ($checkBal < $sellPrice) {
								
								if($buyOrertype!='market'){
									if ($checkBuyTemp) {
										CoinOrder::where('id', $res)->update(['status' => 'filled']);
									} else {
										CoinOrder::where('id', $res)->update(['status' => 'noorder']);
									}
									return;
								}
								else
								{
									$calAmount = $sBalance / ((1 + $fee_per / 100) * $sellPrice);
									$calAmount = number_format($calAmount, 8, '.', '');
									$tradeFee = ($calAmount * $sellPrice) * $fee_per / 100;
									$tradeFee = number_format($tradeFee, 8, '.', '');
									$finalAmount = ($calAmount * $sellPrice) + $tradeFee;
									$finalAmount = number_format($finalAmount, 8, '.', '');
									$calAmount = number_format($calAmount + $buyFilledAmount, 8, '.', '');
									if($calAmount > 0){
										$updateCoinOrder = CoinOrder::where('id', $res)->update(['Price' => $sellPrice, 'Amount' => $calAmount, 'Fee' => $tradeFee, 'Total' => $finalAmount]);
										$remaining_balance = $sBalance - $finalAmount;
										CoinOrder::where('id', $res)->update(['status' => 'active']);
										Wallet::updateBalance($buyuserId, $s_currId, $remaining_balance);
										TradeModel::initialize_mapping($res, $pair);
										return;
									}
								}
							} else {
								$calAmount = $sBalance / ((1 + $fee_per / 100) * $sellPrice);
								$calAmount = number_format($calAmount, 8, '.', '');
								$tradeFee = ($calAmount * $sellPrice) * $fee_per / 100;
								$tradeFee = number_format($tradeFee, 8, '.', '');
								$finalAmount = ($calAmount * $sellPrice) + $tradeFee;
								$finalAmount = number_format($finalAmount, 8, '.', '');
								$calAmount = number_format($calAmount + $buyFilledAmount, 8, '.', '');
								$updateCoinOrder = CoinOrder::where('id', $res)->update(['Price' => $sellPrice, 'Amount' => $calAmount, 'Fee' => $tradeFee, 'Total' => $finalAmount]);
								$remaining_balance = $sBalance - $finalAmount;
								Wallet::updateBalance($buyuserId, $s_currId, $remaining_balance);
								TradeModel::initialize_mapping($res, $pair);
								return;
							}
						} else {
							$remaining_balance = $sBalance - $matketTotal;

							Wallet::updateBalance($buyuserId, $s_currId, $remaining_balance);
						}


						if (trim($approxiAmount) != 0 && trim($buySumamount) != 0) {
							$date = date('Y-m-d');
							$time = date("H:i:s");
							$datetime = date("Y-m-d H:i:s");
							$data = array(
								'sellorderId' => $sellorderId,
								'sellerUserId' => $selluserId,
								'askAmount' => $sellAmount,
								'firstCurrency' => $fcurrId,
								'secondCurrency' => $scurrId,
								'askPrice' => $sellPrice,
								'filledAmount' => $amount,
								'buyorderId' => $buyorderId,
								'buyerUserId' => $buyuserId,
								'sellerStatus' => "inactive",
								'buyerStatus' => "inactive",
								"pair" => $pair,
								// "fee_per" => $fee_per,
								"datetime" => $datetime,
							);
							$inserted = OrderTemp::create($data);
							TradePairs::where('id', $pair)->update(['last_price' => $sellPrice]);
							$theftprice = 0;
							if ($inserted) {
								$feeTotal = $amount * $sellPrice;
								$feeAmt = ($feeTotal * $fee_per) / 100;
								//complete seller order
								if (trim($approxiAmount) == trim($amount)) {
									TradeModel::ordercompletetype($sellorderId, "sell", $inserted->id, $selluserId, $buyorderId);
								} else {
									TradeModel::orderpartialtype($sellorderId, "sell", $inserted->id, $buyorderId);
									$updatedata = array('status' => "partially");
									CoinOrder::where('id', $sellorderId)->update($updatedata);
								}
								if ((trim($approxiAmount) == trim($buySumamount)) || (trim($approxiAmount) > trim($buySumamount))) {
									TradeModel::ordercompletetype($buyorderId, "buy", $inserted->id, $buyuserId, $sellorderId);
								} else {
									TradeModel::orderpartialtype($buyorderId, "buy", $inserted->id, $sellorderId);
									$updatedata1 = array('status' => "partially");
									CoinOrder::where('id', $buyorderId)->update($updatedata1);
								}
								self::checkStopOrder($pair_id);
							}
						} else {
							break;
						}
					}
				} else {
					if ($buySumamount) {
						CoinOrder::where('id', $res)->update(['status' => 'filled']);
						return;
					} else {
						CoinOrder::where('id', $res)->update(['status' => 'noorder']);
						if ($api == '1') {
							$data = array('status' => '0', 'message' => 'No Sell orders available');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						} else {
							echo "No Sell orders available";exit();
						}
					}
				}
			} elseif ($buy->Type == 'sell') {
				$sell = $buy;
				$final = "";
				$sellorderId = $sell->id;
				$selluserId = $sell->user_id;
				$sellPrice = highestbidprice($pair_id);
				$sellOrertype = $sell->ordertype;
				$sellPrice = (float) $sellPrice;
				$sellAmount = (float) $sell->Amount;
				$sellSumamount = TradeModel::checkOrdertemp($sellorderId, 'sellorderId');
				if ($sellSumamount) {if (trim($sellAmount) == trim($sellSumamount)) {return;}}
				$pair = $sell->pair;
				$Total1 = $sell->Total;
				$Fee1 = $sell->Fee;
				$fcurrId = $sell->firstCurrency;
				$scurrId = $sell->secondCurrency;
				$fetchbuyRecords = TradeModel::getParticularbuyorders($sellPrice, $selluserId, $pair);
				if ($fetchbuyRecords) {
					$k = 0;
					foreach ($fetchbuyRecords as $buy) {
						$fBalance = Wallet::getBalance($selluserId, $f_currId);
						$k++;
						$buyorderId = $buy->id;
						$buyuserId = $buy->user_id;
						$buyPrice = $buy->Price;
						$buyOrdertype = $buy->ordertype;
						$buyAmount = $buy->Amount;
						$buyPrice = (float) $buyPrice;
						$buyAmount = (float) $buyAmount;
						$pair = $buy->pair;
						$buystatus = $buy->status;
						$Total = $buy->Total;
						$Fee = $buy->Fee;
						$fee_per = $buy->fee_per;
						$fcurrId = $buy->firstCurrency;
						$scurrId = $buy->secondCurrency;
						$buySumamount = TradeModel::checkOrdertemp($buyorderId, 'buyorderId');
						$sellSumamount = TradeModel::checkOrdertemp($sellorderId, 'sellorderId');
						if ($buySumamount) {
							$approxiAmount = $buyAmount - $buySumamount;
							$approxiAmount = number_format($approxiAmount, 8, '.', '');
						} else {
							$approxiAmount = $buyAmount;
						}
						if ($sellSumamount) {
							$sellSumamount = $sellAmount - $sellSumamount;
							$sellSumamount = number_format($sellSumamount, 8, '.', '');
						} else {
							$sellSumamount = $sellAmount;
						}
						if (trim($approxiAmount) >= trim($sellSumamount)) {
							$amount = $sellSumamount;
						} else {
							$amount = $approxiAmount;
						}
						if (trim($approxiAmount) < trim($sellSumamount)) {
							$remaining_balance = $fBalance - $approxiAmount;
							Wallet::updateBalance($selluserId, $f_currId, $remaining_balance);
						} else {
							$remaining_balance = $fBalance - $sellSumamount;
							Wallet::updateBalance($selluserId, $f_currId, $remaining_balance);
						}
						if (trim($approxiAmount) != 0 && trim($sellSumamount) != 0) {
							$date = date('Y-m-d');
							$time = date("H:i:s");
							$datetime = date("Y-m-d H:i:s");
							$data = array(
								'sellorderId' => $sellorderId,
								'sellerUserId' => $selluserId,
								'askAmount' => $sellAmount,
								'firstCurrency' => $fcurrId,
								'secondCurrency' => $scurrId,
								'askPrice' => $sellPrice,
								'filledAmount' => $amount,
								'buyorderId' => $buyorderId,
								'buyerUserId' => $buyuserId,
								'sellerStatus' => "inactive",
								'buyerStatus' => "inactive",
								"pair" => $pair,
								"fee_per" => 0,
								"datetime" => $datetime,
							);
							$inserted = OrderTemp::create($data);
							TradePairs::where('id', $pair)->update(['last_price' => $sellPrice]);
							$theftprice = 0;
							if ($inserted) {
								$feeTotal = $amount * $sellPrice;
								$feeAmt = ($feeTotal * $fee_per) / 100;
								//complete seller order
								if (trim($approxiAmount) == trim($amount)) {
									TradeModel::ordercompletetype($buyorderId, "buy", $inserted->id, $buyuserId, $sellorderId);
								} else {
									TradeModel::orderpartialtype($buyorderId, "buy", $inserted->id, $sellorderId);
									$updatedata1 = array('status' => "partially");
									CoinOrder::where('id', $buyorderId)->update($updatedata1);
								}
								if ((trim($approxiAmount) >= trim($sellSumamount))) {
									TradeModel::ordercompletetype($sellorderId, "sell", $inserted->id, $selluserId, $buyorderId);
								} else {
									TradeModel::orderpartialtype($sellorderId, "sell", $inserted->id, $buyorderId);
									$updatedata1 = array('status' => "partially");
									CoinOrder::where('id', $sellorderId)->update($updatedata1);
								}
								self::checkStopOrder($pair_id);
							}
						} else {
							break;
						}
					}
				} else {
					if ($sellSumamount) {
						CoinOrder::where('id', $res)->update(['status' => 'filled']);
						return;
					} else {
						CoinOrder::where('id', $res)->update(['status' => 'noorder']);
						if ($api == '1') {
							$data = array('status' => '0', 'message' => 'No Buy orders available');
							echo json_encode($data, JSON_FORCE_OBJECT);
							exit;
						} else {
							echo "No Buy orders available";exit();
						}
					}
				}
			}
			TradeModel::market_mapping($res, $api);
		}
		$updateStatus = array('status' => 'filled');
		CoinOrder::where('ordertype', 'market')->whereIn('status', ['active', 'partially'])->update($updateStatus);
	}
   
	public static function chartData($pair, $from, $to) 
	{

		


		   $getresolution = $_GET['resolution'];
		   if ($getresolution == "1D") {
		   	$resolution = 24 * 60 * 60;
		   } else if ($getresolution == "1W") {
		   	$resolution = 7 * 24 * 60 * 60;
		   } else if ($getresolution == "1M") {
		   	$resolution = 30 * 24 * 60 * 60;
		   } else {
		    // 5 min 15 min 30 min 60 min 1 hrs 3 hrs 6 hrs 12 hrs
		   	$resolution = $getresolution * 60;
		   }
		   $groupBy = (is_numeric($resolution)) ? "round(unix_timestamp(ot.created_at) div " . ($resolution) .")" : 'DATE_FORMAT(ot.created_at, "%Y-%m-%d")';
		   if($getresolution == 1){
		   	$from = strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 10 days')));
		   }
		   $from_date = date('Y-m-d H:i:s', $from);
		   $to_date = date('Y-m-d H:i:s', $to);

		   $sql = 'SELECT ot.created_at as t_date,MIN(ot.askPrice) as low,MAX(ot.askPrice) as high,SUM(ot.filledAmount * ot.askPrice) as volume,(SELECT nt.askPrice FROM tmaitb_pmetredor as nt WHERE nt.id=MIN(ot.id)) as open,(SELECT nt.askPrice FROM tmaitb_pmetredor as nt WHERE nt.id=MAX(ot.id)) as close FROM tmaitb_pmetredor as ot WHERE ot.pair="'.$pair.'" AND (DATE_FORMAT(ot.created_at, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$from_date.'" AND "'.$to_date.'") AND ot.cancel_id IS NULL GROUP BY ' . $groupBy . ' ORDER BY ot.created_at ASC';

		   $query = DB::connection('mysql')->select($sql);

		   
		   if (!$query) {
		   	$out = array('s' => 'no_data');
		   	return json_encode($out, JSON_PRETTY_PRINT);
		   }
		   $i = 0;
		   foreach ($query as $que) {
		   	$o[] = $que->open;
		   	$c[] = $que->close;
		   	$l[] = $que->low;
		   	$h[] = $que->high;
		   	$v[] = $que->volume;
		   	$t[] = strtotime($que->t_date);
		   	$i++;
		   }
		   $out = array('t' => $t, 'o' => $o, 'h' => $h, 'l' => $l, 'c' => $c, 'v' => $v, 's' => 'ok');

		   return json_encode($out, JSON_PRETTY_PRINT);
    }




	public static function find_fee_type($id, $id1) 
	{

		$query = CoinOrder::where('id', $id1)->select('maker_fee_per')->first();
		if ($id > $id1) {
			$query = CoinOrder::where('id', $id)->select('taker_fee_per')->first();

			return $query->taker_fee_per;
		} else {

			return $query->maker_fee_per;
		}
	}
	public static function createArray($query, $check_active = array()) 
	{
		$stop_orders = $type = '';
		$returnActive = array();
		if (isset($check_active) && !empty($check_active)) {
			$returnActive = $check_active;
		}
		$data_result = $active_arr = $active_order_array = $history = $partial_array = $history_data = array();
		foreach ($query as $key => $valueArr) {

			if (isset($valueArr['active_order'])) {

				$active_orderArr = isset($valueArr['active_order']) ? $valueArr['active_order'] : '';
				if ($active_orderArr) {
					foreach ($active_orderArr as $key => $active_order) {

						
						foreach ($active_order as $key1 => $value) {

							$active['amount'] = rtrim(rtrim(sprintf('%.8F', $value['amount']), '0'), ".");
							$active['price'] = rtrim(rtrim(sprintf('%.8F', $value['price']), '0'), ".");
							$total = $value['amount'] * $value['price'];
							$active['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
							$active['datetime'] = date('Y-m-d H:i');
							$active['id'] = $value['order_id'];
							$rem = 'check_' . $value['order_id'];
							$active['rem'] = 'rem_' . $value['order_id'];

							if (isset($returnActive[$rem])) {
								$rem_amount = $returnActive[$rem]['amount'];
								$returnActive[$rem] = array('amount' => $rem_amount + $active['amount'], 'price' => $active['price']);
							} else {
								$returnActive[$rem] = array('amount' => $active['amount'], 'price' => $active['price']);
							}
							$user_id = $active['user_id'] = $value['user_id'];
							$type = strtolower($value['type']);

							// $type = $type == 'buy' ? 'sell' : 'buy';
							$ordertype = strtolower($value['ordertype']);

							if ($type == "buy") {
								$type_val = trans('app_lang.buy_tab');
							} else {
								$type_val = trans('app_lang.sell_tab');
							}
							if ($ordertype == 'limit') {
								$order_ty_text = trans('app_lang.limit_tab');
							} else if ($ordertype == 'stoporder') {
								$order_ty_text = trans('app_lang.stoporder_tab');
							}

							$active['type'] = $type_val;
							$active['ordertype'] = $order_ty_text;
							$active_arr[] = $active;
						}
					}
				}
				//history
				if (isset($valueArr['history'])) {
					foreach ($valueArr['history'] as $value) {
						$history_data['amount'] = $value['filledAmount'];
						$history_data['price'] = $value['askPrice'];
						$history_data['datetime'] = $value['datetime'];
						$history[] = $history_data;
					}
				}

				$type_partial = ($type == 'buy') ? 'buy' : 'sell';
				if (isset($valueArr['partial'])) {
					//to minus price

					if (isset($valueArr['partial'][$type_partial])) {
						if (count($valueArr['partial'][$type_partial])) {
							$active_order_array = $active_arr;
							foreach ($valueArr['partial'][$type_partial] as $key => $value) {
								$partial['amount'] = $value['amount'];
								$partial['price'] = $value['price'];

								if ($partial['amount'] && $partial['price']) {
									$partial_array[] = $partial;
								}
							}
						}
					}
					$data['new_array'] = '0';
					$data['active'] = '0';
					$type = ($type == 'buy') ? 'sell' : 'buy';

					//to add price
					if (isset($valueArr['partial'][$type]['amount'])) {
						if ($valueArr['partial'][$type]['amount'] == 0) {

							$data['new_array'] = '0';
							$data['active'] = '0';
						} else {

							$amount = $valueArr['partial'][$type]['amount'];
							$price = $valueArr['partial'][$type]['price'];

							$data['new_array'] = array('0' => array('amount' => $amount, 'price' => $price));
							$total = $amount * $price;
							$data['active'] = $active_arr[0];
							$data['active']['amount'] = rtrim(rtrim(sprintf('%.8F', $amount), "."), ".");
							$data['active']['price'] = rtrim(rtrim(sprintf('%.8F', $price), "."), ".");
							$data['active']['total'] = rtrim(rtrim(sprintf('%.8F', $total), '0'), ".");
						}
					}

				}
				$data['type'] = $type;
				$data['orders'] = 1;
				$data['datetime'] = date('Y-m-d H:i');
				// $data['existing_array'] = 0;
				$data['existing_array'] = is_array($partial_array) ? $partial_array : 0;
				$data['existing_type'] = $type_partial;
				$data['active_order'] = is_array($active_order_array) ? $active_order_array : 0;
				$data['tradehistory'] = $history;
				$stop_orders = array();
				if (isset($valueArr['stop_orders'])) {

					foreach ($valueArr['stop_orders'] as $stop_arr) {

						$filled = self::createArray($stop_arr['stop'], $returnActive);
						$stop_orders['filled'][] = $filled;
					}
					$data['check_order'] = $returnActive;
					$data['stop_orders'] = $stop_orders;
				}

				$data_result[] = $data;

			}
		}
		return $data_result;

	}
}