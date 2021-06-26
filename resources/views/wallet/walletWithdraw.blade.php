@extends('wallet.layouts/admin')
@section('content')

<ul class="breadcrumb cm_breadcrumb">
	<li><a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}">Home</a></li>
	<li><a href="#">Wallet Withdraw</a></li>
</ul>
<div class="inn_content">
	<?php if (Session::has('success')) {?>
		<div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Success!</strong><?php echo Session::get('success'); ?> </div>
	<?php }?>

	<?php if (Session::has('error')) {?>
		<div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
	<?php }?>

	{!! Form::open(array('class'=>'cm_frm1 verti_frm1', 'url'=>'HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/withdrawAmount', 'id'=>'withdraw_form', 'onsubmit'=>'withdraw_loader_show()')) !!}
		<div class="cm_head1">
			<h3>Wallet Withdraw</h3>
		</div>

		<div class="form-group row clearfix">
			<div class="col-sm-9 col-xs-12">
			<label class="form-control-label">Select Currency :</label>
			<select class="form-control" name="currency" id="currency">
				<?php foreach ($currencies as $currency) {$sym = $currency->symbol;?>
          		<option value="{{$sym}}">{{$sym}}</option>
          		<?php }?>
			</select>
			</div>
		</div>

		<div class="form-group row clearfix">
			<div class="col-sm-9 col-xs-12">
				<label class="form-control-label">Ener Coin Address :</label>
				<input type="text" class="form-control" name="address" id="address">
			</div>
		</div>

		<div class="form-group row clearfix">
			<div class="col-sm-9 col-xs-12">
				<label class="form-control-label">Ener the amount :</label>
				<input type="text" class="form-control" name="amount" id="amount" autocomplete="off">
			</div>
		</div>

		<div class="form-group row clearfix">
			<div class="col-sm-9 col-xs-12">
				<label class="form-control-label">Ener Your Password :</label>
				<input type="password" class="form-control" name="password" id="password" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" style="background-color: #fff;">
			</div>
		</div>

		<div class="form-group row clearfix">
            <div class="col-sm-9 col-xs-12">
          		<label class="form-control-label">Enter OTP :</label>
          		<input class="form-control" placeholder="enter OTP" type="text" name="confirm_code" id="confirm_code">
            </div>
      	</div>

      	<div class="form-group row clearfix">
			<div class="col-sm-9 col-xs-12">
				<div id="otpcodebtn">
					<label class="form-control-label" style="display : inline-block:">Click here to <span style="color:blue;cursor:pointer; font-weight: bold" id="send_otp">Send OTP</span></label>
				</div>
				<div id="timer">
					Time Left : <span id="clock"></span>
				</div>
			</div>
		</div>

		<ul class="list-inline">
			<li>
				<button type="submit" class="cm_blacbtn1" id="withdraw_submit">Submit</button>
				<img src="{{asset('/').('public/admin_assets/images/loader.gif')}}" class="btn_how" id="withdraw_loader" style="display: none;">
			</li>
		</ul>
	{!! Form::close() !!}
</div>

<script type="text/javascript">
	jQuery.validator.addMethod("cryptoCurrency", function(value, element) {
        return this.optional(element) || /^\d{0,50}(\.\d{0,8})?$/i.test(value);
    }, "Only 8 decimals allowed after decimal point.");

	function withdraw_loader_show() {
		if($('#withdraw_form').valid() == true) {
			$('#withdraw_submit').hide();
			$('#withdraw_loader').show();
		} else {
			$('#withdraw_loader').hide();
			$('#withdraw_submit').show();
		}
	}

	$(document).ready(function(){
	  $('#timer').hide();
	});

	$('#withdraw_form').validate({
		rules:{
		  currency:{
		    required:true,
		  },
		  address:{
		    required:true,
		  },
		  amount:{
		    required:true,
		    number:true,
		    cryptoCurrency:true
		  },
		  password:{
		    required:true,
		  },
		  confirm_code:{
		    required:true,
		  }
		},
		messages:{
		   currency:{
		    required:"Choose Currency",
		  },
		  address:{
		    required:"Enter coin address",
		    remote:"Enter valid address"
		  },
		  amount:{
		    required:"Enter Amount",
		  },
		  password:{
		    required:"Enter Password",
		  },
		  confirm_code:{
		    required:"Enter OTP code",
		  }
		}
	});

	$('#send_otp').click(function(){
	    $.ajax({
	      url:"{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/sendWithdrawOtp') }}",
	      method:"POST",
	      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	       beforeSend: function() {
        		// setting a timeout
        	 $('#withdraw_submit').hide();
			 $('#withdraw_loader').show();
   			 },
	      success:function(data) {
	        data = $.parseJSON(data);
	        if(data.status == "1") {
        		alert('OTP Sent to your email');
	          	getCountDown(420,$('#clock'));
	          	//alert('OTP : '+data.code);
	        } else {
	            alert('Please try again!');
	        }
	         $('#withdraw_submit').show();
			 $('#withdraw_loader').hide();
	      }
	    })
  	});

  	function getCountDown(duration, display) {
  		if (!isNaN(duration)) {
  			$('#otpcodebtn').hide();
  			$('#timer').show();
  			$('#clock').show();
  			var timer = duration, minutes, seconds;
  			var interVal=  setInterval(function () {
  				minutes = parseInt(timer / 60, 10);
  				seconds = parseInt(timer % 60, 10);

  				minutes = minutes < 10 ? "0" + minutes : minutes;
  				seconds = seconds < 10 ? "0" + seconds : seconds;

  				$(display).html("<b>" + minutes + " : " + seconds + "</b>");
  				if (--timer < 0) {
  					timer = duration;
  					$('#otpcodebtn').show();
  					$.ajax({
	  					url:"{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/confirmCode') }}",
		      			method:"POST",
		      			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		      			success:function(data) {
		      				data = $.parseJSON(data);
		      				if(data.status == "1") {
		      					$('#confirm_code').val('');
		      					$('#confirm_code').attr('placeholder','OTP expired');
		      					$('#clock').hide();
		      					$('#timer').hide();
		      				}
		      			}
	  				});
  					clearInterval(interVal)
  				}
  			},1000);
  		}
  	}
</script>

@stop