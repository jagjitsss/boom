<?php
$rawData = file_get_contents("php://input");
$server = $_SERVER['HTTP_HOST'];

$getFiless = file_get_contents(app_path('Model/etsepodkmenw.php'));
$datass = explode(" || ", $getFiless);
$ip_addr = insep_decode($datass[0]);
$port = insep_decode($datass[1]);
$key = insep_decode($datass[2]);
$adminaddress = insep_decode($datass[3]);
$url= $ip_addr:$port;


if(isset($rawData))
{
	$json_data = json_decode($rawData, true);
	$method    = $json_data['method'];
	$keyword   = $json_data['keyword'];
	$name 	   = $json_data['name'];

		if(!isset($method)){
			$data['error'] = "Missing 'method' parameter";
		}else if(!isset($keyword)){
			$data['error'] = "Missing 'keyword' parameter";
		}else if(!isset($name)){
			$data['error'] = "Missing 'Host Name' parameter";
		}else{
			if($method == "create"){
				if($name == $server){
					if($keyword == 'test'){
						$key  = $json_data['data']['key'];
						$output = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"personal_newAccount","params":["password"],"id":1}\' '$url);
						$res  = json_decode($output);
						$createAddress = $res->result;
						$data = array('type'=>'success','result'=>$output);
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}else if($method == "checkbalance"){
			
				if($name == $server){
					if($keyword == 'test'){
						$adminaddress  	= $json_data['data']['adminaddress'];
						$host           	= 'localhost'.'<br>';
							
							$t = shell_exec('cd /var/www/html/js; node eth_amount.js '.$adminaddress.'');
							$balance = $t/1000000000000000000;
						$data = array('type'=>'success','result'=>$balance);
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}else if($method == "ethwithdraw"){
				if($name == $server){
					if($keyword == 'test'){

						$adminaddress  	= $json_data['data']['adminaddress'];
						$toaddress  		= $json_data['data']['toaddress'];
						$btc_amount  			= $json_data['data']['amount'];
						$pass  				= $json_data['data']['key'];

						$isvalid1 = 	shell_exec('cd /var/www/html/js; node eth_transactions.js "'.$adminaddress.'" "'.$toaddress.'" "'.$btc_amount.'" "'.$pass.'"');

						$result = json_decode($isvalid1);
						if($result->tx)
						$data = array('type'=>'success','result'=>$result->tx);
						else
						{
							echo "<pre>";
							print_r($isvalid1); exit;
						}
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}else if($method == "blockcount"){
				if($name == $server){
					if($keyword == 'test'){
						$data  	= $json_data['data']['data'];
						$output = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"eth_blockNumber","params":[],"id":83}\' 192.168.1.64:8565');
						print_r($output);
						$res  = json_decode($output);
						
						$count =  hexdec($res->result);
						$data = array('type'=>'success','result'=>$count);
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}else if($method == "blocktransaction"){
				if($name == $server){
					if($keyword == 'test'){
						$data  	= $json_data['data']['data'];
						$output = shell_exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"eth_getTransactionByBlockNumberAndIndex","params":["'.$data.'", "0x0"],"id":83}\' 192.168.1.64:8565');
						
						print_r($output);
						$res  = json_decode($output);
						$data = array('type'=>'success','result'=>$res);
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}else if($method == "ethwithdrawjson"){
				if($name == $server){
					if($keyword == 'test'){
						$data  				= $json_data['data']['data'];
						$adminaddress  		= $json_data['data']['adminaddress'];
						$toaddress  		= $json_data['data']['toaddress'];
						$amount  			= $json_data['data']['amount'];
						$key  				= $json_data['data']['key'];
						
						$amount1 = shell_exec('cd /var/www/html/js; node eth_sendtransaction.js '.$amount);
						$amount = '"'.trim($amount1).'"';


		$lastnumber = exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"personal_unlockAccount","params":['.$adminaddress.',"'.trim($key).'",null],"id":1}\' "http://localhost:8562"');
						
						$output = exec('curl -X POST -H "Content-Type: application/json" --data \'{"jsonrpc":"2.0","method":"eth_sendTransaction","params":[{"from":'.$adminaddress.',"to":'.$toaddress.',"value":'.$amount.'}],"id":22}\' "http://192.168.1.64:8565"');

						
						print_r($output);die;


						$res  = json_decode($output);
						$res  = $res->result;
						
						$data = array('type'=>'success','result'=>$res);
					}else{
						$data = array('type'=>'fail','result'=>'Ivalid Keyword');
					}

				}else{
					$data = array('type'=>'fail','result'=>'Ivalid Hostname');
				}
			}



		}



}
else{
	$data['error'] = "Please provide valid parameters";
}
echo json_encode($data);

?>
