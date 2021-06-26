<?php $support = getSite();?>
<?php $i1 = app('request')->input('name');?>



<div class="container dashboard-tabs-cnt">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-11 tab-content"> 
      <div class="tab-pane container-fluid active no-padding" id="overview">
        <div class="inner_banner">
          @include('front.common.titlebarmenu')
        </div>
         
      </div>
      </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-11 tab-content"> 
      <div class="tab-pane container active no-padding" id="overview">
       
         <div class="dash-buy-sell-sec">
          <div class="">
            <div class="row">
          <div class="col-md-12 col-sm-6 col-lg-6 wow fadeInLeft">
            <div class="ms-l">
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item resetcardboard" data-type="Buying"> <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Buy</a> </li>

                <li class="nav-item resetcardboard" data-type="Selling"> <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Sell</a> </li>

              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  <div>
                    <div class="banner-under-boompay2">


                      {!! Form::open(array('id'=>'buy_exchange','url'=>'makeexchange','class'=>'my-5','form_style_1','method'=>'POST')) !!}
                      <input type="hidden" id="instantMethod" name="instantMethod" value="false">
                      <input type="hidden" name="extype" value="buy">
                      <div class="row justify-content-center d-flex bub2">
                        <?php
                              
                              $currencyList = getAllCurrencyForBuySell('crypto');
                              
                              $i = 1;
                              foreach($currencyList as $key => $value)
                              {

                                  $image = getCurrencyImage($value->symbol);
                                  $name = getCurrencyname($value->symbol);

                                  $style = '';
                                  $checked = '';
                                  if($value->symbol == 'BTC')
                                  {
                                    $style = 'border:2px solid orange;';  
                                    $checked = 'checked="true"';
                                  }
                                  
                        ?>
                          
                            <div class="col-md-3">
                              <label for="ksad_{{$i}}">
                              <div id="selectnew_{{$i}}" style="{{$style}}" class="boompay-box2 text-center currencyselectedlist">
                                <div class="text-center">
                                  @if($image)
                                    <img class="img-fluid" src="{{url('public/assets/images/'.$image)}}" alt="">
                                  @endif
                                </div>
                                <div>
                                  <h3>{{$name}}</h3>
                                    <p class="last-price-box">
                                        <span id="selected_box_currency_amt_{{$value->symbol}}">
                                          <?php echo number_format($value->inr_value, 8, '.',''); ?>
                                        </span>
                                        <span class="selected_box_currency"> USD</span>
                                    </p>
                                  <p><input {{$checked}} id="ksad_{{$i}}" data-id="{{$i}}" type="radio" name="select_to_currency" class="select_to_currency" value="{{$value->symbol}}" style="position: inherit!important;" /></p>
                                  
                                </div>
                                
                              </div>
                              </label>
                            </div>
                          
                        <?php $i++; }  ?>
                        
                        <div class="col-md-3"> </div>
                      </div>
                      <h2>Buy Payment Method</h2>
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                            <div class="drop-dash-main">
                              <div class="drop-dash-main2">
                                  <div>
                                    <img style="width: 69% !important;" id="triggeredImage" src="<?php echo url('public/images/admin_currency/usdd.png'); ?>" alt="">
                                  </div>
                                <div>
                                  <h3 id="updated_selected_currency">USD Wallet</h3>
                                  
                                </div>
                              </div>
                            </div>
                        </button>
                         <div class="drop-dash-main3">
                            <a href="javascript:void(0);" id="triggerdepositex">
                               <i class="fa fa-paper-plane"></i>
                               INSTANT
                            </a>
                          </div>


                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item currencyselectionpypl" data-image="<?php echo url('public/images/admin_currency/usdd.png'); ?>" data-pay="USD" href="javascript:void(0);">USD Wallet</a>
                            <a class="dropdown-item currencyselectionpypl" data-image="<?php echo url('public/images/admin_currency/eurr.png'); ?>" data-pay="EUR" href="javascript:void(0);">EUR Wallet</a>
                            <a class="dropdown-item currencyselectionpypl" data-image="<?php echo url('public/images/admin_currency/gbpp.png'); ?>" data-pay="GBP" href="javascript:void(0);">GBP Wallet</a>
                          </div>
                        </div> 

                        

                      <h2>Limit</h2>
                      <div class="total-usd-box d-flex">
                        
                        <div class="total-usd-box-l">
                            <i class="fa fa-folder"></i>
                            Minimum <span class="selected_box_currency">USD</span> Limit
                        </div>
                        <div id="buy_min_amt_txt" class="total-usd-box-r">0.00</div>
                      </div>

                      <div class="total-usd-box d-flex">
                        
                        <div class="total-usd-box-l"><i class="fa fa-folder"></i> Maximum <span class="selected_box_currency">USD</span> Limit</div>
                        <div id="buy_max_amt_txt" class="total-usd-box-r">0.00</div>
                      </div>

                      <div class="progress-bar"></div>
                      <div class="usd-btc buysell-ms-box">
                        <div class="row">
                          <div class="col-md-5">
                          <div class="total-usd-boxs-m"><b>
                            

                            <input type="hidden" name="from_currency" value="USD" id="buy_from_currency">

                            <input type="text" class="" placeholder="Enter Amount" value="" aria-label="memo" name="amount" id="buy_amount" onkeypress="return isNumberKey(event)" onkeyup="buy_caltot()"> </b>
                            <b><span id="payment_method_wallet_2">USD</span></b>
                          </div>
                          </div>
                          <div class="col-md-1"> 
                          <div class="total-usd-boxs-c"><i class="fa fa-arrows-h" aria-hidden="true"></i> </div></div>
                          <div class="col-md-6">
                          <div class="total-usd-boxs-m">
                            <b>
                              <input type="hidden" name="to_currency" value="BTC" id="buy_to_currency"/>

                              <input type="text" name="to_currency_amt" class="buy_total_input" id="buy_to_currency_amt" value="" placeholder="0.00"
                              onkeypress="return isNumberKey(event)" onkeyup="buy_caltot_to_curr()"/>
                            </b>
                            <b><span class="crypto_to_currency">BTC</span></b>
                          </div>
                          </div>
                        </div>
                        <hr>
                        
                        <div>
                          <button id="buy_exchange_button" class="dash-btn-primary">Buy BTC Instantly</button>
                        </div>
                        </div>
                        {!! Form::close() !!}
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div>
                <div class="banner-under-boompay2">
                   {!! Form::open(array('id'=>'sell_exchange','url'=>'makeexchange','class'=>'my-5','form_style_1','method'=>'POST')) !!}
                      <input type="hidden" name="extype" value="sell">
                      <div class="row justify-content-center d-flex bub2">
                        <?php
                              $currency_pairs_details = currency_pairs_details_home();
                              $currencyList = getAllCurrencyForBuySell('crypto');
                              
                              $i = 1;
                              foreach($currencyList as $key => $value)
                              {

                                  $image = getCurrencyImage($value->symbol);
                                  $name = getCurrencyname($value->symbol);

                                  $style = '';
                                  $checked = '';
                                  if($value->symbol == 'BTC'){
                                    $style = 'border:2px solid orange;';  
                                    $checked = 'checked="true"';
                                  }

                                  
                        ?>
                          
                            <div class="col-md-3">
                              <label for="ksadsell_{{$i}}">
                              <div id="selectsell_{{$i}}" style="{{$style}}" class="boompay-box2 text-center currencyselectedlistsell">
                                <div class="text-center">
                                  @if($image)
                                            <img class="img-fluid" src="{{url('public/assets/images/'.$image)}}" alt="">
                                          @endif
                                </div>
                                <div>
                                  <h3>{{$name}}</h3>
                                  <p class="last-price-box">

                                    <span id="sell_selected_box_currency_amt_{{$value->symbol}}">
                                          <?php echo number_format($value->inr_value, 8, '.',''); ?>
                                        </span>
                                        <span class="sell_selected_box_currency"> USD</span>
                                      
                                    </p>
                                  <p><input {{$checked}} id="ksadsell_{{$i}}" data-id="{{$i}}" type="radio" name="select_to_currency" class="select_to_currency_sell" value="{{$value->symbol}}" style="position: inherit!important;" /></p>
                                  
                                </div>
                                
                              </div>
                              </label>
                            </div>
                          
                        <?php $i++; }  ?>
                        
                        <div class="col-md-3"> </div>
                      </div>

                      <h2>Sell Payment Method</h2>
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButtonSell" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                            <div class="drop-dash-main">
                              <div class="drop-dash-main2">
                                <div>
                                  <!-- <img src="public/assets/images/img5.png" alt=""> -->
                                  <img style="width: 69% !important;" id="triggeredImageSell" src="<?php echo url('public/images/admin_currency/usdd.png'); ?>" alt="">
                                </div>
                                <div>
                                  <h3 id="updated_selected_currency_payout">USD Wallet</h3>
                                  
                                </div>
                              </div>
                            </div>
                        </button>
                         <div class="drop-dash-main3">
                            <a href="javascript:void(0);" id="triggerdepositex_payout">
                               <i class="fa fa-paper-plane"></i>
                               INSTANT
                            </a>
                          </div>


                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonSell">

                            <a class="dropdown-item currencyselectionpyplpout" data-pay="USD" href="javascript:void(0);" data-image="<?php echo url('public/images/admin_currency/usdd.png'); ?>">USD Wallet</a>
                            <a class="dropdown-item currencyselectionpyplpout" data-pay="EUR" href="javascript:void(0);" data-image="<?php echo url('public/images/admin_currency/eurr.png'); ?>">EUR Wallet</a>
                            <a class="dropdown-item currencyselectionpyplpout" data-pay="GBP" href="javascript:void(0);" data-image="<?php echo url('public/images/admin_currency/gbpp.png'); ?>">GBP Wallet</a>
                          </div>
                        </div> 





                      <h2>Limit</h2>
                      <div class="total-usd-box d-flex">
                        
                        <div class="total-usd-box-l"><i class="fa fa-folder"></i> Minimum <span class="selected_box_currency_crypto">USD</span> Limit</div>
                        <div id="sell_min_amt_txt" class="total-usd-box-r">0.00</div>
                      </div>

                      <div class="total-usd-box d-flex">
                        
                        <div class="total-usd-box-l"><i class="fa fa-folder"></i> Maximum <span class="selected_box_currency_crypto">USD</span> Limit</div>
                        <div id="sell_max_amt_txt" class="total-usd-box-r">0.00</div>
                      </div>

                     

                      
                      <div class="progress-bar"></div>
                      <div class="usd-btc buysell-ms-box">
                        <div class="row">
                          <div class="col-md-5">
                          <div class="total-usd-boxs-m"><b>
                            

                            <input type="hidden" name="from_currency" value="BTC" id="sell_from_currency">

                            <input type="text" name="from_currency_amt" class="sell_total_input" id="sell_amount" value="" placeholder="0.00"
                              onkeypress="return isNumberKey(event)" onkeyup="sell_caltot()"/>

                          </b>
                            <b class="crypto_to_currency">BTC</b>
                          </div>
                          </div>
                          <div class="col-md-1"> 
                          <div class="total-usd-boxs-c"><i class="fa fa-arrows-h" aria-hidden="true"></i> </div></div>
                          <div class="col-md-6">
                          <div class="total-usd-boxs-m">
                            <b>
                              

                              <input type="hidden" name="to_currency" value="USD" id="sell_to_currency"/>

                               <input type="text" class="" placeholder="Enter Amount" value="" aria-label="memo" name="amount" id="sell_to_currency_amt" onkeypress="return isNumberKey(event)" onkeyup="sell_caltot_to_curr()">

                            </b>
                            <b>
                              <span class="fiat_to_currency">USD</span>
                            </b>
                          </div>
                          </div>
                        </div>
                        <hr>
                       
                        <div>
                          <button id="sell_exchange_button" class="dash-btn-primary">Sell BTC Instantly</button>
                        </div>
                        </div>
                        {!! Form::close() !!}
                    
                </div>
                    
                    </div>
              </div>
            </div>
          </div>
        </div>
            
            <div class="col-md-12 col-sm-6 col-lg-6 wow fadeInLeft">
              <div class="buying-btc-box">
                <h6 class="font16">You are <span id="typeofmode">Buying</span></h6>
                <h1 class="font21 p"><span  class="buy_total_scrn">0.0000</span></h1>
                <h5 class="font14"> <span id="perpriceusdvalue">{{getUsd()}}</span> <span id="perpriceusdvaluesymbol">USD</span> per <span id="perpricesymbol">BTC</h5>
                <div class="buying-btc-box-inn">
                  <div class="d-flex pay-method">
                      <div class="pay-method-l"><b><i class="fa fa-folder"></i></b></div>
                      <div class="pay-method-r">
                      <p class="font16">Payment Method</p>
                      <h3 class="font20"><span id="payment_method_wallets">USD</span> Wallet</h3>
                    </div>
                  </div>
                  
                  <div class="d-flex pay-method">
                      <div class="pay-method-l"><b><i class="fa fa-folder"></i></b></div>
                      <div class="pay-method-r">
                      <p class="font16">Deposit to</p>
                      <h3 class="font20"><span class="crypto_to_currency">BTC</span> Wallet</h3>
                    </div>
                  </div>
                </div>
                <div class="buying-btc-box-inn2">
                  <div class="d-flex pay-method2">
                    <div><b>Fee</b></div>
                    <div><b><span class="buy_fees">0</span></b></div>
                  </div>
                  <div class="d-flex pay-method2">
                    <div><b>Subtotal</b></div>
                    <div><b><span class="buy_sub_total">0</span> </b></div>
                  </div>
                  <div class="d-flex pay-method2">
                    <div><b>Total</b></div>
                    <div><b><span class="buy_total">0</span></b></div>
                  </div>
                </div>
              </div>
              <p class="text-center">Learn more about our fees <a target="_blank" href="<?php echo Config::get('domain.url').'fees'; ?>">here</a></p>

              <p class="text-center">Exchange History <a target="_blank" href="<?php echo url("/funds"); ?>#exchange-bs-history">Here</a></p>

            </div>
      </div>
      </div>
  </div>
