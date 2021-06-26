<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Sats;
use App\Model\CoinOrder;
use App\Model\ConsumerVerification;
use App\Model\TradePairs;
use App\Model\SiteSettings;
use App\Model\Currency;
use App\Model\Deposit;
use App\Model\Details;
use App\Model\HelpCentre;
use App\Model\OrderTemp;
use App\Model\TradeModel;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Tokens;
use App\Model\CoinAddress;
use App\Model\Notificationlist;
use App\Model\Admindeposit;
use App\Model\ExchangePairs;
use Config;
use DB;
use URL;
use Batch;


class Cron extends Controller {						
	public function __construct() {
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				
	}

	public function movetokens()
	{

		/*echo "<br/>";
		$adrss = trim(strtolower('0x2441a49Cfb1b5cE0c28d6c67f8ac96878DD88D24'));

		// 
		echo insep_encode($adrss);
		echo "<br/>";
		echo "<br/>";
		echo "<br/>";
		echo "<br/>";
		$adrss = 'bamtSgMtYC32rF3UYsyjpX91qSXMh0TmcrpVVtj7b-HcpUU1ZRaTy-pLdzgX15LaQWz3nqk_TZsdi1AMakLLdg';
		echo insep_decode($adrss);
		echo "<br/>";
		exit;*/

		$currencySymbol = 'BoomCoin';
		$curData = Currency::where('symbol', $currencySymbol)->select('symbol','contract_address','decimalnum')->first();		

		  	if (count($curData) != 0)
		  	{
		  		$contractAddress = $curData->contract_address;
				$decimal = $curData->decimalnum;
				$symbol = $curData->symbol;

				$toAddress = Config::get('boomc.adminAddress'); //coinAddr.bep.adminaddress
				$toAddress = insep_decode($toAddress);
				$privateKey = Config::get('boomc.privateKey');
				$privateKey = insep_decode($privateKey);

				//$currencySymbol = 'USDT'; // Need to remove
				$userdeposit_table = array(
					"currency" => $currencySymbol,
					"move_status"=> 0
				);

				$userdeposit_table_or = array(
					"currency" => $currencySymbol,
					"move_status"=> 1
				);

				$depositData = Deposit::where($userdeposit_table)->orWhere($userdeposit_table_or)->get();
				
				/*deposit.find(userdeposit_table).exec(function (err2, depositData) { */
					if (count($depositData) > 0)
					{
							foreach($depositData as $key => $trans)
							{
								
								
								$user_id = $trans->user_id;
								$amount = $trans->amount;
								$currency = $trans->currency;
								$address = $trans->crypto_address;
								$dep_id = $trans->id;
								$move_status = $trans->move_status;

								$addrData = CoinAddress::where(array('address' => insep_encode($address), "user_id" => $user_id, "currency" => 'BoomCoin'))->first();
								
							/*userAddress.find({ "address":address,"user_id" : mongoose.mongo.ObjectId(user_id) ,"currency": 'BEP'}).select('secret').exec(function(error,addrData){*/
								if(count($addrData) > 0)
								{	
									$address = insep_decode($addrData->address);
									$secret = insep_decode($addrData->secret);

									$objData = array(
										'tokenAddress' => $contractAddress,
									  	'walletAddress' => $address
									);
									
									$tokenbal = $this->bscTokenBalance($objData);
									

									/*common.bscTokenBalance(objData,function(tokenbal){*/
									/*console.log('tokenbal ',tokenbal);*/
									if(isset($tokenbal))
									{
										
											$sendbal = $tokenbal;
											$getDecimals = $decimal + 1;          
											$decimals = str_pad('1',$getDecimals,0, STR_PAD_RIGHT);
											$tokenbal = $tokenbal / $decimals;
											
											if($tokenbal > 0)
											{
												$bebbal = $this->bnbBalance($objData);
												if($bebbal > 0)
												{
													$bep_balance = $bebbal;
												}
												else
												{
													$bep_balance = 0;
												}

													$tokenmove = $this->checkBNBfee($bep_balance);

													if(isset($tokenmove))
													{
														$sendData = array(
															'privateKey' => $secret,
														  	'toAddress' => $toAddress,
														  	'value' => $sendbal,
														  	'contractAddress' => $contractAddress,
														  	'fromAddress' => $address
														);

														$txhash = $this->sendBNBtoken($sendData);
														if($txhash)
														{

															$depositData = Deposit::where(array('id' => $dep_id))->first();
															$depositData->move_status = 2;
															if($depositData->save())
															{

												           		$array_deposit = array(
												           			'currency' => $currencySymbol,
												           			'status' => 'completed',
												           			'transaction_id' => $txhash,
												           			'address' => $address,
												           			'amount' => $tokenbal,
												           			'blockno' => 0,
												           			'confirmation'=>0
												           		);
													            Admindeposit::create($array_deposit);
													            echo "Deposit Saved";

																/*var payments = {
													 				"user_id": mongoose.mongo.ObjectId(user_id),
													 				"crypto_address": address,
													 				"amount": +tokenbal,
													 				"currency": currencySymbol,
													 				"txnid": txhash,
													 				"status": "completed"
													 			}; 
																moveToken.create(payments, function (dep_err, dep_res) {});*/

															}


															/*deposit.updateOne({"_id": mongoose.mongo.ObjectId(dep_id)}, { $set: {"move_status":'2'} }).exec(function(err,resUpdate)
															{ */
																
															/*});	*/
														}
														else
														{
															echo "No Funds";
														}
															
													}
													else
													{
															
													   if($move_status == 0)
													   {
													   		$secret_admin = $privateKey;
															$sendBNB = array(
																'privateKey' => $secret_admin,
															  	'toAddress' => $address,
															  	'value' => 0.001,
															  	'fromAddress' => $toAddress
															);

															$txhash = $this->sendBNB($sendBNB);
															  if(isset($txhash))
															  {

																  	$depositData = Deposit::where(array('id' => $dep_id))->first();
																  	$depositData->move_status = 1;
																  	if($depositData->save())
																  	{

																  		$array_deposit = array(
														           			'currency' => $currencySymbol,
														           			'status' => 'completed',
														           			'transaction_id' => $txhash,
														           			'address' => $address,
														           			'amount' => $tokenbal,
														           			'blockno' => 0,
														           			'confirmation'=>0
														           		);
															            Admindeposit::create($array_deposit);
																	  	echo "Deposit Saved 2";
																  	}

																  	/*deposit.updateOne({"_id": mongoose.mongo.ObjectId(dep_id)}, { $set: {"move_status":'1'} }).exec(function(err,resUpdate)
																  	{ 
																		var payments = {
															 				"user_id": mongoose.mongo.ObjectId(user_id),
															 				"crypto_address": address,
															 				"amount": '0.001',
															 				"currency": currencySymbol,
															 				"txnid": txhash,
															 				"status": "completed"
															 			}; 
																		moveToken.create(payments, function (dep_err, dep_res) { });
																	});*/
																	
															  }
															  else
															  {
																echo "No Funds";
															  }
															/*})*/
													   }
													}
											}
									}
								}
								
													
							} //foreach
					}

				
		  	}
		
	}
	public function sendBNBtoken($post_data){

		$cmc_url = getSocketUrl()."/sendBNBtoken";		
		$response = files_get_content_post($cmc_url, $post_data);
		return $response->data;
	}
	public function sendBNB($post_data){

		$cmc_url = getSocketUrl()."/sendBNB";
		$response = files_get_content_post($cmc_url, $post_data);		
		return $response->data;
	}
	public function checkBNBfee($bep_balance)
	{
		$cmc_url = getSocketUrl()."/checkBNBfee";
		$post_data = array(
		    'bep_balance' => $bep_balance
		);
		$response = files_get_content_post($cmc_url, $post_data);
		return $response->data;
	}

