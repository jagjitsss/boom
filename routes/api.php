<?php

use Illuminate\Http\Request;


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});
Route::post('/register', 'Api\ApiHome@registration');
Route::post('/email_validation', 'Api\ApiHome@email_validation');
Route::post('/login', 'Api\ApiHome@login');
Route::post('/tfalogin', 'Api\ApiHome@tfaLogin');
Route::post('/forgotPassword', 'Api\ApiHome@forgotPasswordRequest');
Route::get('/getcountries', 'Api\ApiHome@getCountries');
Route::get('/kyc_types', 'Api\ApiUsers@kyc_types');

Route::post('/cms', 'Api\ApiHome@cms_pages');
Route::get('/faq', 'Api\ApiHome@faq');
Route::get('/news', 'Api\ApiHome@news');
Route::post('/forgot_passcode', 'Api\ApiHome@forgot_passcode');
Route::post('/contact_us', 'Api\ApiHome@contact_us');
Route::get('/contact_address', 'Api\ApiHome@contact_address');
Route::post('/subscribe', 'Api\ApiHome@subscribe');
Route::post('/getPairDetails', 'Api\ApiTrade@getPairDetails');
Route::post('/market_list', 'Api\ApiUsers@market_list');
Route::post('/getTradeHistory', 'Api\ApiTrade@getTradeHistory');
Route::post('/myorders', 'Api\ApiTrade@myorders');
Route::post('/tradehistory', 'Api\ApiTrade@tradehistory');

Route::get('/getTradeHistory', 'Api\ApiTrade@getTradeHistory');

Route::post('/passcode', 'Api\ApiUsers@passcode');
Route::post('/checkpasscode', 'Api\ApiUsers@checkpasscode');
Route::post('/checkmobile', 'Api\ApiHome@checkmobile');
Route::post('/sendmobileotp', 'Api\ApiHome@sendotpreg');
Route::post('/checkotp', 'Api\ApiHome@checkotp');
Route::get('/tradechart_view', 'Api\ApiTrade@tradechart_view');
Route::get('/tradechartview/{id}', 'Api\ApiTrade@tradechartview');

