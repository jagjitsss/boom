<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=320; user-scalable=no; initial-scale=1.0; maximum-scale=1.0">
    <title>BoomCoin Wallet</title>

    
    <link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/style.css')}}">

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
        <div class="loginForm col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">

      {!! Form::open(array('url' => 'HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/updatePassword', 'class'=>'form-horizontal', 'id'=>'reset_form')) !!}

        <div class="form-group">
          <div class="col-md-12 col-sm-12 text-center">
            <h4 class="logTit">Reset password</h4>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-12 col-sm-12">New Password</label>
          <div class="col-md-12 col-sm-12">
            <input type="password" name="new_pwd" id="new_pwd" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-12 col-sm-12">Confirm Password</label>
          <div class="col-md-12 col-sm-12">
            <input type="password" name="cnfirm_pwd" id="cnfirm_pwd" class="form-control" />
          </div>
        </div>

        <input type="hidden" name="fcode" value="<?php echo strip_tags($data['fcode']); ?>">
        <input type="hidden" name="ucode" value="<?php echo strip_tags($data['ucode']); ?>">



         <div class="form-group">
              <div class="col-sm-6">

              </div>
              <div class="col-sm-6 text-right">
               
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12 col-sm-12">
                <button type="submit" class="btn btn-block">Save</button>
              </div>
            </div>

  
          {!! Form::close() !!}
        </div>
      </div>
    </div>

  
  <script src="{{asset('/').('public/admin_assets/js/jquery-2.1.1.min.js')}}"> </script>
  <script src="{{asset('/').('public/admin_assets/js/bootstrap.min.js')}}"> </script>
  <script src="{{asset('/').('public/admin_assets/js/jquery.validate.min.js')}}"> </script>
<script>
$('document').ready(function(){
  var error = "<?php echo Session::get('error'); ?>";
  if(error != "")
  {
      alert(error);
  }
})




$('#reset_form').validate({
  rules:{
    new_pwd:{
      required:true,

    },
    cnfirm_pwd:{
      required:true,
    }

  },
  messages:{
   new_pwd:{
      required:"Enter New Password",
    },
    cnfirm_pwd:{
      required:"Enter Confirm Password",
    }
  }
})
</script>


  </body>
</html>