	public function bnbBalance($post_data)
	{
		$cmc_url = getSocketUrl()."/bnbBalance";
		$response = files_get_content_post($cmc_url, $post_data);
		return $response->data;
		
	}

	public function bscTokenBalance($post_data)
	{
		$cmc_url = getSocketUrl()."/bscTokenBalance";
		$response = files_get_content_post($cmc_url, $post_data);
		return $response->data;	
	}

	public function generateNewTokenAddress()
	{	
		
		$cmc_url = getSocketUrl()."/generateNewTokenAddress";
		$response = files_get_content($cmc_url);
		if(isset($response->data))
		{
			$data = $response->data;
			print_r($data);
			echo "<br/>";	
			echo $address = $data->address;
			echo "<br/>";
			echo $privateKey = $data->privateKey;
			echo "<br/>";			

		}
	}
	public function bscTokenDeposit()
	{	

		/*echo insep_encode('0x936636b2bf94455eab438c48d040ea5cb02ac072');
		exit;*/
		//0x93b328c301efb5182f5975b55f661527ed3ce3e6

		//address : SJYI9nVRY7OYXjmcRIQfkVcV0O6BVth68dnicNkbDg7c0k-7zBWJZMgyv5X3726GJ0l8PfsnzEbg-146GZ6CKQ
		//secret : g0X1UTxKi8tgsuPz8g6pe_8WHBsiuVv5Y-isLiLczNwhbA6EvLGl4ktZ4_BLCyBHjjDm6xgzhK4aniDAF5gqO6Rw_I-sV4e9NXHofUu5IoBOFaOdifD6bdm-r0MdgL9D

		$currencySymbol = 'BoomCoin';
		$currency = Currency::where('symbol', $currencySymbol)->select('id','name', 'symbol', 'contract_address', 'decimalnum', 'lastblock')->first();

		$adminAddr = Config::get('boomc.adminAddress');
		$adminAddr = insep_decode($adminAddr);
		$privateKey = Config::get('boomc.privateKey');
		$privateKey = insep_decode($privateKey);

		$info = array(
		    'module' => 'account',
		    'action' => 'tokentx',
		    'sort' => 'asc',
		    'contractaddress' => $currency->contract_address,
		    'startblock' => $currency->lastblock,
		    'endblock' => 'latest',
		    'apikey' => '8K4RDKT4IEAX3DFUPZH1MF9JRWISVQS35S'
		);
		$params = http_build_query($info, '', '&');
		//$cmc_url = "https://testnet.bscscan.com/api?".$params;
		$cmc_url = "https://api.bscscan.com/api?".$params;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $cmc_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		$responseTxn = json_decode($output);

		print_r($responseTxn);

		if($responseTxn->message == 'OK' && $responseTxn->status == 1)
		{
			$transactions = $responseTxn->result;

			/*echo "<br/> 127 Adrss ---->";
			echo insep_decode('G-YF33dYqxibCQkB5-Kz8gmFQQ2ZM2KW_zwTL9Ig_PGynqCB3Ssr4_TD1VtsxgLERp-0csBgTfTcI-fAfl7T2g');
			echo "<br/>";*/
			foreach($transactions as $trans)
			{
				$confirmations = $trans->confirmations;
				$lastblock = $trans->blockNumber;
				
				if($confirmations >= 5)
				{	  				
	  				$fromaddress = trim($trans->from);
	  				$txid = $trans->hash;
	  				$value = $trans->value;
	  				$address = strtolower($trans->to);


	  				
	  				

	  				if(strtolower($fromaddress) != strtolower($adminAddr))
	  				{
						$getDecimals = $currency->decimalnum + 1;
						//$decimals = '1'.padEnd($getDecimals,0);
						$decimals = str_pad('1',$getDecimals,0, STR_PAD_RIGHT);						
						$bep_balance = $value / $decimals;
						/*
						0xeed79C0AeFE7e59df636fB9592A711b19E8F5953
						0xeed79c0aefe7e59df636fb9592a711b19e8f5953
						echo "<br/>";
						echo $address.' ----------> '.insep_encode($address);
						//G-YF33dYqxibCQkB5-Kz8gmFQQ2ZM2KW_zwTL9Ig_PGynqCB3Ssr4_TD1VtsxgLERp-0csBgTfTcI-fAfl7T2g
						echo "<br/>";
						echo "<br/>";
						echo "<br/>";
						echo insep_decode("G-YF33dYqxibCQkB5-Kz8gmFQQ2ZM2KW_zwTL9Ig_PGynqCB3Ssr4_TD1VtsxgLERp-0csBgTfTcI-fAfl7T2g");
						echo "<br/>";*/
						$userAddress = CoinAddress::where(array('address' => insep_encode($address), "currency" => $currencySymbol))->first();
						/*echo "<br/>";
						echo "<br/>";
						print_r($userAddress);
						echo "<br/>";
						echo "<br/>";
							if(count($userAddress) > 0)
							{
								echo "<br/>";
								echo "<br/>";
								echo "User Address >>> ".$address." >>>> ".insep_encode($address);
								echo "<br/>";
								echo "<br/>";
							}
							else
							{
								echo "<br/>";
								echo "<br/>";
								echo "User Not Founds >>> ".$address." >>>> ".insep_encode($address);
								echo "<br/>";
								echo "<br/>";
							}*/
							
						
							if(count($userAddress) > 0)
							{
								$user_id = $userAddress->user_id;
								echo "<br/>";
								echo "<br/>";
								echo "<br/>";
								echo "<br/>";
								echo "User Address >>".$address;
								echo "<br/>";
								echo "User ID >>".$user_id;
								
								
								$userdeposit_table = array("transaction_id" => $txid, "currency" => $currencySymbol);
								$depositData = Deposit::where($userdeposit_table)->first();
								if (count($depositData) == 0)
								{
									
									$upBal = updateUserBalance($user_id, $currency->id, $bep_balance);
									if(isset($upBal))
									{

										echo "<br/>";
										echo "TXT ID >>".$txid;
										echo "<br/>";
										echo "Value >>".$bep_balance;
										echo "<br/>";
										echo "<br/>";
										

							 			$payments = array(
							 				"user_id" => $user_id,
							 				"address" => $address,
							 				"amount" => $bep_balance,
							 				"currency" => $currencySymbol,
							 				"transaction_id" => $txid,
							 				"status" => "Completed",
							 				"block_number" => $lastblock,
							 				'created_at' => date("Y-m-d h:i:s"),
							 				'updated_at' => date("Y-m-d h:i:s"),
							 				'remarks' => "Deposit BoomCoin ".$bep_balance,
							 				'confirmation' => $confirmations,
							 				'move_to_admin' => 0,
							 				'order_status' => 0
							 			); 
							 			$depositData = Deposit::create($payments);
							 			if($depositData)
							 			{
							 				echo "<br/>";
							 				echo "Deposited successfully";
							 			}												
									}	
								}
							}
							else
							{
								echo "<br/>";
								echo "<br/>";
								echo "User Address Not Found >>> ".$address." >>>> ".insep_encode($address);
								echo "<br/>";
								echo "<br/>";
							}
	  				}
			  	}
			}
		}

	}
    