</div>

</div>
  </div>
  </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>




<div class="modal" id="myModal_ex">
  <div class="modal-dialog">
    <div class="modal-content">


      <div class="modal-header">
        <h4 class="modal-title">Attention</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button> 
      </div>


      <div class="modal-body" id="messages">
        
      </div>


      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal" id="accept">Accept</button>
        <button type="button" class="btn" data-dismiss="modal" id="cancel">Cancel</button>
      </div>

    </div>
  </div>
</div>


<div class="modal" id="myModal_buyex">
  <div class="modal-dialog">
    <div class="modal-content">

      
      <div class="modal-header">
        <h4 class="modal-title">PayPal</h4>
         
      </div>


      
       <form id="payment_service" method="POST" class="my-5 form_style_1">
      
      <div class="modal-body" id="messagess">
       

          <div class="col-md-4">

            <label for="select_pay_service_net"> Deposit</label>
            <label id="selectedcurrency_label" for="select_pay_service_net">USD</label>
            <input  checked="true "type="radio" id="pay_net_service" name="pay_net_service" class="form-control" value="paypal">
            

          </div>
          <div class="col-md-4">
            
            <input type="text" name="pay_net_amt" class="form-control" id="pay_net_amt" value="" placeholder="0.00" onkeypress="return isNumberKey(event)"/>
            <input type="hidden" id="pay_net_currency_hidden" name="pay_net_currency_hidden" class="form-control" value="USD">
            

          </div>
          
          

      </div>

      
      <div class="modal-footer">
        <div class="deposit-paypal-box">
          <a href="javascript:void(0);" id="pay_net_paypalsubmit_goto" onclick="return proceedpaypal()" class="form-control btn-primary" style="display: none;">
              Proceed
          </a>
          <button id="pay_net_paypalsubmit" class="form-control btn-primary" name="paypalsubmit">
              Deposit Now
          </button>
          <button type="button" class="btn-secondary" data-dismiss="modal" id="pay_net_cancel">Cancel</button>
        </div>
      </div>

      
    </form>

    </div>

  </div>
