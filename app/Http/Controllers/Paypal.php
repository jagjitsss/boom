<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Input;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

use PayPal\Api\Payout;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\PayoutItem;
use PayPal\Api\Currency;

use PayPal\Api\Payee;
use PayPal\Api\OpenIdTokeninfo;

use App\Model\Fiatdeposit;
use App\Model\Fiatwithdraw;

class Paypal extends Controller
{
    private $_api_context;

    public function __construct()
    {
        parent::__construct();
    }

    
   /* public function payWithPaypal()
    {
        return view('paywithpaypal');
    }*/

     public function postPayoutPaymentWithpaypal(Request $request)
    {   

        $id = session::get('tmaitb_user_id');           
        if($id)
        {
            $data = Input::all();
            $validate = Validator::make($data, [
                'pay_net_service' => "required",
                'pay_net_amt_payout' => "required|numeric",
                'pay_net_currency_hidden_payout' => "required",
                'select_email_idpy' => "required",
            ],
            [
                'pay_net_service.required' => 'Choose Payment Serivce',
                'pay_net_amt_payout.required' => 'Enter Amount',
                'select_email_idpy' => "Enter Email ID"
            ]);

            if($validate->fails())
            {
                foreach($validate->messages()->getMessages() as $val => $msg)
                {
                    $response = array('status' => '0', 'result' =>  $msg[0]);
                    echo json_encode($response);
                    exit;
                }
            }

            if($data['pay_net_amt_payout'] > 0 && !empty($data['select_email_idpy']))
            {

                $currency_symbol = $data['pay_net_currency_hidden_payout'];
                $pay_net_amt_payout = $data['pay_net_amt_payout'];

                $currency_id = getCurrencyid($currency_symbol);
                $getBalance = getBalance($id, $currency_id);



                if($pay_net_amt_payout <= $getBalance)
                {   



                    $paypal_conf = \Config::get('sdkpaypal');
                    $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
                    $_api_context->setConfig($paypal_conf['settings']);


                    $payouts = new Payout();
                    $senderBatchHeader = new PayoutSenderBatchHeader();
                    $senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You have a Payout!");
                    $senderItem = new PayoutItem();
                    $setSenderItemId = rand(1111111, 99999999).rand(222222, 8888888);


                    $amount = $pay_net_amt_payout;
                    $fee_per = getCurrencyWithDrawFee($currency_symbol);
                    $fee_amt = ($fee_per * $amount) / 100;
                    $fee_amtt =number_format((float) $fee_amt, 8, '.', '');

                    $final_amount = $amount - $fee_amtt;


                    $senderItem->setRecipientType('Email')
                    ->setNote('Thanks for your patronage!')
                    ->setReceiver($data['select_email_idpy']) //suriyaprakash-buyer@sellbitbuy.net
                    ->setSenderItemId($setSenderItemId)
                    ->setAmount(new Currency('{"value":"'.$final_amount.'","currency":"'.$currency_symbol.'"}'));

                    $payouts->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);

                    //$request = clone $payouts;
                    try {
                        //$output = $payouts->createSynchronous($_api_context);
                        $output = $payouts->create(null, $_api_context);

                    }
                    catch(Exception $ex)
                    {
                       
                    }
                    
                    $payoutBatchId = $output->getBatchHeader()->getPayoutBatchId();
                    //$payoutBatchId = $setSenderItemId;
                    $payment_id = Session::put('payoutBatchId', $payoutBatchId);

                    
                    


                    $fiat_withdraw = Fiatwithdraw::create();
                    $fiat_withdraw->user_id = $id;
                    $fiat_withdraw->bankid = 0;
                    $fiat_withdraw->currency_id = getCurrencyid($currency_symbol);
                    $fiat_withdraw->currency = $currency_symbol;
                    $fiat_withdraw->amount = number_format((float) $amount, 8, '.', '');
                    $fiat_withdraw->fee_amt = $fee_amtt;
                    $fiat_withdraw->fee_per = $fee_per;
                    $fiat_withdraw->given_amount = $final_amount;
                    $fiat_withdraw->status = 'Processing';
                    $fiat_withdraw->confirm_code = $setSenderItemId;
                    $fiat_withdraw->ip_addr = recent_login_ip($id);
                    $fiat_withdraw->is_flag = 1;
                    $fiat_withdraw->remarks = "You have a Payout!";
                    $fiat_withdraw->created_at = date("Y-m-d h:i:s");
                    $fiat_withdraw->updated_at = date("Y-m-d h:i:s");
                    $fiat_withdraw->with_token = $payoutBatchId;
                    $fiat_withdraw->save();

                    
                    /*with_token
                    approve_date*/

                    echo json_encode(array(
                        'status' => 1,
                        'message' => "Authenticated Transaction",
                        'transactionUniqueID' => insep_encode($setSenderItemId)
                    ));
                }
                else
                {
                    echo json_encode(array(
                        'status' => 0,
                        'message' => "Insufficient Funds"
                    ));
                }

            }
        }
    }
    public function getPayoutTransactionSync()
    {
        
        $payoutBatchId = Session::get('payoutBatchId');
        if(isset($payoutBatchId))
        {

             $data = Input::all();
            $validate = Validator::make($data, [
                'transactionUniqueID' => "required",
            ],
            [
                'transactionUniqueID.required' => 'Required Transaction ID',
            ]);

            if($validate->fails())
            {
                foreach($validate->messages()->getMessages() as $val => $msg)
                {
                    $response = array('status' => '0', 'result' =>  $msg[0]);
                    echo json_encode($response);
                    exit;
                }
            }

            $transactionUniqueID = insep_decode($data['transactionUniqueID']);
            $fiat_withdraw_data = Fiatwithdraw::where(array(
                'with_token' => $payoutBatchId,
                'confirm_code' => $transactionUniqueID,
                'status'=> 'Processing'
            ));
            if($fiat_withdraw_data->count() > 0)
            {

                $fiat_withdraw = $fiat_withdraw_data->first();
                $getBalance = getBalance($fiat_withdraw->user_id, $fiat_withdraw->currency_id);
                $updateAmount = $fiat_withdraw->given_amount;
                $debitAmount = $fiat_withdraw->amount;
                if($updateAmount > 0 && $updateAmount  <= $getBalance)
                {
                    $newBalance = updateBalance($fiat_withdraw->user_id, $fiat_withdraw->currency_id, $debitAmount,'-');



                    $paypal_conf = \Config::get('sdkpaypal');
                    $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
                    $_api_context->setConfig($paypal_conf['settings']);



                    $payouts = new Payout();
                    $payoutTrans = $payouts->get($payoutBatchId, $_api_context, $restCall = null);
                    $getItems = $payoutTrans->getItems();
                    $item = $getItems[0];
                    

                    $transaction_status = $item->getTransactionStatus();
                    if($transaction_status == "SUCCESS")
                    {
                        $getPayoutItem = $item->getPayoutItem();
                        $transactionOtherDetails = json_encode(array(
                            'receiver' => $getPayoutItem->getReceiver(),
                            'recipient_type' => $getPayoutItem->getRecipientType(),
                            'sender_item_id' => $getPayoutItem->getSenderItemId(),
                            'batch_id' => $payoutBatchId,
                            'payout_item_id' => $item->getPayoutItemId(),
                            'transaction_id' => $item->getTransactionId(),
                            $transaction_id = $item->getTransactionId()
                        ));
                        $transaction_final_status = 'Completed';
                    }
                    else
                    {
                        $transactionOtherDetails = '';
                        $transaction_final_status = 'Cancelled';
                        $transaction_id = '--';
                    }


                    $fiat_withdraw->other_transaction_details = $transactionOtherDetails;
                    $fiat_withdraw->status = $transaction_final_status;
                    $fiat_withdraw->updated_at = date("Y-m-d h:i:s");
                    $fiat_withdraw->approve_date = date("Y-m-d h:i:s");
                    $fiat_withdraw->transaction_id = $transaction_id;
                    $fiat_withdraw->save();

                    $response = array('status' => '1', 'result' => 'Transaction '.$transaction_final_status);
                    echo json_encode($response);
                    exit;
                }
                else
                {
                    $response = array('status' => '0', 'result' =>  'Insufficient Amount');
                    echo json_encode($response);
                    exit;
                }

            }
            else
            {

                $response = array('status' => '0', 'result' =>  'Unknown Transaction');
                echo json_encode($response);
                exit;
            }
            
        }
    }
    
    public function postPaymentWithpaypal(Request $request)
    {

        $id = session::get('tmaitb_user_id');           
        if($id)
        {
            $data = Input::all();
            $validate = Validator::make($data, [
                'pay_net_service' => "required",
                'pay_net_amt' => "required|numeric",
                'pay_net_currency_hidden' => "required",
            ], [
                'pay_net_service.required' => 'Choose Payment Serivce',
                'pay_net_amt.required' => 'Enter Amount'
            ]);

            if ($validate->fails())
            {
                
                foreach ($validate->messages()->getMessages() as $val => $msg)
                {
                    $response = array('status' => '0', 'result' =>  $msg[0]);
                    echo json_encode($response);exit;
                }
            }


            if(isset($data['pay_net_amt']) 
                && $data['pay_net_amt'] > 0 
                && isset($data['pay_net_service']) 
                && $data['pay_net_service'] == 'paypal'
            )
            {

                $transactionUniqueID = rand(1111111, 99999999).rand(222222, 8888888);


                $paypal_conf = \Config::get('sdkpaypal');
                $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
                $_api_context->setConfig($paypal_conf['settings']);


                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $item_1 = new Item();

                $item_1->setName('Deposit Fiat') 
                    ->setCurrency($data['pay_net_currency_hidden'])
                    ->setQuantity(1)
                    ->setPrice($data['pay_net_amt']); 

                $item_list = new ItemList();
                $item_list->setItems(array($item_1));

                $amount = new Amount();
                $amount->setCurrency($data['pay_net_currency_hidden'])
                    ->setTotal($data['pay_net_amt']);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Your Are Buying Crypto');

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(url('/status/').'/'.$transactionUniqueID)
                    ->setCancelUrl(url('/cancel-transaction/').'/'.$transactionUniqueID);

                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));

                    
                try
                {
                    $output = $payment->create($_api_context);
                }
                catch(\PayPal\Exception\PPConnectionException $ex)
                {
                    if (\Config::get('app.debug'))
                    {
                        \Session::put('error','Connection timeout');
                        return redirect('/cancel-transaction/'.'/'.$transactionUniqueID);
                    
                    }
                    else
                    {
                        \Session::put('error','Some error occur, sorry for inconvenient');
                        return redirect('/cancel-transaction/'.'/'.$transactionUniqueID);
                    
                    }
                }
                foreach($payment->getLinks() as $link)
                {
                    if($link->getRel() == 'approval_url')
                    {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }

                
                if(isset($redirect_url))
                {
                    Session::put('paypal_payment_id', $payment->getId());
                    $transactionReferenceNumber = $payment->getId();
                    $fiat_deposit = Fiatdeposit::create();
                    $fiat_deposit->user_id = $id;
                    $fiat_deposit->payment_method = 'paypal';
                    $fiat_deposit->currency_id = getCurrencyid($data['pay_net_currency_hidden']);
                    $fiat_deposit->currency = $data['pay_net_currency_hidden'];
                    $fiat_deposit->amount = number_format((float) $data['pay_net_amt'], 2, '.', '');
                    $fiat_deposit->referencenum = $transactionReferenceNumber;
                    $fiat_deposit->bankid = 0;
                    $fiat_deposit->status = 'Pending';
                    $fiat_deposit->reject_reason = '';
                    $fiat_deposit->verify_transacion_id = $transactionUniqueID;
                    $fiat_deposit->created_at = date('Y-m-d h:i:s');
                    $fiat_deposit->updated_at = date('Y-m-d h:i:s');
                    $fiat_deposit->save();

                    echo json_encode(array(
                        'status' => 1,
                        'message' => "Authenticated Redirections",
                        'redirect_url' => $redirect_url,
                        'transactionUniqueID' => insep_encode($transactionUniqueID)

                     ));
                     exit;
                }

                
                echo json_encode(array(
                        'status' => 0,
                        'message' => "Unknown error occurred"
                     ));
                exit;

            }
            echo json_encode(array(
                        'status' => 0,
                        'message' => "Unknown error occurred"
                     ));
            exit;
        }
    }
    public function getPaymentSuccessStatus($transactionID)
    {
        
       $payment_id = Session::get('paypal_payment_id');
       $payment_id = (isset($payment_id) && !empty($payment_id))?$payment_id:Input::get('paymentId');
       if (empty(Input::get('PayerID')) || empty(Input::get('token')) || empty($transactionID))
       {
            return view('paypal_response');
       }

       $paypal_conf = \Config::get('sdkpaypal');
        $_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $_api_context->setConfig($paypal_conf['settings']);
        

       $payment = Payment::get($payment_id, $_api_context);        
       $execution = new PaymentExecution();
       $execution->setPayerId(Input::get('PayerID'));
       $result = $payment->execute($execution, $_api_context);
       

       /* $payer = $result->getPayer();
        $payer_info = $payer->getPayerInfo();
        $transactions = $result->getTransactions();
        $getRelatedResources = $transactions[0]->getRelatedResources();
        $getSale = $getRelatedResources[0]->getSale();*/
        /*echo "Get amount >>";
        $getAmount = $getSale->getAmount();            
        $getAmountDetails = $getAmount->getDetails();
        echo "<br/> subtotal >>>";
        echo $subtotal = $getAmountDetails->getSubTotal();
        echo "<br/> shipping >>>";
        echo $shipping = $getAmountDetails->getShipping();
        echo "<br/> insurance >>>";
        echo $insurance = $getAmountDetails->getInsurance();
        echo "<br/> handling fee >>>";
        echo $handling_fee = $getAmountDetails->getHandlingFee();
        echo "<br/> shipping discount >>>";
        echo $shipping_discount = $getAmountDetails->getShippingDiscount();
        echo "<br/> discount >>>";*/
       

       if($result->getState() == 'approved')
       {
           $payer = $result->getPayer();
           $payer_info = $payer->getPayerInfo();
           $transactions = $result->getTransactions();
           $getRelatedResources = $transactions[0]->getRelatedResources();
           $getSale = $getRelatedResources[0]->getSale();

           $getAmount = $getSale->getAmount();
           
           $getAmountDetails = $getAmount->getDetails();
           $subtotal = $getAmountDetails->getSubTotal();        
           $shipping = $getAmountDetails->getShipping();        
           $insurance = $getAmountDetails->getInsurance();        
           $handling_fee = $getAmountDetails->getHandlingFee();        
           $shipping_discount = $getAmountDetails->getShippingDiscount();

           $total = $subtotal - $insurance - $handling_fee;

           $TransactionDetails = json_encode(
               array(
                   'email' => $payer_info->getEmail(),
                   'first_name' => $payer_info->getFirstName(),
                   'last_name' => $payer_info->getLastName(),
                   'payer_id' => $payer_info->getPayerId(),
                   'phone' => $payer_info->getPhone(),
                   'country_code' => $payer_info->getCountryCode(),
                   'transaction_id' => $getSale->getId(),
                   'subtotal'=> $subtotal,
                   'shipping'=> $shipping,
                   'insurance'=> $insurance,
                   'handling_fee'=> $handling_fee,
                   'shipping_discount'=> $shipping_discount,
                   'total' => $total

               )
           );

           $fiat_deposit_data = Fiatdeposit::where(array('referencenum'  => $payment_id, 'verify_transacion_id' => $transactionID, 'status' => 'Pending'));
           if($fiat_deposit_data->count() > 0)
           {
                $fiat_deposit = $fiat_deposit_data->first();                
                $getBalance = getBalance($fiat_deposit->user_id, $fiat_deposit->currency_id);
                
                $updateAmount = $total;
                if($updateAmount > 0)
                {
                    $newBalance = updateBalance($fiat_deposit->user_id, $fiat_deposit->currency_id, $updateAmount, '+');
                }
                $fiat_deposit->amount = $updateAmount;
                $fiat_deposit->updated_at = date('Y-m-d h:i:s');
                $fiat_deposit->status = 'Completed';
                $fiat_deposit->paypal_transaction_detail = $TransactionDetails;
                
                $fiat_deposit->save();
                Session::forget('paypal_payment_id');
            }
        }
        return view('paypal_response');
    }    
    public function getCancellPaymentStatus($transactionID)
    {     
         if(isset($transactionID) &&  !empty($transactionID))
         {
            $fiat_deposit_data = Fiatdeposit::where(array('verify_transacion_id' => $transactionID, 'status' => 'Pending'));
            if($fiat_deposit_data->count() > 0)
            {
                $TransactionDetails = json_encode(array(''));
                $fiat_deposit = $fiat_deposit_data->first();
                
                $fiat_deposit->updated_at = date('Y-m-d h:i:s');
                $fiat_deposit->referencenum = 'Failed-'.$transactionID;
                $fiat_deposit->status = 'Cancelled';
                $fiat_deposit->paypal_transaction_detail = $TransactionDetails;
                $fiat_deposit->save();
            }       
            return view('paypal_response');
         }
         else
         {
            return view('paypal_response');
         }
    }

    public function getTransactionStatusSyncing()
    {
        $data = Input::all();
        $validate = Validator::make($data, [
            'transactionUniqueID' => "required",
        ], [
            'transactionUniqueID.required' => 'Choose Payment Serivce',
        ]);

        if ($validate->fails())
        {                
            foreach ($validate->messages()->getMessages() as $val => $msg)
            {
                $response = array('status' => '0', 'result' =>  $msg[0]);
                echo json_encode($response);
                exit;
            }
        }
            
        if(isset($data['transactionUniqueID']) && !empty($data['transactionUniqueID']))
        {
            $transactionUniqueID = insep_decode($data['transactionUniqueID']);
            $fiat_deposit_data = Fiatdeposit::where(array('verify_transacion_id' => $transactionUniqueID,));
            if($fiat_deposit_data->count() > 0)
            {                
                $fiat_deposit = $fiat_deposit_data->first();
                if($fiat_deposit->status == "Completed")
                {

                    $response = array('status' => '1', 'result' => 'Transaction '.ucfirst($fiat_deposit->status));
                    echo json_encode($response);
                    exit;    
                }
                elseif($fiat_deposit->status == "Cancelled")
                {
                    $response = array('status' => '2', 'result' => 'Transaction '.ucfirst($fiat_deposit->status));
                    echo json_encode($response);
                    exit;    
                }
                else
                {
                    $response = array('status' => '0', 'result' => 'Please Wait');
                    echo json_encode($response);
                    exit;    
                       
                }                
                
            }
        }
        
    }

}