	public function convercurr_usd_cron()
	{
		$pair_image = Currency::where('status', '1')->select('symbol', 'id')->get();
		foreach ($pair_image as $key => $value) {
			$price = convercurr_usd($value->symbol, 'USD');
			Currency::where('id', $value->id)->update(array('inr_value' => $price));
		}
	}


	public function convercurr_btc_cron() 
	{
		$pair_image = Currency::where('status', '1')->select('symbol', 'id')->get();
		foreach ($pair_image as $key => $value) {
			$price = convercurr_btc($value->symbol);
			Currency::where('id', $value->id)->update(array('btc_value' => $price));
		}
	}

	public function all_convercurr_cron() 
	{
		$pair_image = ExchangePairs::where('status', '1')->select('from_symbol', 'to_symbol', 'id')->get();
		foreach ($pair_image as $key => $value)
		{			
			$price = convercurr_usd($value->from_symbol, $value->to_symbol);
			if($price > 0)
			{
				ExchangePairs::where('id', $value->id)->update(array('last_price' => $price));
				TradePairs::where(['from_symbol' => $value->from_symbol, 'to_symbol' => $value->to_symbol])->update(array('last_price' => $price));
			}
		}
	}

	
	public function deposits_cron($currency = 'BTC') 
	{
		$coins = new Sats;
		$address = $coins->deposits($currency);		
	}
	
