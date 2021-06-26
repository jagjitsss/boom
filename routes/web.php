<?php


use App\Model\BlockIP;
use App\Model\SiteSettings;

$remote = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $remote;
$checkIp = BlockIP::where('ip_addr', $ip)->count();
if ($checkIp > 0) {
	$img = asset('/') . ('public/assets/images/blocked.png');
	echo '<img src="' . $img . '" style="max-width:100%;height:auto">';exit;
}

Route::get('error/{id}', 'Front\Home@errorPage');
Route::get('site_under_maintenance', 'Front\Home@maintenance');
Route::get('/clear-log', function() {
$exitCode = Artisan::call('cache:clear');
$exitCode = Artisan::call('view:clear');
$exitCode = Artisan::call('config:clear');
$exitCode = Artisan::call('config:cache');
$output = shell_exec('rm -f storage/framework/sessions/*');
return "session is cleared";
});


Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::group(['prefix' => '', 'middleware' => ['web', 'under_maintain', 'language']], function ()
{

	


	Route::post('payout', array('as' => 'paypal','uses' => 'Paypal@postPayoutPaymentWithpaypal'));
	Route::post('getPayoutTransactionSync', array('as' => 'payout_sync_transaction_status','uses' => 'Paypal@getPayoutTransactionSync'));

	Route::post('paypal', array('as' => 'paypal','uses' => 'Paypal@postPaymentWithpaypal'));
	Route::get('status/{id}', array('as' => 'status','uses' => 'Paypal@getPaymentSuccessStatus'));
	Route::get('cancel-transaction/{id}', array('as' => 'cancel_transaction','uses' => 'Paypal@getCancellPaymentStatus'));

	Route::post('getTransactionSync', array('as' => 'sync_transaction_status','uses' => 'Paypal@getTransactionStatusSyncing'));
	Route::get('payout-status', array('as' => 'payout_status','uses' => 'Paypal@getPayoutPaymentStatus'));

	Route::get('/', 'Front\Home@index');
	Route::get('/validatemail', 'Front\Home@validatEmail');
	Route::get('register/validatemail', 'Front\Home@validatEmail');

	//Route::get('checktemplate/{id}', 'Front\Cron@checktemplate');
	Route::get('expiredordercancel', 'Front\Cron@expiredordercancel');
	Route::get('/coinlists/{id}', 'Front\Users@coinlist');
	Route::get('/coinlist/{id}', 'Front\Users@coinList');	

	/*Route::get('/updatebalance/{id}/{ids}/{idss}', 'Front\Users@updatebalance');*/

	
	Route::get('/exchange', 'Front\Exchange@index');
	Route::post('/makeexchange', 'Front\Exchange@makeexchange');
	Route::get('/exchangeHistory', 'Front\Exchange@exchange_history');
	

	Route::post('/paynetservice', 'Front\Exchange@paynetservice');	

	
	Route::get('/getMyTradeHistory/{id}/{td}', 'Front\Trade@getMyTradeHistory');
	Route::get('/trade', 'Front\Trade@index');
	Route::get('/trade/{id}', 'Front\Trade@trade');
	Route::get('/advance_trade', 'Front\Trade@advance_trade');
	Route::get('/advance_trade/{id}', 'Front\Trade@advance_trade_pair');
	Route::get('PairData/{id}', 'Front\Trade@getPairData');
	Route::get('PairData/{id}/{id1}', 'Front\Trade@getPairData');
	Route::get('PairDataadvance/{id}', 'Front\Trade@getPairDataadvance');
	Route::get('PairDataadvance/{id}/{id1}', 'Front\Trade@getPairDataadvance');
	Route::get('dummy/{id}/{id1}/{id2}', 'Front\Trade@dummy');
	Route::get('showdata', 'Front\Trade@showUserBalance');
	Route::get('showadvancedata', 'Front\Trade@showadvancUserBalance');
	Route::get('showMarket', 'Front\Trade@showTradeMarket');
	Route::get('coinPairs', 'Front\Trade@coinPairs');
	Route::get('coinPairs/{id}', 'Front\Trade@coinPairs');
	Route::get('coinPairs/{id}/{pair}', 'Front\Trade@coinPairs');
	Route::get('/chart/{coin}/{type}', 'Front\Trade@chart');
	
	Route::get('/comingsoon', 'Front\Home@comingsoon');
	
	Route::get('/userallow/{id}/{td}', 'Front\Home@useractivityallow');
	Route::get('/userblock/{id}/{td}', 'Front\Home@useractivityblock');
	Route::get('checkmethod/{id}/{type}', 'Front\Sats@checkmethod');
	Route::get('confirmtranferbyuser/{id}', 'Front\Transactions@confirmWithdrawProcess');
	Route::get('rejecttranferbyuser/{id}', 'Front\Transactions@RejectWithdrawProcess');



	Route::get('bscTokenDeposit', 'Front\Cron@bscTokenDeposit');
	Route::get('bscTokenGenerate', 'Front\Cron@generateNewTokenAddress');
	Route::get('movetokens', 'Front\Cron@movetokens');

	

	Route::get('checkDeposit/{id}', 'Front\Cron@deposits_cron');
	Route::get('tokenadmindeposit/{id}', 'Front\Cron@tokenadmindeposit');
	Route::get('getDepositeth', 'Front\Cron@getDepositeth');
	Route::get('checkTxn', 'Front\Cron@checkTxn');

	//Route::get('get_walletnotify', 'Front\Cron@get_walletnotify');
	//Route::get('get_blocknotify', 'Front\Cron@get_blocknotify');
	Route::get('moveToadmin', 'Front\Cron@moveToadmin');
	Route::get('/getchart/{id}', 'Front\Home@getchart');
	Route::get('captcha', 'Front\Home@captcha');
	//Route::get('kycalt', 'Front\Cron@kycalt');
	//Route::get('kycrjt', 'Front\Cron@kycrjt');
	//Route::get('not_ate', 'Front\Cron@not_ate');
	//Route::get('supportmail', 'Front\Cron@supportmail');
	//Route::get('blockcount', 'Front\Sats@blockcount');
	Route::get('/coinmarket', 'Front\Cron@coinmarket');
	
	Route::get('/sendsms/{id}/{con}', 'Front\Users@sendsms');

	Route::post('/refnoexists', 'Front\Users@refnoexists');
	Route::get('/test', 'Front\Home@test');
	Route::get('/test1/{id}', 'Front\Home@test1');
	Route::get('/checklog', 'Front\Users@checkloginstatus');
	Route::post('/userhiddenact', 'Front\Home@userhiddenact');

	Route::group(['middleware' => ['guest']], function () {
		Route::get('/forgotpassword', 'Front\Home@forgotPassword');
		Route::post('/forgotpassword', 'Front\Home@forgotPasswordRequest');
		Route::post('/resetpassword', 'Front\Home@resetPassword');
		Route::post('/register', 'Front\Home@makeRegister');
		Route::get('/login', 'Front\Home@login');
		
		Route::get('/register/{id}', 'Front\Home@register');
		Route::get('/register', 'Front\Home@register');
		Route::post('/login', 'Front\Home@checkLogin');
		Route::get('/resetaccount/{id}', 'Front\Home@resetaccount');
		Route::get('/twofa', 'Front\Home@tfaLogin');
		Route::post('/twofa', 'Front\Home@checkTfaLogin');
		Route::get('/activateaccount/{id}', 'Front\Home@activateUserAccount');
		Route::post('/sendmobileotp', 'Front\Users@sendotpreg');
	 	Route::post('/checkmobile', 'Front\Users@checkmobile');
		Route::post('/checkotp', 'Front\Users@checkotp');
		Route::post('/checkpassword', 'Front\Users@checkpassword');
		
	});
	
	Route::group(['middleware' => ['user_session', 'active_user']], function () {
		
		Route::get('/dashboard', 'Front\Users@dashboard');

		Route::get('profile', 'Front\Users@user_profile_view');
		//Route::get('/dashboards/{$id}', 'Front\Users@dashboardmsks');

		Route::get('/depositchart', 'Front\Home@depositchart');
		Route::group(['middleware' => ['check_user']], function () {		
	
			Route::get('/funds', 'Front\Users@funds');
			
			Route::post('/fiatdeposit', 'Front\Users@fiatdeposit');
			Route::post('/fiatwithdraw', 'Front\Users@fiatwithdraw');
			Route::get('/confirmwithdrawbyuser/{id}', 'Front\Users@confirmWithdrawProcess');
			Route::get('/rejectwithdrawbyuser/{id}', 'Front\Users@RejectWithdrawProcess');
			Route::get('/getadminbankwire/{id}', 'Front\Users@getadminbankwire');
			Route::get('/get_adminbankwire/{id}', 'Front\Users@get_adminbankwire');
			Route::get('/get_fiatcurrency/{id}', 'Front\Users@get_fiatcurrency');
			Route::get('/balancecheck', 'Front\Users@balancecheck');
			Route::get('/referral', 'Front\Users@referral');
			Route::post('/updatetfa', 'Front\Users@updateTfa');
			
			Route::post('/updateAlert', 'Front\Users@updateUserAlert');
			Route::post('/updatePassword', 'Front\Users@updateUserPassword');
			Route::get('/support', 'Front\Users@support');
			Route::get('/viewsupport', 'Front\Users@view_support');
			Route::get('/bankwire/{id}', 'Front\Users@bankwire');
			Route::post('/bankwire', 'Front\Users@updatebankwire');
			Route::post('/bankwire', 'Front\Users@updatebankwire');
			Route::get('/page404', 'Front\Users@handelerror');
			Route::post('/add_support', 'Front\Users@addSupport');
			Route::get('/viewticket/{id}', 'Front\Users@viewSupportTicket');
			Route::get('/ticketdetails/{id}', 'Front\Users@TicketDetails');
			Route::post('/edit_support', 'Front\Users@editSupport');
			Route::post('/updatekyc', 'Front\Users@updatekycDoc');
			Route::post('/referral_request', 'Front\Users@referral_request');
			Route::get('/referalHistory', 'Front\Users@referalHistory');
			Route::get('/referalList', 'Front\Users@referalList');
			Route::post('/autologout', 'Front\Users@autologout');
			Route::get('/autologout', 'Front\Users@autologout');
			Route::post('createorder', 'Front\Trade@createOrder');
			Route::post('cancelOrder', 'Front\Trade@cancelOrder');
			Route::post('addFav', 'Front\Trade@favoritePair');
			Route::get('get_coin_details/{id}', 'Front\Transactions@get_coins');
			Route::get('get_token_details/{id}', 'Front\Transactions@get_tokens');
			Route::post('accept_coin_warning/{id}', 'Front\Transactions@accept_alert');
			Route::post('withdraw', 'Front\Transactions@makeWithdraw');
			
			Route::get('depositHistory', 'Front\Users@deposit_history');
			Route::get('fiatdepositHistory', 'Front\Users@fiatdeposit_history');
			Route::get('withdrawHistory', 'Front\Users@withdraw_history');
			Route::get('fiatwithdrawhistory', 'Front\Users@fiatwithdraw_history');
			Route::get('resend_withdraw_mail/{id}', 'Front\Users@resend_email');
			Route::get('fiat_resend_withdraw_mail/{id}', 'Front\Users@fiat_resend_email');
			Route::get('close_withdraw/{id}', 'Front\Users@cancel_withdraw_request');
			Route::get('fiat_close_withdraw/{id}', 'Front\Users@fiat_cancel_withdraw_request');
			Route::get('/change_password', 'Front\Users@change_password');
			Route::get('/change_notification', 'Front\Users@change_notification');
			Route::get('/change_tfa', 'Front\Users@change_tfa');
			Route::get('/notification_list', 'Front\Users@notification_list');
		});
		Route::get('/getDevices', 'Front\Users@getDevices');
		Route::get('/editprofile', 'Front\Users@editProfile');
		Route::post('/editprofile', 'Front\Users@updateProfile');
		Route::get('/logout', 'Front\Home@logout');  


		Route::get('/check', 'Front\Home@check');  

		Route::get('/buy-sell', 'Front\Users@buy_sell_exchange');

		
		Route::get('/enable_key', 'Front\Users@apirequest'); 
		Route::post('/choose-currency', 'Front\Users@defaultCurrrencyChoosen'); 
		
	});
	 
	 
	 
	 Route::get('/api/api_document', 'Front\Home@apidocument');
	 Route::get('/otp/{id}', 'Front\Home@OTP');
	 Route::get('/destroysession', 'Front\Cron@destroysession');
	
});	


