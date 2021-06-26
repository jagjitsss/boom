
<section class="dashboard_cont">
  <div class="container-fluid px-0">
    <div class="row mx-0">

      <div class=" col-sm-12 col-md-12 col-lg-content px-0">
        <div class="row mt-2 mx-0">
          <div class="col-12 px-1">
            <div class="card shadow round_card my-2 w-100">
              <div class="card-header bg-white">
                <p class="mb-0  p-3 text-center "><span class="text_blue font_weight_700 font_20">Exchange</span></p>
              </div>
              <div class="card-body py-4">
                <div class="row justify-content-center">
                  <div class="exc-tab-cnt col-lg-6">
                    <ul class="nav nav-tabs tab_style_trade buy-sell-tab" style="border:2px solid rgba(255,255,255,0.2);">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#buy">Buy</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#sell">Sell</a>
                      </li>
                    </ul>
                    <div class="tab-content buy_sell_tab_sec">
                      <div class="tab-pane container active" id="buy">
                       

                        {!! Form::open(array('id'=>'buy_exchange','url'=>'makeexchange','class'=>'my-5','form_style_1','method'=>'POST')) !!}
                        <div class="col-12 col-md-12 mb-2 pr-0 pl-0">
                          <div class="row mx-0">
                            <div class="col-12 col-md-6 pl-0 pr-0 pr-md-3">
                              <label for="currency" class="">Select From Currency</label>
                              <select class="form-control form-control-big" name="from_currency" id="buy_from_currency">
                                <option value="">Please select</option>

                              
                              </select>
                            </div>
                            <div class="col-12 col-md-6 pr-0 pl-0 pl-md-3">
                              <label for="min_max" class="">Select To Currency</label>
                              <select class="form-control form-control-big" name="to_currency" id="buy_to_currency">
                                 <option value="BTC">BTC</option>
                                <option value="ETH">ETH</option>
                                <option value="BNB">BNB</option>
                                <option value="OWN">OWN</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row mx-0">
                          <div class="col-12 col-md-6 pl-0 pr-0 pr-md-2 mb-2">
                            <label for="currency" class="">Amount<span class="text-red pl-1 font_18">*</span></label>
                            <input type="text" class="form-control purple_placeholder" placeholder="Enter Amount" value="" aria-label="memo" name="amount" id="buy_amount" onkeypress="return isNumberKey(event)" onkeyup="buy_caltot()"> 
                            <input type="hidden" name="extype" value="buy">
                          </div>
                        </div>

                        <div class="exc-text-cnt d-lg-flex justify-content-between mb-3">
                          <span>Exchange Rate</span>
                          <div><span class="buy_rate">0.00000000 </span> <span class="buy_to_symbol">BTC</span></div>
                        </div>
                        <div id="buy_orange" style="display: none;">
                          <p class="blue-txt">Minimum Exchange Amount : <span id="buy_min_amt" class="buy_min_amt">0</span> <span class="buy_cur">BTC</span></p> 
                          <p class="blue-txt">Maximum Exchange Amount  : <span id="buy_max_amt" class="buy_max_amt">0</span> <span class="buy_cur">BTC</span></p> 
                          
                          <p class="blue-txt">Fees : <span class="buy_fees">0</span> <span class="buy_to_symbol">BTC</span></p>
                          <p class="blue-txt">Total : <span class="buy_total">0</span> <span class="buy_to_symbol">BTC</span></p>
                        </div>
                        <div class="row mx-0 d-flex align-items-center">              
                          <div class="col-12 col-md-6 col-lg-4 mb-0 px-0">
                            
                            @if($type == 1)
                            <button type="submit" class="dsb-blue-btn profile_update">Exchange</button>
                            @else 
                            <a href="{{url('login')}}"><button type="button" class="dsb-blue-btn profile_update" title="Please login to continue">Exchange</button> </a>
                            @endif
                            
                          </div>
                        </div>
                        <p class="small-txt grey-txt mt-3">Note : Please check the rate and fees before exchange</p>
                        {!! Form::close() !!}
                      </div>
                      <div class="tab-pane container fade" id="sell">
                       
                        {!! Form::open(array('id'=>'sell_exchange','url'=>'makeexchange','class'=>'my-5','form_style_1','method'=>'POST')) !!}
                        <div class="col-12 col-md-12 mb-2 pr-0 pl-0">
                          <div class="row mx-0">
                            <div class="col-12 col-md-6 pl-0 pr-0 pr-md-3">
                              <label for="currency" class="">Select From Currency</label>
                              <select class="form-control form-control-big" name="from_currency" id="sell_from_currency">
                                <option value="BTC">BTC</option>
                                <option value="ETH">ETH</option>
                                <option value="BNB">BNB</option>
                                <option value="OWN">OWN</option>

                                
                              </select>
                            </div>
                            <div class="col-12 col-md-6 pr-0 pl-0 pl-md-3">
                              <label for="min_max" class="">Select To Currency</label>
                              <select class="form-control form-control-big" name="to_currency" id="sell_to_currency">
                                <option value="">Please select</option>
                                
                              </select>

                            </div>
                          </div>
                        </div>
                        <div class="row mx-0">
                          <div class="col-12 col-md-6 pl-0 pr-0 pr-md-2 mb-2">
                            <label for="currency" class="">Amount<span class="text-red pl-1 font_18">*</span></label>
                            <input type="text" class="form-control purple_placeholder" placeholder="Enter Amount" value="" aria-label="memo" name="amount" id="sell_amount" onkeypress="return isNumberKey(event)" onkeyup="sell_caltot()"> 
                            <input type="hidden" name="extype" value="sell">
                          </div>
                        </div>

                        <div class="exc-text-cnt d-lg-flex justify-content-between mb-3">
                          <span>Exchange Rate</span>
                          <div><span class="sell_rate">0.00000000 </span> <span class="sell_to_symbol">BTC</span></div>
                        </div>
                        <div id="sell_orange" style="display: none;">
                          <p class="blue-txt">Minimum Exchange Amount : <span id="sell_min_amt" class="sell_min_amt">0</span> <span class="sell_cur">BTC</span></p>
                          <p class="blue-txt">Maximum Exchange Amount  : <span id="sell_max_am" class="sell_max_amt">0</span> <span class="sell_cur">BTC</span></p>
                          
                          <p class="blue-txt">Fees : <span class="sell_fees">0</span> <span class="sell_to_symbol">BTC</span></p>
                          <p class="blue-txt">Total : <span class="sell_total">0</span> <span class="sell_to_symbol">BTC</span></p>
                        </div>
                        <div class="row mx-0 d-flex align-items-center">
                          <div class="col-12 col-md-6 col-lg-4 mb-0 px-0">
                            

                            @if($type == 1)
                            <button type="submit"  class="dsb-blue-btn profile_update">Exchange</button>
                            @else 
                            <a href="{{url('login')}}"><button type="button"  class="dsb-blue-btn profile_update" title="Please login to continue">Exchange</button></a>
                            @endif

                          </div>
                        </div>
                        <p class="small-txt grey-txt mt-3">Note : Please check the rate and fees before exchange</p>
                        {!! Form::close() !!}
                      </div>
                    </div>

                  </div>
                  
                </div>


                <?php if($type ==1)
                {?>

                
                
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table border-0 hor-scroll-table-no-scroll bank-table" id="exchange_table">
                      <thead>
                        <tr>
                          <th>Sno</th>
                          <th>Datetime</th>
                          <th>Type</th>
                          <th>From Currency</th>
                          <th>To Currency</th>
                          <th>Amount</th>
                          <th>Fees</th>
                          <th>Total</th>
                          <th>Status</th>
                          
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <?php } ?>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



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
<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
<script src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.js"></script>
<script src="{{asset('/').('public/assets/js/popper.min.js')}}"></script>
<script src="{{asset('/').('public/assets/js/bootstrap.min.js')}}"></script>

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






  var buyArr = '<?php echo json_encode($buyexchangepairs); ?>';
  wc = wr = JSON.parse(buyArr);
  
  var exchange_wcwr = '<?php echo $wcwr;?>';


  



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
    $('#buy_from_currency').html(from_symbol_set)



  var to_symbol = 'BTC';
    fromCur = res[to_symbol];
    key = Object.keys(res[to_symbol]);
    from_symbol_set = '<option value="">Please select</option>';
    key.forEach(function(item){
      from_symbol_set +='<option value="'+item+'">'+item+'</option>';
    });
    $('#sell_to_currency').html(from_symbol_set)


  });

  $.validator.addMethod('positiveNumber',function(value) {
    return Number(value) > 0;
  }, 'Enter a positive number.');

  $('#buy_from_currency').change(function(){
    var from_symbol = $(this).val();
    if(from_symbol != '') {
      $('#buy_orange').css('display','block');
      $('#buy_bal').css('display','block');
      var to_symbol = $('#buy_to_currency').val();
      var concat = wc[from_symbol+to_symbol];

      var min_amt =  concat['min_amt'];
      var max_amt =  concat['max_amt'];
      
      if(to_symbol == 'INR') {
        digits = 2;
      } else {
        digits = 8;
      }
      $('#buy_amount').val('');
      $('.buy_fees').html('0');
      $('.buy_total').html('0');
      $('.buy_min_amt').html(parseFloat(concat['min_amt']).toFixed(digits));
      $('.buy_max_amt').html(parseFloat(concat['max_amt']).toFixed(digits));
      $('.buy_rate').html(parseFloat(concat['last_price']).toFixed(digits));
      $('.buy_fee').html(concat['trade_fee']);
      $('.buy_to_symbol').html(concat['to_symbol']);
      $('.buy_cur').html(from_symbol);
      $('.buy_cur_bal').html(wallet[from_symbol]);
      $('#buy_min_max').attr('title','Min: '+ min_amt + ', Max '+ max_amt);
    } else {
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
      if(to_symbol == 'INR') {
        digits = 2;
      } else {
        digits = 8;
      }
      $('#sell_amount').val('');
      $('.sell_fees').html('0');
      $('.sell_total').html('0');
      $('.sell_min_amt').html(parseFloat(concat['min_amt']).toFixed(digits));
      $('.sell_max_amt').html(parseFloat(concat['max_amt']).toFixed(digits));
      $('.sell_fee').html(concat['trade_fee']);
      $('.sell_rate').html(last_price.toFixed(digits));
      $('.sell_fee').html(concat['trade_fee']);
      $('.sell_to_symbol').html(to_symbol);
      $('.sell_cur').html(to_symbol);
      $('.sell_cur_bal').html(wallet[to_symbol]);
      $('.sell_cur').html(from_symbol);
      $('.sell_cur_bal').html(wallet[from_symbol]);
      $('#sell_min_max').attr('title','Min: '+ min_amt + ', Max '+ max_amt);
    } else {
      $('#sell_orange').css('display','none');
      $('#sell_bal').css('display','none');
    }
  });

  function buy_caltot() {
    var from_symbol = $('#buy_from_currency').val();
    var to_symbol = $('#buy_to_currency').val();    
    var concat = wc[from_symbol+to_symbol];
    var amount = $('#buy_amount').val();
    var fee = concat['trade_fee'];
    var last_price = concat['last_price'];
    if(last_price > 0) {
      var total = amount * last_price;
      var fees = total * fee / 100;
      final = total - fees;
      
      if(to_symbol == 'INR') {
        fees =  parseFloat(fees).toFixed(2);
        final =  parseFloat(final).toFixed(2);
      } else {
        fees =  parseFloat(fees).toFixed(8);
        final =  parseFloat(final).toFixed(8);
      }
      $('.buy_fees').html(fees);
      $('.buy_total').html(final);
    } else {
      $('.buy_fees').html('0');
      $('.buy_total').html('0');
    }
  } 

  function sell_caltot() {
    var from_symbol = $('#sell_from_currency').val();
    var to_symbol = $('#sell_to_currency').val();
    var concat = wr[to_symbol+from_symbol];
    var amount = $('#sell_amount').val();
    var fee = concat['trade_fee'];
    var lastprice = concat['last_price'];
    if(lastprice > 0) {
      var last_price = 1 / lastprice;
      var total = amount * last_price;
      var fees = total * fee / 100;
      final = total - fees;

      if(to_symbol == 'INR') {

        fees =  parseFloat(fees).toFixed(2);
        final =  parseFloat(final).toFixed(2);
      } else {

        fees =  parseFloat(fees).toFixed(8);
        final =  parseFloat(final).toFixed(8);
      }
      $('.sell_fees').html(fees);
      $('.sell_total').html(final);
    } else {
      $('.sell_fees').html('0');
      $('.sell_total').html('0');
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
    },
    submitHandler:function() {
       $('#messages').html('Do you want to Buy order');
      $('#accept').attr('data-value','buy');
      $('#cancel').attr('data-value','buy');
      $('#myModal_ex').modal({
        backdrop: 'static'
      });
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
    },
    submitHandler:function() {

      $('#messages').html('Do you want to Sell order');


      $('#accept').attr('data-value','sell');
      $('#cancel').attr('data-value','sell');
      $('#myModal_ex').modal({
        backdrop: 'static'
      });
    }
  });

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


  $('#accept').click(function() {
    var action = $('#accept').attr('data-value');
    if(action == 'buy') {
      var dataform = $('#buy_exchange').serialize();
      $.ajax({
        type:'POST',
        url: "{{ URL::to('makeexchange') }}",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: dataform,
        async: false,
        before:function(output) {
          alert(output);
          $('.buy_exchange_sub').attr('disabled',true);
          $('.buy_exchange_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
        },
        success:function(output) { 
        
          var data = JSON.parse(output);
          if(data.status=='1') {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "success" }); 
          } else {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "error" });
          }
          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'buy'});
          $('.buy_exchange_sub').attr('disabled',false);
          $('.buy_exchange_sub').html('Exchange');
          setTimeout(function(){ location.reload(); }, 500);
        },
        error:function(data,status,xhr) {
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
        success:function(output) {   
          var data = JSON.parse(output);
          if(data.status=='1') {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "success" }); 
          } else {
            notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+data.result, type: "error" });
          }
          socket.emit('receivewindow',{'msg':'1','recId':exchange_wcwr,'recType':'sell'});
          $('.sell_exchange_sub').attr('disabled',false);
          $('.sell_exchange_sub').html('Exchange');
          setTimeout(function(){ location.reload(); }, 500);
        },
        error:function(data,status,xhr) {
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


  
</script>
<script>


</script>


