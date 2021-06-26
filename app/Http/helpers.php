<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Sats;
use App\Model\Cms;
use App\Model\CoinOrder;
use App\Model\Currency;
use App\Model\HelpCentre;
use App\Model\News;
use App\Model\Notificationlist;
use App\Model\OrderTemp;
use App\Model\SiteSettings;
use App\Model\SubAdmin;
use App\Model\TradePairs;
use App\Model\User;
use App\Model\UserActivity;
use App\Model\Wallet;
use App\Model\Tokens;
use App\Model\Hidden;
use App\Model\WhiteIP;
/*use Session;
use Redirect;*/

	
	function insep_encode($value) 
	{
		
		$skey = "C7HkvBq2KVaXcSCB";
		if (!$value) {return false;}
		$text = $value;
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
		return trim(safe_b64encode($crypttext));
	}

	
	function insep_decode($value) 
	{
		
		$skey = "C7HkvBq2KVaXcSCB";
		if (!$value) {return false;}
		$crypttext = safe_b64decode($value);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}

	
	function safe_b64encode($string) 
	{
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}

	
	function safe_b64decode($string) 
	{
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	function firstEmail($a) {return substr($a, 0, 4);}

	function secondEmail($a) {return substr($a, 4);}


	
	function getReferralKey($len = 8) 
	{
		$characters = '0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $len; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$checkKey = User::where('refer_id', $randomString)->count();
		if ($checkKey > 0) {
			return getReferralKey(8);
		} else {
			return $randomString;
		}
	}

	
	function randomString($length) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	
	function getSiteName() 
	{
		return SiteSettings::where('id', 1)->select('site_name')->first()->sitename;
	}

	
	function getSiteKey() 
	{
		return SiteSettings::where('id', 1)->select('site_key')->first()->site_key;
	}

	
	function getSite() 
	{
		return SiteSettings::where('id', 1)->select('site_name', 'site_logo', 'tradesite_logo','site_favicon','copy_right_text', 'fb', 'gplus', 'twitter', 'linkedin', 'site_email', 'contact_number')->first();
	}
	
	function getAppurl() 
	{
		return SiteSettings::where('id', 1)->select('app_url')->first()->app_url;
	}

	
	function getIosurl() 
	{
		return SiteSettings::where('id', 1)->select('ios_url')->first()->ios_url;
	}

	function getHeaders() {
		
	}

	
	function recent_login($id) 
	{
		$result = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('created_at')->orderBy('id', 'DESC')->first();
		return $result ? $result->created_at : false;
	}

	
	function recent_login_ip($id)
	{
		$result = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('ip_address')->orderBy('id', 'DESC')->first();
		return $result ? $result->ip_address : false;
	}

	
	function last_recent_login($id) 
	{
		$result = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('created_at')->orderBy('id', 'DESC')->skip(1)->take(1)->first();
		return $result ? $result->created_at : false;
	}

	
	function last_recent_login_ip($id) 
	{
		$result = UserActivity::where('user_id', $id)->where('activity', 'Logged_in')->select('ip_address')->orderBy('id', 'DESC')->skip(1)->take(1)->first();
		return $result ? $result->ip_address : false;
	}

	
	function get_cms() 
	{
		$result = Cms::where('type', 'page')->where('status', '1')->select('title', 'name', 'id')->orderBy('title', 'ASC')->get();
		return $result;
	}

	
	function highestbidprice($pair) 
	{
		$highPrice = CoinOrder::where('pair', $pair)->where('Type', 'buy')->whereIn('status', ['active', 'partially'])->select('Price')->orderBy('Price', 'desc')->limit(1)->get();
		if ($highPrice->isEmpty()) {
			$lastPrice = TradePairs::where('id', $pair)->select('last_price')->first()->last_price;
			return $lastPrice;
		} else {
			return $highPrice[0]->Price;
		}
	}

	
	function lowestaskprice($pair) 
	{
		$lowPrice = CoinOrder::where('pair', $pair)->where('Type', 'sell')->whereIn('status', ['active', 'partially'])->select('Price')->orderBy('Price', 'asc')->limit(1)->get();
		if ($lowPrice->isEmpty()) {
			$lastPrice = TradePairs::where('id', $pair)->select('last_price')->first()->last_price;
			return $lastPrice;
		} else {
			return $lowPrice[0]->Price;
		}
	}

	
	function priceRange($pair) 
	{
		$color = "identical";
		$query = OrderTemp::where('pair', $pair)->select('askPrice')->where('cancel_id', NULL)->orderBy('id', 'desc')->limit(2)->get();
		if (!$query->isEmpty()) {
			$i = 0;
			foreach ($query as $open_order) {
				$j = $i + 1;
				if (isset($query[$j])) {
					$preActivePrice = $query[$j]->askPrice;
				} else {
					return $color;
				}
				$activePrice = $open_order->askPrice;
				if ($activePrice == $preActivePrice) {
					return $color;
				}
				$color = ($activePrice < $preActivePrice) ? "negVal" : "posVal";
				break;
			}
		}
		return $color;
	}

	
	function getTradeData($pair_id, $firstCurrency, $secondCurrency) 
	{
		$x = array('volume' => '0.0000', 'change' => '0.0000', 'high' => '0.0000', 'low' => '0.0000', 'class' => "posVal");
		$price = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->orderBy('id', 'desc');
		if ($price->count() > 0) {
			$price = $price->first();
			$today_open = $price->askPrice;
			$yesterday = date('Y-m-d H:i:s', strtotime("-1 days"));
			$change_price = OrderTemp::where('datetime', '>=', $yesterday)->where('cancel_id', NULL)->where('pair', $pair_id)->select(DB::raw('SUM(askPrice * filledAmount) as total_volume'), DB::raw('askPrice as price'))->orderBy("id", "asc");
			$highprice = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->select(DB::raw('askPrice as price'))->where('datetime', '>=', $yesterday)->orderBy("askPrice", "desc");
			$lowprice = OrderTemp::where('pair', $pair_id)->where('cancel_id', NULL)->select(DB::raw('askPrice as price'))->where('datetime', '>=', $yesterday)->orderBy("askPrice", "asc");
			if ($change_price->count() > 0) {
				$bitcoin_rate = $change_price->first()->price;
				$daily_change = $today_open - $bitcoin_rate;
				$arrow = ($today_open > $bitcoin_rate) ? "+" : "";
				$class = ($today_open >= $bitcoin_rate) ? "posVal" : "negVal";
				$per = ($daily_change / $bitcoin_rate) * 100;
				$per = $arrow . number_format((float) $per, 2, '.', '');
				$daily = $arrow . number_format((float) $daily_change, 2, '.', '');
				$x['change'] = $per;
				$x['daily'] = $daily;
				$x['class'] = $class;
				$vol_val = $change_price->first()->total_volume;
				$x['volume'] = number_format((float) $vol_val, 4, '.', '');
			} else {
				$x['daily'] = '0.00';
				$x['change'] = '0.00';
			}
			if ($highprice->count() > 0) {
				$x['high'] = number_format((float) $highprice->first()->price, 8, '.', '');
			}
			if ($lowprice->count() > 0) {
				$x['low'] = number_format((float) $lowprice->first()->price, 8, '.', '');
			}
		} else {
			$x['high'] = '0.0000';
			$x['volume'] = '0.0000';
			$x['low'] = '0.0000';
			$x['change'] = '0.00';
			$x['daily'] = '0.00';
			$x['class'] = 'posVal';
			$x['lastprice'] = '0.00';
		}
		$x['europrice']=getconvertionprice($firstCurrency,$secondCurrency);
		$x['lastprice'] = lastmarketprice($pair_id);

		return $x;
	}

	
	function get_pair($id) 
	{
		$result = TradePairs::where('id', $id)->select('from_symbol_id', 'to_symbol_id')->first();
		return $result ? $result : false;

	}

	
	function get_Pairid($id1, $id2) 
	{
		$pair_id = TradePairs::where('to_symbol', $id1)->where('from_symbol', $id2)->select('id');
		if ($pair_id->count() > 0) {
			return $pair_id->first()->id;
		} else {
			return 'Not_in';
		}
	}

	
	function lastmarketprice($id) 
	{
		$result = CoinOrder::where('pair', $id)->where('status', 'filled')->select('price')->orderBy('id', 'DESC')->first();
		if ($result) {
			return $result->price;
		} else {
			$result = TradePairs::where('id', $id)->select('last_price')->first();
			return $result->last_price;
		}

	}

	
	function getAllPairIds($cur_symbol) 
	{
		$pair_ids = TradePairs::where('from_symbol', $cur_symbol)->orWhere('to_symbol', $cur_symbol)->select('id')->get();
		$pairs = array();
		foreach ($pair_ids as $pair) {
			$pairs[] = $pair->id;
		}
		return $pairs;
	}
	function inorders($curr, $id) 
	{
		$inorder['inorder_buy'] = DB::table('redor_nioc')->where('user_id', $id)->where('Type','buy')->where('firstCurrency', $curr)->whereIn('status', ['active', 'partially','stoporder'])->sum('Total');


		$inorder['inorder_sell'] = DB::table('redor_nioc')->where('user_id', $id)->where('Type','sell')->where('secondCurrency', $curr)->whereIn('status', ['active', 'partially','stoporder'])->sum('Amount');


		$inorder['inorder_crypto_withdraw'] = DB::table('wardhtiw')->where('user_id', $id)->where('currency', $curr)->where('status', 'Pending')->sum('amount');


		$cur_id = getCurrencyid($curr);
		$inorder['inorder_fiat_withdraw'] = DB::table('wardhtiw_taif')->where(['user_id' => $id, 'currency_id' => $cur_id])->whereIn('status', ['Pending', 'Processing'])->sum('amount');

		 $inorder['exchange_sell'] = DB::table('egnahcxe')->where('user_id', $id)->where('type','sell')->where('from_symbol', $curr)->whereIn('status', ['pending'])->sum('Amount');


  		$inorder['exchange_buy'] = DB::table('egnahcxe')->where('user_id', $id)->where('type','buy')->where('from_symbol', $curr)->whereIn('status', ['pending'])->sum('total');

		return $inorder;
	}

	function getFiatCurrencyList() 
	{
		$fiatSymbols = Currency::where('type', 'crypto')->select('symbol')->get();
		return $fiatSymbols;
	}



	function currency_pairs_details_home() 
	{
		$pairDetails = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 and b.from_symbol LIKE 'USD' GROUP BY b.id, b.from_symbol Order by  b.id desc ");
		return $pairDetails;
	}

	
	function currency_pairs_details() 
	{
		$pairDetails = DB::select("select b.id,b.last_price, b.from_symbol, b.to_symbol,a.askPrice as yesterday_price,min(askPrice) as low_price,max(askPrice) as high_price, (sum(askPrice * filledAmount)) as volume FROM tmaitb_pmetredor a right join tmaitb_sriap_edart b on a.pair = b.id and a.created_at >= date_add(now(), interval -1 day) and a.cancel_id is null where b.status = 1 GROUP BY b.id, b.from_symbol ");
		return $pairDetails;
	}

	
	function notification_list() 
	{
		$id = session::get('tmaitb_user_id');
		$notf_count = Notificationlist::where('user_id', $id)->where('status', 0)->get();
		return count($notf_count);
	}

	
	function notification_list_web($user_id) 
	{
		$id = $user_id;
		$notf_count = Notificationlist::where('user_id', $id)->where('status', 0)->get();
		return count($notf_count);
	}

	
	function news_lang_content($val, $lan, $auto_id) 
	{
		if (!empty($lan)) {
			if ($lan == 'en') {
				$show_res = "title";
			} else if ($lan == 'zh-CN') {
				$show_res = "title_CN";
			} else if ($lan == 'zh-TW') {
				$show_res = "title_TW";
			} else {
				$show_res = "title_" . $lan;
			}
			$getContent = News::where('id', $auto_id)->select($show_res)->get();
			$result = $getContent[$val]->$show_res;
		} else {
			$show_res = "title";
			$getContent = News::whereIn('id', [$auto_id])->select($show_res)->get();
			$result = $getContent[$val]->$show_res;
		}
		if (strlen($result) > 25) {
			$result = substr($result, 0, 23) . '...';
		}
		return $result;
	}

	
	function cms_lang($type, $val, $lan, $auto_id) 
	{

		if (!empty($lan)) {

			if ($lan == 'en') {
				$show_res = $type;
			} else if ($lan == 'zh-CN') {
				$show_res = $type . "_CN";
			} else if ($lan == 'zh-TW') {
				$show_res = $type . "_TW";
			} else {
				$show_res = $type . "_" . $lan;
			}

			$getContent = Cms::where('id', $auto_id)->select($show_res)->get();
			if (isset($getContent[$val])) {
				$result = $getContent[$val]->$show_res;
			} else {
				$result = '';
			}
		} else {
			$show_res = $type;
			$getContent = Cms::whereIn('id', [$auto_id])->select($show_res)->get();
			if (isset($getContent[$val])) {
				$result = $getContent[$val]->$show_res;
			} else {
				$result = '';
			}
		}
		return $result;
	}

	
	function getUserName($user_id) 
	{
		return User::where('id', $user_id)->select('first_name')->first()->first_name;
	}

	
	function getUserEmail($user_id) 
	{
		$get_data = User::where('id', $user_id)->select('liame', 'contentmail')->first();
		return insep_decode($get_data->contentmail) . insep_decode($get_data->liame);
	}

	
	function getsupportcategory($ref_no) 
	{
		$where_id = HelpCentre::where('reference_no', $ref_no)->select('id')->first()->id;
		return HelpCentre::where('id', $where_id)->select('category')->first()->category;
	}

	
	function getuserid_support($ref_no) 
	{
		$where_id = HelpCentre::where('reference_no', $ref_no)->select('id')->first()->id;
		return HelpCentre::where('id', $where_id)->select('user_id')->first()->user_id;
	}

	function get_gas($cur) 
	{
		return 6;
	}

	
	function getSiteaddress($info) 
	{
		return SiteSettings::where('id', 1)->first()->$info;
	}

	
	function getUserImage() 
	{
		$id = session::get('tmaitb_user_id');
		return User::where('id', $id)->select('profile')->first()->profile;
	}

	function getAllCurrency($currencyType) 
	{
		return currency::where(array('status' => 1, 'type' => $currencyType, 'visible_front' => 1))->get();
	}

	function getAllCurrencyForBuySell($currencyType) 
	{
		return currency::where(array('status' => 1, 'type' => $currencyType, 'visible_front' => 1))->orwhere(array('visible_front' => 2))->get();
	}

	function getCurrencyImage($symbol) 
	{
		return currency::where('symbol', $symbol)->select('image')->first()->image;
	}

	function getCurrencyCoinMrkWidget($symbol) 
	{
		return currency::where('symbol', $symbol)->select('coinmrkt_widget')->first()->coinmrkt_widget;
	}

	
	function getCurrencysymbol($id) 
	{
		return currency::where('id', $id)->select('symbol')->first()->symbol;
	}

	
	function getCurrencyid($symbol) 
	{
		return currency::where('symbol', $symbol)->select('id')->first()->id;
	}
	function getCurrencyWithDrawFee($symbol) 
	{
		return currency::where('symbol', $symbol)->select('with_fee')->first()->with_fee;
	}

	
	function getCurrencyname($symbol) 
	{
		return currency::where('symbol', $symbol)->select('name')->first()->name;
	}

	function getCurrencyLastPrice($symbol) 
	{
		return currency::where('symbol', $symbol)->select('inr_value')->first()->inr_value;
	}


	function lang_flag() 
	{
		$country = session::get('language');

		if ($country == '' || $country == 'en') {
			return 'us-flag-icon.png';
		} else if ($country == 'zh-CN' || $country == 'zh-TW') {
			return 'china-flag-icon.png';
		} else if ($country == 'fr') {
			return 'french-flag-icon.png';
		} else if ($country == 'es') {
			return 'spanish-flag-icon.png';
		} else if ($country == 'th') {
			return 'thailand-flag-icon.png';
		}

	}

	
	function checkMobile() 
	{
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/(tablet|ipad|amazon|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($useragent))) {
				return true;
			}
			;

			if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
				return true;
			}
		}
		return 0;
	}

	
	function admin_name($id) 
	{
		return SubAdmin::where('id', $id)->select('username')->first()->username;
	}

	
	function admin_image($admin_name) 
	{
		return SubAdmin::where('username', $admin_name)->select('profile')->first()->profile;
	}

	
	function userfull_name($id) 
	{
		$user = User::where('id', $id)->select('first_name')->first();
		if ($user) {
			return $user->first_name;
		} else {
			return '';
		}
	}

	
	function profilename_check($id) 
	{
		$user = User::where('id', $id)->select('first_name', 'last_name')->first();
		return $user->first_name . ' ' . $user->last_name;
	}

	
	function getcopyright() 
	{
		return SiteSettings::where('id', 1)->select('copy_right_text')->first()->copy_right_text;
	}

	
	function total_deposit($currency,$currencytype) 
	{
		if($currencytype == 'crypto')
		{
			$deposit = DB::table('tisoped')
			->where('currency', '=', $currency)
			->sum('amount');
			$dep_amount = number_format($deposit, 8, '.', '');
			
		}
		else
		{
			$deposit = DB::table('tisoped_taif')
			->where('currency', '=', $currency)
			->sum('amount');
			$dep_amount = number_format($deposit, 2, '.', '');
			
		}
		return $dep_amount;
	}

	
	function total_withdraw($currency,$currencytype) 
	{
		if($currencytype == 'crypto')
		{
			$withdraw = DB::table('wardhtiw_taif')
			->where('currency', '=', $currency)
			->where('status', '=', 'Completed')
			->sum('amount');
			$with_amount = number_format($withdraw, 8, '.', '');
			
		}
		else
		{
			$withdraw = DB::table('wardhtiw_taif')
			->where('currency', '=', $currency)
			->where('status', '=', 'Completed')
			->sum('amount');
			$with_amount = number_format($withdraw, 2, '.', '');
			
		}
		return $with_amount;
	}

	
	function ticket_details($id) 
	{
		$query = HelpCentre::where('reference_no', $id)->orderBy('id', 'asc')->get()->toArray();
		return $query;
	}

	
	function getProfile($id) 
	{
		$user = User::where('id', $id)->select('first_name', 'last_name')->first();
		if ($user) {
			return $user->first_name . ' ' . $user->last_name;
		} else {
			return '';
		}
	}

	
	function checkOrdertemp($id, $type) 
	{
		$query = OrderTemp::where($type, $id)->where('cancel_id', NULL)->select(DB::raw('SUM(filledAmount) as totalamount'));
		if ($query->count() >= 1) {
			$row = $query->first()->totalamount;
			return $row;
		} else {
			return false;
		}
	}

	
	function getBalance($id, $currency = '') 
	{
		
		$balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('amount')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->amount);
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					$balance = $wallets[$currency]; 
				} else {
					$wallets[$currency] = 0;
					$uptwallet = serialize($wallets);
					Wallet::where('user_id', $id)->update(array('amount' => $uptwallet));
					$balance = Wallet::getBalance($id, $currency);
				}
			} else {
				$balance = $wallets;
			}
		}
		return $balance;
	}
	function updateBalance($id, $currency, $updateAmount, $sum)
	{
		$balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('amount')->first();

		if ($wallet) {
			$wallets = unserialize($wallet->amount);

			if ($currency != '')
			{
				
				if(isset($wallets[$currency]))
				{

					$updateAmount = (isset($updateAmount) && $updateAmount > 0)?$updateAmount:0;
					$getbalance = $wallets[$currency];
					if($sum == '+')
					{
						$wallets[$currency] = $getbalance + $updateAmount;
					}

					if($sum == '-')
					{
						$wallets[$currency] = $getbalance - $updateAmount;
					}


					$balance = number_format((float) $wallets[$currency], 2, '.', '');
					$uptwallet = serialize($wallets);
					Wallet::where('user_id', $id)->update(array('amount' => $uptwallet));
				}

			}
		}
		return $balance;
	}

	
	function getPercent() 
	{
		return SiteSettings::where('id', 1)->select('trade_percent')->first()->trade_percent;
	}

	
	function randomcode($length) 
	{
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomcode = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
		return $randomcode;
	}
	function getUsd() 
	{
		$lastPrice = Currency::where('symbol', 'BTC')->select('inr_value')->first()->inr_value;
		return number_format($lastPrice, 2, '.', '');
	}

	
	function getadminBalance($currency, $address = '') 
	{	
		if($currency){
			$checkerc = Currency::where('symbol', $currency)->select('ERC20')->first();
			$confirmerc = $checkerc->ERC20;
			if($currency=='BTC'){
				$arr=array();
				return $balance = curl_request($currency,'getbalance',$arr);
			}
			 else if($currency=='ETH') {
             	$eth_adr = Config::get('sats.ETH.adminaddr');
                $adminaddress = insep_decode($eth_adr);
             	$data = array('adminaddress' => $adminaddress);
             	$balance = connecteth('checkbalance', $data);
             	return $balance;
            } else if($currency && $confirmerc == '1') {
             	$getdetails         = Tokens::where(['token_symbol' => $currency])->select('token_symbol', 'decimalval','contract_address', 'id')->first();
             	$contractaddress=$getdetails->contract_address;
             	$decimalval=$getdetails->decimalval;
             	$usname = Config::get('sats.ETH.adminaddr');
             	$adaccount =  insep_decode($usname);
             	$outputt = file_get_contents('https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=' . $contractaddress . '&address=' .trim($adaccount). '&tag=latest');
             	$resultt = json_decode($outputt);
             	if ('OK' == $resultt->message) {
             		$tokenbalance = $resultt->result;
             	}                
                if($decimalval!=0){
                	return $balance=$tokenbalance/$decimalval;
                }else{
                	return $balance=$tokenbalance;
                }             	
            } else {
             	return $balance = 0;
            }
        }
    }


    function curl_request($currency, $method, $postfields = null) 
    {		
		$usname = Config::get('sats.'.$currency.'.'.$currency.'usname');
		$pass = Config::get('sats.'.$currency.'.'.$currency.'pssword');
		$port = Config::get('sats.'.$currency.'.'.$currency.'port');
		$host = Config::get('sats.'.$currency.'.'.$currency.'host');
		
			$connection_parms['user'] = insep_decode($usname);
			$connection_parms['pass'] = insep_decode($pass);
			$connection_parms['port'] = insep_decode($port);
			$connection_parms['ip_addr'] =insep_decode($host);

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

	 function connecteth($method, $data = array()) 
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

	
	function getbottomcontent($id) 
	{	
		return Cms::where(array('status' => '1', 'type' => 'content', 'id' =>$id))->select('content', 'title', 'image_url', 'id')->first();
   	}

   	
   	function getconvertionprice($fromSymbol,$toSymbol)
   	{
   		$getpairs = TradePairs::where('from_symbol',$fromSymbol)->first();      
   		return number_format($getpairs->convertedeur,6);
   	}
   	
   	function getconvertionpricedb($fromSymbol,$toSymbol,$lastprice)
   	{
   		$getpairs = TradePairs::where('status', '1')->get();
		$to=$toSymbol;
	    $from1=$fromSymbol;
	 

	 	if($to == "EUR" and $from1=="BTC")
	 	{
	 		$eur = TradePairs::where('from_symbol', $from1)->first();
	 		$tocur = $eur->last_price;
	 		
	 		TradePairs::where('to_symbol', $to)->update(array('convertedeur' => $tocur));
			
	 	}
	 	else
	 	{
		   
					$marketeurprice = TradePairs::where('from_symbol','BTC')->where('to_symbol','EUR')->first();                  
                   	$tocur2 = (1/$lastprice) * $marketeurprice->last_price;                    
					TradePairs::where('from_symbol', $from1)->update(array('convertedeur' => $tocur2));
                
		}
	}

	function getconvertionbtcprice($fromSymbol,$toSymbol,$amount)
	{
   		$getpairs = TradePairs::where('status', '1')->get();
		$to=$toSymbol;
	    $from1=$fromSymbol;
	    
	   
	 	
	    if($from1 == "BTC")
	    {
            $tocur = $amount;
       
			return $tocur;
		}
		else
		{

				$marketprice = TradePairs::where('from_symbol', $fromSymbol)->where('to_symbol','BTC')->first();
				$tocur = 0;
				if($marketprice)
				{
					
					if(isset($marketprice->last_price) && $marketprice->last_price > 0)
					{
						$btcprice = $marketprice->last_price;
						$tocur = $amount * (1/$btcprice);
						return $tocur;
					}
					return $tocur;
	            }
	            else
	            {
					$tocur = 0;
					return $tocur;
				
				}	
			
		}
	}

	
	function getconvertionusdprice($fromSymbol,$toSymbol,$amount)
	{
   		$getpairs = TradePairs::where('status', '1')->get();
		$to=$toSymbol;
	    $from1=$fromSymbol;
	
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

		if(isset($response->data) && $response->status->credit_count == 1)
		{	
			$preres = $response->data->$from1->quote->$to;	
			$tocur = $preres->price * $amount;
			return $tocur;exit;
		}
		else
		{
			$marketprice = TradePairs::where('from_symbol', $fromSymbol)->where('to_symbol','BTC')->first();
			if($marketprice){
				$btcprice = $marketprice->last_price;
			$from2 = "BTC";
			$to1 = "USD";
			$cmc_url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?CMC_PRO_API_KEY=".$getSite->coinmarketapi."&symbol=".$from2."&convert=".$to1;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cmc_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($output);
			if ($response && !empty($response->data)) 
			{	
				$preres = $response->data->$from2->quote->$to1;	
				$tocur = $preres->price * $btcprice;
				return $tocur * $amount;
			}
		}else{
			$tocur = 0;
			return $tocur;
		}	
		}
	}

	
	function getcurrencynotice($type, $val, $lan, $cur) 
	{
	    $result = '';
		if (!empty($lan)) {
			if ($lan == 'en') {
				$show_res = $type;
			} else if ($lan == 'zh-CN') {
				$show_res = $type . "_CN";
			} else if ($lan == 'zh-TW') {
				$show_res = $type . "_TW";
			} else {
				$show_res = $type . "_" . $lan;
			}
			$getContent = Cms::where('name', 'Deposit_'.$cur)->select($show_res)->get();       
			if (isset($getContent[$val])) {
				$result = $getContent[$val]->$show_res;
			} else {
				$result = '';
			}
		} else {			
			$show_res = $type;
			$getContent = Cms::where('name','Deposit_'.$cur)->select($show_res)->get();
			
			if (isset($getContent[$val])) {
				$result = $getContent[$val]->$show_res;
			} else {
				$result = '';
			}
		}
		return $result;
	}

	
	function showpair($cur)
	{
		$trade=TradePairs::where('from_symbol',$cur)->orWhere('to_symbol',$cur)->get();
		return $trade;
	}
	
	
	function getbtcconversionusers($id, $currency,$cursymbol)
	{
		$balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('amount')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->amount);
			
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					  $balance = $wallets[$currency]; 	                  
	                  $btcwallets = Wallet::where('user_id', $id)->select('BTC_conversion')->first();
	                  $btcwallets = unserialize($btcwallets->BTC_conversion);
	                  $btcwallets[$currency] = getconvertionbtcprice($cursymbol,'BTC',$balance);
	                  $uptwallet = serialize($btcwallets);
	                  Wallet::where('user_id', $id)->update(array('BTC_conversion' => $uptwallet));
				} 
			} else {
				$balance = 0;
			}
		}
		return $balance;
	}

	
	function geteurconversionusers($id, $currency,$cursymbol)
	{
	    $balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('BTC_conversion')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->BTC_conversion);
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					  $balance = $wallets[$currency]; 
					  $eurwallets = Wallet::where('user_id', $id)->select('EUR_conversion')->first();
	                  $eurwallets = unserialize($eurwallets->EUR_conversion);
	                  $eurwallets[$currency] =getconvertioneurprice($cursymbol,'EUR',$balance);
	                 
					  $uptwallet = serialize($eurwallets);
					  Wallet::where('user_id', $id)->update(array('EUR_conversion' => $uptwallet));
				} 
			} else {
				$balance = 0;
			}
		}
		return $balance;
	}

 	
	function getconvertioneurprice($fromSymbol,$toSymbol,$amount)
	{
		
   		$getpairs = TradePairs::where('status', '1')->get();
		$to=$toSymbol;
	    $from1=$fromSymbol;
	  
	    if($from1 == "BTC")
	    {
            $tocur = $amount;
            
			return $tocur;
		}
		else
		{

			$marketprice = TradePairs::where('from_symbol', 'BTC')->where('to_symbol','EUR')->first();
			if($marketprice)
			{
				$btcprice = $marketprice->last_price;
				$tocur = $btcprice * $amount;
				
				return $tocur;
			}
			
			}
		
	}

	
	function callconversion($id)
	{

	      $getall=Currency::where('status', 1 )->select('symbol','id')->get();
	      $c = array();
	      foreach($getall as $val){
	        $a = getbtcconversionusers($id, $val->id,$val->symbol);
	        array_push($c, $a);
	        $b = geteurconversionusers($id, $val->id,$val->symbol);
	        
	        array_push($c, $b);
	      }
		  return $c;
	}

	
	function totalconversion($id,$convcur)
	{
		$tol=0;
	    $getall=Currency::where('status', 1 )->select('symbol','id')->get();
	    foreach($getall as $val){
	       	$wallet = Wallet::where('user_id', $id)->select($convcur.'_conversion')->first();
		    if ($wallet) {
			  	$getcr= $convcur.'_conversion';
				$wallets = unserialize($wallet->$getcr);
		  	    if ($val->id != '') {
					if (isset($wallets[$val->id])) {
					  $cal = $wallets[$val->id]; 
					  $tol += $cal;
					}
				}
			}
	    }	      
		return $tol;
	}

	
	function singlecurrencyconversion($id,$convcur,$cid)
	{
	    $cal=0;
	    $getall=Currency::where('id',$cid)->select('symbol','id')->first();
	    $wallet = Wallet::where('user_id', $id)->select($convcur.'_conversion')->first();
		if ($wallet) {
		  	$getcr= $convcur.'_conversion';
			$wallets = unserialize($wallet->$getcr);
			if ($getall->id != '') {
				if (isset($wallets[$getall->id])) {
				  $cal = $wallets[$getall->id]; 
				}
			}
		}
	    return $cal;
	}

	
	function conv_user()
	{
		$id = session::get('tmaitb_user_id');
		$convertion = callconversion($id);
	}

	
	function conv_home()
	{
		$currency = TradePairs::where('status', '1')->get();
		foreach ($currency as $value) 
		{
			$first = $value->from_symbol;
			$second = "EUR";
			$lastprice = $value->last_price;
			
			getconvertionpricedb($first,$second,$lastprice);
		}
		
	}

	
	function getbtc_conversionusers($id, $currency,$cursymbol)
	{
		$balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('amount')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->amount);
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					$balance = $wallets[$currency]; 	                  
	                $btcwallets = Wallet::where('user_id', $id)->select('BTC_conversion')->first();
	                $btcwallets = unserialize($btcwallets->BTC_conversion);
	                $btcwallets[$currency] = getconvertion_btcprice($cursymbol,'EUR',$balance);
	                $uptwallet = serialize($btcwallets);
					Wallet::where('user_id', $id)->update(array('EUR_conversion' => $uptwallet));
				} 
			} else {
				$balance = 0;
			}
		}
		return $balance;
	}

	
	function getconvertion_btcprice($fromSymbol,$toSymbol,$amount)
	{
   		$getpairs = TradePairs::where('status', '1')->get();
		$to=$toSymbol;
	    $from1=$fromSymbol;
	    if($to == "EUR")
	    {
            $tocur = $amount;
			return $tocur;
		}
		else
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
			if(isset($response->data) && $response->status->credit_count == 1)
			{	
				$preres = $response->data->$from1->quote->$to;	
				$tocur = $preres->price * $amount;
				return $tocur;
			}
			else
			{
				$marketprice = TradePairs::where('from_symbol', $fromSymbol)->where('to_symbol','EUR')->first();
				if($marketprice)
				{
					$btcprice = $marketprice->last_price;
					$tocur = $amount * $btcprice;
					return $tocur;				
				}
				else
				{
					$tocur = 0;
					return $tocur;
				}	
			}
		}
	}

	
	function getordertype($orderid)
	{
		$ordertype = CoinOrder::select('ordertype')->where('id',$orderid)->first()->ordertype;
		return $ordertype;
	}

	


	function loginAttemptspages($attempts = "",$url) 
	{
		
		$ip = \Request::ip();
	
		Hidden::insert(['url'=>$url,'ip' => $ip,'created_date'=>date('Y-m-d H:i:s')]);
		
		Cookie::queue('loginAttemptspages', $attempts + 1, time() + 600);
	
   }

   function getStaticContent($slug)
	{	
		$pageconte = Cms::where(array('status' => '1', 'type' => 'content', 'name' =>$slug))->select('content', 'title', 'image_url', 'id')->first();
		if(isset($pageconte->content))
		{
			return $pageconte;	
		}
		else
		{	
			$obj = (object) array('content' => "--");
			return $obj;
		}
   	}

   	function getSessionUserProfile(){

   		$ip = Controller::getIpAddress();
		$userLoggedIN = DB::table('sresu')->where('user_active_ip', $ip)->where('user_active_status', 1)->first();
		return $userLoggedIN;
   	}


   function files_get_content($url)
   	{

		$crl = curl_init();
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($crl);

		if(!$result){
			return array('');
		}
		curl_close($crl);
		$result = json_decode($result);
		return $result;
   	}
   	function files_get_content_post($url, $post_data)
   	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, count($post_data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		if(!$result){
			return array('');
		}
		curl_close($ch);
		$result = json_decode($result);
		return $result;
   	}

   	function getIpAddress() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) { 
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) { 
		$ipaddress = $_SERVER['HTTP_X_FORWARDED']; 
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) { 
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR']; 
		} else if (isset($_SERVER['HTTP_FORWARDED'])) { 
		$ipaddress = $_SERVER['HTTP_FORWARDED']; 
		} else if (isset($_SERVER['REMOTE_ADDR'])) { 
		$ipaddress = $_SERVER['REMOTE_ADDR']; 
		} else { 
		$ipaddress = 'UNKNOWN';
		} 
		return $ipaddress;


	}

   	function WhitelistIPCheck(){
   		$ip = getIpAddress();
   		$connected = WhiteIP::where(array('ip_addr' => $ip))->count();
   		if($connected == 1)
   		{
   			return true;
   		}
   		else{
   			
   			echo "<h1>Forbidden</h1> ".$ip;
   			die();

   		}
   		
   	}

   	function updateUserBalance($user_id, $currency, $addAmount) 
	{
		
		$balance = 0;
		$wallet = Wallet::where('user_id', $user_id)->select('amount')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->amount);
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					$balance = $wallets[$currency];
					$wallets[$currency] = $balance + $addAmount;
					$uptwallet = serialize($wallets);
					Wallet::where('user_id', $user_id)->update(array('amount' => $uptwallet));

					$balance = Wallet::getBalance($user_id, $currency);

				} else {
					$wallets[$currency] = $addAmount;
					$uptwallet = serialize($wallets);
					Wallet::where('user_id', $user_id)->update(array('amount' => $uptwallet));
					$balance = Wallet::getBalance($user_id, $currency);
				}
			} else {
				$balance = $wallets;
			}
		}
		return $balance;
	}
	function getSocketUrl(){
   		$url = Config::get('boomc.socketUrl');
   		if(isset($url) && !empty($url))
   		{
   			return $url;

   		}
   		else
   		{
   			return $url = url('/').':2053';
   		}

   	}

/*	function checkUserSessionIp()
	{

		if(session::get('tmaitb_user_id'))
		{

			$user_id = session::get('tmaitb_user_id');
			$active_ip = getIpAddress();
			$user = User::where('id', $user_id);
			
			if($user->count() == 1)
			{
				$user_active_ip = $user->first()->user_active_ip;
				if($user_active_ip == $active_ip)
				{
					return true;
				}
				else
				{
					
				}
				
			}
		}	

	}
*/