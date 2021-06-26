<!DOCTYPE html>
<?php
$getSite = App\Model\User::getSiteLogo();
?>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=320; user-scalable=no; initial-scale=1.0; maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BoomCoin Wallet</title>

    <link rel="icon" href="{{ $getSite->site_favicon }}" type="image/x-icon">

    
    <link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/patternLock.css')}}"  type="text/css">

<style>
    body{
        font-family:Arial, Helvetica, sans-serif;
    }
    .error{
      color:red;
    }
</style>

  </head>
  <body class="loginBg">

    <div class="" id="login_div">
      <div class="container">
        <div class="loginForm col-md-4 col-sm-6 col-xs-12 fn center-block">
    <?php if (Session::has('success')) {?>
    <div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Success!</strong><?php echo Session::get('success'); ?> </div>
    <?php }?>

    <?php if (Session::has('error')) {?>
    <div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
    <?php }?>

      {!! Form::open(array('url' => 'HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/adminLogin', 'class'=>'form-horizontal', 'id'=>'login_form')) !!}

            <div class="form-group">
              <div class="col-md-12 col-sm-12 text-center">
                <h4 class="logTit">Login</h4>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-12 col-sm-12">Email</label>
              <div class="col-md-12 col-sm-12">
                <input type="text" name="username" id="username" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-12 col-sm-12">Password</label>
              <div class="col-md-12 col-sm-12">
                <input type="password" name="user_pwd" id="user_pwd" class="form-control" />
              </div>
            </div>

            <div class="control-group">
              <label class="col-md-12 col-sm-12">Pattern Code</label>
              <div class="col-md-12 col-sm-12">
              <div id="patternContainer"></div>
               <input type="hidden"  name="pattern_code" id="patterncode">
              </div>
            </div>

            <div class="form-group otpid" style="display:none;">
              <label class="col-md-12 col-sm-12">OTP : </label>
              <div class="col-md-12 col-sm-12">
                <input type="password" name="key_code" id="key_code" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12 col-sm-12 text-center">
                <p>
                  Send OTP? <a href="" id="send_otp">Click here</a>
                </p>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6">
              </div>
              <div class="col-sm-6 text-right">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12 col-sm-12">
                <button type="submit" class="btn btn-block logbtn">Login</button>
              </div>
            </div>

          {!! Form::close() !!}
        </div>
      </div>
    </div>


<script src="{{asset('/').('public/admin_assets/js/jquery-2.1.1.min.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/bootstrap.min.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/patternLock.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/jquery.validate.min.js')}}"> </script>
<script>
var lock = new PatternLock("#patternContainer",{
   onDraw:function(pattern){
      word();
    }
});

function word() {
  var pat=lock.getPattern();

  $("#patterncode").val(pat);
  $('#patterncode').valid()
}

$('#login_form').validate({
  ignore:"",
  rules:{
    username:{
      required:true,
      email:true,
    },
    user_pwd:{
      required:true,
    },
    pattern_code:{
      required:true,
    }
  },
  messages:{
     username:{
      required:"Enter username",
    },
    user_pwd:{
      required:"Enter password",
    },
    pattern_code:{
      required:"Draw pattern",
    }
  }
});

$('#send_otp').click(function(e){
$('.otpid').css('display','block');
$('.logbtn').css('display','block');
  e.preventDefault();
  $.ajax({
    url:"{{URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/sendLoginOtp')}}",
    method:"POST",
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success:function(data,status,xhr) {
      data = $.parseJSON(data);
      if(data.status == "1") {
        alert('OTP Sent to your email');
        // alert('OTP : '+data.code);
      } else {
        alert('Please Try Again');
      }
    },
    error:function() {
      alert('Please refresh the page and try again!');
    }
  })
});

$(document).ready(function(){
   $('.logbtn').css('display','none');
  setTimeout(function() {
    $('.alert').fadeOut('fast');
  }, 3000); // <-- time in milliseconds
});
</script>


  </body>
</html>