	public function getDepositeth($method, $data = array()) 
	{
		$coins = new Sats;
		$url = '';
		$name = $_SERVER['SERVER_NAME'];
		$data = array("method" => $method, "name" => $name, "keyword" => '', 'data' => $data);
		$data_string = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		
		curl_close($ch);
		$result = json_decode($response, true);



		if ($result['type'] == 'success') {
			return $result['result'];
		} else {

		}

	}
	public function checkT1xn() 
	{

		$res = self::getDepositeth('create', array('key' => '4766154'));
		
		
	}

	function checkTxn() 
	{
		$pairDetails = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 and b.show_home=1 GROUP BY b.id, b.from_symbol");
		
		foreach ($pairDetails as $pairs)
		{
			$lastPrice = $pairs->last_price ? $pairs->last_price : 0;
			$yesterPrice = $pairs->yesterday_price ? $pairs->yesterday_price : 0;
			$clsName = "text-success";
			$arrow = "";
			if ($yesterPrice <= 0) {
				$changePer = 0;
				$arrow = "";
			} else {
				$changePrice = ($lastPrice - $yesterPrice) / $yesterPrice;
				$changePer = $changePrice * 100;
				if (($lastPrice >= $yesterPrice)) {
					$clsName = "text-success";
					$arrow = "+";
				} else {
					$clsName = "text-danger";
					$arrow = "";
				}
			}
			$decimal = 8;
			$yesterPrice = number_format($yesterPrice, 8, '.', '');
			 $changePer = number_format($changePer, 2, '.', ',') . '%';
			
			$volume = number_format($pairs->volume, 4, '.', '');
			$volume = ($volume == null) ? "0.00" : $volume;
			$lastPrice = rtrim(rtrim(sprintf('%.8F', $lastPrice), '0'), ".");
		}
		exit;
		$maxBlock = Details::where('type', 'ETH')->select('block_number')->first();

		$maxBlock = $maxBlock->block_number + 1;
		$getBlocks = self::getDepositeth('blocktransaction', array('block' => $maxBlock));
		
		$transactions = $getBlocks['trans'];
		$lastBlock = $getBlocks['block'];
		if ($lastBlock) {
			Details::where('type', 'ETH')->update(['block_number' => $lastBlock]);
		}

		$blocks = array();
		$toAddr = "";
		if ($transactions) {
			foreach ($transactions as $transaction) {
				foreach ($transaction as $trans) {

					$to = $trans['to'];
					
					if ($to != "") {
						$blocks[strtolower($to)][] = $trans;
						$to = insep_encode($to);
						$toAddr .= "(address = '$to') OR ";
					}
				}
			}
			$condition = rtrim($toAddr, ' OR ');

			$query = DB::table('sserdda_nioc')->select('user_id', 'address');
			$getAddress = $query->whereRaw($condition)->get();

			if ($getAddress) {

				$address = Config::get('sats.ETH.address');
				$ethAdmin = trim($address);
				$ethAdmin = strtolower($ethAdmin);

				$getUsers = $getAddress;
				foreach ($getUsers as $users) {
					$user_id = $users->user_id;
					$account = insep_decode($users->address);
					
					$transactions = $blocks[$account];

					foreach ($transactions as $transaction) {
						$block_number = hexdec($transaction['blockNumber']);
						$address = $transaction['to'];
						$txid = $transaction['hash'];
						$value = hexdec($transaction['value']);
						$amount = $value / 1000000000000000000;
						$from = strtolower($transaction['from']);

						
						
						$already_exist = Deposit::where('transaction_id', $txid)->count();
						if (!$already_exist) {
							$confirmations = self::getDepositeth('checkReceipt', array('hash' => $txid));
							$confirmations = hexdec($confirmations['status']);
							if ($confirmations == 1) {
								$trans = new Sats;
								$updatedeposit = $trans->updateDeposit('ETH', '2', $user_id, $amount, $txid, $address);
								echo 'updateDeposit';

							} else {
								echo "already exist";
							}

						} else {
							echo "already exist";
						}
					}
				}
			}
		}
	}