</div>






<div class="modal" id="myModal_sellex">
  <div class="modal-dialog">
    <div class="modal-content">

      
      <div class="modal-header">
        <h4 class="modal-title">PayPal</h4>
        
      </div>


      
       <form id="payment_service_payout" method="POST" class="my-5 form_style_1">
      
      <div class="modal-body" id="messagess">
       

        
          <div class="col-md-4">

            <label for="select_pay_service_net"> Withdraw</label>
            <label id="selectedcurrency_label_payout" for="select_pay_service_net">USD</label>
            <input  checked="true "type="radio" id="pay_net_service" name="pay_net_service" class="form-control" value="paypal">
            

          </div>
          <div class="col-md-4">
            <label for="select_email_idpy"> PayPal Email ID</label>
            <input type="text" id="select_email_idpy" name="select_email_idpy" class="form-control" value="">
          </div>

          <br/>

          <div class="col-md-4">
            <label for="select_email_idpy">Amount</label>
            <input type="text" name="pay_net_amt_payout" class="form-control" id="pay_net_amt_payout" value="" placeholder="0.00" onkeypress="return isNumberKey(event)"/>
            <input type="hidden" id="pay_net_currency_hidden_payout" name="pay_net_currency_hidden_payout" class="form-control" value="USD">


            

          </div>
          
          

      </div>

      
      <div class="modal-footer">

        <div class="deposit-paypal-box">
          <button id="pay_net_paypalsubmit_payout" class="form-control btn-primary" name="paypalsubmit">
              Withdraw Now
          </button>
          <button type="button" class="btn-secondary" data-dismiss="modal" id="pay_net_cancel_payout">Cancel</button> 
         </div>
       
         
      </div>

      
    </form>

    </div>

  </div>
</div>



<script>
  var no_records   = "{{trans('app_lang.no_records_found') }}";
  var submit = "{{trans('app_lang.submit')}}";
  var profile_btn= "{{trans('app_lang.update_profile') }}";
</script>

<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
<script src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.js"></script>
<script src="{{asset('/').('public/assets/js/popper.min.js')}}"></script>

