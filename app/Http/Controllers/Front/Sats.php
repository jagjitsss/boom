<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\CoinAddress;
use App\Model\Currency;
use App\Model\Deposit;
use App\Model\Details;
use App\Model\Notificationlist;
use App\Model\User;
use App\Model\Wallet;
use App\Model\Tokens;
use App\Model\Admindeposit;
use Config;
use DB;
use Exception;
use URL;

class Sats extends Controller {
	public function __construct() {

	}
	

    public function generateAddressNewAdmin($currency, $email)
    {

        $address = self::curl_request($currency, 'getnewaddress', array($email));
        return $address;
    }
	public function checkmethod($currency, $method) 
	{
		$res = self::curl_request($currency, $method);
		
		
	}
	
	public function releaseFunds($currency, $amount, $to_address, $extra = '') 
	{
        $to_address = trim($to_address);
        $amount = (float) $amount;

        if($currency){
            $checkerc = Currency::where('symbol', $currency)->select('ERC20')->first();
            $confirmerc = $checkerc->ERC20;
            if($currency=="BTC")
            {
                $getFiless = file_get_contents(app_path('Model/btslelwieow.php'));
                $datass = explode(" || ", $getFiless);
                $admin_pass = insep_decode($datass[2]);
                
                $setarray = array(trim($to_address), (float) $amount);
                $trans = new Sats;
                $open_wlt = $trans->curl_request('walletpassphrase', array($admin_pass, 12));

                $sendtoaddress = $trans->curl_request('BTC', 'sendtoaddress', $setarray); 

                if ($sendtoaddress) {
                    return $sendtoaddress;
                } else {
                    return false;
                }

            }
            elseif($currency=="BCH")
            {
                $getFiless = file_get_contents(app_path('Model/bchslelwieow.php'));
                $datass = explode(" || ", $getFiless);
                $admin_pass = insep_decode($datass[2]);
                $setarray = array(trim($to_address), (float) $amount);
                $trans = new Sats;
                $open_wlt = $trans->curl_request('walletpassphrase', array($admin_pass, 12));

                $sendtoaddress = $trans->curl_request('BCH', 'sendtoaddress', $setarray); 

                if ($sendtoaddress)
                {
                    return $sendtoaddress;
                }
                else
                {
                    return false;
                }

            }
            else if($currency=="ETH"){                

                    $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
                    $datass = explode(" || ", $getFiless);
                    $admin_pass = insep_decode($datass[2]);
                    $ethadminaddress = insep_decode($datass[3]);
                    $gas_limit =21000;

                    $amount = bcmul($amount, 1000000000000000000);

                    $gas_price = self::get_gasprice();
                    $fee = bcmul($gas_limit, $gas_price);
                    
                    $transAmount = bcsub($amount, $fee);

                 
                    $transAmount = bcmul($transAmount, 1000000000000000000);

                    $data3 = array('account' => $ethadminaddress, 'toaddress' => $to_address, 'amount' => $transAmount, 'key' => $admin_pass, 'gasPrice' => $gas_price, 'gasLimit' => $gas_limit);

                    $resulttx = self::connectethcheck('send', $data3);

                    

                    if ($resulttx != "none")
                    {
                        return $isvalid = $resulttx;
                    }
                    else
                    {
                        return false;
                    }            
                
            }
            else if($currency && $confirmerc == '1')
            {

                $adnm=  Config::get('sats.ETH.adminaddr');
                $adps= Config::get('sats.ETH.adkey');
             
                $ethadminaddress  = insep_decode($adnm); 
                $admin_pass = insep_decode($adps);

                $getdetails         = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();
                $contractaddress=$getdetails->contract_address;
                $decimalval=$getdetails->decimalval;

                if($decimalval==0){
                    $amountnew = $amount;
                }else{
                    $a = $amount;
                    $mul= '1e'.$decimalval;
                    $test = sprintf('%18f', ($a * $mul));
                    $t = explode(".", $test);
                    $amountnew = $t[0];
                }

                
                $data3 = array('fromaddress' => $ethadminaddress, 'toaddress' => $to_address, 'amount' => $amountnew, 'keyto' => $admin_pass, 'contractaddress' => $contractaddress);

                $resulttx = self::connecttoken('tokentransfer', $data3);


                if ($resulttx != "none") {
                    return $isvalid = $resulttx;
                } else {
                    return false;
                }

            }else{
                return false;
            }

        }
		
	}
	
