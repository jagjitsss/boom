<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Addcoin;
use App\Model\AdminBankwire;
use App\Model\AdminNotification;
use App\Model\Bankwire;
use App\Model\Banner;
use App\Model\CoinProfit;
use App\Model\ConsumerVerification;
use App\Model\Country;
use App\Model\Currency;
use App\Model\Deposit;
use App\Model\Fiatdeposit;
use App\Model\Fiatwithdraw;
use App\Model\Googleauthenticator;
use App\Model\HelpCentre;
use App\Model\HelpIssue;
use App\Model\Notificationlist;
use App\Model\Notifications;
use App\Model\Referral;
use App\Model\Reqotp;
use App\Model\SiteSettings;
use App\Model\TradePairs;
use App\Model\User;
use App\Model\UserActivity;
use App\Model\Wallet;
use App\Model\Withdraw;
use App\Model\VerificationType;
use App\Model\News;
use App\Model\Verification;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use URL;
use Validator;


use App\Model\ExchangePairs;
use App\Model\ExchangeModel;


class Users extends Controller {

	public function __construct()
	{


	}
   
	public function dashboard() 
	{
		$id = session::get('tmaitb_user_id');
		
		
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}

		
		$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject','type','api_status','api_key','api_secret', 'set_default_currency')->first();


		$all_cur = $userbalance = $curr = array();
		$allcurr = Currency::where('status', 1)->select('image', 'type', 'symbol', 'id', 'name')->get();
		$userbalance = Wallet::getBalance($id);
		$estimateinr = 0;
		$userbalance = $curr = array();
		$allcurr = Currency::where('status', 1)->select('type', 'image', 'symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance','deposit_status', 'inr_value', 'btc_value','eur_value', 'gbp_value')->get()->map(function ($curr) {return ['key' => $curr->symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();


		$userbalance = Wallet::getBalance($id);
		$estimateinr = 0.00;
		$portFolioList = array();
		foreach ($allcurr as $curr)
		{
			$symbol = $curr['symbol'];
			if (isset($userbalance[$curr['id']]))
			{
				$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");

				if($user->set_default_currency =='EUR')
				{

					$inrbalance = $balance * $curr['eur_value'];
					$portFolioList[$symbol] = array("name" => $curr['name'], "balance" => $balance, "amtvalue" => $inrbalance, 'img' => $curr['image'], 'symbol' => $symbol);
				}
				elseif($user->set_default_currency =='GBP')
				{
					$inrbalance = $balance * $curr['gbp_value'];
					$portFolioList[$symbol] = array("name" => $curr['name'], "balance" => $balance, "amtvalue" => $inrbalance, 'img' => $curr['image'], 'symbol' => $symbol);
				}
				else
				{
					$inrbalance = $balance * $curr['inr_value'];
					$portFolioList[$symbol] = array("name" => $curr['name'], "balance" => $balance, "amtvalue" => $inrbalance, 'img' => $curr['image'], 'symbol' => $symbol);
				}

				
			}
			else
			{	
				$balance = 0;
				$inrbalance = 0;
			}
			$estimateinr += $inrbalance;
		}
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;

		$deposit = Deposit::where(array('user_id'=>$id, "status" => "Completed"))->orderBy('id', 'desc')->limit(2)->get();
		$fiatdeposit = Fiatdeposit::where(array('user_id'=>$id, "status" => "Completed"))->orderBy('id', 'desc')->limit(2)->get();


		/*
		$withdraw = Withdraw::where('user_id', $id)->where('status', '!=', 'Pending')->orderBy('id', 'desc')->limit(5)->get();
		$fiatdeposit = Fiatdeposit::where('user_id', $id)->orderBy('id', 'desc')->limit(5)->get();

		$fiatwithdraw = Fiatwithdraw::where('user_id', $id)->where('status', '!=', 'Pending')->orderBy('id', 'desc')->limit(5)->get();*/

		/*$logins = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('ip_address', 'browser_name', 'created_at','country','city')->orderBy('id', 'DESC')->take(4)->get();*/

		

		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();

		$dashboard = 1;
		$viewsource = 'front.users.overview';
		$editprofile = 0;
		$page = 1;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'logins', 'all_cur', 'dashboard', 'deposit', 'withdraw', 'fiatdeposit', 'fiatwithdraw', 'page', 'news', 'user','pairid', 'estimateinr', 'portFolioList'));
	}


	public function user_profile_view() 
	{
		$id = session::get('tmaitb_user_id');
		
		
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}

		callconversion($id);
		totalconversion($id,'EUR');
        $country = Country::where('status', '1')->select('country_name')->get();
		$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject','type','api_status','api_key','api_secret')->first();

		$logins = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('ip_address', 'browser_name', 'created_at','country','city')->orderBy('id', 'DESC')->take(4)->get();
		$all_cur = $userbalance = $curr = array();
		$allcurr = Currency::where('status', 1)->select('image', 'type', 'symbol', 'id', 'name')->get();
		$userbalance = Wallet::getBalance($id);
		foreach ($allcurr as $key => $value) {
			if (isset($userbalance[$value->id])) {
				$balance_val = rtrim(rtrim(sprintf('%.8F', $userbalance[$value->id]), '0'), ".");
			} else {
				$balance_val = 0;
			}
			$curr['balance'] = $balance_val;
			$curr['symbol'] = $value->symbol;
			$curr['image'] = $value->image;
			$curr['name'] = $value->name;
			$curr['type'] = $value->type;
			array_push($all_cur, $curr);
		}

		$orders = DB::table('redor_nioc as CO')->where(['CO.user_id' => $id])->whereIn('CO.status', ['partially', 'filled', 'cancelled'])->leftjoin('pmetredor AS A', 'A.buyorderId', '=', 'CO.id')->leftjoin('pmetredor AS B', 'B.sellorderId', '=', 'CO.id')->select('CO.Type as Type', 'CO.id as id', 'CO.Price as Price', 'CO.Amount as Amount', 'CO.status as status', 'CO.ordertype as ordertype', 'CO.Fee as Fee', 'CO.firstCurrency as firstCurrency', 'CO.secondCurrency as secondCurrency', 'CO.updated_at as updated_at', 'A.buyorderId as buyorderId', 'B.sellorderId as sellorderId', 'A.cancel_id as buyCancel', 'B.cancel_id as sellCancel', 'A.filledAmount as buyFilled', 'B.filledAmount as sellFilled', 'A.askPrice as buyPrice', 'B.askPrice as sellPrice', 'A.updated_at as buyUpdate', 'B.updated_at as sellUpdate')->orderBy('CO.updated_at', 'desc')->limit(5)->get()->toArray();

		$deposit = Deposit::where('user_id', $id)->orderBy('id', 'desc')->limit(5)->get();

		$withdraw = Withdraw::where('user_id', $id)->where('status', '!=', 'Pending')->orderBy('id', 'desc')->limit(5)->get();

		$fiatdeposit = Fiatdeposit::where('user_id', $id)->orderBy('id', 'desc')->limit(5)->get();

		$fiatwithdraw = Fiatwithdraw::where('user_id', $id)->where('status', '!=', 'Pending')->orderBy('id', 'desc')->limit(5)->get();

		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;

