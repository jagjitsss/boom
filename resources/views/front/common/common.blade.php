<!DOCTYPE html>
<html lang="en">
<head>
  <?php
getHeaders();
$site = getSite();
?>
  <title><?php echo $site->site_name; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
  <link rel="shortcut icon" href="{{$site->site_favicon}}" type="image/png">
  <link rel="stylesheet" href="{{asset('/').('public/assets/css/bootstrap.min.css')}}">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">

  
  <link rel="stylesheet" href="{{asset('/').('public/assets/css/style.css')}}?{{date('Y-m-d h:i:s')}}">
  <link rel="stylesheet" href="{{asset('/').('public/assets/css/animate.css')}}">
  <link rel="stylesheet" href="{{asset('/').('public/assets/css/notifIt.css')}}">

  <link href="{{asset('/').('public/assets/css/aos-animation-style.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('/').('public/build/css/')}}intlTelInput.css">
 <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <style type="text/css">
    .error {
      color: red !important;
    }
    .hide{
      display: none;
    }
  </style>
</head>
<body>
<div class="header-section">
  <div class="container index-navigation px-0">
    
    <nav class="navbar navbar-expand-md justify-content-center index-header navbar-fixed-top"> <a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>"> 
      <img src="{{$site->site_logo}}" class="img-fluid"></a>
      <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2"> <span class="navbar-toggler-icon fa fa-fw fa-align-justify"></span> </button>
      <div class="container-fluid cf-main">
        <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
          <ul class="navbar-nav mr-auto text-center center-nav-links">
           

          
          
          
          </ul>
          <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap right-nav-links">
            <?php if (Session::has('tmaitb_user_id')) { 
            $p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
          ?>

          <li class="nav-item"> <a class="nav-link" href="<?php echo url('api/api_document'); ?>" id="overview-link"> {{trans('app_lang.api') }}</a> </li>

            <li class="nav-item dashboard-header-links dash-header-icon-link">
              <div class="dropdown"> <a href="{{url('/notification_list')}}" onclick="view_notf('<?php echo $p; ?>')">
                <button  class="profile-dd dropdown-toggle" style="cursor: pointer">
                <div class="notif-icon" id="notif_icon" title="Notification"></div>
                </button>
                </a> </div>
              <div class="notif-counter"><?php echo notification_list(); ?></div>
            </li>

            <li class="nav-item d-flex"> 
              <div class="signin-btn d-flex">
                <a class="nav-link index-header-link nav-register" href="<?php echo url('dashboard'); ?>">Dashboard</a> 
              </div>
            </li>

            <?php } else { ?>

               <li class="nav-item"> <a class="nav-link" href="<?php echo url('api/api_document'); ?>" id="overview-link"> {{trans('app_lang.api') }}</a> </li>

            <li class="nav-item"> <a class="nav-link  " href="{{url('/trade')}}"> Trade </a> </li>
            <li class="nav-item"> <a class="nav-link" href="{{url('/funds')}}">Wallet</a> </li>


            <li class="nav-item d-flex"> 
              <div class="signin-btn d-flex">
                <a class="nav-link index-header-link nav-register" href="<?php echo url('register'); ?>">{{trans('app_lang.signup') }}</a> 
                <span>/</span> 
                <a class="nav-link index-header-link" href="<?php echo url('login'); ?>">{{trans('app_lang.login') }}</a> 
              </div>
            </li>

            <?php } ?>
            <?php if (Session::has('tmaitb_user_id')) {
            ?>
            <li class="nav-item dashboard-header-links">
              <div class="dropdown right-dd">
                <button type="button" class="profile-dd dropdown-toggle" data-toggle="dropdown">
                <?php $name = session::get('tmaitb_profile');
                  if ($name) {
                    $user_profile = getUserImage();
                    ?>
                <div class="header-profile-icon">
                  <?php
                  if ($user_profile == '') {?>
                  <img src="{{asset('/').('public/assets/images/')}}avatar.png" class="header-profile-icon img-fluid">
                  <?php } else {?>
                  <img src="{{$user_profile}}" class="header-profile-icon img-fluid">
                  <?php }?>
                </div>
                </button>
                <div class="dropdown-menu">
                  <div class="pro_name clearfix">
                    <?php }?>
                    <div class="header-profile-name">
                      <?php
                      echo $name ? $name : 'Hi ';
                      ?>
                    </div>
                    <a class="" href="<?php echo url('logout'); ?>">{{trans('app_lang.logout') }}</a> </div>
                  <ul>
                    <li class="drop_new_blk"> <a class="dropdown-item" href="<?php echo url('dashboard'); ?>"> <img src="{{asset('/').('public/assets/images/')}}men_img.png" class="img-fluid"> {{trans('app_lang.dashboard') }}</a> </li>
                  </ul>
                </div>
              </div>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
  </div>
@include($viewsource)


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


<script type="text/javascript">

    $(document).ready(function() {

    $('.post-fadeUp').addClass("hidden").viewportChecker({
        classToAdd: 'visible animated fadeInUp', // Class to add to the elements when they are visible
        offset: 100
       });

       $('.hometable').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
});
</script>
<script>
    $( document ).ready(function() {
        <?php if (Session::has('success')) {?>
          var sucess= "{{ Session::get('success') }}";
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+sucess, type: "success" });
        <?php }?>
        <?php if (session()->has('error')) {?>
          var error= "{{ Session::get('error') }}";
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" "+error, type: "error" });
        <?php }?>
    });
var siteurl = "{{URL::to('/')}}";
var user_id = '<?php echo Session::has('tmaitb_user_id') ? Session::get('tmaitb_user_id') : '0 '; ?>';
 </script>

 <script type="text/javascript">
var SITE_URL = "{{url('/')}}";
</script>
<script src="{{asset('/').('public/assets/js/asdfksdowlslslsl.js')}}"></script> 


<script src="{{asset('/').('public/assets/js/custom_script.js')}}?{{date('Y-m-d h:i:s')}}"></script>
<script src="{{asset('/').('public/assets/js/script.js')}}"></script>

<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit" async defer></script>

<script>
 

$(window).on('keydown',function(event)
    {
    if(event.keyCode==123)
    {
        return false;
    }
    else if(event.ctrlKey && event.shiftKey && event.keyCode==73)
    {
        return false;  //Prevent from ctrl+shift+i
    }
    else if(event.ctrlKey && event.keyCode==73)
    {
        return false;  //Prevent from ctrl+shift+i
    }
});
$(document).on("contextmenu",function(e)
{
e.preventDefault();
});



</script>


<script>
   $(document).on('focusout', ':input', function() {
    var str = $(this).val();
     var res = str.replace(/\</g, "");
    var res1 = res.replace(/\>/g, "");
    $(this).val(res1);
  });
</script>


<script>
  var captcha_key = '<?php echo getSiteKey(); ?>';

function recaptchaCallback (e) {
  if($('#Captcha').length){
  var container = document.getElementById('Captcha')
  container.innerHTML = ''
  var recaptcha = document.createElement('div')
  grecaptcha.render(recaptcha, {
    'sitekey': captcha_key
  })
     container.appendChild(recaptcha);
 }
}

  function setlang(lan)
  {
    var fiat = lan;
    var link = "{{URL::to('/setlanguage')}}"+'/'+fiat;
    $.ajax({
      url:link,
      method:"GET",
      data:{
      "_token": "{{ csrf_token() }}",
      "lan": fiat
      },
      success:function(msg) {
         location.reload();
      }
    })
  }

</script>

<script>
AOS.init();
</script>
</body>
</html>