	public function getBalance($currency, $address = '') 
	{
		if($currency)
        {
			$checkerc = Currency::where('symbol', $currency)->select('ERC20')->first();
			$confirmerc = $checkerc->ERC20;
			
            if($currency=='BTC' || $currency=='BCH')
            {
                return $balance = self::curl_request($currency, 'getbalance',array());
            }
            else if($currency=='ETH')
            {

             $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
            $datass = explode(" || ", $getFiless);
            $adminaddress = insep_decode($datass[3]);
                $outputt = file_get_contents(Config::get('app.EthApi').'api?module=account&action=balance&address=' . trim($adminaddress) . '&tag=latest&apikey='.Config::get('app.EthApiKey'));


                $resultt = json_decode($outputt);

                $tokenbalance = 0;
                if ('OK' == $resultt->message) {
                    $tokenbalance = $resultt->result;

                    $balance = $tokenbalance / 1000000000000000000;
                    return number_format($balance, 8);
                } else {
                    return $balance = 0;
                }
            } 
             
             else if($currency && $confirmerc == '1'){
             $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
                $datass = explode(" || ", $getFiless);
                    $adaccount = insep_decode($datass[3]);
             	$getdetails         = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();
             	$contractaddress=$getdetails->contract_address;
             	$decimalval=$getdetails->decimalval;
             	
             	$outputt = file_get_contents(Config::get('app.EthApi').'api?module=account&action=tokenbalance&contractaddress=' . $contractaddress . '&address=' .trim($adaccount). '&tag=latest&apikey='.Config::get('app.EthApiKey'));

             	$resultt = json_decode($outputt);
             	if ('OK' == $resultt->message) {
             		$tokenbalance = $resultt->result;
             	}

             	if($decimalval == 0){
             		return $balance=$tokenbalance;
             	}else{
             		return $balance=$tokenbalance/$decimalval;
             	}
             	
             }else{
             	return $balance = 0;
             }

         }
     }
     public function generateAddress($currency,$id) 
     {
     	$user_id = $id;
     	$get_data = User::where('id', $user_id)->select('first_name', 'last_name', 'liame', 'contentmail')->first();
     	$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
     	$checkerc = Currency::where('symbol', $currency)->select('ERC20','type')->first();
     	$confirmerc = $checkerc->ERC20;
     	$ctype = $checkerc->type;


     		if($currency=='BTC'){

     			$address = self::curl_request($currency, 'getnewaddress', array($email));
     			return $address;
     		}
            if($currency=='BCH'){

                $address = self::curl_request($currency, 'getnewaddress', array($email));
                $address = ltrim($address, 'bitcoincash:');
                return $address;
            }
     		else 
            {
            $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
            $datass = explode(" || ", $getFiless);
            $ip = insep_decode($datass[0]);
            $port = insep_decode($datass[1]);
            $user_key = insep_decode($datass[2]);
            $adminaddress = insep_decode($datass[3]);
            $url = $ip . ":" . $port;
            $address = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"personal_newAccount","params":["' . trim($user_key) . '"],"id":1}\' ' . $url);
            $output = json_decode($address);
            $addressnew = $output->result;
            