	public function kycalt() 
	{

		$current = date('Y-m-d H:i:s');
		$t1 = StrToTime($current);

		$query = ConsumerVerification::where('noitacifirev.selfie_status', 0)->where('noitacifirev.id_status', 0)->where('b.status', 1);
		$query->leftJoin('users as b', 'b.id', '=', 'noitacifirev.user_id')->select('b.*');
		$data = $query->get();

		$email = array();
		$sendEmail = '';
		$message = "Thank you for registering with  and verifying your email address. Please login to your account at " . URL('/login') . " to upload your verification documents and complete your KYC. You be able to start trading on  only after successful verification of your KYC documents. Thank you.";

		foreach ($data as $subscribers) {
			$emaill = insep_decode($subscribers['contentmail']) . insep_decode($subscribers['liame']);
			$info = array('###MSG###' => $message, '###LINK###' => 'Testing');
			$upto = $subscribers['kyc_mail_created'] ? $subscribers['kyc_mail_created'] : $subscribers['activation_date'];

			if ($subscribers['kyc_mail_created'] == '') {
				User::where('id', $subscribers['id'])->update(['kyc_mail_created' => date('Y-m-d H:i:s')]);
			}

			$t2 = StrToTime($upto);
			$diff = $t1 - $t2;
			$hours = $diff / (60 * 60);

			if ($hours >= 24) {
				$email[] = $emaill;
				if (count($email) == 100) {
					$sendEmail = Controller::sendKYC($email, $info, '8');
					$email = array();
				}
			}

		}

		if ($email) {
			$sendEmail = Controller::sendKYC($email, $info, '8');
		}

		if ($sendEmail) {
			echo 'Newsletter Send successfully';
		} else {
			echo 'No Email Founds';
		}

		die;
	}

	public function kycrjt() 
	{

		$current = date('Y-m-d H:i:s');
		$t1 = StrToTime($current);

		$query = ConsumerVerification::orWhere('noitacifirev.selfie_status', 2)->orWhere('noitacifirev.id_status', 2)->where('b.status', 1);
		$query->leftJoin('users as b', 'b.id', '=', 'noitacifirev.user_id')->select('b.*');
		$data = $query->get()->toArray();

		$email = array();
		$sendEmail = '';
		foreach ($data as $subscribers) {

			$message = "Thank you for registering with  verifying your email address. Please login to your account at " . URL('/login') . " to upload your rejected KYC documents again to complete your KYC";

			$emaill = insep_decode($subscribers['contentmail']) . insep_decode($subscribers['liame']);
			$info = array('###MSG###' => $message, '###LINK###' => 'Testing');

			$upto = $subscribers['kyc_mail_created'] ? $subscribers['kyc_mail_created'] : $subscribers['activation_date'];

			if ($subscribers['kyc_mail_created'] == '') {
				User::where('id', $subscribers['id'])->update(['kyc_mail_created' => date('Y-m-d H:i:s')]);
			}

			$t2 = StrToTime($upto);
			$diff = $t1 - $t2;
			$hours = $diff / (60 * 60);

			if ($hours >= 24) {
				$email[] = $emaill;
				if (count($email) == 100) {
					$sendEmail = Controller::sendKYC($email, $info, '8');
					$email = array();
				}
			}

		}

		if ($email) {
			$sendEmail = Controller::sendKYC($email, $info, '8');
		}

		if ($sendEmail) {
			echo 'Newsletter Send successfully';
		} else {
			echo 'No Email Founds';
		}

		die;
	}

	public function not_ate() 
	{

		$current = date('Y-m-d H:i:s');
		$t1 = StrToTime($current);

		$data = User::where('status', 0)->get();

		$email = array();
		$sendEmail = '';
		foreach ($data as $subscribers) {

			$emaill = insep_decode($subscribers['contentmail']) . insep_decode($subscribers['liame']);
			$info = array('###MSG###' => 'Testing', '###LINK###' => 'Testing');

			$upto = $subscribers['kyc_mail_created'] ? $subscribers['kyc_mail_created'] : $subscribers['created_on'];

			if ($subscribers['kyc_mail_created'] == '') {
				User::where('id', $subscribers['id'])->update(['kyc_mail_created' => date('Y-m-d H:i:s')]);
			}

			$t2 = StrToTime($upto);
			$diff = $t1 - $t2;
			$hours = $diff / (60 * 60);

			if ($hours >= 24) {
				$email[] = $emaill;
				if (count($email) == 100) {
					//$sendEmail = Controller::sendKYC($email, $info, '19');
					$sendEmail = '';
					$email = array();
				}
			}

		}

		if ($email) {
			//$sendEmail = Controller::sendKYC($email, $info, '19');
			$sendEmail = '';
		}

		if ($sendEmail) {
			echo 'Newsletter Send successfully';
		} else {
			echo 'No Email Founds';
		}

		die;
	}