<script src="{{asset('/').('public/assets/js/viewportchecker.js')}}"></script>
<script src="{{asset('/').('public/assets/js/aos-animation-script.js')}}"></script>
<script src="{{asset('/').('public/assets/js/jquery.validate.js')}}"></script>
<script src="{{asset('/').('public/assets/js/additional-methods.js')}}"></script>
<script src="{{asset('/').('public/assets/js/notifIt.min.js')}}"></script>
<script src="{{asset('/').('geetest/gt3-php-sdk-master/static/gt.js?ver=123')}}"></script>
<script src="{{asset('/').('public/build/js/')}}intlTelInput.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="{{asset('/').('public/assets/js/socket.io.min.js') }}"></script>
<script type="text/javascript">

  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,      
    maximumFractionDigits: 2,
  });

    const formatterOne = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 1,      
    maximumFractionDigits: 1,
  });

    var CLOSING_WINDOW_TIME;
    var START_SYNCING = false;

  
    var pair_data = {};
    var site_url  = window.location.hostname;
    if(site_url == "localhost" || site_url == "127.0.0.1") {
      var socket                  =   io.connect( 'http://'+window.location.hostname+':8443',{transports:['websocket'], upgrade: false}, {'force new connection': true} );
    } else if(site_url == "") {
      var socket                  =   io.connect( 'https://'+window.location.hostname+':8443',{transports:['websocket'], upgrade: false}, {'force new connection': true} );
    } else {
     var currentUrl = document.location.origin;
     var socket = io.connect( 'https://'+window.location.hostname+':8443',{transports:['websocket'], upgrade: false}, {'force new connection': true} );
   }


   var last_price = parseFloat("{{getUsd()}}");

   
  $('#selectUserBankDetails').change(function(){
    
    $("#selectedBankDetails").html("");
  });

  $('#payment_service_payout').validate({
     rules:
     {      
      pay_net_service:
      {
        required: true,
      },
      pay_net_amt_payout: {
        required: true,
        number:true,
        positiveNumber:true,
      },
      pay_net_currency_hidden_payout:
      {
        required: true,
      },
      select_email_idpy : {
        required:true,
        email:true
      },
    },
    submitHandler:function()
    {

      var dataform = $('#payment_service_payout').serialize();
      $.ajax({
        type:'POST',
        url: "{{ URL::to('payout') }}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },        
        data:dataform,
        beforeSend:function(output)
        {
          $('#pay_net_paypalsubmit_payout').attr('disabled',true);
          $('#pay_net_cancel_payout').attr('disabled',true);
          $('#pay_net_paypalsubmit_payout').html('Please Wait <i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(output)
        {  
          var data = JSON.parse(output);          
          if(data.status == 1)
          {
            
             START_SYNCING = true;
             setInterval(function()
              {
                if(START_SYNCING == true){
                  syncTransactionProcessPayout(data.transactionUniqueID);  
                }
              }, 3000);
          }
          else
          {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.message, type: "error" });
          }
          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'buy'});
          $('.buy_exchange_sub').attr('disabled',false);
          $('.buy_exchange_sub').html('Exchange');
          setTimeout(function(){ location.reload(); }, 500);
        },
        error:function(data,status,xhr) {
          if(xhr == 'Too Many Requests')
          {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Too Many Requests!", type: "error" });

          }
          else
          {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Error encountered!", type: "error" });
          }
          $('#pay_net_paypalsubmit_payout').removeAttr('disabled');
          $('#pay_net_cancel_payout').removeAttr('disabled');
          $('#pay_net_paypalsubmit_payout').html('Pay Now');
        },
      });
      return false;
      
    }

  });
  
  $('#payment_service').validate({
     rules:
     {      
      pay_net_service:
      {
        required: true,
      },
      pay_net_amt: {
        required: true,
        number:true,
        positiveNumber:true,
      },
      pay_net_currency_hidden:
      {
        required: true,
      },      
    },
    submitHandler:function()
    {

      var dataform = $('#payment_service').serialize();
      $.ajax({
        type:'POST',
        url: "{{ URL::to('paypal') }}",
        
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data:dataform,
        beforeSend:function(output)
        {
          
          $('#pay_net_paypalsubmit').attr('disabled',true);
          $('#pay_net_cancel').attr('disabled',true);
          $('#pay_net_paypalsubmit').html('Please Wait <i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(output)
        {  
          var data = JSON.parse(output);          
          if(data.status == 1)
          {

            
           /*CLOSING_WINDOW_TIME =  window.open(data.redirect_url,'','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');*/

           $("#pay_net_paypalsubmit").attr('style', 'display:none');
           $("#pay_net_cancel").attr('style', 'display:none');
           
           $("#pay_net_paypalsubmit_goto").attr('data-href', data.redirect_url);
           $("#pay_net_paypalsubmit_goto").attr('data-transid', data.transactionUniqueID);
           $("#pay_net_paypalsubmit_goto").attr('style', 'display:block');

           /*if(CLOSING_WINDOW_TIME)
           {

              START_SYNCING = true;
               setInterval(function()
                {
                  if(START_SYNCING == true){
                    syncTransactionProcess(data.transactionUniqueID);  
                  }
                }, 5000);
           }*/
          }
          else
          {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.message, type: "error" });
          }
          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'buy'});
          $('.buy_exchange_sub').attr('disabled',false);
          $('.buy_exchange_sub').html('Exchange');
          
        },
        error:function(data,status,xhr) {
          if(xhr == 'Too Many Requests')
          {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Too Many Requests!", type: "error" });

          }
          else
          {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Error encountered!", type: "error" });
          }
          $('#pay_net_paypalsubmit').removeAttr('disabled');
          $('#pay_net_cancel').removeAttr('disabled');
          $('#pay_net_paypalsubmit').html('Pay Now');
        },
      });
      return false;
      
    }

  });
  $('#buy_exchange').validate({
    rules: {      
      amount: {
        required: true,
        number:true,
        positiveNumber:true,
        
      },
      from_currency: {
        required: true,
      },
      paymentmethod: {
        required: true,
      },
      to_currency_amt: {
        required: true,
        number:true,
        positiveNumber:true,
      },
    },
    submitHandler:function() {
       $('#messages').html('Do you want to Buy order');
      $('#accept').attr('data-value','buy');
      $('#cancel').attr('data-value','buy');
      $('#myModal_ex').modal({
        backdrop: 'static'
      });
      return false;
    }
  });

  $('#sell_exchange').validate({
    rules: {      
      amount: {
        required: true,
        number:true,
        positiveNumber:true,

      },
      to_currency: {
        required: true,
      },
      from_currency_amt: {
        required: true,
        number:true,
        positiveNumber:true,
      },
    },
    submitHandler:function()
    {
      
      $('#messages').html('Do you want to Sell order');

      $('#accept').attr('data-value','sell');
      $('#cancel').attr('data-value','sell');
      $('#myModal_ex').modal({
        backdrop: 'static'
      });

    }
  });



  var buyArr = '<?php echo json_encode($buyexchangepairs); ?>';
  wc = wr = JSON.parse(buyArr);

  var exchange_wcwr = '<?php echo $wcwr;?>';  
  var PAY_NET_CURRENCY_GLOBAL = 'USD';

    var currencyUSDprice = '<?php echo json_encode($currency); ?>';
  currencyUSDprice = JSON.parse(currencyUSDprice);

 





  var resArr = '<?php echo json_encode($results); ?>';
  res = JSON.parse(resArr);


  socket.on('window',function(res){
    if(res.msg == "1") {
      wcwr = res.wcwr;
      btype = res.btype;
      if(wcwr == exchange_wcwr) {
        start_loader(btype);
      }
    }
  })

  socket.on('getwindow',function(res){
    if(res.msg == "1") {
      wcwr = res.wcwr;
      btype = res.btype;
      if(wcwr == exchange_wcwr) {
        stop_loader(btype);
      }
    }
  })

  $( document ).ready(function() {

     var to_symbol = 'BTC';
    fromCur = res[to_symbol];
    key = Object.keys(res[to_symbol]);
    from_symbol_set = '<option value="">Please select</option>';
    key.forEach(function(item){
      from_symbol_set +='<option value="'+item+'">'+item+'</option>';
    });
    



  var to_symbol = 'BTC';
    fromCur = res[to_symbol];
    key = Object.keys(res[to_symbol]);
    from_symbol_set = '<option value="">Please select</option>';
    key.forEach(function(item){
      from_symbol_set +='<option value="'+item+'">'+item+'</option>';
    });
    $('#sell_to_currency').html(from_symbol_set);



  var from_symbol = $("#buy_from_currency").val();
  var concat = wc[from_symbol+to_symbol];

  var min_amt =  concat['min_amt'];
  var max_amt =  concat['max_amt'];
  

  $("#buy_min_amt_txt").text(min_amt);
  $("#buy_max_amt_txt").text(max_amt);


  var concattwo = wc[to_symbol+from_symbol];
  var min_amttwo =  concattwo['min_amt'];
  var max_amttwo =  concattwo['max_amt'];
    
  

  $("#sell_min_amt_txt").text(min_amttwo);
  $("#sell_max_amt_txt").text(max_amttwo);

  });

  $(".resetcardboard").click(function(){


    

      var typsoft = $(this).attr("data-type");

      $("#typeofmode").html(typsoft);
      
      $("#buy_amount").val('');
      $("#buy_to_currency_amt").val('');
      $(".buy_total_scrn").html('0.00 ');
      $(".buy_total").html('0.00 ');      
      $(".buy_fees").html('0.00 ');
      $(".buy_sub_total").html('0.00 ');
      $("#sell_amount").val('');
      $("#sell_to_currency_amt").val('');
      

      if(typsoft == "Selling")
      {
        PAY_NET_CURRENCY_GLOBAL = 'USD';
        resetsellform();
      }
        if(typsoft == "Buying")
      {
        PAY_NET_CURRENCY_GLOBAL = 'USD';
        resetbuyform();
      }      
      

  });


  $.validator.addMethod('positiveNumber',function(value) {
    return Number(value) > 0;
  }, 'Enter a positive number.');

  $('#buy_amount').keyup(function(){
    var from_symbol = $("#buy_from_currency").val();
    

    if(from_symbol != '') {
      $('#buy_orange').css('display','block');
      $('#buy_bal').css('display','block');
      var to_symbol = $('#buy_to_currency').val();
      var concat = wc[from_symbol+to_symbol];

      var min_amt =  concat['min_amt'];
      var max_amt =  concat['max_amt'];
      
      if(to_symbol == 'USD')
      {
        digits = 2;
      }
      else
      {
        digits = 8;
      }
      
      
      $('.buy_fee').html(concat['trade_fee']);
      
      $('.buy_cur').html(from_symbol);
      $('#buy_min_max').attr('title','Min: '+ min_amt + ', Max '+ max_amt);

    }
    else
    {
      $('#buy_orange').css('display','none');
      $('#buy_bal').css('display','none');
    }
  });

  $('#buy_to_currency').change(function(){
    var to_symbol = $(this).val();
    fromCur = res[to_symbol];
    key = Object.keys(res[to_symbol]);
    from_symbol_set = '<option value="">Please select</option>';
    key.forEach(function(item){
      from_symbol_set +='<option value="'+item+'">'+item+'</option>';
    });
    $('#buy_from_currency').html(from_symbol_set)
  });


  $('#sell_from_currency').change(function(){
    var to_symbol = $(this).val();
    fromCur = res[to_symbol];
    key = Object.keys(res[to_symbol]);
    from_symbol_set = '<option value="">Please select</option>';
    key.forEach(function(item){
      from_symbol_set +='<option value="'+item+'">'+item+'</option>';
    });
    $('#sell_to_currency').html(from_symbol_set)
  });

  $('#sell_to_currency').change(function(){
    var to_symbol = $(this).val();
    if(to_symbol != '') {
      $('#sell_orange').css('display','block');
      $('#sell_bal').css('display','block');
      var from_symbol = $('#sell_from_currency').val();
      var concat = wr[to_symbol+from_symbol];
      var min_amt = concat['min_amt'];
      var max_amt = concat['max_amt'];
      if(concat['last_price'] > 0) {
        var last_price = 1 / concat['last_price'];
      } else {
        var last_price = 0;
      }
      if(to_symbol == 'USD') {
        digits = 8;
      } else {
        digits = 8;
      }
      $('#sell_amount').val('');
      $('.sell_fees').html('0');
      $('.sell_total').html('0');
      
      $('.sell_fee').html(concat['trade_fee']);
      $('.sell_rate').html(last_price.toFixed(digits));
      $('.sell_fee').html(concat['trade_fee']);
      $('.sell_to_symbol').html(to_symbol);
      $('.sell_cur').html(to_symbol);
      
      $('.sell_cur').html(from_symbol);
      
      $('#sell_min_max').attr('title','Min: '+ min_amt + ', Max '+ max_amt);
    } else {
      $('#sell_orange').css('display','none');
      $('#sell_bal').css('display','none');
    }
  });
  

  
  $('.select_to_currency').change(function(){
    
    var from_symbol = $('#buy_from_currency').val();

    to_symbol = this.value;

    $('#buy_to_currency').val(to_symbol);
    var selected_id = $(this).attr('data-id');
    $("#perpricesymbol").html(to_symbol);
    $("#perpriceusdvalue").html(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][to_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);
    
    last_price = currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][to_symbol];

    $(".crypto_to_currency").html(to_symbol);

    $(".currencyselectedlist").each(function()
    {
      $(this).removeAttr('style');
    });

    $("#selectnew_"+selected_id).attr('style', 'border:2px solid orange;');

    $("#buy_amount").val('');
    $("#buy_to_currency_amt").val('');
    
    $(".buy_total").html('0.00 '+to_symbol);    
    $(".buy_total_scrn").html('0.00 '+to_symbol);
    
    $(".buy_fees").html('0.00 '+to_symbol);
    $(".buy_sub_total").html('0.00 '+to_symbol);
    $(".buy_total").html('0.00 '+to_symbol);

    var concat = wc[from_symbol+to_symbol];
    var min_amt =  concat['min_amt'];
    var max_amt =  concat['max_amt'];
    

    $("#buy_min_amt_txt").text(min_amt);
    $("#buy_max_amt_txt").text(max_amt);

    $("#buy_exchange_button").text("Buy "+to_symbol+" Instantly");

  });

  $('.select_to_currency_sell').change(function()
  {
    var to_symbol = $('#sell_to_currency').val();
    from_symbol = this.value;
    $('#sell_from_currency').val(from_symbol);
    var selected_id = $(this).attr('data-id');
    $("#perpricesymbol").html(from_symbol);
    $("#perpriceusdvalue").html(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][from_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);
    last_price = currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][from_symbol];

    $(".crypto_to_currency").html(from_symbol);

    $(".currencyselectedlistsell").each(function(){
      $(this).removeAttr('style');
    });
    $("#selectsell_"+selected_id).attr('style', 'border:2px solid orange;');
    
    $("#sell_amount").val('');
    $("#sell_to_currency_amt").val('');

    $(".buy_total").html('0.00 '+to_symbol);    
    $(".buy_fees").html('0.00 '+to_symbol);
    $(".buy_sub_total").html('0.00 '+to_symbol);
    $(".buy_total_scrn").html('0.00 '+to_symbol);

    

    var from_symboltw = $("#sell_from_currency").val();
    var concatwo = wc[from_symboltw+to_symbol];

    var min_amttwo =  concatwo['min_amt'];
    var max_amttwo =  concatwo['max_amt'];
    
    

    $("#sell_min_amt_txt").text(min_amttwo);
    $("#sell_max_amt_txt").text(max_amttwo);

    $("#sell_exchange_button").text("Sell "+from_symbol+" Instantly");

  });

  function buy_caltot_to_curr()
  {
    var from_symbol = $('#buy_from_currency').val();
    var to_symbol = $('#buy_to_currency').val();    
    var concat = wc[from_symbol+to_symbol];
    var amount = $('#buy_to_currency_amt').val();
    var fee = concat['trade_fee'];
    
    


    min_amt =  concat['min_amt'];
    max_amt =  concat['max_amt'];

    $("#buy_min_amt_txt").text(min_amt);
    $("#buy_max_amt_txt").text(max_amt);



    $("#perpriceusdvalue").html(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][to_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);

    last_price = currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][to_symbol];
    
    if(last_price > 0 && amount > 0)
    {

      total = parseFloat(amount).toFixed(8);
      fee = parseFloat(fee).toFixed(8);
      last_price = parseFloat(last_price).toFixed(8);

      

      var fees = total * fee / 100;
      final = total - fees;
      
      
      if(from_symbol == 'USD' || from_symbol == 'GBP' || from_symbol == 'EUR')
      {
        fees =  parseFloat(fees).toFixed(8);        

      }
      else
      {
        fees =  parseFloat(fees).toFixed(8);
        
      }

      amount =  parseFloat(amount).toFixed(8);
      var from_amount = amount * last_price;
      from_amount = parseFloat(from_amount).toFixed(8);

      $('.buy_fees').html(fees +' '+ to_symbol);
      $('.buy_sub_total').html(amount +' '+ to_symbol);
      $('.buy_total').html(final +' '+ to_symbol);
      $('.buy_total_scrn').html(amount +' '+ to_symbol);
      
      
      $('#buy_amount').val(from_amount);

      

    } else {
      $('.buy_fees').html('0');
      $('.buy_total').html('0');
      $('.buy_total_scrn').html('0.00 '+to_symbol);      
      $('#buy_amount').val('0');
    }
  } 
  function buy_caltot()
  {

      var from_symbol = $('#buy_from_currency').val();

      var to_symbol = 'BTC';

      var v= $('input[type=radio].select_to_currency:checked');
      $(v).each(function(i){
      to_symbol = $(this).val();
      });

      $(".crypto_to_currency").html(to_symbol);
      $("#buy_to_currency").val(to_symbol);


      
      var concat = wc[from_symbol+to_symbol];
      var amount = $('#buy_amount').val();
      var fee = concat['trade_fee'];
      var last_price = concat['last_price'];
      
      if(last_price > 0 && amount > 0)
      {
        amount = parseFloat(amount).toFixed(8);
        last_price = parseFloat(last_price).toFixed(8);
        fee = parseFloat(fee).toFixed(8);
        

        var tempamunt = parseFloat(amount).toFixed(8);
        var total = tempamunt * last_price;
        total = parseFloat(total).toFixed(8);

        
        var fees = total * fee / 100;
        

        final = total - fees;

        
        

        if(to_symbol == 'USD')
        {
          fees =  parseFloat(fees).toFixed(8);
          final =  parseFloat(final).toFixed(8);
        }
        else
        {
          fees =  parseFloat(fees).toFixed(8);
          final =  parseFloat(final).toFixed(8);
        }
        $('.buy_fees').html(fees +' '+ to_symbol);
        $('.buy_total').html(final+' '+to_symbol);
        $('.buy_total_input').val(total);

        $('.buy_total_scrn').html(final+' '+to_symbol);

        $('.buy_sub_total').html(total +' '+ to_symbol);
      } else {
        $('.buy_fees').html('0');
        $('.buy_total').html('0');
        $('.buy_total_input').val('0');
        $('.buy_total_scrn').html('0.00 '+to_symbol);
      }
  } 

  function sell_caltot() {
    var from_symbol = $('#sell_from_currency').val();
    var to_symbol = $('#sell_to_currency').val();
    
    var concat = wr[to_symbol+from_symbol];
    var amount = $('#sell_amount').val();
    var fee = concat['trade_fee'];
    
    last_price = currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][from_symbol];    

    min_amt =  concat['min_amt'];
    max_amt =  concat['max_amt'];

    $("#sell_min_amt_txt").text(min_amt);
    $("#sell_max_amt_txt").text(max_amt);

    if(last_price > 0 && amount > 0)
    {
      
      var total = (amount * last_price);
      var fees = total * fee / 100;
      
      final = total - fees;
      if(to_symbol == 'USD')
      {
        fees =  parseFloat(fees).toFixed(8);
        final =  parseFloat(final).toFixed(8);
      } else {
        fees =  parseFloat(fees).toFixed(8);
        final =  parseFloat(final).toFixed(8);
      }
      
      $('.buy_fees').html(fees +' '+ to_symbol);

      $('.buy_total').html(final +' '+ to_symbol);
      $('.buy_total_scrn').html(final +' '+ to_symbol);

      $('.buy_sub_total').html(total +' '+ to_symbol);

      $('#sell_to_currency_amt').val(total);

    } else {
      $('.buy_fees').html('0');
      $('.buy_total').html('0');
      $('.buy_total_scrn').html('0.00 '+to_symbol);
      $('#sell_to_currency_amt').val(0);
    }
  } 