            return $addressnew;
     			
     		}
     		
     		
     	

     }
	
     public function generateredeemString_xp($length = 8) 
     {

     	$characters = '123456789';
     	$randomString = '';
     	for ($i = 0; $i < $length; $i++) {
     		$randomString .= $characters[rand(0, strlen($characters) - 1)];
     	}

     	$res = CoinAddress::where(['tag' => $randomString])->count();
     	if ($res) {
     		return generateredeemString_xp();
     	}

     	return $randomString;
     }
     public function validateAddress($address, $currency) 
     {
     	switch ($currency) {
     		case 'BTC':
     		return 1;
     		case 'ETH':
     		$result = self::connecteth('isaddress', array('address' => $address));
     		if (!strcmp($result, '"false"')) {
     			return 0;
     		} else {
     			return 1;
     		}


     		default:
     		return false;
     	}

     }
	
     public function deposits($currency = 'BTC') 
     {

        $transactions = self::curl_request($currency, 'listtransactions', array('*', 50));
        print_r($transactions);

     	if($transactions)
        {
     		$currency_id = Currency::where('symbol', $currency)->select('id')->first();
     		$currency_id = $currency_id->id;
     		for ($i = 0; $i < count($transactions); $i++) {

     			$category = $transactions[$i]->category;
     			$txid = $transactions[$i]->txid;
     			$address = $transactions[$i]->address;
     			$confirm = $transactions[$i]->confirmations;
     			$amount = $transactions[$i]->amount;

               
                  $addcoin = Details::where('type','BTC')->select('address')->first();
                 
                    $adminaddress = insep_decode($addcoin->address);
                     if($address==$adminaddress and $category == "receive")
                     {
                          
                          $adminalready_exist = self::admintransactions_list($txid);
                          
                          if (!$adminalready_exist){
                             $updatedeposit = self::createadminDeposit($currency, $currency_id, $amount, $txid, $address,'',$confirm);
                          }
                     }
                     else
                     {

                        if ($category == "receive")
                        {
                            $address = ltrim($address, "bitcoincash:");
                            $userid = self::get_userId_address($address, $currency); 
                            if ($userid)
                            {
                                $already_exist = self::transactions_list($txid);
                                $get_datastatus = Deposit::where('transaction_id',$txid)->select('status')->first();
                                $get_datastatus_count = Deposit::where('transaction_id',$txid)->select('status')->count();

                                if($get_datastatus)
                                {
                                    $status=$get_datastatus->status;
                                }
                                if ($already_exist == 0 and $get_datastatus_count == 0)
                                {
                                    $updatedeposit = self::createDeposit($currency, $currency_id, $userid, $amount, $txid, $address,'',$confirm);
                                }
                                else if($already_exist == 1 and $status =="Unconfirmed" and  $confirm >= 2)
                                {
                                    $updatedeposit = self::updateDepositnew($currency, $currency_id, $userid, $amount, $txid, $address,'',$confirm);
                                }
                                else if($already_exist and $status =="Completed")
                                {
                                            
                                }
                            }
                                    
                          }
                        }
                    }
     		    }
     	    }
	
     	public function updateDeposit($currency, $c_id, $user_id, $amount, $transaction_id, $address, $block = '',$confirmations='') 
     	{
            $address_encrypt = insep_encode($address);
            $depositUser = CoinAddress::where(['address' => $address_encrypt, 'currency' => $currency])->select('user_id')->first();
            $user_id  = $depositUser->user_id;

     		$fetchbalance = Wallet::getBalance($user_id, $c_id);
     		$update_balance = $fetchbalance + $amount;
     		$remarks = 'Deposit ' . $currency . ' ' . $amount;
     		$array_deposit = array('user_id' => $user_id, 'currency' => $currency, 'status' => 'Completed', 'transaction_id' => $transaction_id, 'address' => $address, 'amount' => $amount, 'block_confirm' => $block, 'remarks' => $remarks,'confirmation'=>$confirmations);
     		$result = DB::transaction(function () use ($array_deposit, $user_id, $c_id, $update_balance, $amount, $currency,$transaction_id,$confirmations) {

     			$message = 'You have Deposit amount ' . $amount . ' ' . $currency . ' completed successfully';
     			Notificationlist::create(array('user_id' => $user_id, 'message' => $message));

                

     			Deposit::create($array_deposit);
     			return Wallet::updateBalance($user_id, $c_id, $update_balance);

     		});
     		if ($result) {
     			$get_data = User::where('id', $user_id)->select('first_name', 'last_name', 'liame', 'contentmail')->first();

     			$name = $get_data->first_name . ' ' . $get_data->last_name;
     			$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
     			$info = array('###CUR###' => $currency, '###AMOUNT###' => $amount, '###ADDR###' => $address, '###HASH###' => $transaction_id, '###USER###' => $name);

     			$sendEmail = Controller::sendEmail($email, $info, '10');
     		}
     	}
     	public function updateDepositnew($currency, $c_id, $user_id, $amount, $transaction_id, $address, $block = '',$confirmations='') 
     	{
     		$fetchbalance = Wallet::getBalance($user_id, $c_id);
     		$update_balance = $fetchbalance + $amount;
     		$remarks = 'Deposit ' . $currency . ' ' . $amount;
     		$array_deposit = array('user_id' => $user_id, 'currency' => $currency, 'status' => 'Completed', 'transaction_id' => $transaction_id, 'address' => $address, 'amount' => $amount, 'block_confirm' => $block, 'remarks' => $remarks);
     		$result = DB::transaction(function () use ($array_deposit, $user_id, $c_id, $update_balance, $amount, $currency,$transaction_id,$confirmations) {

     			$message = 'You have Deposit amount ' . $amount . ' ' . $currency . ' completed successfully';
     			Notificationlist::create(array('user_id' => $user_id, 'message' => $message));
     			Deposit::where('transaction_id',$transaction_id)->update(['status' => 'Completed','confirmation'=>$confirmations]);

     			return Wallet::updateBalance($user_id, $c_id, $update_balance);

     		});
     		if ($result) {
     			$get_data = User::where('id', $user_id)->select('first_name', 'last_name', 'liame', 'contentmail')->first();

     			$name = $get_data->first_name . ' ' . $get_data->last_name;
     			$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
     			$info = array('###CUR###' => $currency, '###AMOUNT###' => $amount, '###ADDR###' => $address, '###HASH###' => $transaction_id, '###USER###' => $name);

     			$sendEmail = Controller::sendEmail($email, $info, '10');
     		}
     	}
    
     	public function createDeposit($currency, $c_id, $user_id, $amount, $transaction_id, $address, $block = '',$confirmations='') 
     	{

            $address_encrypt = insep_encode($address);
            $depositUser = CoinAddress::where(['address' => $address_encrypt, 'currency' => $currency])->select('user_id')->first();
            $user_id  = $depositUser->user_id;
            
     		$fetchbalance = Wallet::getBalance($user_id, $c_id);
     		$update_balance = $fetchbalance + $amount;
     		$remarks = 'Deposit ' . $currency . ' ' . $amount;
     		if($confirmations<2){
     			$status = "Unconfirmed";
     		}else if($confirmations>=2){
     			$status = "Completed";
     			self::updateDeposit($currency, $c_id, $user_id, $amount, $transaction_id, $address, $block = '',$confirmations);
     		}
     		$array_deposit = array('user_id' => $user_id, 'currency' => $currency, 'status' => $status, 'transaction_id' => $transaction_id, 'address' => $address, 'amount' => $amount, 'block_confirm' => $block, 'remarks' => $remarks,'confirmation'=>$confirmations);
     		Deposit::create($array_deposit);

     	}
	
     	public function transactions_list($txid) 
     	{
     		return Deposit::where('transaction_id', $txid)->count();
     	}
	
     	public function get_userId_address($address, $currency) 
     	{
     		$address = insep_encode($address);
     		$user_id = CoinAddress::where('currency', $currency)->where('address', $address)->select('user_id')->first();
     		if ($user_id) {
     			return $user_id->user_id;
     		} else {
     			return false;
     		}

     	}
	
     	public function connecteth($method, $data = array()) 
     	{

     		$usname = Config::get('sats.ETH_PTH.urlkey');
     		$url = insep_decode($usname).'/api.php';
     		$name = $_SERVER['SERVER_NAME'];
     		$data = array("method" => $method, "name" => $name, "keyword" => 'test', 'data' => $data);

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
     			exit;
     		}
     	}
	
     	public function curl_request($currency, $method, $postfields = null) 
     	{

        //$getFiless = file_get_contents(app_path('Model/btslelwieow.php'));

        if($currency == 'BTC')
        {

            $getFiless = file_get_contents(app_path('Model/btslelwieow.php'));    
        }
        if($currency == 'BCH')
        {

            $getFiless = file_get_contents(app_path('Model/bchslelwieow.php'));
        }

        $datass = explode(" || ", $getFiless);
        $connection_parms['user'] = insep_decode($datass[0]);
        $connection_parms['pass'] = insep_decode($datass[1]);
        $connection_parms['ip_addr'] = insep_decode($datass[2]);
        $connection_parms['port'] = insep_decode($datass[3]); 
				$data = array();
				$data['jsonrpc'] = 2.0;
				$data['id'] = 1;
				$data['method'] = $method;
				$data['params'] = $postfields;

				$url = 'http://' . $connection_parms['user'] . ':' . $connection_parms['pass'] . '@' . $connection_parms['ip_addr'] . ':' . $connection_parms['port'];
			$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, count($postfields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $ret = curl_exec($ch);
            $res = curl_getinfo($ch);
            curl_close($ch);

				if ($ret !== FALSE) {
					if (isset($formatted->error)) {
						throw new Exception($formatted->error->message, $formatted->error->code);
					} else {
						$output = json_decode($ret);
						if ($output) {
							return $output->result;
						} else {
							return false;
						}

					}
				} else {
					return false;
				}
					
	    }

	function blockcount(){

		

}
	
function connecttoken($method, $data = array()) 
{
    $usname = Config::get('sats.ETH_PTH.urlkey');
	$coinadurl = insep_decode($usname);

	$url=$coinadurl.'/token.php';
	$name = $_SERVER['SERVER_NAME'];
	$data = array("method" => $method, "name" => $name, "keyword" => 'xxxx', 'data' => $data);
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

		
	$result = json_decode($response);
	if ($result->type == 'success') {
		
		return $result->result;
	} else {
		exit;
	}
}

function connecttoken2($method, $data = array()) 
{   
	$usname = Config::get('sats.ETH_PTH.urlkey');
	$coinadurl = insep_decode($usname);

	$url=$coinadurl.'/token.php';
	$name = $_SERVER['SERVER_NAME'];
	$data = array("method" => $method, "name" => $name, "keyword" => 'xxxxx', 'data' => $data);
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
	exit;

	curl_close($ch);

		
	$result = json_decode($response);
	if ($result->type == 'success') {
		
		return $result->result;
	} else {
		exit;
	}
}

public function depositsETH() 
{

		$currency = "ETH";
		$maxBlock = "";
		$query = "SELECT user_id, address FROM tmaitb_sserdda_nioc WHERE currency = 'ETH'";
		$getAddress = DB::select($query);
		if (!empty($getAddress)) {
			foreach ($getAddress as $users) {
				$userId = $users->user_id;
				$account = insep_decode(trim($users->address));
                echo "<br/>";
                echo "User address=".$account;
                echo "<br/>";
				if($account != "") 
				{


                  $url = Config::get('app.EthApi').'api?module=account&action=txlist&address='.$account.'&endblock=latest&apikey='.Config::get('app.EthApiKey');
                  $crl = curl_init();
                  
                  curl_setopt($crl, CURLOPT_URL, $url);
                  curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
                  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                  $result = curl_exec($crl);
                  
                  if(!$result){
                      die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
                  }
                  curl_close($crl);
                  
                  
                  $result = json_decode($result);


					if($result->message == 'OK')
					{
						$transaction=$result->result;
						
						

                        for($tr=0;$tr<count($transaction);$tr++)
                        {
                            $value    = $transaction[$tr]->value;
                            $ether_balance = ($value/1000000000000000000); 
                            

                        }
                        
						for($tr=0;$tr<count($transaction);$tr++)
						{
							$block_number  = $transaction[$tr]->blockNumber;
							$address  = $transaction[$tr]->to; 
							$txid     = $transaction[$tr]->hash;
							$value    = $transaction[$tr]->value;
							$ether_balance = ($value/1000000000000000000); 
							$confirmations =$transaction[$tr]->confirmations;
							$from = $transaction[$tr]->from;

							

                            $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
                            $datass = explode(" || ", $getFiless);
                            $usname = $datass[3];                
							$adminaddress  = insep_decode($usname);

							if($address!=$adminaddress)
                            {

                        		$getuseridnow = CoinAddress::where([
                                    'currency' => 'ETH',
                                    'address'=>insep_encode($address)])->select('address', 'user_id')->first();

        						echo $userid =$getuseridnow['user_id'];
								echo "<br>";
							
								if( $transaction[$tr]->confirmations > 8 ) 
								{
									$orders = Deposit::where('transaction_id', $txid)->count();


									if ($orders == 0)
                                    {
										echo "deposited";
                                        $trans = $this->ethadmintrans($ether_balance, $address);
										$getcurid  = Currency::where(['symbol' => 'ETH'])->select('id')->first();

										$trans = new Sats;                                        
                                        $address_encrypt = insep_encode($address);
                                        $depositUser = CoinAddress::where(['address' => $address_encrypt, 'currency' => 'ETH'])->select('user_id')->first();
                                        $depositUserId  = $depositUser->user_id;
                                        
										$updatedeposit = $trans->updateDeposit('ETH', $getcurid->id, $depositUserId, $ether_balance, $txid, $address,'',$confirmations);

										$lastBlock = $block_number;
										Currency::where('symbol', 'ETH')->update(['lastblock' => $lastBlock]);

									}


								}
							}
						}
					}

				}
			}
		}


        $addcoin = Details::where('type',"ETH")->select('address')->first();
                 
                    $adminaddress = insep_decode($addcoin->address);
                     if($adminaddress)
                    {
                            $output = file_get_contents(Config::get('app.EthApi').'api?module=account&action=txlist&address='.$account.'&endblock=latest&apikey='.Config::get('app.EthApiKey'));
                            $result = json_decode($output);


                            if($result->message == 'OK')
                            {
                                $transaction=$result->result;
                                
                                
                                for($tr=0;$tr<count($transaction);$tr++)
                                {
                                    $block_number  = $transaction[$tr]->blockNumber;
                                    $address  = $transaction[$tr]->to; 
                                    $txid     = $transaction[$tr]->hash;
                                    $value    = $transaction[$tr]->value;
                                    $ether_balance = ($value/1000000000000000000); 
                                    $confirmations =$transaction[$tr]->confirmations;
                                    $from = $transaction[$tr]->from;
                                  }
                              }
                                  $adminalready_exist = self::admintransactions_list($txid);
                                  if (!$adminalready_exist){
                                     $updatedeposit = self::createadminDeposit('ETH', '', $ether_balance, $txid, $address,'',$confirmations);
                                  }
                                 

                     }
	
}


public function ethadmintrans($amount, $to_address) {

        $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
        $datass = explode(" || ", $getFiless);
        $ip = insep_decode($datass[0]);
        $port = insep_decode($datass[1]);
        $admin_pass = insep_decode($datass[2]);
        $adnm = $datass[3];
        $url = $ip . ":" . $port;

        $ethadminaddress = trim($to_address);
       
        $to_address = $adnm;
        $gas =21000;
        $amount = bcmul($amount, 1000000000000000000);
        $gas_limit = self::get_gasprice();
        $fee = bcmul($gas_limit, $gas);
        $transAmount = bcsub($amount, $fee);
        $data3 = array('account' => $ethadminaddress, 'toaddress' => $to_address, 'amount' => $transAmount, 'key' => $admin_pass, 'gasPrice' => $gas, 'gasLimit' => $gas_limit);

        $resulttx = self::connectethtransfar('send', $data3);

        if ($resulttx != "none") {
            return $isvalid = $resulttx;
        } else {
            return false;
        }
    }



    
    public function connectethtransfar($method, $data_param = array()) {

        if ($method == 'send') {

            $json_data = array('data' => $data_param);

            $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
        $datass = explode(" || ", $getFiless);
        $ip = insep_decode($datass[0]);
        $port = insep_decode($datass[1]);
        $admin_pass = insep_decode($datass[2]);
        $adnm = $datass[3];
        $url = $ip . ":" . $port;

            $fromaddress = strtolower($json_data['data']['account']);

            $touseraddress = strtolower($json_data['data']['toaddress']);

            $amount = trim($json_data['data']['amount']);



            $key = trim($json_data['data']['key']);
            
            $amount = '0x' . dechex($amount);

              $gas = strtolower($json_data['data']['gasPrice']);
              $gas = '0x' . dechex($gas);

                $gasprice = strtolower($json_data['data']['gasLimit']);
                $gas_limit = '0x' . dechex($gasprice);
        

            $unlockAddr = exec('curl -H "Content-Type: application/json" -X POST --data \'{"jsonrpc":"2.0","method":"personal_unlockAccount","params":["' . $fromaddress . '","' . trim($key) . '",null],"id":1}\' ' . $url);

            $unlockRes = json_decode($unlockAddr, true);


            if (!isset($unlockRes['error'])) {

                $unlockEth = $unlockRes['result'];

                if ($unlockEth == 1 || $unlockEth == "true" || $unlockEth == true) {

                    $output = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"eth_sendTransaction","params":[{"from":"' . $fromaddress . '","to":"' . $touseraddress . '","gas":"' . $gas . '","gasPrice":"' . $gas_limit . '","value":"' . $amount . '"}],"id":22}\' ' . $url);
                    $result = json_decode($output, true);

                    if (!isset($result['error'])) {
                        $res = $result['result'];

                        if ($res == '' || $res == null) {
                            return false;
                        } else {
                            return $res;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

function cronmoveETHtoadmin() 
{

    $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
            $datass = explode(" || ", $getFiless);
            $ip = insep_decode($datass[0]);
            $port = insep_decode($datass[1]);
            $pass = insep_decode($datass[2]);
            $address = $datass[3];
            $url = $ip . ":" . $port;
		$data = array('key' => $pass, 'adminaddress' => $address);
		$output = self::connecteth('toadminwallet', $data);
		
		exit;
	
}

function runcron()
{
	Notificationlist::create(array('user_id' => "0", 'message' => "working cron"));
}


public static function connecttrx($method, $data = array()) 
{
	$url = 'http://localhost/api_trx.php';
	$name = $_SERVER['SERVER_NAME'];
	$data = array("method" => $method, "name" => $name, "keyword" => 'tronnxtrs', "data" => $data);
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
	$response = json_decode($response, true);
	if ($response['type'] == 'success') {
		return $response['result'];
	} else {
		return 'error';
	}
}
	
function create_wave_addressnew()
{
	$result = shell_exec('cd '.public_path().'/assets/wavejs; /usr/bin/node createaddress.js');
	$result1 = json_decode($result);
	$data = array(
		'phrase'  => $result1->phrase,
		'address' => $result1->address,
		'publick_key' => $result1->keyPair->publicKey,
		'secret_key' => $result1->keyPair->privateKey,
	);
	return $data;
}


        public function createadminDeposit($currency, $c_id, $user_id, $amount, $transaction_id, $address, $block = '',$confirmations='') 
        {
            
            $array_deposit = array('currency' => $currency, 'status' => 'completed', 'transaction_id' => $transaction_id, 'address' => $address, 'amount' => $amount, 'blockno' => $block,'confirmation'=>$confirmations);
            Admindeposit::create($array_deposit);

        }

        
        public function admintransactions_list($txid) 
        {
            return Admindeposit::where('transaction_id', $txid)->count();
        }


        function get_gasprice()
        {
            $outputt = file_get_contents(Config::get('app.EthApi').'api?module=gastracker&action=gasoracle&apikey='.Config::get('app.EthApiKey'));
            $resultt = json_decode($outputt);
            $gasprice = $resultt->result->FastGasPrice;
            $gasprice = bcmul($gasprice, 1000000000);
            return $gasprice;
        }

        function connectethcheck($method, $data_param = array())
        {

            

                    if ($method == 'send') {

                        $json_data = array('data' => $data_param);
               
                        $getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
                        $datass = explode(" || ", $getFiless);
                        $ip = insep_decode($datass[0]);
                        $port = insep_decode($datass[1]);
                      
                        $url = $ip . ":" . $port;                      

                        $fromaddress = strtolower($json_data['data']['account']);
                        $touseraddress = strtolower($json_data['data']['toaddress']);

                        $amount = trim($json_data['data']['amount']);
                        $key = trim($json_data['data']['key']);
                        $gasPrice = trim($json_data['data']['gasPrice']);
                        $gasLimit = trim($json_data['data']['gasLimit']);
                        $gasLimit = '0x' . dechex($gasLimit);
                        $amount = '0x' . dechex($amount);
                        $gasPrice = '0x' . dechex($gasPrice);


            $unlockAddr = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"personal_unlockAccount","params":["' . $fromaddress . '","' . $key . '",null],"id":1}\' ' . $url);
            $unlockRes = json_decode($unlockAddr, true);
            
            if (!isset($unlockRes['error'])) {

                $unlockEth = $unlockRes['result'];

                if ($unlockEth == 1 || $unlockEth == "true" || $unlockEth == true) {

                    
                        $output = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"eth_sendTransaction","params":[{"from":"' . $fromaddress . '","to":"' . $touseraddress . '","gas":"' . $gasLimit . '","gasPrice":"' . $gasPrice . '","value":"' . $amount . '"}],"id":22}\' ' . $url);

                        
                        $result = json_decode($output, true);
                        if (!isset($result['error'])) {
                            $res = $result['result'];

                            if ($res == '' || $res == null) {
                                return false;
                            } else {
                                return $res;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

}