	public function supportmail() 
	{

		$user = HelpCentre::where('admin_name', '!=', '')->where('email_send', 0)->get()->toArray();
		$admin = HelpCentre::where('user_id', '!=', '0')->where('email_send', 0)->get()->toArray();

		$email = array();
		$sendEmail = '';

		if ($user) 
		{
			foreach ($user as $data) 
			{

				$info = array('###MSG###' => 'Testing', '###LINK###' => 'Testing');
				$ref_no = $data['reference_no'];

				$user_id = getuserid_support($ref_no);
				$emaill = getUserEmail($user_id);
				$email[] = $emaill;

				if (count($email) == 100)
				{
					$sendEmail = Controller::sendKYC($email, $info, '8');
					$email = array();
				}

				HelpCentre::where('admin_name', '!=', '')->where('reference_no', $ref_no)->update(['email_send' => 1]);
			}
			if ($email) {
				$sendEmail = Controller::sendKYC($email, $info, '8');
			}


			if ($sendEmail) {
				echo 'Newsletter Send successfully';
			} else {
				echo 'No Email Founds';
			}

		}

		if ($admin) 
		{
			foreach ($admin as $datas) 
			{
				$username = Config::get('sats.Email.username');
				$username = insep_decode($username);
				$email[] = $username;
				$info = array();

				HelpCentre::where('user_id', '!=', '0')->update(['email_send' => 1]);
				if (count($email) == 100) {
					$sendEmail = Controller::sendKYC($email, $info, '8');
					$email = array();
				}

			}

			if ($email) {
				$sendEmail = Controller::sendKYC($email, $info, '8');
			}

		}

		die;

	}

	public function moveToadmin() 
	{
		$result = Deposit::where(['currency' => 'ETH', 'order_status' => '0'])->select('address', 'amount', 'id')->get();
		if ($result) {
			$address = Config::get('sats.ETH.address');
			$gas_price = Details::where(['type' => 'ETH'])->select('gas_price')->first();
			$gas_price = $gas_price->gas_price;
			$password = Config::get('sats.ETH_USR.key');
			$password = insep_decode($password);
			$ethAdmin = trim($address);

			$ethAdmin = strtolower($ethAdmin);
			foreach ($result as $res) {
				$data = array('fromaddress' => $res->address, 'toaddress' => $ethAdmin, 'key' => $password, 'gasPrice' => $gas_price, 'amount' => $res->amount);
				$ress = self::getDepositeth('ethwithdraw', $data);
				if ($ress) {
					Deposit::where('id', $res->id)->update(['order_status' => '1']);
				}
			}
		}
	}
	