		$verificationtype = VerificationType::get();
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$banner = Banner::where('page','Dashboard')->first();
		$dashboard = 1;
		$viewsource = 'front.users.profile_overview';
		$editprofile = 0;
		$page = 1;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'logins', 'all_cur', 'dashboard', 'orders', 'deposit', 'withdraw', 'fiatdeposit', 'fiatwithdraw', 'page', 'from_symbol', 'to_symbol', 'pairid', 'country','verificationtype','news','banner'));
	}

	public function buy_sell_exchange()
	{

		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$wcwr = $id = session::get('tmaitb_user_id');

		if($wcwr)
		{
			$type=1;
		}
		else
		{
			$type=0;
		}

		$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject','type','api_status','api_key','api_secret')->first();


		$userbank = Bankwire::where(array('user_id'=>$id))->get();





		$wallet = Wallet::where('user_id',$wcwr)->first();
		$viewsource = 'front.users.buysellexchange';
		$coinPairs = array();		

		$buyexchangepairs = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->map(function ($curr) {return ['key' => $curr->from_symbol.$curr->to_symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();

			$sellexchangepairs = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->map(function ($curr) {return ['key' => $curr->to_symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();	

			$exchangepair = ExchangePairs::where('status','1')->orderBy('eid','asc')->get()->toArray();
			$results = self::arraygroupBy($exchangepair,'to_symbol');
			
		

		$currency['USD'] = Currency::where('status','1')->get()->map(function ($currL) {return ['key' => $currL->symbol, 'value' => $currL->inr_value];})->pluck('value', 'key')->toArray();

		$currency['EUR'] = Currency::where('status','1')->get()->map(function ($currL) {return ['key' => $currL->symbol, 'value' => $currL->eur_value];})->pluck('value', 'key')->toArray();

		$currency['GBP'] = Currency::where('status','1')->get()->map(function ($currL) {return ['key' => $currL->symbol, 'value' => $currL->gbp_value];})->pluck('value', 'key')->toArray();

		


		$tradepairs = TradePairs::where('status', '1')->select('id','from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$newshome = News::where('status', 'active')->orderBy('id', 'desc')->limit(2)->get();
		$newsdetails = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$banner = Banner::select('image_url','url')->where('status','1')->where('page','Home')->get();

		$home = 1;
		$page = 0;

		

		$news =News::where('status', 'active')->orderBy('id', 'desc')->get();
		$editprofile = 1;
		$page = 1;
		$vieweditsource = 'front.users.buysellexchange';
		$viewsource = 'front.users.buysellexchange';

		return view('front.users.buysellindex', compact('vieweditsource',  'home', 'features', 'currency','from_symbol','to_symbol','pairid','banner','newsdetails','newshome','page','buyexchangepairs','sellexchangepairs','wcwr','wallet','results','type','user', 'userbank', 'news', 'editprofile', 'page', 'viewsource'));

	}
	
	public function editProfile() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');

		if (isset($_GET['name'])) {
			$show_tab = '';
			$param = $_GET['name'];
			if ($param == 'notification') {
				$update_alert = Notificationlist::where('user_id', $id)->update(['status' => 1]);
			}

		}
		$pro = session::get('form');

		$country = Country::where('status', '1')->select('country_name')->get();
		/*$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject', 'Flag', 'Short', 'Flagcode')->first();*/

		$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject','type','api_status','api_key','api_secret','Flag', 'Short', 'Flagcode', 'set_default_currency')->first();



		$notification = Notificationlist::where('user_id', $id)->orderBy('id', 'desc')->paginate(50);

		require_once app_path('Model/Googleauthenticator.php');
		$ga = new Googleauthenticator();
		$secret = $ga->createSecret();
		$tfa_url = $ga->getQRCodeGoogleUrl('BoomCoin (' . getUserName($id) . ')', $secret);
		if ($user->mobile == '' && $pro == 'check')
		{
			Session::flash('error', trans('app_lang.fill_your_profile_details'));
			Session::put('form', 'no');
		}

		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news =News::where('status', 'active')->orderBy('id', 'desc')->get();
		$vieweditsource = 'front.users.editdashboard';
		$editprofile = 1;
		$page = 1;
		return view('front.users.index', compact('vieweditsource', 'editprofile', 'country', 'user', 'secret', 'tfa_url', 'notification', 'from_symbol', 'to_symbol', 'pairid', 'page','news'));

	}
	
	public function funds() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$convertion = callconversion($id);
		
		$adminbank = AdminBankwire::where('status', 1)->select('accountholdername', 'accountno', 'swift', 'bankname', 'bankaddress', 'id')->first();

		$fiatcurr = Currency::where('type', 'fiat')->select('symbol', 'id')->get();
		$userbank = Bankwire::where(array('user_id'=>$id))->first();

		$bankDetailsByFiat = array();
		foreach($fiatcurr as $fc)
		{
			$userbankitem = Bankwire::where(array('user_id'=>$id, 'currency' => $fc->symbol))->first();
			$bnkDetail = array(
				'bankname' => $userbankitem['bankname'],
				'id' => $userbankitem['id'],
				'currency' => $userbankitem['currency']

			);
			$bankDetailsByFiat[$fc->symbol] = $bnkDetail;
		}
		
		$user = DB::table('sresu')
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('profile', 'verified_status', 'randcode', 'id_status', 'selfie_status')->first();

		$withdarw_content = $withdraw_maintenance = $all_cur = $userbalance = $curr = array();
		
		$allcurr = Currency::where('status', 1)->select('type', 'image', 'symbol', 'id', 'name', 'min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status', 'withdarw_content', 'withdraw_maintenance','deposit_status', 'inr_value', 'btc_value', 'eur_value')->get()->map(function ($curr) {return ['key' => $curr->symbol, 'value' => $curr];})->pluck('value', 'key')->toArray();

		$userbalance = Wallet::getBalance($id);
		$estimatebtc = 0;
		$estimateinr = 0;
		foreach ($allcurr as $curr)
		{
			$btcval = $curr['btc_value'];
			$symbol = $curr['symbol'];
			$withdraw[$symbol]['maintenance'] = $curr['withdraw_maintenance'];
			$withdraw[$symbol]['content'] = $curr['withdarw_content'];

			$allcurr[$symbol]['status'] = $curr['withdarw_status'];
            $allcurr[$symbol]['depstatus'] = $curr['deposit_status'];

			

			$inorders = inorders($symbol, $id);

   			$inorders = $inorders['inorder_buy'] + $inorders['inorder_sell'] + $inorders['inorder_crypto_withdraw'] + $inorders['inorder_fiat_withdraw']+ $inorders['exchange_sell'] + $inorders['exchange_buy'];

   			$inorders = rtrim(rtrim(sprintf('%.8F', $inorders), '0'), ".");

			if (isset($userbalance[$curr['id']])) {
				$balance = rtrim(rtrim(sprintf('%.8F', $userbalance[$curr['id']]), '0'), ".");
				$btcbalance = $btcval * $balance;
				
				$inrbalance = $balance * $curr['eur_value'];


				
			} else {
				$balance = 0;
				$btcbalance= 0;
				$inrbalance = 0;

			}
			$allcurr[$symbol]['inorders'] = $inorders;
			$allcurr[$symbol]['balance'] = $balance;
			$allcurr[$symbol]['total'] = $inorders + $balance;
			$allcurr[$symbol]['btctotal'] = $btcbalance;
			$allcurr[$symbol]['inrtotal'] = $inrbalance;
			unset($allcurr[$symbol]['id']);
			unset($allcurr[$symbol]['withdraw_maintenance']);
			unset($allcurr[$symbol]['withdarw_content']);
			$estimatebtc += $btcbalance;
			$estimateinr += $inrbalance;
		}



		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();


		$exchange = ExchangeModel::where('user_id', $id);
		$exchange_count = $exchange->count();
		$dataRecords = array();
			$no = 1;

		if ($exchange_count) 
		{
			$orders = $exchange->orderBy('created_at', 'desc')->get();

			foreach ($orders as $r) {
				
				$digits = ($r['from_symbol'] == 'USD') ? 2 : 8;
				$digits1 = ($r['to_symbol'] == 'USD') ? 2 : 8;

				$amount =number_format($r['amount'], $digits, '.', '');
				$fees =number_format($r['fees'], $digits1, '.', '');
				$total =number_format($r['total'], $digits1, '.', '');

		
				array_push($dataRecords, array(
					$no,
					$r['created_at'],
					ucfirst($r['type']),
					$r['from_symbol'],
					$r['to_symbol'],
					$amount." ".$r['from_symbol'],
					$fees." ".$r['to_symbol'],
					$total." ".$r['to_symbol'],
					$r['status'],
					
				));
				$no++;
			}
		}


		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
	$viewsource = 'front.users.funds';
		$editprofile = 0;
		$page = 3;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'allcurr', 'page', 'withdraw', 'bankwire', 'adminbank', 'fiatcurr', 'userbank', 'from_symbol', 'to_symbol', 'pairid','news', 'estimatebtc', 'estimateinr', 'bankDetailsByFiat', 'dataRecords'));

	}
	 
	public function getadminbankwire($currency) 
	{
		$adminbank = AdminBankwire::where('status', 1)->where('currency',$currency)->first();
		$show_json = json_encode($adminbank, JSON_FORCE_OBJECT);
		echo $show_json;
		exit;
	}
	 
	public function get_adminbankwire($bid) 
	{
		$adminbank = AdminBankwire::where('id', $bid)->first();
		$show_json = json_encode($adminbank, JSON_FORCE_OBJECT);
		echo $show_json;
		exit;
	}
	
	public function get_fiatcurrency($fid) 
	{
		$fiat = Currency::where('id', $fid)->first();
		$show_json = json_encode($fiat, JSON_FORCE_OBJECT);
		echo $show_json;
		exit;
	}
	
	public function refnoexists(Request $request) 
	{
		if ($request->isMethod('post')) 
		{
			$refno = $request['refno'];
			$currency = $request['currency'];
			$fiat = Currency::where('symbol', $currency)->first();
			$check = DB::table('tisoped_taif')->select('referencenum')->where('referencenum', $refno)->where('currency_id', $fiat->id)->where('status', '!=', 'Cancelled')->count();
			if ($check > 0) 
			{
				echo json_encode(array(false, 'Reference number already exist'));
			}
			else 
			{
				echo json_encode(true);
			}
		}
	}
	
	public function fiatdeposit() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$randcode = randomcode(5);
		$data = Input::all();
		$validate = Validator::make($data, [
			'account' => "required",
			'currency' => "required",
			'depositamount' => 'required',
			'ref_no' => 'required',
			'payment' => 'required',
			'file' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$account = strip_tags($data['account']);
		$currency = strip_tags($data['currency']);
		$currencyid = getCurrencyid($currency);
		$depositamount = strip_tags($data['depositamount']);
		$refno = strip_tags($data['ref_no']);
		$payment = strip_tags($data['payment']);
		if($depositamount > 0)
		{
			$update_arr = ['user_id' => $id, 'payment_method' => $payment, 'currency_id' => $currencyid, 'currency' => $currency, 'amount' => $depositamount, 'referencenum' => $refno, 'bankid' => $account, 'status' => 'Pending'];
			if ($_FILES['file']['name']) {
				$fileExtensions = array('jpeg', 'jpg', 'png');
				$filename = Controller::uploadFiles('file', $fileExtensions);
				$update_arr['proof'] = $filename;
			}

			$check = Fiatdeposit::select('referencenum')->where('referencenum', $refno)->where('currency_id', $currencyid)->where('status', '!=', 'Cancelled')->count();
			if($check > 0)
			{
				Session::flash('error', trans('app_lang.refnoexists'));
				return Redirect::back();
			}
			else
			{
				$update = Fiatdeposit::create($update_arr);
				if ($update) 
				{
					Session::flash('success', trans('app_lang.fiat_deposit_success'));
					return Redirect::back();
				} 
				else 
				{
					Session::flash('error', trans('app_lang.please_try_again'));
					return Redirect::back();
				}
			}
		}
		else
		{
			Session::flash('error', trans('app_lang.depamount_error'));
			return Redirect::back();
		}
	}
	
	public function fiatwithdraw() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		$validate = Validator::make($data, [
			'withbank' => "required",
			'fiatamount' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$get_data = DB::table('sresu')
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'verified_status', 'randcode', 'id_status', 'selfie_status', 'liame', 'contentmail', 'secret')->first();
		if ($get_data->id_status != 3 || $get_data->selfie_status != 3) {
			Session::flash('error', trans('app_lang.verify_kyc'));
			return redirect("funds?name=withdraw");
		}
		$bank = strip_tags($data['withbank']);
		$currencyid = strip_tags($data['fiatcurrency']);
		$withdrawamount = strip_tags($data['fiatamount']);

		$fiatcurrency = Currency::where('symbol', $currencyid)->select('min_withdraw', 'max_withdraw', 'with_fee', 'withdarw_status','id')->first();

		$min_withdraw = $fiatcurrency->min_withdraw;
		$max_withdraw = $fiatcurrency->max_withdraw;
		$with_fee = $fiatcurrency->with_fee;

		if ($fiatcurrency) 
		{
			if ($fiatcurrency->withdarw_status == 1) 
			{
				$balance = getBalance($id, $fiatcurrency->id);
				$fee = $withdrawamount * $with_fee / 100;
				$givenamount = $withdrawamount - $fee;
				
				if($balance > 0){
					if ($withdrawamount < $min_withdraw) 
					{
						Session::flash('error', trans('app_lang.enter_withdraw_amount_greater'));
						return redirect("funds?name=withdraw");
					} 
					else if ($withdrawamount > $max_withdraw) 
					{
						Session::flash('error', trans('app_lang.enter_withdraw_amount_lesser'));
						return redirect("funds?name=withdraw");
					} 
					else if ($withdrawamount > $balance) 
					{
						Session::flash('error', trans('app_lang.enter_amount_less_balance_amount'));
						return redirect("funds?name=withdraw");
					} 
					else 
					{
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
						$currency = getCurrencysymbol($fiatcurrency->id);
						$code = time() . $id . rand(99, 99999);
						$encryptUId = insep_encode($code);
						$expire = strtotime('+1 day', time());
						$remarks = 'withdraw ' . $currency . ' ' . $withdrawamount;
						$update_arr = array('user_id' => $id, 'bankid' => $bank, 'currency_id' => $fiatcurrency->id, 'currency' => $currency, 'amount' => $withdrawamount, 'fee_amt' => $fee, 'fee_per' => $with_fee, 'given_amount' => $givenamount, 'ip_addr' => $_SERVER['REMOTE_ADDR'], 'confirm_code' => $code, 'expire' => $expire, 'status' => 'Pending', 'remarks' => $remarks);

						$update = Fiatwithdraw::create($update_arr);
						if ($update) 
						{
							$bal = Wallet::getBalance($id, $fiatcurrency->id);
							$update_balance = $bal - $withdrawamount;

							$balupdate = Wallet::updateBalance($id, $fiatcurrency->id, $update_balance);


							$useremail = getUserEmail($id);
							$name = getUserName($id);

							$securl = url("/confirmwithdrawbyuser/" . $encryptUId);
							$rsecurl = url("/rejectwithdrawbyuser/" . $encryptUId);

							$info = array('###CUR###' => $currency, '###AMOUNT###' => $withdrawamount, '###TRANSFER###' => $givenamount, '###FEE###' => $fee, '###USER###' => $name, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl);

							$sendEmail = Controller::sendEmail($useremail, $info, '28');
							Session::flash('success', trans('app_lang.fiat_withdraw_success'));
							return Redirect::back();
						} 
						else 
						{
							Session::flash('error', trans('app_lang.please_try_again'));
							return Redirect::back();
						}
					}
				}
				else
				{
					Session::flash('error',trans('app_lang.insufficient_balance'));
					return redirect("funds?name=withdraw");
				}
			} 
			else 
			{
				Session::flash('error', trans('app_lang.withdraw_disabled'));
				return redirect("funds?name=withdraw");
			}
		}
    }
    
	public function confirmWithdrawProcess($id) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$enc_id = $id;
		$id = insep_decode($id);
		return self::checkWithdrawalRequest($id, $enc_id, 1);
	}
	
	public function RejectWithdrawProcess($id) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$enc_id = $id;
		$id = insep_decode($id);
		return self::checkWithdrawalRequest($id, $enc_id, 0);
	}
	
	public function checkWithdrawalRequest($id, $enc_id, $status) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
        $checkWithdraw = Fiatwithdraw::where('confirm_code', $id)->select('user_id')->first();
		$user_id = session::get('tmaitb_user_id');
		if ($user_id) 
		{
			if ($checkWithdraw) 
			{
				if ($checkWithdraw->user_id == $user_id) 
				{
					$checkWithdraw = Fiatwithdraw::where('confirm_code', $id)->select('given_amount', 'amount', 'currency_id', 'expire', 'status', 'is_flag', 'id')->first();
					if ($checkWithdraw->status != 'Pending') 
					{
						Session::flash('error', trans('app_lang.withdraw_link_used_lng'));
						return Redirect::to('/funds');
					} 
					else if ($checkWithdraw->expire < time()) 
					{
						Session::flash('error', trans('app_lang.withdraw_request_expired'));
						return redirect('/funds');
					} 
					else 
					{
						if ($status == 1) 
						{
							if ($checkWithdraw->is_flag == 0) 
							{
								$update = Fiatwithdraw::where('confirm_code', $id)->update(array('is_flag' => '1'));
								if ($update) {
									$transfer_amount = $checkWithdraw->given_amount;
									$currency = getCurrencysymbol($checkWithdraw->currency_id);
									return self::completeWithdraw($id, $user_id, $transfer_amount, $currency);
								}
							} 
							else 
							{
								Session::flash('error', trans('app_lang.please_try_again'));
								return Redirect::to('/funds');
							}
						} 
						else if ($status == 0)
                        {
							$amount = $checkWithdraw->amount;
							$currency = getCurrencysymbol($checkWithdraw->currency_id);
							return self::rejectWithdraw($id, $user_id, $amount, $currency);
						} 
						else 
						{
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
	
	function completeWithdraw($confirm, $user_id, $transfer_amount, $currency) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$log_user_id = session::get('tmaitb_user_id');
		if ($log_user_id) 
		{
			if ($log_user_id == $user_id) 
			{
				$code = time() . $user_id . rand(999, 999999);
				Fiatwithdraw::where('confirm_code', $confirm)->update(array('status' => 'Processing', 'with_token' => $code));

				$withdraw_req = Fiatwithdraw::where('confirm_code', $confirm)->select('fee_amt', 'amount')->first();

				$email = session::get('tmaitb_user_email');
				$fee_amt = $withdraw_req->fee_amt;
				$name = getUserName($user_id);
				$encryptUId = insep_encode($code);
				$encryptUsId = insep_encode($user_id);
				$getSiteDetails = Controller::getSitedetails();

				$adminParam = $getSiteDetails->admin_redirect;
				$admin = env('DOMAIN_URL').$adminParam.'/';
				
				$securl = $admin . "confirmfiatWithdraw/" . $encryptUId;
				$rsecurl = $admin . "rejectfiatWithdraw/" . $encryptUId . '/' . $encryptUsId;

				$info = array('###TRANSFER###' => $transfer_amount, '###CUR###' => $currency, '###AMOUNT###' => $withdraw_req->amount, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl, '###FEE###' => $fee_amt, '###USER###' => $email, '###NAME###' => $name);

				$toemail1 = $getSiteDetails->site_email;
				$toemail = insep_decode($toemail1);

				$bcc = '1';
				$sendEmail = Controller::sendEmail($toemail, $info, '32');
				if ($sendEmail) 
				{
					Session::flash('success', 'Withdraw request send to admin');
					return Redirect::to('/funds');
				} 
				else 
				{
					Session::flash('error', trans('app_lang.please_try_again'));
				}
			}
			return Redirect::to('/funds');
		}
	}
	
	function rejectWithdraw($confirm, $user_id, $amount, $currency) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$log_user_id = session::get('tmaitb_user_id');
		if ($log_user_id) 
		{
			if ($log_user_id == $user_id) 
			{
				$currency_detail = Currency::where('symbol', $currency)->select('id')->first();
				if ($currency_detail) {
					$cur_id = $currency_detail->id;
					$array_withdraw = array('status' => '2');
					$balance = Wallet::getBalance($user_id, $cur_id);
					$update_balance = $balance + $amount;
					$remarks = 'withdraw request cancelled ' . $currency . ' ' . $amount;

					$result = DB::transaction(function () use ($confirm, $user_id, $cur_id, $update_balance, $remarks) {
						Fiatwithdraw::where('confirm_code', $confirm)->update(array('status' => 'Cancelled', 'remarks' => $remarks));
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
	
	public function referral() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');

		$user = User::where('id', $id)->select('profile', 'referrer_name')->first();
		$record = DB::select("SELECT SUM(commision) AS refer_commision, count(*) as count FROM tmaitb_larrefer where refered_by =" . $id);
		$refer_commision = $record[0]->refer_commision;
		$refer_count = $record[0]->count;
		$refercount =  User::where('refer_by', $id )->count();
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.referral';
		$editprofile = 0;
		$page = 4;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'refer_count', 'refer_commision', 'page', 'from_symbol', 'to_symbol', 'pairid','refercount','news'));

	}
	
	public function addCoins() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');

		$user = User::where('id', $id)->select('profile')->first();
		$coin_info = SiteSettings::where('id', 1)->select('new_coin_fee', 'new_coin_fee_status')->first();
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.coins';
		$editprofile = 0;
		$page = 5;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'coin_info', 'from_symbol', 'to_symbol', 'pairid','news'));

	}
	
	public function updateCoins() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$randcode = randomcode(5);
		$data = Input::all();
		$validate = Validator::make($data, [
			'coin_name' => "required|min:3|max:20",
			'coin_symbol' => "required|min:3|max:20",
			'coin_type' => "required",
			'coin_website' => 'required|url',
			'coin_chat' => 'required|url',
			'coin_git' => 'required|url',
			'coin_explorer' => 'required|url']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}

		$getUrl = SiteSettings::where('id', 1)->select('new_coin_fee', 'new_coin_fee_status')->first();
		$status = $getUrl->new_coin_fee_status;

		if ($status == 1) 
		{
			$fees = $getUrl->new_coin_fee;
			$firstbal = Wallet::getBalance($id, 1);
			if ($firstbal < $fees) {
				Session::flash('error', "You don't have enough minimum balance to add new coin");
				return Redirect::back();
			}
			$debit_balance = $firstbal - $fees;
			Wallet::updateBalance($id, 1, $debit_balance);

			$theftdata = array(
				'user_id' => $id,
				'theftAmount' => $fees,
				'theftCurrency' => 'BTC',
				'type' => 'New coin fees',
			);

			CoinProfit::create($theftdata);

		}

		$coin_name = strip_tags($data['coin_name']);
		$coin_symbol = strip_tags($data['coin_symbol']);
		$coin_type = strip_tags($data['coin_type']);
		$coin_website = strip_tags($data['coin_website']);
		$coin_chat = strip_tags($data['coin_chat']);
		$coin_git = strip_tags($data['coin_git']);
		$coin_explorer = strip_tags($data['coin_explorer']);

		$update_arr = ['user_id' => $id, 'coin_name' => $coin_name, 'coin_symbol' => $coin_symbol, 'coin_type' => $coin_type, 'coin_website' => $coin_website, 'coin_chat' => $coin_chat, 'coin_git' => $coin_git, 'coin_explorer' => $coin_explorer];
		if ($_FILES['file']['name']) {
			$fileExtensions = array('jpeg', 'jpg', 'png');
			$filename = Controller::uploadFiles('file', $fileExtensions);

			$update_arr['image'] = $filename;
		}
		$update = Addcoin::create($update_arr);
		if ($update) 
		{

			$get_data = User::where('id', $id)->select('liame', 'contentmail')->first();
			$email = insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
			$update_id = $update->id;
			$adminNotify['admin_id'] = 1;
			$adminNotify['doc_id'] = $update_id;
			$adminNotify['type'] = "Coin";
			$adminNotify['message'] = $email . ' has added a new coin ' . $coin_name;
			$adminNotify['status'] = "unread";
			AdminNotification::create($adminNotify);

			Session::flash('success', trans('app_lang.coin_added_success'));
			return Redirect::back();
		} 
		else 
		{
			Session::flash('error', trans('app_lang.please_try_again'));
			return Redirect::back();
		}

	}
	
	public function updateProfile() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		$randcode = randomcode(5);
		$validate = Validator::make($data, [
			'first_name' => "required|min:3|max:15",
			//'last_name' => "required|min:3|max:15",
			//'dob' => 'required',
			//'address1' => 'required',
			//'city' => 'required',
			///'state' => 'required',
			///'country' => 'required',
			'pincode' => 'required',
		]);
		if ($validate->fails())
		{
			foreach ($validate->messages()->getMessages() as $val => $msg)
			{
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$first_name = strip_tags($data['first_name']);
		//$last_name = strip_tags($data['last_name']);
		//$dob = strip_tags($data['dob']);
		//$gender = strip_tags($data['gender']);
		//$address1 = strip_tags($data['address1']);
		//$address2 = strip_tags($data['address2']);
		//$city = strip_tags($data['city']);
		//$state = strip_tags($data['state']);
		//$country = strip_tags($data['country']);
		$pincode = strip_tags($data['pincode']);
		
		// $update_arr = ['first_name' => $first_name, 'last_name' => $last_name, 'dob' => $dob, 'gender' => $gender, 'address1' => $address1, 'address2' => $address2, 'city' => $city, 'state' => $state, 'country' => $country, 'pincode' => $pincode];
		$update_arr = ['first_name' => $first_name, 'pincode' => $pincode];

		if (isset($_FILES['file']['name']) && $_FILES['file']['name']) {
			$fileExtensions = array('jpeg', 'jpg', 'png');
			$filename = Controller::uploadFiles('file', $fileExtensions);
			
			$update_arr['profile'] = $filename;
		}
		$update = User::where('id', $id)->update($update_arr);
		if ($update) 
		{
			$message = 'You have updated your profile details';
			Controller::siteNotification($message, $id);
			session::put(['tmaitb_profile' => $first_name]);
			Session::flash('success', trans('app_lang.profile_updated_success_lng'));
			return Redirect::back();
		} 
		else 
		{
			Session::flash('error', trans('app_lang.please_try_again'));
			return Redirect::back();
		}
	}
	
	public function updateTfa() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		$validate = Validator::make($data, [
			'onecode' => 'required|numeric|min:6']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$get_notify = Notifications::where('user_id', $id)->first();
		$get_tfa = $get_notify->tfa;
		$get_data = User::where('id', $id)->select('secret', 'randcode')->first();
		$secret = $get_data->secret && $get_data->randcode == 1 ? $get_data->secret : $data['secret'];
		$code = $data['onecode'];
		$pswd = insep_encode($data['psswd']);
		$check = User::select('ticket')->where('ticket', $pswd)->where('id', $id)->count();
		if($check > 0)
		{
			require_once app_path('Model/Googleauthenticator.php');
			$ga = new Googleauthenticator();

			if ($ga->verifyCode($secret, $code, 2)) {
				if ($get_data->randcode) {
					$status = 'deactivated';
					$update = array('randcode' => 0, 'secret' => '');
				} else {
					$status = 'activated';
					$update = array('randcode' => 1, 'secret' => $secret);
				}
				$result = User::where('id', $id)->update($update);
				if ($result) 
				{
					$message = 'You have ' . $status . ' 2FA status';
					Notificationlist::create(array('user_id' => $id, 'message' => $message));
					if ($get_tfa == 1) {
						$info = array('###MESSAGE###' => $message, '###USER###' => getUserName($id), '###LINK###' => URL('/contactus'));
						$to = getUserEmail($id);
						Controller::sendEmail($to, $info, 17);
					}
					if ($status == 'activated') {
						Session::flash('success', trans('app_lang.your_tfa_activate_lng'));
						return redirect('/dashboard');
					} else {
						Session::flash('success', trans('app_lang.your_tfa_deactivate_lng'));
						return Redirect::back();
					}
					
				} 
				else 
				{
					Session::flash('error', trans('app_lang.please_try_again'));
					return Redirect::back();
				}
			} 
			else 
			{
				Session::flash('error', trans('app_lang.invalid_2fa_lng'));
				return Redirect::back();
			}
		} 
		else 
		{
			Session::flash('error', trans('app_lang.pass_wrong'));
			return Redirect::back();
		}
	}
	
	public function updateUserAlert() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		$validate = Validator::make($data, [
			'type' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				echo "validation_error";exit;
			}
		}

		$my_array = array('device' => 'new_device_login', '2fa' => 'tfa', 'password' => 'change_password', 'trade' => 'trade');
		$type = $data['type'];
		$update = $my_array[$type];
		$get_data = Notifications::where('user_id', $id)->select($update)->first();
		if ($get_data[$update] == 1) 
		{
			$update_value = 0;
		} else {
			$update_value = 1;
		}
		$update_alert = Notifications::where('user_id', $id)->update([$update => $update_value]);
		if ($update_alert) 
		{
			if ($update_value == 0) 
			{
				echo "1";
			} else {
				echo "2";
			}
		} 
		else 
		{
			echo "0";
		}
		exit;

	}
	
	public function updateUserPassword() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
			$data = Input::all();
			$id = session::get('tmaitb_user_id');
			$get_notify = Notifications::where('user_id', $id)->first();
			$get_password = $get_notify->change_password;

			$validate = Validator::make($data, [
				'oldpassword' => 'required|min:8',
				'password' => 'required|confirmed|min:8',
				'password_confirmation' => 'required|min:8',
			], [
				'oldpassword.required' => 'Enter old password',
				'password.required' => 'Enter password',
				'password.min' => 'Enter atleast 8 characters',
				'password_confirmation.required' => 'Enter confirm password',
			]
		    );
			if ($validate->fails()) {
				foreach ($validate->messages()->getMessages() as $val => $msg) {
					Session::flash('error', $msg[0]);
					return Redirect::back();
				}
			}
			$oldpassword = strip_tags(insep_encode($data['oldpassword']));
			$validpssword = User::where('id', $id)->where('ticket', $oldpassword)->count();
			if ($validpssword == 0) {
				Session::flash('error', trans('app_lang.incorrect_current_password_lng'));
				return Redirect::back();
			}

			$password = strip_tags(insep_encode($data['password']));
			$update = User::where('id', $id)->update(['ticket' => $password,'online'=>1]);
			if ($update) 
			{

				$get_count = Notifications::where('user_id', $id)->where('change_password', 1)->count();
				if ($get_count == 1) 
				{
					$message = 'You have changed your password';
					Notificationlist::create(array('user_id' => $id, 'message' => $message));
					if ($get_password == 1) 
					{
						$info = array('###MESSAGE###' => $message, '###USER###' => getUserName($id), '###LINK###' => URL('/contactus'));
						$to = getUserEmail($id);
						Controller::sendEmail($to, $info, 15);
					}
				}
				Session::flash('success', trans('app_lang.password_changed_success_lng'));
				$type = 'Logged_out';
				$create_activity = Controller::UserActivityEntry($id, $type);
				DB::table('sresu')->where('id', $id)->update(['session_id' => '','login_status'=>'0','browser_status' => '0']);
				Session::forget('tmaitb_user_id');
				return Redirect::to('/');
			} 
			else 
			{
				Session::flash('error', trans('app_lang.please_try_again'));
				return Redirect::back();
			}
			
	}
	
	public function getDevices() 
	{
		$id = session::get('tmaitb_user_id');
		$totalrecords = intval(Input::get('totalrecords'));
		$draw = Input::get('draw');
		$start = Input::get('start');
		$length = Input::get('length');
		$sorttype = Input::get('order');
		$sort_col = $sorttype['0']['column'];
		$sort_type = $sorttype['0']['dir'];
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		if ($sort_col == '1') {
			$sort_col = 'created_at';
		} else if ($sort_col == '2') {
			$sort_col = 'ip_address';
		} else if ($sort_col == '3') {
			$sort_col = 'browser_name';
		} else if ($sort_col == '4') {
			$sort_col = 'os_name';
		} else {
			$sort_col = 'id';
		}
		$results = DB::select("SELECT * FROM `tmaitb_ytivitca_resu` WHERE `user_id` = " . $id . " AND `activity` = 'Logged_in'  ORDER BY `" . $sort_col . "` " . $sort_type . " LIMIT " . $start . ", " . $length);
		$totalrecords = DB::select("SELECT count(*) as count  FROM `tmaitb_ytivitca_resu` WHERE `user_id` = " . $id . " AND `activity` = 'Logged_in'");
		$totalrecords = $totalrecords[0]->count;

		$data = array();
		$no = $start + 1;
		if ($totalrecords) 
		{
          foreach ($results as $r) 
          {
				$ip = $r->ip_address;
				$browser = $r->is_site ? $r->browser_name . '(OS - ' . $r->os . ')' : $r->browser_name . '(App)';
				$date = $r->created_at;
				if (!empty($r->city)) {
					$location = $r->city . ', ' . $r->country;
				} else {
					$location = $r->country;
				}
				array_push($data, array(
					$no,
					$ip,
					$browser,
					$date,
					$location,

				));
				$no++;
			}
			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => $data));
		} 
		else 
		{

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => array()));
		}
	}
	
	public function support() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$lang = session::get('language') ? session::get('language') : 'en';

		$tickets_active = $tickets_inactive = array();
		$user = User::where('id', $id)->select('verified_status', 'randcode', 'mobile', 'profile')->first();
		$category = HelpIssue::where('language_code', $lang)->select('category', 'id')->get();
		$tickets_inactive = HelpCentre::where('ticket_status', 'close')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$tickets_active = HelpCentre::where('ticket_status', 'active')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$categories = HelpIssue::select('id', 'category')->get();
		$profile = User::where('id', $id)->select('profile')->first()->profile;

		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$vieweditsource = 'front.users.support';
		$editprofile = 2;
		$page = 6;
		return view('front.users.index', compact('vieweditsource', 'editprofile', 'user', 'category', 'tickets_inactive', 'tickets_active', 'page', 'profile', 'from_symbol', 'to_symbol', 'pairid','news'));

	}
   
	public function view_support() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$lang = session::get('language') ? session::get('language') : 'en';

		$tickets_active = $tickets_inactive = array();
		$user = User::where('id', $id)->select('verified_status', 'randcode', 'mobile', 'profile')->first();
		$category = HelpIssue::where('language_code', $lang)->select('category', 'id')->get();
		$tickets_inactive = HelpCentre::where('ticket_status', 'close')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$tickets_active = HelpCentre::where('ticket_status', 'active')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$categories = HelpIssue::select('id', 'category')->get();
		$profile = User::where('id', $id)->select('profile')->first()->profile;

		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$vieweditsource = 'front.users.support_view';
		$editprofile = 2;
		$page = 6;
		return view('front.users.index', compact('vieweditsource', 'editprofile', 'user', 'category', 'tickets_inactive', 'tickets_active', 'page', 'profile', 'from_symbol', 'to_symbol', 'pairid','news'));

	}
	 
	public function addSupport() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$data = Input::all();
		$randcode = randomcode(5);
		$id = session::get('tmaitb_user_id');
		$email = insep_encode($data['email']);
		$Validation = Validator::make($data, HelpCentre::$addRule);
		if ($Validation->fails()) {
			foreach ($Validation->messages()->getMessages() as $field => $message) {
				Session::flash('error', $message[0]);
				return Redirect::back();
			}
		} 
		else 
		{
			$data_arr = array('user_id' => $id,'email' => $email, 'category' => strip_tags($data['category']), 'subject' => $data['subject'], 'message' => $data['description'], 'status' => 'unread', 'ticket_status' => 'active');
			if ($_FILES['file']['name']) {
				$fileExtensions = array('jpeg', 'jpg', 'png');
				$filename = Controller::uploadFiles('file', $fileExtensions);

				
				$data_arr['image'] = $filename;
			}
			$create = HelpCentre::create($data_arr);
			if ($create) 
			{
				$helpId = $create->id;
				HelpCentre::where('id', $helpId)->update(['reference_no' => $helpId]);
				$message = 'You have added support ticket TKT-' . $helpId;
				Controller::siteNotification($message, $id);

				
				$name = getUserName($id);
				$to = getUserEmail($id);
				$info = array('###MESSAGE###' => $message, '###USER###' => $name);
				Controller::sendEmail($to, $info, 4);

				Session::flash('success', trans('app_lang.ticket_added_success'));
				return Redirect::back();
			} 
			else 
			{
				Session::flash('error', trans('app_lang.please_try_again'));
				return Redirect::back();
			}
		}
	}
	
	public function editSupport() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
			$randcode = randomcode(5);
			$data = Input::all();
			$id = session::get('tmaitb_user_id');
			$Validation = Validator::make($data, [
				'comment' => 'required',
				'file' => 'mimes:jpeg,jpg,png|max:10000',
			], [
				'comment.required' => 'Description required',
				'file.mimes' => 'only files with jpg,png,jpeg extension are allowed',
			]);
			if ($Validation->fails()) {
				foreach ($Validation->messages()->getMessages() as $field => $message) {
					echo $message[0];exit;
				}
			} 
			else 
			{
				$ticket_no_send = strip_tags($data['edit_ref_no']);
				$ticket_no = insep_decode(strip_tags($data['edit_ref_no']));
				$data_arr = array('user_id' => $id, 'message' => $data['comment'], 'status' => 'unread', 'ticket_status' => 'active', 'reference_no' => $ticket_no, 'email_send' => 0);

				if ($_FILES['file']['name']) {
					$fileExtensions = array('jpeg', 'jpg', 'png');
					$filename = Controller::uploadFiles('file', $fileExtensions);



					
					$image = $filename;
					if (!$image) {
						echo "Unable to upload the file";exit;
					}
					$data_arr['image'] = $image;
				}
				if (isset($data['close_ticket'])) {
					$data_arr['ticket_status'] = 'close';
				}
				$create = HelpCentre::create($data_arr);
				if ($create) 
				{
					if (isset($data['close_ticket'])) 
					{
						$result = HelpCentre::where('reference_no', $ticket_no)->update(['status' => 'read', 'ticket_status' => 'close']);
					}
					$message = 'You have updated your ticket details TKT-' . $ticket_no;
					Controller::siteNotification($message, $id);
					$datas['check_no'] = $ticket_no_send;
					$datas['status'] = isset($data['close_ticket']) ? '2' : '1';

					echo json_encode($datas);
					exit;
					
				} 
				else 
				{
					$datas['reference_no'] = $ticket_no_send;
					$datas['status'] = '0';

					echo json_encode($datas);
					exit;
				}
			}
	}
	
	public function viewSupportTicket($tid) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$refId = $tid;
		$query = HelpCentre::where('reference_no', $refId)->orderBy('id', 'asc')->get()->toArray();
		$profile = User::where('id', $id)->select('profile')->first()->profile;

		$status = HelpCentre::where('id', $refId)->select('ticket_status')->first()->ticket_status;

		$lang = session::get('language') ? session::get('language') : 'en';

		$tickets_active = $tickets_inactive = array();
		$user = User::where('id', $id)->select('verified_status', 'randcode', 'mobile', 'profile')->first();
		$category = HelpIssue::where('language_code', $lang)->select('category', 'id')->get();
		$tickets_inactive = HelpCentre::where('ticket_status', 'close')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$tickets_active = HelpCentre::where('ticket_status', 'active')->where('user_id', $id)->orderBy('created_at', 'desc')->groupBy('reference_no')->get();
		$categories = HelpIssue::select('id', 'category')->get();
		$page = 6;

		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$vieweditsource = 'front.users.support';
		$editprofile = 2;
		$content = 7;
		return view('front.users.index', compact('vieweditsource', 'editprofile', 'user', 'category', 'tickets_inactive', 'tickets_active', 'page', 'query', 'profile', 'content', 'refId', 'status', 'from_symbol', 'to_symbol', 'pairid','news'));
	}
    
	public function TicketDetails($tid) 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$refId = insep_decode($tid);
		$query = HelpCentre::where('reference_no', $refId)->orderBy('id', 'asc')->get()->toArray();
		$profile = User::where('id', $id)->select('profile')->first()->profile;
		$data = compact('query', 'profile');
		return view('front.users.viewsupport_page', $data);
	}
    
	public function updatekycDoc() 
	{

		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		
		$randcode = randomcode(5);
		$validate = Validator::make($data, [
			'file1' => 'mimes:jpeg,jpg,png|max:10000',
			'file2' => 'mimes:jpeg,jpg,png|max:10000',
			'file3' => 'mimes:jpeg,jpg,png|max:10000',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$update_arr = array();
		$verification = ConsumerVerification::where('user_id', $id)->select('id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status','type')->first();
		if ($verification->id_status == 0 || $verification->id_status == 2) 
		{
			if ($_FILES['file1']['name'] && $_FILES['file2']['name']) 
			{
				$fileExtensions = array('jpeg', 'jpg', 'png');

					$filename1 = Controller::uploadFiles('file1', $fileExtensions);



				$update_arr['id_proof_front'] = $filename1;

				$filename2 = Controller::uploadFiles('file2', $fileExtensions);

				$update_arr['id_proof_back'] = $filename2;
				$update_arr['id_status'] = 1;
			} else {

				Session::flash('error', trans('app_lang.back_proof_required'));
				return Redirect::to('dashboard?name=verification');
			}
		}
		if ($verification->selfie_status == 0 || $verification->selfie_status == 2) 
		{
			if ($_FILES['file3']['name'] == '') {
				Session::flash('error', trans('app_lang.selfie_proof_require'));
				
				return Redirect::to('dashboard?name=verification');
			} else {
				$fileExtensions = array('jpeg', 'jpg', 'png');
				$update_arr['selfie_status'] = 1;
		
				$update_arr['selfie_proof'] = Controller::uploadFiles('file3', $fileExtensions);
			}
		}
		if(!empty($verification->type))
		{
			$update_arr['type'] = $verification->type;
		}
		else
		{
			$update_arr['type'] = $data['verifytype'];
		}
		
		$update = ConsumerVerification::where('user_id', $id)->update($update_arr);
		if ($update) 
		{
			$message = 'You have updated your kyc details';
			Controller::siteNotification($message, $id);
			Session::flash('success', trans('app_lang.kyc_documents_update_lng'));
			
			return Redirect::to('dashboard?name=verification');
		} 
		else 
		{
			Session::flash('error', trans('app_lang.please_try_again'));
			
			return Redirect::to('dashboard?name=verification');
		}

	}
	
	public function referral_request() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$data = Input::all();
		$validate = Validator::make($data, [
			'referral_email' => 'required|email|unique_email',
		],
		[
			'referral_email.required' => 'Enter email address',
			'email.email' => 'Enter valid email address',
			'referral_email.unique_email' => 'Email address already registered',
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				echo $msg[0];exit;
			}
		}
		$referrer_name = User::where('id', $id)->select('referrer_name')->first()->referrer_name;
		$securl = url("/register/" . $referrer_name);
		$info = array('###EMAIL###' => $data['referral_email'], '###LINK###' => $securl);

		$sendEmail = Controller::sendEmail($data['referral_email'], $info, '5');
		if ($sendEmail) 
		{
			echo "1";
		} 
		else 
		{
			echo "Please try again";
		}

	}
	
	public function referalHistory() 
	{

		$id = session::get('tmaitb_user_id');
		$totalrecords = intval(Input::get('totalrecords'));
		$draw = Input::get('draw');
		$start = Input::get('start');
		$length = Input::get('length');
		$sorttype = Input::get('order');
		$sort_col = $sorttype['0']['column'];
		$sort_type = $sorttype['0']['dir'];

		if ($sort_col == '1') {
			$sort_col = 'created_at';
		} else if ($sort_col == '3') {
			$sort_col = 'commision';
		} else if ($sort_col == '4') {
			$sort_col = 'updated_at';
		} else {
			$sort_col = "id";
		}
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$results = DB::select("SELECT `users`.`liame`,`users`.`contentmail`, `referral`.`currency`,`referral`.`commision`,`referral`.`status`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  left join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type . " LIMIT " . $start . ", " . $length);
		$totalrecords = DB::select("SELECT count(*) as count FROM `tmaitb_larrefer` as referral left join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id);
		$totalrecords = $totalrecords[0]->count;

		$data = array();
		$no = $start + 1;
		if ($totalrecords) 
		{
			foreach ($results as $r) 
			{
				$id = insep_decode($r->contentmail) . insep_decode($r->liame);
				$currency = $r->currency;
				$commision = $r->commision . ' ' . $currency;
				$datetime = $r->updated_at;
				array_push($data, array(
					$no,
					$id,
					$commision,
					$datetime,
				));
				$no++;
			}

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => $data));
		} 
		else 
		{

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => array()));
		}
	}
	
	public function referalList() 
	{
		$id = session::get('tmaitb_user_id');
		$totalrecords = intval(Input::get('totalrecords'));
		$draw = Input::get('draw');
		$start = Input::get('start');
		$length = Input::get('length');
		$sorttype = Input::get('order');
		$sort_col = $sorttype['0']['column'];
		$sort_type = $sorttype['0']['dir'];

		$sort_col = 'id';
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$results = DB::select("SELECT `users`.`id`,`users`.`activation_code`,`users`.`status`,`users`.`liame`,`users`.`contentmail`, `referral`.`currency`,`referral`.`commision`,`referral`.`updated_at` FROM `tmaitb_larrefer` as referral  right join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . "  GROUP BY `users`.`id`  ORDER BY `referral`.`" . $sort_col . "` " . $sort_type . " LIMIT " . $start . ", " . $length);
		$totalrecords = DB::select("SELECT count(*) as count FROM `tmaitb_larrefer` as referral right join `tmaitb_sresu` as users ON `users`.`id`= `referral`.user_id WHERE `refer_by` = " . $id . " GROUP BY `users`.`id`");
		if ($totalrecords) {
			$totalrecords = $totalrecords[0]->count;
			$data = array();
			$no = $start + 1;
			if ($totalrecords) {

				foreach ($results as $r) {
					$id = insep_decode($r->contentmail) . insep_decode($r->liame);
					$status = $r->activation_code ? 'Registered' : ($r->status == 1 ? 'Active' : 'Deactive');
					array_push($data, array(
						$no,
						$id,
						$status,

					));
					$no++;
				}

				echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => $data));
			} else {

				echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $totalrecords, 'recordsFiltered' => $totalrecords, 'data' => array()));
			}

		} else {

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array()));
		}

	}
	
	public function deposit_history() 
	{
       $id = session::get('tmaitb_user_id');
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
			$sort_col = 'currency';
		} else if ($sort_col == '3') {
			$sort_col = 'transaction_id';
		} else if ($sort_col == '4') {
			$sort_col = 'amount';
		} else if ($sort_col == '5') {
			$sort_col = 'address';
		} else if ($sort_col == '5') {
			$sort_col = 'updated_at';
		} else {
			$sort_col = "id";
		}
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$data = $orders = array();
		$deposit = Deposit::where('user_id', $id);
		if ($search != '') {
			$deposit = $deposit->where(function ($q) use ($search) {
				$q->where('transaction_id', 'like', '%' . $search . '%')->orWhere('currency', 'like', '%' . $search . '%')->orWhere('address', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%')->orWhere('updated_at', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');}
			);
		}

		if ($from_date) {
			$deposit = $deposit->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($from_date)));
		}

		if ($to_date) {
			$deposit = $deposit->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($to_date)));
		}

		$deposit_count = $deposit->count();
		if ($deposit_count) {

			$deposit = $deposit->select('updated_at', 'address', 'transaction_id', 'currency', 'amount', 'status', 'confirmation');

			$orders = $deposit->skip($start)->take($length)->orderBy($sort_col, $sort_type)->get()->toArray();
		}

		$data = array();
		$no = $start + 1;

		if ($deposit_count) {
			foreach ($orders as $r) {
				$tx = $r['transaction_id'];
				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");

				if ($r['status'] == 'Completed') {
					$status = '<span style="color:#7BC9A3">' . trans('app_lang.completed') . '</span>';
				} else if ($r['status'] == 'Pending') {
					$status = '<span style="color:brown">' . trans('app_lang.pending') . '</span>';
				} else {
					$status = '<span style="color:red">' . $r['status'] . '</span>';
				}
				array_push($data, array(
					$no,
					$r['currency'],
					$tx,
					$amount,
					$r['address'],
					$r['updated_at'],
					$r['confirmation'],
					$status,

				));
				$no++;
			}

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $deposit_count, 'recordsFiltered' => $deposit_count, 'data' => $data));
		} else {

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $deposit_count, 'recordsFiltered' => $deposit_count, 'data' => array()));
		}
	}
	
	public function fiatdeposit_history() 
	{
		$id = session::get('tmaitb_user_id');
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
			$sort_col = 'currency_id';
		} else if ($sort_col == '3') {
			$sort_col = 'transaction_id';
		} else if ($sort_col == '4') {
			$sort_col = 'amount';
		} else if ($sort_col == '5') {
			$sort_col = 'address';
		} else if ($sort_col == '5') {
			$sort_col = 'updated_at';
		} else {
			$sort_col = "id";
		}
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$data = $orders = array();
		$deposit = Fiatdeposit::where('user_id', $id);
		if ($search != '') {
			$deposit = $deposit->where(function ($q) use ($search) {
				$q->where('currency_id', 'like', '%' . $search . '%')->orWhere('referencenum', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%')->orWhere('proof', 'like', '%' . $search . '%')->orWhere('updated_at', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');}
			);
		}

		if ($from_date) {
			$deposit = $deposit->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($from_date)));
		}

		if ($to_date) {
			$deposit = $deposit->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($to_date)));
		}

		$deposit_count = $deposit->count();
		if ($deposit_count) {

			$deposit = $deposit->select('updated_at', 'proof', 'referencenum', 'currency_id', 'amount', 'status');

			$orders = $deposit->skip($start)->take($length)->orderBy($sort_col, $sort_type)->get()->toArray();
		}
		$data = array();
		$no = $start + 1;

		if ($deposit_count) 
		{
         foreach ($orders as $r) {

				if ($r['status'] == 'Completed') {
					$status = '<span style="color:#7BC9A3">' . trans('app_lang.completed') . '</span>';
				} else if ($r['status'] == 'Pending') {
					$status = '<span style="color:brown">' . trans('app_lang.pending') . '</span>';
				} else if ($r['status'] == 'Cancelled') {
					$status = '<span style="color:red">' . trans('app_lang.cancelled') . '</span>';
				} else {
					$status = '<span style="color:blue">' . $r['status'] . '</span>';
				}
				$url =  $r['proof'];
				$image = '<img id="proof" height = "40" width = "40" src = "' . $url . '">';
				array_push($data, array(
					$no,
					getCurrencysymbol($r['currency_id']),
					$r['referencenum'],
					$r['amount'],
					$image,
					$r['updated_at'],
					$status,

				));
				$no++;
			}
			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $deposit_count, 'recordsFiltered' => $deposit_count, 'data' => $data));
		} 
		else 
		{

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $deposit_count, 'recordsFiltered' => $deposit_count, 'data' => array()));
		}
	}
	
	public function withdraw_history() 
	{
		$id = session::get('tmaitb_user_id');
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
			$sort_col = 'currency';
		} else if ($sort_col == '3') {
			$sort_col = 'transaction_id';
		} else if ($sort_col == '4') {
			$sort_col = 'amount';
		} else if ($sort_col == '5') {
			$sort_col = 'address';
		} else if ($sort_col == '5') {
			$sort_col = 'updated_at';
		} else {
			$sort_col = "id";
		}
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$data = $orders = array();
		$withdraw = Withdraw::where('user_id', $id);
		if ($search != '') {
			$withdraw = $withdraw->where(function ($q) use ($search) {
				$q->where('transaction_id', 'like', '%' . $search . '%')->orWhere('currency', 'like', '%' . $search . '%')->orWhere('address', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%')->orWhere('fee_amt', 'like', '%' . $search . '%')->orWhere('updated_at', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');}
			);
		}

		if ($from_date) {
			$withdraw = $withdraw->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($from_date)));
		}

		if ($to_date) {
			$withdraw = $withdraw->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($to_date)));
		}

		$withdraw_count = $withdraw->count();
		if ($withdraw_count) {

			$withdraw = $withdraw->select('updated_at', 'address', 'transaction_id', 'currency', 'amount', 'status', 'fee_amt', 'id');

			$orders = $withdraw->skip($start)->take($length)->orderBy($sort_col, $sort_type)->get()->toArray();
		}

		$data = array();
		$no = $start + 1;

		if ($withdraw_count) {

			foreach ($orders as $r) {

				$tx = $r['transaction_id'];
				if ($r['status'] == 'Pending') {
					$status = '<span style="color:brown">' . trans('app_lang.pending') . '</span>';
					$resend_mail = trans('app_lang.click_resend_withdraw_email');
					$click_cancel = trans('app_lang.click_here_cancel');

					$href = 'cancel_withdraw(this,\'' . insep_encode($r['id']) . '\');return false;';
					$href_1 = 'resend_mail_withdraw(this,\'' . insep_encode($r['id']) . '\');return false;';
					$status = '<span class="penClr">' . $status . '</span> | <a href="javascript:;" onclick="' . $href_1 . '" title="' . $resend_mail . '"><i class="fa fa-eye" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="' . $href . '" title="' . $click_cancel . '"><i class="fa fa-times-circle time_ic" aria-hidden="true"></i></a>';
				} else {
					if ($r['status'] == 'Cancelled') {
						$status = '<span style="color:red">' . trans('app_lang.cancelled') . '</span>';
					} else if ($r['status'] == 'Completed') {
						$status = '<span style="color:green">' . trans('app_lang.completed') . '</span>';
					} else if ($r['status'] == 'Processing') {
						$status = '<span style="color:blue">' . trans('app_lang.processing') . '</span>';
					} else {
						$status = $r['status'];
					}
				}

				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");

				array_push($data, array(
					$no,
					$r['currency'],
					$tx,
					$amount,
					$fee_amt,
					$r['address'],
					$r['updated_at'],
					$status,

				));
				$no++;
			}

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $withdraw_count, 'recordsFiltered' => $withdraw_count, 'data' => $data));
		} else {

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $withdraw_count, 'recordsFiltered' => $withdraw_count, 'data' => array()));
		}

	}
	
	public function fiatwithdraw_history() 
	{
		$id = session::get('tmaitb_user_id');
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
			$sort_col = 'currency_id';
		} else if ($sort_col == '2') {
			$sort_col = 'given_amount';
		} else if ($sort_col == '3') {
			$sort_col = 'transaction_id';
		} else if ($sort_col == '4') {
			$sort_col = 'amount';
		} else if ($sort_col == '5') {
			$sort_col = 'updated_at';
		} else if ($sort_col == '6') {
			$sort_col = 'fee_amt';
		} else {
			$sort_col = "id";
		}
		if ($sort_type == 'asc') {
			$sort_type = 'desc';
		} else {
			$sort_type = 'asc';
		}

		$data = $orders = array();
		$withdraw = Fiatwithdraw::where('user_id', $id);
		if ($search != '') {
			$withdraw = $withdraw->where(function ($q) use ($search) {
				$q->where('transaction_id', 'like', '%' . $search . '%')->orWhere('currency_id', 'like', '%' . $search . '%')->orWhere('given_amount', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%')->orWhere('fee_amt', 'like', '%' . $search . '%')->orWhere('updated_at', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');}
			);
		}

		if ($from_date) {
			$withdraw = $withdraw->where('updated_at', '>=', date('Y-m-d 00:00:00', strtotime($from_date)));
		}

		if ($to_date) {
			$withdraw = $withdraw->where('updated_at', '<=', date('Y-m-d 23:59:59', strtotime($to_date)));
		}

		$withdraw_count = $withdraw->count();
		if ($withdraw_count) {

			$withdraw = $withdraw->select('updated_at', 'given_amount', 'transaction_id', 'currency_id', 'amount', 'status', 'fee_amt', 'id');

			$orders = $withdraw->skip($start)->take($length)->orderBy($sort_col, $sort_type)->get()->toArray();
		}

		$data = array();
		$no = $start + 1;
		if ($withdraw_count) {

			foreach ($orders as $r) {
				if ($r['status'] == 'Pending') {
					$status = '<span style="color:brown">' . trans('app_lang.pending') . '</span>';
					$resend_mail = trans('app_lang.click_resend_withdraw_email');
					$click_cancel = trans('app_lang.click_here_cancel');

					$href = 'fiat_cancel_withdraw(this,\'' . insep_encode($r['id']) . '\');return false;';
					$href_1 = 'fiat_resend_mail_withdraw(this,\'' . insep_encode($r['id']) . '\');return false;';
					$status = '<span class="penClr">' . $status . '</span> | <a href="javascript:;" onclick="' . $href_1 . '" title="' . $resend_mail . '"><i class="fa fa-eye" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="' . $href . '" title="' . $click_cancel . '"><i class="fa fa-times-circle time_ic" aria-hidden="true"></i></a>';
				} else if ($r['status'] == 'Cancelled') {
					$status = '<span style="color:red">' . trans('app_lang.cancelled') . '</span>';
				} else if ($r['status'] == 'Completed') {
					$status = '<span style="color:green">' . trans('app_lang.completed') . '</span>';
				} else if ($r['status'] == 'Processing') {
					$status = '<span style="color:blue">' . trans('app_lang.processing') . '</span>';
				}else {
					$status = $r['status'];
				}

				$amount = rtrim(rtrim(sprintf('%.8F', $r['amount']), '0'), ".");
				$fee_amt = rtrim(rtrim(sprintf('%.8F', $r['fee_amt']), '0'), ".");
				$givenamount = rtrim(rtrim(sprintf('%.8F', $r['given_amount']), '0'), ".");
				if (!empty($r['transaction_id'])) {
					$transid = $r['transaction_id'];
				} else {
					$transid = '-';
				}
				array_push($data, array(
					$no,
					getCurrencysymbol($r['currency_id']),
					$transid,
					$amount,
					$fee_amt,
					$givenamount,
					$r['updated_at'],
					$status,

				));
				$no++;
			}

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $withdraw_count, 'recordsFiltered' => $withdraw_count, 'data' => $data));
		} else {

			echo json_encode(array('draw' => intval($draw), 'recordsTotal' => $withdraw_count, 'recordsFiltered' => $withdraw_count, 'data' => array()));
		}

	}
	
	function resend_email($id) 
	{
		$id = insep_decode($id);
		$user_id = session::get('tmaitb_user_id');
		$rec = Withdraw::where('id', $id)->where('user_id', $user_id)->select('currency', 'amount', 'address', 'transfer_amount', 'fee_amt', 'status')->first();
		if ($rec) {
			if ($rec->status == 'Pending') {
				$code = time() . '11' . $user_id . rand(99, 99999);
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

					$sendEmail = Controller::sendEmail($email, $info, '9');
					$message = 'You have requested a withdraw resend link';
					Controller::siteNotification($message, $user_id);
					echo "1";exit;
				} else {
					echo '0';
				}
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
	}
	
	function fiat_resend_email($id) 
	{
		$id = insep_decode($id);
		$user_id = session::get('tmaitb_user_id');
		$rec = Fiatwithdraw::where('id', $id)->where('user_id', $user_id)->select('currency', 'amount', 'fee_amt', 'given_amount','status')->first();
		if ($rec) {
			if ($rec->status == 'Pending') {
				$code = time() . '11' . $user_id . rand(99, 99999);
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
					$info = array('###CUR###' => $currency, '###AMOUNT###' => $amount, '###TRANSFER###' => $transfer_amount, '###FEE###' => $fee_amt, '###USER###' => $name, '###CONFIRM###' => $securl, '###CANCEL###' => $rsecurl);

					$sendEmail = Controller::sendEmail($email, $info, '28');
					$message = 'You have requested a withdraw resend link';
					Controller::siteNotification($message, $user_id);
					echo "1";exit;
				} else {
					echo '0';
				}
			} else {
				echo "0";
			}
		} else {
			echo "0";
		}
	}
	
	function cancel_withdraw_request($id) 
	{
		$id = insep_decode($id);
		$log_user_id = session::get('tmaitb_user_id');
		$rec = Withdraw::where('id', $id)->where('user_id', $log_user_id)->where('is_flag', '0')->select('currency', 'amount', 'user_id', 'status')->first();
		if ($log_user_id == $rec->user_id) {
			if ($rec->status == 'Pending') {
				$currency = $rec->currency;
				$currecny_detail = Currency::where('symbol', $currency)->select('id')->first();
				if ($currecny_detail) {
					$cur_id = $currecny_detail->id;
					$amount = $rec->amount;
					$balance = Wallet::getBalance($log_user_id, $cur_id);
					$update_balance = $balance + $amount;
					$result = DB::transaction(function () use ($id, $log_user_id, $cur_id, $update_balance) {

						Wallet::updateBalance($log_user_id, $cur_id, $update_balance);
						return Withdraw::where('id', $id)->update(array('status' => 'Cancelled'));
					});
					if ($result) {
						$message = 'You have cancelled your withdraw request for -' . $amount . ' ' . $currency;
						Controller::siteNotification($message, $log_user_id);
						echo '1';exit;
					}
				}
			} else {
				echo "0";
			}
		}
	}
	
	function fiat_cancel_withdraw_request($id) 
	{
		$id = insep_decode($id);
		$log_user_id = session::get('tmaitb_user_id');
		$rec = Fiatwithdraw::where('id', $id)->where('user_id', $log_user_id)->where('is_flag', '0')->select('currency', 'amount', 'user_id', 'status')->first();
		if ($log_user_id == $rec->user_id) {
			if ($rec->status == 'Pending') {
				$currency = $rec->currency;
				$currecny_detail = Currency::where('symbol', $currency)->select('id')->first();
				if ($currecny_detail) {
					$cur_id = $currecny_detail->id;
					$amount = $rec->amount;
					$balance = Wallet::getBalance($log_user_id, $cur_id);
					$update_balance = $balance + $amount;
					$result = DB::transaction(function () use ($id, $log_user_id, $cur_id, $update_balance) {

						Wallet::updateBalance($log_user_id, $cur_id, $update_balance);
						return Fiatwithdraw::where('id', $id)->update(array('status' => 'Cancelled'));
					});
					if ($result) {
						$message = 'You have cancelled your withdraw request for -' . $amount . ' ' . $currency;
						Controller::siteNotification($message, $log_user_id);
						echo '1';exit;
					}
				}
			} else {
				echo "0";
			}
		}
	}
	
	public function bankwire($currency = 'USD') 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$user = User::where('id', $id)->select('profile')->first();
		$bankwire = DB::table('eriwknab')->where('user_id', $id)->where('currency',$currency)->first();


		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.bankwire';
		$editprofile = 0;
		$page = 7;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'bankwire', 'from_symbol', 'to_symbol', 'pairid','news'));

	}
	
	public function updatebankwire() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');	

		$data = Input::all();
		$validate = Validator::make($data, [
			'accountholdername' => "required",
			'accountnumber' => "required",
			'swift' => 'required',
			'bankname' => 'required',
			'bankaddress' => 'required',
			'routing' => 'required']);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}
		$currency = strip_tags($data['currency']);

		$check = DB::table('eriwknab')->where('user_id', $id)->where('currency',$currency)->count();
		$accholdername = strip_tags($data['accountholdername']);
		$accno = strip_tags($data['accountnumber']);
		$swift = strip_tags($data['swift']);
		$routing = strip_tags($data['routing']);
		$bankname = strip_tags($data['bankname']);
		$bankaddress = strip_tags($data['bankaddress']);
		$insert_arr = ['user_id' => $id,'currency' => $currency, 'accountholdername' => $accholdername, 'accountno' => $accno, 'swift' => $swift, 'routingno' => $routing, 'bankname' => $bankname, 'bankaddress' => $bankaddress];
		$update_arr = ['accountholdername' => $accholdername, 'accountno' => $accno, 'swift' => $swift, 'routingno' => $routing, 'bankname' => $bankname, 'bankaddress' => $bankaddress];

		if($check > 0)
		{
			$insert = Bankwire::where('user_id',$id)->where('currency',$currency)->update($update_arr);
			if ($insert) 
			{
				Session::flash('success', trans('app_lang.bankwire_updated_success'));
				return Redirect::back();
			} else {
				Session::flash('error', trans('app_lang.please_try_again'));
				return Redirect::back();
			}
		}
		else 
		{				
			$insert = Bankwire::create($insert_arr);
			if ($insert) 
			{
				Session::flash('success', trans('app_lang.bankwire_added_success'));
				return Redirect::back();
			} else {
				Session::flash('error', trans('app_lang.please_try_again'));
				return Redirect::back();
			}
		}
    }
    
	function autologout() 
	{
		$user_id = session::get('tmaitb_user_id');
		if (empty($user_id)) 
		{  
			
			DB::table('sresu')->where('id', $user_id)->update(['session_id' => '','login_status'=>'0','logout'=>'0','browser_status' => '0']);
			Session::flush();
			echo '2';
		}
	}
	
	function handelerror() 
	{
		return view("front.common.page404");
	}
	
	function sendsms1($mobile, $code, $message){
		$message = 'Your OTP code is' . $message;
		$coin_info = SiteSettings::where('id', 1)->select('site_name', 'smsapikey')->first();
		$curl = curl_init();
		$sender = 'BoomCoin';
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms?country".$code,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{ \"sender\": \"".$sender."\", \"route\": \"4\", \"country\": \"".$code."\", \"sms\": [ { \"message\": \"".$message."\", \"to\": [ \"".$mobile."\" ] } ] }",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		  CURLOPT_HTTPHEADER => array(
		    "authkey:".$coin_info->smsapikey,
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}
	
	function checkmobile(Request $request) 
	{
		if ($request->isMethod('post')) 
		{
			$mobile_otp = $request['mobile_otp'];
			$check = User::select('mobile')->where('mobile', $mobile_otp)->count();
			if ($check > 0) {
				echo "false";
			} else {
				echo "true";
			}
		}
	}
	
	function sendotpreg(Request $request) 
	{
		if ($request['key']) 
		{
			$num = $request['key'];
			$code = $request['country'];

			$ddd = User::where('mobile', $num)->count();

			if ($ddd == '0') 
			{

				$rand = '123456';

				if ($rand) {
					$get = Reqotp::where('mobilenum', $num)->delete();

					$ins = Reqotp::insert(array('mobilenum' => $num, 'otp' => $rand, 'status' => '0', 'created_date' => date('Y-m-d H:i:s'), 'expire_date' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'))));
					$pho = '+' . $num;
					self::sendsms1($num, $code, $rand);
					return '1';

				} else {
					return '0';
				}
			}

		} 
		else 
		{
			return '0';
		}
	}
    
	function checkotp(Request $request) 
	{

		if ($request->isMethod('post')) {
			$otpcode = $request['otp_num'];
			$mobilenum = $request['mobileno'];
			$check = Reqotp::select('otp')->where('mobilenum', $mobilenum)->first();
			if ($check->otp == $otpcode) {
				echo "true";
			} else {
				echo "false";
			}
		}

	}
	
	public function change_password() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');

		$user = User::where('id', $id)->select('profile')->first();
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news =News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.changepassword';
		$editprofile = 0;
		$page = 5;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'pairid','news'));

	}
	
	public function change_notification() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');

		$user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject')->first();
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.notification';
		$editprofile = 0;
		$page = 5;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'pairid','news'));

	}
	
	public function change_tfa() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
        $user = DB::table('sresu')
		->join('noitacifiton', 'sresu.id', '=', 'noitacifiton.user_id')->where('sresu.id', $id)
		->join('noitacifirev', 'sresu.id', '=', 'noitacifirev.user_id')->where('sresu.id', $id)
		->select('first_name', 'last_name', 'mobile', 'profile', 'dob', 'gender', 'country', 'city', 'state', 'address1', 'address2', 'pincode', 'randcode', 'verified_status', 'trade', 'tfa', 'change_password', 'new_device_login', 'id_proof_front', 'id_proof_back', 'id_status', 'selfie_proof', 'selfie_status', 'selfie_reject', 'id_reject')->first();
		$site_name = SiteSettings::where('id', 1)->select('site_name')->first()->site_name;

		$country = Country::where('status', '1')->select('country_name')->get();
		require_once app_path('Model/Googleauthenticator.php');
		$ga = new Googleauthenticator();
		$secret = $ga->createSecret();
		$tfa_url = $ga->getQRCodeGoogleUrl($site_name,$secret);
		$viewsource = 'front.users.tfa';
		$editprofile = 0;
		$page = 5;
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news =News::where('status', 'active')->orderBy('id', 'desc')->get();
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'secret', 'tfa_url', 'country', 'pairid','news'));
	}
   
	public function notification_list() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$id = session::get('tmaitb_user_id');
		$update_alert = Notificationlist::where('user_id', $id)->update(['status' => 1]);
		$user = User::where('id', $id)->select('profile')->first();
		$notification = Notificationlist::where('user_id', $id)->orderBy('id', 'desc')->paginate(50);
		$tradepairs = TradePairs::where('status', '1')->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->first();
		$from_symbol = $tradepairs->from_symbol;
		$to_symbol = $tradepairs->to_symbol;
		$pairid = $tradepairs->id;
		$news = News::where('status', 'active')->orderBy('id', 'desc')->get();
		$viewsource = 'front.users.notificationlist';
		$editprofile = 0;
		$page = 5;
		return view('front.users.index', compact('viewsource', 'editprofile', 'user', 'page', 'pairid', 'notification','news'));
	}
    
	function checkloginstatus() 
	{
		if(Controller::checkUserSessionIp() == false){return redirect("logout");}
		$user_id = session::get('tmaitb_user_id');
		
		$logintime = recent_login($user_id);
		$temp_time =  date('Y-m-d H:i:s',strtotime('+50 minutes',strtotime($logintime)));
		$current_time = date('Y-m-d H:i:s');
		
		if($current_time > $temp_time)
		{
			$type = 'Logged_out';
			$create_activity = Controller::UserActivityEntry($user_id, $type);
			$update = User::where('id', $user_id)->update(['session_id' => '','login_status'=>'0','browser_status' => '0']);
			Session::forget('tmaitb_user_id');
			echo '1';
		}
		else
		{
			$user = User::where('id', $user_id)->select('status','online','logout','login_status','session_id')->first();
			if ($user->status=='0') 
			{
				
				$type = 'Logged_out';
				$create_activity = Controller::UserActivityEntry($user_id, $type);
				DB::table('sresu')->where('id', $user_id)->update(['session_id' => '','login_status'=>'0','browser_status' => '0']);
				Session::forget('tmaitb_user_id');
				echo '2';

			}
			else if($user->online=='1')
			{
				$type = 'Logged_out';
				$create_activity = Controller::UserActivityEntry($user_id, $type);
				$update = User::where('id', $user_id)->update(["online"=>'0','session_id' => '','login_status'=>'0','browser_status' => '0']);
				Session::forget('tmaitb_user_id');
				echo '3';

			}
			else if($user->login_status=='1' and $user->logout=='1' and  $user->session_id != '')
			{
				$type = 'Logged_out';
				$create_activity = Controller::UserActivityEntry($user_id, $type);
				DB::table('sresu')->where('id', $user_id)->update(['logout'=>'0','session_id' => '','browser_status' => '1']);
				Session::forget('tmaitb_user_id');
				echo '4';
			}
		}
	}
   
   function defaultCurrrencyChoosen()
	{

		$data = Input::all();
		$validate = Validator::make($data, [
			'defaultcurrencyUpdate' => "required"
		]);
		if ($validate->fails()) {
			foreach ($validate->messages()->getMessages() as $val => $msg) {
				Session::flash('error', $msg[0]);
				return Redirect::back();
			}
		}

		$choosedCurrency = $data['defaultcurrencyUpdate'];
		if($choosedCurrency == 2)
		{
			$symbol = 'EUR';
		}
		elseif($choosedCurrency == 3)
		{
			$symbol = 'GBP';
		}
		else
		{
			$symbol = 'USD';
		}

		$user_id = session::get('tmaitb_user_id');
		$userSettings = User::where('id', $user_id)->update(['set_default_currency' => $symbol]);
		if ($userSettings) {
				
				Session::flash('success',trans('Currency settings updated successfully'));
					
		} else {
			Session::flash('error', trans('app_lang.please_try_again'));
		}
		return Redirect::to('/editprofile');
	}

	function apirequest()
	{
		$user_id = session::get('tmaitb_user_id');
		$userkyc = User::where('id', $user_id)->first();
		if($userkyc)
		{
			
				$update = User::where('id', $user_id)->update(['api_status' => '2']);
				$getSiteDetails = Controller::getSitedetails();
				$email = session::get('tmaitb_user_email');
				$name = getUserName($user_id);
				$admin = $getSiteDetails->admin_redirect;
				$message ="User ".$name." has sent api enable request for access API account";
				$info = array('###USER###' => $email, '###NAME###' => $name,'###MESSAGE###' => $message);

				$toemail1 = $getSiteDetails->site_email;
				$toemail = insep_decode($toemail1);

				$bcc = '1';
				$sendEmail = Controller::sendEmail($toemail, $info, '33');
				if ($sendEmail) {
					Session::flash('success',trans('app_lang.admin_apirequest'));
					
				} else {
					Session::flash('error', trans('app_lang.please_try_again'));
				}
		}
		else {
			Session::flash('error', trans('app_lang.please_try_again'));
		}
			

		
		return Redirect::to('/editprofile');
	}


	function sendsms($mobile, $message)
	{  
		$message1 = 'Your OTP code is' . $message;
		$coin_info = SiteSettings::where('id', 1)->select('site_name', 'smsapikey')->first();

       

	
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://control.msg91.com/api/sendotp.php?otp=".$message."&sender=OTPSMS&message=".$message1."&mobile=" . $mobile . "&authkey= ". $coin_info->smsapikey."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "",
		  CURLOPT_SSL_VERIFYHOST => 0,
		  CURLOPT_SSL_VERIFYPEER => 0,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo "Response:". $response;
		}
	}

	function coinlist($currency) {

		

		$tradepairs = TradePairs::where('status', '1')->where('to_symbol', $currency)->where('status', 1)->select('id', 'from_symbol', 'to_symbol')->orderBy('id', 'asc')->get();

		$favValues = '';
		foreach ($tradepairs as $tpair) {

			$tid = $tpair['id'];
			$tfrom_symbol = $tpair['from_symbol'];

			$curencyusd = Currency::where('status', 1)->select('inr_value')->first();

			$tto_symbol = $tpair['to_symbol'];

			$forUrl = $tto_symbol . '_' . $tfrom_symbol;

			$a = getTradeData($tid, $tfrom_symbol, $tto_symbol);

			$fav_id = insep_encode($tpair->id);

			$style = "";
			$url3 = getCurrencyImage($tto_symbol);

			$lastId = "id=last_price_" . $forUrl;
			$changeId = "id=change_" . $forUrl;
			$volumeId = "id=volume_" . $forUrl;
			$activeId = "id=active_pair_" . $forUrl;
			$activeCls = "all_active_pairs active_pair_" . $forUrl;
			$activeCls = "class='" . $activeCls . "'";

			$lastPrice = $a['lastprice'];
			$changePer = $a['change'];
			$high_price = $a['high'];
			$low_price = $a['low'];
			$volume = $a['volume'];

			

			$url = URL::to('/') . "/public/assets/images/img-222.png";
			$url1 = URL::to('/') . "/public/assets/images/img-222.png";

			$tradeurl = URL::to('/advance_trade') . "/" . $forUrl;

			$fiat = "USD";
			

			$convertionnew = $curencyusd->inr_value * $lastPrice;

			$convertionPrice = $convertionnew == '' ? 0 : rtrim(rtrim(sprintf('%.2F', $convertionnew), '0'), ".");

			if ($changePer > 0 || $changePer == 0) {
				$clsName = "class=text-success";
			} else {
				$clsName = "class=text-danger";
			}
			
			$changePer = number_format($changePer, 2, '.', '');

			$favValues .= '<tr class="fav' . $fav_id . '"><td class="portlet-star-cnt" onclick="favPair(this,\'' . $fav_id . '\')"><i class="fa fa-fw fa-star-o"></i></td><td onclick="tradePairChange(\'' . $forUrl . '\')" style="cursor:pointer;" ' . $activeId . ' ' . $activeCls . '><img height="20" width="20" src="' . $url3 . '"> <span class="bold">' . $tto_symbol . '/</span><span class="light">' . $tfrom_symbol . '</span></span></td><td onclick="tradePairChange(\'' . $forUrl . '\')" ' . $lastId . '><span>' . $lastPrice . '</span> / <span class="light">' . $convertionPrice . '</span> <span class ="light">' . $fiat . '</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')"><span ' . $clsName . ' ' . $changeId . '>' . $changePer . ' %</span></td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $high_price . '</td><td>' . $low_price . '</td><td onclick="tradePairChange(\'' . $forUrl . '\')">' . $volume . " " . $tfrom_symbol . '</td><td class="text-center" onclick="tradePairChange(\'' . $forUrl . '\')"><a href = "' . $tradeurl . '" target="_blank"><img title="Open in a new window" class="" src="' . $url . '"></td></tr>';

		}
		return $favValues;

	}

	function updatebalance($user_id, $currencyid, $amount) {
		$bal = Wallet::getBalance($user_id, $currencyid);
		$update_balance = $bal + $amount;

		$balupdate = Wallet::updateBalance($user_id, $currencyid, $update_balance);
	}
	public function arraygroupBy($array, $key) {
		$return = array();
		foreach($array as $val) {
			$return[$val[$key]][$val['from_symbol']] = $val;
		}
		return $return;
	}
	
}