Route::group(['middleware' => ['check_user_app']], function () {
	Route::post('/changePassword', 'Api\ApiUsers@updateUserPassword');
	Route::post('/viewProfile', 'Api\ApiUsers@viewProfile');
	Route::post('/profile_update', 'Api\ApiUsers@profile_update');
	Route::post('/user_details', 'Api\ApiUsers@user_details');
	Route::post('/get_key', 'Api\ApiUsers@TFA_get_key');
	Route::post('/tfa_update', 'Api\ApiUsers@tfa_update');
	Route::post('/kyc', 'Api\ApiUsers@kyc_update');
	Route::post('/kyc_details', 'Api\ApiUsers@kyc_details');
	Route::post('/dashboard', 'Api\ApiUsers@dashboard');
	Route::post('/getdashboardconversion', 'Api\ApiUsers@getdashboardconversion');
	Route::post('/singleCurrency', 'Api\ApiUsers@singleCurrency');
	Route::post('/add_ticket', 'Api\ApiUsers@add_ticket');
	Route::post('/ticket_list', 'Api\ApiUsers@ticket_list');
	Route::post('/support_categories', 'Api\ApiUsers@support_categories');
	Route::post('/add_coin', 'Api\ApiUsers@update_coin');
	Route::post('/get_cointype', 'Api\ApiUsers@coin_type');
	Route::post('/ticket_details', 'Api\ApiUsers@ticket_details');
	Route::post('/edit_support', 'Api\ApiUsers@edit_support');
	Route::post('/close_ticket', 'Api\ApiUsers@close_ticket');
	Route::post('/email_notification', 'Api\ApiUsers@mail_notification');
	Route::post('/email_status', 'Api\ApiUsers@email_status');
	Route::post('/notification_list', 'Api\ApiUsers@notification_list');
	Route::post('/add_Fav', 'Api\ApiUsers@add_favourites');
	Route::post('/Fav_list', 'Api\ApiUsers@Fav_list');
	//Route::post('/market_list', 'Api\ApiUsers@market_list');
	Route::post('/referral_request', 'Api\ApiUsers@referral_request');
	Route::post('/referral', 'Api\ApiUsers@referral');
	Route::post('/referral_list', 'Api\ApiUsers@referral_list');
	Route::post('/referalHistory', 'Api\ApiUsers@referalHistory');
	Route::post('/upload_image', 'Api\ApiUsers@upload_image');
	Route::post('/logout', 'Api\ApiUsers@logout');
	Route::post('/bankwire', 'Api\ApiUsers@updatebankwire');
	Route::post('/viewbankwire', 'Api\ApiUsers@bankwire');
	Route::post('/withdraw_history', 'Api\ApiTransaction@withdraw_history');
	Route::post('/deposit_history', 'Api\ApiTransaction@deposit_history');
	Route::post('/fiat_deposit_history', 'Api\ApiTransaction@fiat_deposit_history');
	Route::post('/fiat_withdraw_history', 'Api\ApiTransaction@fiat_withdraw_history');
	Route::post('/coin_details', 'Api\ApiTransaction@coin_details');
	Route::post('/withdraw', 'Api\ApiTransaction@withdraw');
	Route::post('/fiatwithdraw', 'Api\ApiTransaction@fiatwithdraw');
	Route::post('/deposit', 'Api\ApiTransaction@get_depositcoins');
	Route::post('/fiatdeposit', 'Api\ApiTransaction@fiatdeposit');
	Route::post('/accept_deposit', 'Api\ApiTransaction@accept_deposit');
	Route::post('/cancel_withdraw', 'Api\ApiTransaction@cancel_withdraw_request');
	Route::post('/cancel_fiat_withdraw', 'Api\ApiTransaction@cancel_fiat_withdraw_request');
	Route::post('/resend_withdraw', 'Api\ApiTransaction@resend_request_email');
	Route::post('/fiat_resend_email', 'Api\ApiTransaction@fiat_resend_request_email');
	Route::post('/createOrder', 'Api\ApiTrade@createOrder');
	Route::post('/cancelOrder', 'Api\ApiTrade@cancelTradeOrder');
});
*/
Route::group(['middleware' => ['throttle:20,1']], function () {
	 Route::get('getOrderbook/{id}', 'Apicall\Publicapi@getTradeHistory');
	 Route::get('returnTicker', 'Apicall\Publicapi@getPairDetails');
	 Route::get('getCurrencies', 'Apicall\Publicapi@getcurrencydetails');
	 Route::get('getMarketHistory/{id}', 'Apicall\Publicapi@getMarketHistory');	
 });

Route::group(['middleware' => ['check_user_api','throttle:20,1']], function () {
	Route::post('getAccountbalance', 'Apicall\Privateapi@getaccountbalance');
	Route::post('getDeposithistory', 'Apicall\Privateapi@getDeposithistory');
	Route::post('getWithdrawhistory', 'Apicall\Privateapi@getWithdrawhistory');
	Route::post('openBuyorders', 'Apicall\Privateapi@openBuyorders');
	Route::post('openSellorders', 'Apicall\Privateapi@openSellorders');
	Route::post('getFilledOrder', 'Apicall\Privateapi@getFilledOrder');
	Route::post('getCurrencybalance', 'Apicall\Privateapi@getCurrencybalance');
	Route::post('createOrderapi', 'Apicall\Privateapi@createOrder');
	Route::post('CancelOrder', 'Apicall\Privateapi@cancelOrder');
	Route::post('deposit', 'Apicall\Privateapi@get_depositcoins_api');
	Route::post('withdraw', 'Apicall\Privateapi@withdraw');	
 });