	function expiredordercancel() 
	{

		$d_date = date('Y-m-d', (strtotime('-30 day', strtotime(date('Y-m-d')))));
		$coinorder = CoinOrder::where('updated_at', '<', $d_date)->whereIn('status', ['active', 'partially'])->select('id')->get();
		if ($coinorder) {
			foreach ($coinorder as $key => $value) {

				$result = "";
				$buyorderId = $buyuserId = $sellorderId = $selluserId = 0;
				$cancel_id = $tradeId = $value->id;
				$order = CoinOrder::where('id', $tradeId)->whereIn('status', ['active', 'partially', 'stoporder'])->first();
				if ($order) {
					
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
						echo "string";
						exit;
					} else {
						echo "Invalid request!";exit();
					}

				}
			}
		}

	}
     
	public function tokendeposit($token) 
	{
		

			$getuserblock = Currency::where('symbol', $token)->select('lastblock')->first();

			if($getuserblock->lastblock != ''){
				$maxBlock=$getuserblock->lastblock;
			}else{
				$maxBlock='7484239';
			}

			$getdetails         = Tokens::where(['token_symbol' => $token])->select('token_symbol', 'decimalval','contract_address', 'id')->first();
			

			$curl = curl_init();
		
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => Config::get('app.EthApi').'?module=account&action=tokentx&sort=asc&contractaddress=' . $getdetails->contract_address . '&startblock=' . $maxBlock . '&endblock=latest&apikey='.Config::get('app.EthApiKey'),
				CURLOPT_USERAGENT => 'Sample cURL Request'
			));
		
			$output = curl_exec($curl);
		
			curl_close($curl);
			$result = json_decode($output);
			$transactions=$result->result;
			if($transactions){
				$transactions=$result->result;
			}else{
				$transactions = array();
			}
			

			

			self::ERCTokenDeposit($token,$transactions,$getdetails->decimalval);
		

	}


	public function ERCTokenDeposit($token,$transaction,$decimalval) 
	{

		$cur_date = date('Y-m-d H:i:s');
		$cur_time = date('H:i:s');   

		for ($tr = 0; $tr < count($transaction); $tr++) 
		{


			$block_number = $transaction[$tr]->blockNumber;
			$address = $transaction[$tr]->to;
			$txid = $transaction[$tr]->hash;
			$value = $transaction[$tr]->value;
			$confirmations = $transaction[$tr]->confirmations;
			$from = $transaction[$tr]->from;
			$to = $transaction[$tr]->to;
			$contractAddress = $transaction[$tr]->contractAddress;

            $addcoin = Details::where('type',$token)->select('address')->first();
                 
                    $adminaddress = insep_decode($addcoin->address);
                     if($address==$adminaddress){
                          $trans = new Sats;
                          $adminalready_exist = $trans->admintransactions_list($txid);
                          if (!$adminalready_exist){
                             $updatedeposit = $trans->createadminDeposit($token, $currency_id, $amount, $txid, $address,'',$confirm);
                          }
                         

                     }else{


			$getuserid         = CoinAddress::where(['currency' => $token,'address'=>insep_encode($address)])->select('address', 'user_id')->first();

			if($getuserid ){
				$userId = $getuserid->user_id;

				if($userId){
                         

					$depositalready = Deposit::where('transaction_id', $txid)->count();
					

					if($depositalready==0){


						if($decimalval==0){
							$newbal=$value;
						}else{
							$decimal= pow(10,$decimalval);
							$newbal=$value/$decimal;
						}


						$getcurid  = Currency::where(['symbol' => $token])->select('id')->first();

						$trans = new Sats;
						$updatedeposit = $trans->updateDeposit($token, $getcurid->id, $userId, $newbal, $txid, $address,'',$confirmations);

						echo "deposited successfully";

					}else{

						echo "deposited already";

					}


				}
			}

		}

      }
	}



 

	public function tokenadmindeposit($token) 
	{


			$getuserblock = Currency::where('symbol', $token)->select('lastblock')->first();

			if($getuserblock->lastblock != ''){
				$maxBlock=$getuserblock->lastblock;
			}else{
				$maxBlock='7484239';
			}


			$getdetails         = Tokens::where(['token_symbol' => $token])->select('token_symbol', 'decimalval','contract_address', 'id')->first();

			$curl = curl_init();
		
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => Config::get('app.EthApi').'?module=account&action=tokentx&sort=asc&contractaddress=' . $getdetails->contract_address . '&startblock=' . $maxBlock . '&endblock=latest&apikey='.Config::get('app.EthApiKey'),
				CURLOPT_USERAGENT => 'Sample cURL Request'
			));
		
			$output = curl_exec($curl);
		
			curl_close($curl);
			$result = json_decode($output);
			$transactions=$result->result;
			if($transactions){
				$transactions=$result->result;
			}else{
				$transactions = array();
			}
			
			$this->ERCTokenDepositadmin($token,$transactions,$getdetails->contract_address,$getdetails->decimalval);
		
	}


function ERCTokenDepositadmin($token,$transactions,$contractaddress,$decimalval)
{

	for ($tr = 0; $tr < count($transactions); $tr++) 
	{

                        
		$block_number = $transactions[$tr]->blockNumber;
		$address = $transactions[$tr]->to;
		$addressget = insep_encode($transactions[$tr]->to);
		$txid = $transactions[$tr]->hash;
		$value = $transactions[$tr]->value;
		$confirmations = $transactions[$tr]->confirmations;
		$from = $transactions[$tr]->from;
		$too = $transactions[$tr]->to;
		$contractAddress = $transactions[$tr]->contractAddress;

		$getuseridnow = CoinAddress::where(['currency' => $token,'address'=>$addressget])->select('address', 'user_id')->first();

		if($getuseridnow )
		{

			$userId = $getuseridnow->user_id;
			

			$account =insep_decode(trim($getuseridnow->address));
			

			$getcurid  = Currency::where(['symbol' => $token])->select('id')->first();
			$curid  = $getcurid->id;

			$usname = Config::get('sats.ETH.adminaddr');
			$password = Config::get('sats.ETH.adkey');

                  
			$ethadminaddress  = insep_decode($usname);
			$admin_pass = insep_decode($password);

			if($account != "") 
			{
				


				$data = array('ethaddress'=>$ethadminaddress,'address'=>$account,'contractaddress'=>$contractaddress);
				
				$outputt = file_get_contents('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=' . $contractaddress . '&address=' .trim($account). '&tag=latest');

				$resultt = json_decode($outputt);
	    			
	    			if ('OK' == $resultt->message) {
	    				$tokenbalance = $resultt->result;
	    			}

	    			if($decimalval!='0'){
	    				$decimal= pow(10,$decimalval);
	    				$balance=$tokenbalance/$decimal;
	    			}
	    			else
	    			{
	    				$balance=$tokenbalance;
	    			}



	    			$tokenfee=connecteth('tokenfee',$data);
	    			$dataa=array('ethaddress'=>$account);

	    			
	    			$ethbalance=connecteth('checkethbalance',$dataa);
	    			if($tokenbalance>0){
                  
	    				if( $ethbalance < 0 || $ethbalance < $tokenfee ){
	    		
	    					$amount      = 0.003;  
	    					$pass         = $admin_pass;

	    					$address      = '"'.trim($ethadminaddress).'"';
	    					$to           = '"'.trim($account).'"';


	    					$wtamount = (float)$amount;       
	    					$data2 = array('adminaddress'=>$ethadminaddress,'toaddress'=>$to,'amount'=>$wtamount,'key'=>$pass);

                         
	    					$trans = new Sats;
	    					$result=$trans->connecteth('ethwithdrawjson',$data2);

                        
	    					exit;

	    				}
	    				else
	    				{
	    					if($tokenbalance>0){
	    						
	    						$password = Config::get('sats.Token_USR.tuskey');
	    						$coinkey  = insep_decode($password);



	    						$data3 = array('toaddress'=>$ethadminaddress,'fromaddress'=>$account,'amount'=>$tokenbalance,'keyto'=>$coinkey,'contractaddress'=>$contractaddress);

	    						

	    				
	    				$trans = new Sats;

	    				$resulttx=$trans->connecttoken2('tokentransfer',$data3);
	    				exit;

	    			}

	    		}
		    	}else{
		    		echo "no token balance";
		    	}

            }
        }

    }
}