function sell_caltot_to_curr()
{

    var from_symbol = $('#sell_from_currency').val();
    var to_symbol = $('#sell_to_currency').val();    
    var concat = wc[to_symbol+from_symbol];
    var amount = $('#sell_to_currency_amt').val();
    var fee = concat['trade_fee'];
    var usd_last_price = last_price;
    var cryp_last_price = concat['last_price'];

    $("#perpriceusdvalue").html(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL][from_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);


    if(cryp_last_price > 0 && amount > 0)
    {

      amount = parseFloat(amount).toFixed(8);
      fee = parseFloat(fee).toFixed(8);
      cryp_last_price = parseFloat(cryp_last_price).toFixed(8);
      

      var sell_amount = amount * cryp_last_price;
      sell_amount = parseFloat(sell_amount).toFixed(8);


      var total = sell_amount * usd_last_price;
      total = parseFloat(total).toFixed(8);
      

      var fees = total * fee / 100;
      final = total - fees;
      
      
      fees =  parseFloat(fees).toFixed(8);
      final =  parseFloat(final).toFixed(8);      

      $('.buy_fees').html(fees +' '+ to_symbol);
      $('.buy_total').html(final +' '+ to_symbol);
      $('.buy_total_scrn').html(total +' '+ to_symbol);
      $('.buy_sub_total').html(total +' '+ to_symbol);
      $('#sell_amount').val(sell_amount);

    } else {
      $('.buy_fees').html('0');
      $('.buy_total').html('0');
      $('.buy_total_scrn').html('0.00 '+to_symbol);      
      $('#sell_amount').val(0);
    }
} 



  function truncate_decimal(a,dec) {
    if(isDecimal(a)) {
      var b = a.toString().substring(0, a.toString().indexOf(".") + parseInt(dec+1));
      return parseFloat(b).toFixed(dec);
    } else {
      return a.toFixed(dec);
    }
  }

  function isDecimal(num) {
    return (num ^ 0) !== num;
  }


  jQuery.validator.addMethod('greaterThan', function(value, element, param) {
    return (parseFloat(value) > parseFloat(jQuery(param).html()) );
  }, 'Must be greater than minimum value');

  jQuery.validator.addMethod('lesserThan', function(value, element, param) {
    return (parseFloat(value) < parseFloat(jQuery(param).html()) );
  }, 'Must be less than maximum value');



  function buy_exchange_load(){
    if ($('#buy_exchange').valid() == true) {
      $('.buy_exchange_sub').attr('disabled',true);
      $('.buy_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    }else{
      $('.buy_exchange_sub').attr('disabled',false);
      $('.buy_exchange_sub').html('Exchange');
    }
  }

  function exchange_load(){
    if ($('#sell_exchange').valid() == true) {
      $('.sell_exchange_sub').attr('disabled',true);
      $('.sell_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    }else{
      $('.sell_exchange_sub').attr('disabled',false);
      $('.sell_exchange_sub').html('Exchange');
    }
  }

  $(".currencyselectionpypl").click(function()
  {
    var selectedcurrency = $(this).attr("data-pay");
    var selectedimage = $(this).attr("data-image");

    var texthtml = $(this).text();
    to_symbol = $('#buy_to_currency').val();    
    from_symbol = $('#buy_from_currency').val();
    
    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;

    $("#buy_from_currency").val(selectedcurrency);

    $("#payment_method_wallets").text(selectedcurrency);
    $("#payment_method_wallet_2").text(selectedcurrency);    

    $("#updated_selected_currency").html(texthtml);
    $("#pay_net_currency").html(selectedcurrency);
    $("#pay_net_currency_hidden").val(selectedcurrency);
    $("#selectedcurrency_label").text(selectedcurrency);


    $("#perpriceusdvalue").html(currencyUSDprice[selectedcurrency][to_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);

    $(".selected_box_currency").text(PAY_NET_CURRENCY_GLOBAL);

    $("#selected_box_currency_amt_BTC").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BTC']);
    $("#selected_box_currency_amt_ETH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['ETH']);
    $("#selected_box_currency_amt_BCH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BCH']);
    $("#selected_box_currency_amt_BoomCoin").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BoomCoin']);
    $("#triggeredImage").attr('src',selectedimage);


    buy_caltot_to_curr();
  });

function resetbuyform(){

    var selectedcurrency = PAY_NET_CURRENCY_GLOBAL;
    var texthtml = 'USD Wallet';
    to_symbol = $('#buy_to_currency').val();    
    from_symbol = $('#buy_from_currency').val();
    
    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;

    $("#buy_from_currency").val(selectedcurrency);

    $("#payment_method_wallets").text(selectedcurrency);
    $("#payment_method_wallet_2").text(selectedcurrency);    

    $("#updated_selected_currency").html(texthtml);
    $("#pay_net_currency").html(selectedcurrency);
    $("#pay_net_currency_hidden").val(selectedcurrency);
    $("#selectedcurrency_label").text(selectedcurrency);


    $("#perpriceusdvalue").html(currencyUSDprice[selectedcurrency][to_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);

    $(".selected_box_currency").text(PAY_NET_CURRENCY_GLOBAL);

    $("#selected_box_currency_amt_BTC").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BTC']);
    $("#selected_box_currency_amt_ETH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['ETH']);
    $("#selected_box_currency_amt_BCH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BCH']);
    $("#selected_box_currency_amt_BoomCoin").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BoomCoin']);

    $("#perpricesymbol").html(to_symbol);
    $(".crypto_to_currency").html(to_symbol);

    buy_caltot_to_curr();


}
  function resetsellform(){

    to_symbol = $('#sell_to_currency').val();    
    

    var myRadio = $('.select_to_currency_sell');
    var from_symbol = myRadio.filter(":checked").val();
    $('#sell_from_currency').val(from_symbol);
    $("#perpricesymbol").html(from_symbol);
    $(".crypto_to_currency").html(from_symbol);
    


    var selectedcurrency = PAY_NET_CURRENCY_GLOBAL;
    var texthtml = 'USD Wallet';
    $("#updated_selected_currency_payout").html(texthtml);
    $("#pay_net_currency").html(selectedcurrency);
    $("#pay_net_currency_hidden_payout").val(selectedcurrency);
    $("#selectedcurrency_label_payout").text(selectedcurrency);

    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;

    $("#sell_to_currency").val(PAY_NET_CURRENCY_GLOBAL);

    $("#perpriceusdvalue").html(currencyUSDprice[selectedcurrency][from_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);


    $(".sell_selected_box_currency").text(PAY_NET_CURRENCY_GLOBAL);
    $(".selected_box_currency_crypto").text(from_symbol);

    $("#payment_method_wallets").text(selectedcurrency);

    $("#sell_selected_box_currency_amt_BTC").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BTC']);
    $("#sell_selected_box_currency_amt_ETH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['ETH']);
    $("#sell_selected_box_currency_amt_BCH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BCH']);
    $("#sell_selected_box_currency_amt_BoomCoin").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BoomCoin']);


    $(".fiat_to_currency").html(PAY_NET_CURRENCY_GLOBAL);

    



    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;
    sell_caltot();
    sell_caltot_to_curr();

  }
  $(".currencyselectionpyplpout").click(function()
  {

    to_symbol = $('#sell_to_currency').val();    
    from_symbol = $('#sell_from_currency').val();

    var selectedcurrency = $(this).attr("data-pay");
    var selectedimage = $(this).attr("data-image");
    var texthtml = $(this).text();
    $("#updated_selected_currency_payout").html(texthtml);
    $("#pay_net_currency").html(selectedcurrency);
    $("#pay_net_currency_hidden_payout").val(selectedcurrency);
    $("#selectedcurrency_label_payout").text(selectedcurrency);

    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;

    $("#sell_to_currency").val(PAY_NET_CURRENCY_GLOBAL);

    $("#perpriceusdvalue").html(currencyUSDprice[selectedcurrency][from_symbol]);
    $("#perpriceusdvaluesymbol").html(PAY_NET_CURRENCY_GLOBAL);


    $(".sell_selected_box_currency").text(PAY_NET_CURRENCY_GLOBAL);
    $(".selected_box_currency_crypto").text(from_symbol);

    $("#payment_method_wallets").text(selectedcurrency);

    $("#sell_selected_box_currency_amt_BTC").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BTC']);
    $("#sell_selected_box_currency_amt_ETH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['ETH']);
    $("#sell_selected_box_currency_amt_BCH").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BCH']);
    $("#sell_selected_box_currency_amt_BoomCoin").text(currencyUSDprice[PAY_NET_CURRENCY_GLOBAL]['BoomCoin']);


    $(".fiat_to_currency").html(PAY_NET_CURRENCY_GLOBAL);

    $("#triggeredImageSell").attr('src',selectedimage);

    PAY_NET_CURRENCY_GLOBAL = selectedcurrency;
    sell_caltot();

  });


 $("#triggerdepositex").click(function()
 {
  

      $("#instantMethod").val('true');
      

    $('#myModal_buyex').modal({
      backdrop: 'static'
    });

 });

 $("#triggerdepositex_payout").click(function()
 {
    $('#myModal_sellex').modal({
      backdrop: 'static'
    });
 });


  $('#accept').click(function()
  {
    var action = $('#accept').attr('data-value');

    $(this).attr('disabled', 'disabled').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    if(action == 'buy') {
      var dataform = $('#buy_exchange').serialize();
      $.ajax({
        type:'POST',
        url: "{{ URL::to('makeexchange') }}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: dataform,
        async: false,
        before:function(output) {
          $('.buy_exchange_sub').attr('disabled',true);
          $('.buy_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(output) { 
        
          var data = JSON.parse(output);
          if(data.status=='1') {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "success" }); 
            setTimeout(function(){ location.reload(); }, 500);          
          } else {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "error" });
          }
          $('#accept').removeAttr('disabled');
          $('#accept').html('Accept');
          
          $('.buy_exchange_sub').attr('disabled',false);
          $('.buy_exchange_sub').html('Exchange');
          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'buy'});
          

        },
        error:function(data,status,xhr) {
          
          $(this).removeAttr('disabled');
          $(this).html('Accept');

          if(xhr == 'Too Many Requests') {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Too Many Requests!", type: "error" });
          } else {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Error encountered!", type: "error" });
          }
        },
      });
    } else {
      var dataform = $('#sell_exchange').serialize();
      $.ajax({
        type:'POST',
        url: "{{ URL::to('makeexchange') }}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: dataform,
        before:function(output) {

         
          $('.sell_exchange_sub').attr('disabled',true);
          $('.sell_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(output)
        {   

          var data = JSON.parse(output);
          if(data.status=='1')
          {

            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "success" }); 
            setTimeout(function(){ location.reload(); }, 1000);

          } else {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "error" });
          }

          $("#accept").removeAttr('disabled');
          $("#accept").html('Accept');

          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'sell'});
          $('.sell_exchange_sub').attr('disabled',false);
          $('.sell_exchange_sub').html('Exchange');
                      

        },
        error:function(data,status,xhr) {
          
          $(this).removeAttr('disabled');
          $(this).html('Accept');

          if(xhr == 'Too Many Requests') {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Too Many Requests!", type: "error" });
          } else {
            notif({ msg: '<img src="{{asset("/").("public/frontend/img/error_notify.png")}}"/>'+" Error encountered!", type: "error" });
          }
        },
      });
    }
  });


  function start_loader(type) {
    if(type == 'buy') {
      $('#buy_exchange')[0].reset();
      $('.buy_exchange_sub').attr('disabled',true);
      $('.buy_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    } else if(type == 'sell') {
      $('#sell_exchange')[0].reset();
      $('.sell_exchange_sub').attr('disabled',true);
      $('.sell_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    } 
  }

  function stop_loader(type) {
    if(type == 'buy') {
      $('.buy_exchange_sub').attr('disabled',false);
      $('.buy_exchange_sub').html('Exchange');
    } else if(type == 'lock') {
      $('.sell_exchange_sub').attr('disabled',false);
      $('.sell_exchange_sub').html('Exchange');
    } 
    setTimeout(function(){ location.reload(); }, 500);
  }

  $(document).ready(function(){
    var table = $('#exchange_table').DataTable({
      dom: 'lBfrtip',
      buttons: [
      'csvHtml5',
      'pdfHtml5'
      ],
      "destroy": true,
      "sServerMethod": "GET",
      "processing": true,
      "serverSide": true,
      oLanguage: { "sSearch": "",sProcessing: "<div id='loader'><i style='font-size:30px' class=''></i></div>",
      sEmptyTable: 'No Records Found',
      sSearch: "Search:",
    },
    "ajax": {
      "url": "{{ URL('exchangeHistory') }}",
      "data": function ( d ) {
        d.from = $("#from_fiat_date").val();
        d.to = $("#to_fiat_date").val();
      }
    },
  });
 
  });


  function syncTransactionProcess(transactionUniqueID){

      $.ajax({
        type:'POST',
        url: "{{ URL::to('getTransactionSync') }}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { transactionUniqueID : transactionUniqueID},
        beforeSend:function(output)
        {
                                        
        },
        success:function(output)
        {  
          var data = JSON.parse(output);          
          if(data.status == 1)
          {
            START_SYNCING = false;
            CLOSING_WINDOW_TIME.close();
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" Transaction Completed ", type: "success" }); 
            setTimeout(function(){
              window.location.reload(true);
            },1000);
          }
          if(data.status == 2)
          {
            START_SYNCING = false;
            CLOSING_WINDOW_TIME.close();
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" Transaction Cancelled ", type: "error" }); 
            setTimeout(function(){
              window.location.reload(true);
            },1000);
          }

        }
      });


  }
   function syncTransactionProcessPayout(transactionUniqueID){

      $.ajax({
        type:'POST',
        url: "{{ URL::to('getPayoutTransactionSync') }}",
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data:{transactionUniqueID : transactionUniqueID},
        beforeSend:function(output)
        {
                                        
        },
        success:function(output)
        {  
          var data = JSON.parse(output);          
          if(data.status == 1)
          {
            START_SYNCING = false;
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" Transaction Completed ", type: "success" }); 
            setTimeout(function(){
              window.location.reload(true);
            },1000)
            

          }
        }
      });


  }

  function proceedpaypal(){


    var urll = $("#pay_net_paypalsubmit_goto").attr('data-href');
    var transid = $("#pay_net_paypalsubmit_goto").attr('data-transid');
    
    $('#pay_net_paypalsubmit_goto').html('Proceed <i class="fa fa-spinner fa-spin"></i>');
    
    CLOSING_WINDOW_TIME =  window.open(urll,'','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');

    $("#pay_net_paypalsubmit_goto").attr("style", "pointer-events:none");

    if(CLOSING_WINDOW_TIME)
     {

      

        START_SYNCING = true;
         setInterval(function()
          {
            if(START_SYNCING == true){
              syncTransactionProcess(transid);  
            }
          }, 5000);
     }
  }
</script>
<style type="text/css">
.ms-l button#dropdownMenuButtonSell
{
    width: 100% !important;
    display: flex !important;
    flex-flow: nowrap;
    border: 1px solid #ddd;
    margin: 0 0 0px 0;
}
@media (max-width: 767px){
  #myModal_buyex .modal-dialog, #myModal_sellex .modal-dialog{ width: 90% !important;}
  #payment_service_payout #messagess{ width: 100% !important;}
  .deposit-paypal-box{ width: 100%!important; }
}
</style>