public function adminethtransferprocess()
{


	$usname = Config::get('sats.ETH.adminaddr');
	$password = Config::get('sats.ETH.adkey');


	$adminaddress= insep_decode($usname);
	$userpassword= insep_decode($password);
	$data  = array('key'=>$userpassword,'adminaddress'=>$adminaddress);    
	$output = self::connecteth('toadminwallet',$data); 
	
}







function coinmarket()
{
	$getpairs = TradePairs::select('*')->get();
       
	if(!empty($getpairs))
	{
		foreach($getpairs as $getpairs)
		{

			$to1=$getpairs->from_symbol;
			$from=$getpairs->to_symbol;
			if($to1 == "BTC" && $from == "EUR")
			{
				$to = $getpairs->to_symbol;
				$from1 = $getpairs->from_symbol;
			}
			else
			{
				$to = $getpairs->from_symbol;
				$from1 = $getpairs->to_symbol;
			}

			
			
			$highPrice = CoinOrder::where('pair', $getpairs->pair)->where('Type', 'buy')->whereIn('status', ['active', 'partially'])->select('Price')->orderBy('Price', 'desc')->limit(1)->get();
			if ($highPrice->isEmpty()) 
			{
	        	
				$getSite = SiteSettings::where('id', 1)->select('coinmarketapi')->first();

				$cmc_url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?CMC_PRO_API_KEY=".$getSite->coinmarketapi."&symbol=".$from1."&convert=".$to;


				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $cmc_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				$output = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($output);
			
				if (isset($response->data) && $response->status->credit_count == 1) 
				{	
				
				$preres = $response->data->$from1->quote->$to;	
				$tocur = $preres->price;	
				
				
				
				$up =TradePairs::where('id', $getpairs->id)->update(array('last_price'=>$tocur));

				} else 
				{
						
				}
				
		    }
	    }
    }
}


function cronmovetokentoadmin() 
{
	
		$details = Config::get('sats.ETH.adminaddr');

		$address = insep_decode($details);
		
		$key = Config::get('sats.Token_USR.tuskey');

		$pass = insep_decode($key);

		$data = array('key' => $pass, 'adminaddress' => $address);

		$trans = new Sats;

		$output = $trans->connecteth('toadminwallet', $data);
		
		exit;
	
}

function runcron(){
	Notificationlist::create(array('user_id' => "0", 'message' => "working cron"));
}

function dummy() {
	

}



	function destroysession() {
		$result = DB::table('sresu')
					->select('id')
					->where('login_status',1)
					->where('browser_status',1)
					->orderBy('id', 'DESC')
					->get();
		foreach($result as $row) {
			$ip = DB::table('ytivitca_resu')
					->select('created_at')
					->where('user_id',$row->id)
					->where('activity','Logged_in')
					->orderBy('id', 'DESC')
					->limit(1)
					->first();
		
			$created_at = $ip->created_at;
			$current_time = date('Y-m-d H:i:s');
			$temp_time =  date('Y-m-d H:i:s',strtotime('+50 minutes',strtotime($created_at)));		;
			if($current_time > $temp_time)
			{
				$userInstance = new User;
				$value[] = array('id'=>$row->id,'session_id' => '','login_status'=>'0','browser_status' => '0');
				$index = 'id';
				Batch::update($userInstance, $value, $index);
				exit;
			}			
		}
	}

}
