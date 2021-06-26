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
<link rel="stylesheet" href="{{asset('/').('public/assets/css/notifIt.css')}}">


<link href="{{asset('/').('public/assets/css/')}}jquery.mCustomScrollbar.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/').('public/assets/css/style.css')}}?{{date('Y-m-d h:i:s')}}">
<link rel="stylesheet" href="{{asset('/').('public/assets/css/animate.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/assets/css/marquee.css')}}">
<link href="{{asset('/').('public/assets/css/aos-animation-style.css')}}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/').('public/build/css/')}}intlTelInput.css">
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="dsb-body lt load_site">
<div class="loader-bg" id="load">
  <div class="loader"> <img src="{{asset('/').('public/assets/images/')}}loader.gif" ></div>
</div>
<div class="api-page-header">
  <div class="container">
    <nav class="navbar navbar-expand-md justify-content-center index-header fixed-top navbar-fixed-top apitopbox"> <a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>"> <img src="{{$site->site_logo}}"class="img-fluid"> </a>
      <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2"> <span class="navbar-toggler-icon fa fa-fw fa-align-justify"></span> </button>
      <div class="container-fluid">
        <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
        <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap right-nav-links">
            <?php if (Session::has('tmaitb_user_id')) {  ?>
            <li class="nav-item"> <a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="overview-link"> {{trans('app_lang.overview') }}</a> </li>
           
            
             <li class="nav-item">
                <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('buy-sell')}}" id="funds-link">
                  <div class="tab-link-txt">{{trans('app_lang.exchange') }}</div>
                </a>
              </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('trade')}}" id="funds-link">
              <div class="tab-link-txt">Trade</div>
            </a>
          </li>
          

            <li class="nav-item"> <a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="overview-link"> {{trans('app_lang.api') }}</a> </li>
            <li class="nav-item"> <a class="nav-link <?php echo $page == 3 ? 'active' : ' ' ?>" href="<?php echo url('funds'); ?>" id="funds-link">{{trans('app_lang.funds') }}</a> </li>
            <li class="nav-item"> <a class="nav-link <?php echo $page == 4 ? 'active' : ' ' ?>"  href="<?php echo url('referral'); ?>" id="referals-link">{{trans('app_lang.refer') }}</a> </li>
            
            <li class="nav-item"> <a class="nav-link <?php echo $page == 6 ? 'active' : ' ' ?>" href="<?php echo url('support'); ?>" id="">{{trans('app_lang.support') }}</a> </li>
            <li class="nav-item"> <a class="nav-link <?php echo $page == 7 ? 'active' : ' ' ?>" href="<?php echo url('bankwire'); ?>" id="">{{trans('app_lang.bankwire') }}</a> </li>
            <?php } else { ?>
           
    
            <li class="nav-item"> <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('trade')}}" id="funds-link">
              <div class="tab-link-txt">{{trans('app_lang.exchange') }}</div>
              </a> </li>
            <li class="nav-item"> <a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="overview-link">{{trans('app_lang.api') }}</a> </li>
            <?php } ?>
            
            <?php if (Session::has('tmaitb_user_id')) {
              $p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
            ?>
            <li class="nav-item notify_msg"> <i class="fa fa-bullhorn" aria-hidden="true"></i>
              <div class="notification">
                <h3>News</h3>
                <div class="notify-content">
                  <div class="notify_blk">
                    <?php
                    foreach($newsdetails as $new) {
                      $d = strtotime($new->updated_at);
                      //$url = URL::to('/newsdetails/'.$new->id);
                      $url = Config::get('domain.url').'newsdetails/'.$new->id;
                      
                      ?>
                    <p><a href="<?php echo $url;?>">【Announcement】<?php echo news_lang_content('0',session('language'),$new->id);?> on <?php echo date("d/m/Y", $d);?></a></p>
                    <?php }  ?>
                  </div>
                </div>
                <div class="notify-footer"> <a href="<?php echo Config::get('domain.url').'news' ?>">View all</a> </div>
              </div>
            </li>
            <li class="nav-item dashboard-header-links dash-header-icon-link">
              <div class="dropdown"> <a href="{{url('/notification_list')}}" onclick="view_notf('<?php echo $p; ?>')">
                <button  class="profile-dd dropdown-toggle" style="cursor: pointer">
                <div class="notif-icon" id="notif_icon" title="Notification"></div>
                </button>
                </a> </div>
              <div class="notif-counter"><?php echo notification_list(); ?></div>
            </li>
            <?php } else { ?>
            <li class="nav-item"> <a class="nav-link index-header-link" href="<?php echo url('login'); ?>">{{trans('app_lang.login') }}</a> </li>
            <li class="nav-item"> <a class="nav-link index-header-link nav-register" href="<?php echo url('register'); ?>">{{trans('app_lang.signup') }}</a> </li>
           
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
                  <img src="{{asset('/').('public/assets/images/')}}avatar.png" class="header-profile-icon">
                  <?php } else {?>
                  <img src="{{$user_profile}}" class="header-profile-icon">
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
                    <li class="drop_new_blk"> <a class="dropdown-item" href="<?php echo url('dashboard'); ?>"> <img src="{{asset('/').('public/assets/images/')}}men_img.png"> {{trans('app_lang.dashboard') }}</a> </li>
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
</div>
@include($viewsource)
@include('front.common.footer')

<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script> 
<script src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.js"></script> 
<script src="{{asset('/').('public/assets/js/popper.min.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/bootstrap.min.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/viewportchecker.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/aos-animation-script.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/jquery.validate.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script> 
<script src="{{asset('/').('public/assets/js/notifIt.min.js')}}"></script> 
<script src="{{asset('/').('geetest/gt3-php-sdk-master/static/gt.js?ver=123')}}"></script> 
<script src="{{asset('/').('public/build/js/')}}intlTelInput.js"></script> 
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> 
<script type="text/javascript">
var siteurl = "{{URL::to('/')}}";
var user_id = '<?php echo Session::has('tmaitb_user_id') ? Session::get('tmaitb_user_id') : '0 '; ?>';
</script> 
<script src="{{asset('/').('public/assets/js/common_script.js')}}?{{date('y-m-d h:i')}}"></script> 
<script type="text/javascript">

jQuery(document).ready(function() {
	jQuery('.post').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated fadeInDown', 
	    offset: 100
	   });

	jQuery('.post-bounce').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated bounceInUp', 
	    offset: 100
	   });

	  jQuery('.post-flip').addClass("hidden").viewportChecker({
		classToAdd: 'visible animated flipInX', 
		offset: 100
	   });

	jQuery('.post-slideUp').addClass("hidden").viewportChecker({
	    classToAdd: 'visible animated slideInUp', 
	    offset: 100
	   });


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
    setTimeout(function () {
        document.getElementById("load").style.display = "none";
        $('body').removeClass('load_site');
}, 2000);



 </script> 
<script src="{{asset('/').('public/assets/js/script.js')}}"></script> 
<script type="text/javascript" src="{{asset('/').('public/charting_library/charting_library.min.js')}}"></script> 
<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/polyfills.js') }}"></script> 
<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/bundle.js') }}"></script> 

<script src="{{asset('/').('public/assets/js/jquery.scrollbox.js')}}"></script> 
<script>
library_path = "{{ asset('/').('public/charting_library/') }}";

$(function () {
  $('#demo1').scrollbox();
  $('#demo2').scrollbox({
    linear: true,
    step: 1,
    delay: 0,
    speed: 100
  });
  $('#demo3').scrollbox({
    
	linear: true,
	 step: 1,
    delay: 0,
    speed: 20
  });
  $('#demo4').scrollbox({
    direction: 'h',
    
	linear: true,
	 step: 1,
    delay: 0,
    speed: 20
  });
  $('#demo5').scrollbox({
    direction: 'h',
    distance: 134
  });
  $('#demo5-backward').click(function () {
    $('#demo5').trigger('backward');
  });
  $('#demo5-forward').click(function () {
    $('#demo5').trigger('forward');
  });
});


if(page_view == null || limit == null){
  var page_view = '';
  var limit = '';
}
if(gcapcha == null){
  var gcapcha = '';
}



$(window).on("load", function () {
	var home = '<?php echo isset($home); ?>';
	var to_currency = '<?php echo $to_symbol;?>';
	var pairid = '<?php echo $pairid;?>';
	var first_currency = '<?php echo $from_symbol;?>';
	var pair = to_currency +'_'+first_currency;
	$('.tab_load').hide();
	if(home){
	  	showMarketTab(2,pairid);
	  	displayChart_home(pair);
	}


	$("#fil_"+page_view).addClass("active");

	if(Number(limit) == Number(page_view)){
       $("#front").hide();
    }
    else{
      $("#front").show();
    }

    if(page_view == 1){
      $("#back").hide();
    }else{
      $("#back").show();
    }

  });

function displayChart_home(pair) {

  var backClr = "#ffffff";
  var gridClr = "#eee";
  var textClr = "#333";

  var widget = new TradingView.widget({
        "fullscreen": true,
        "tvwidgetsymbol" :pair,
        "symbol": pair,
        "style": "1",
        "precision": 3,
        "show_popup_button": true,

        "toolbar_bg": backClr,
        "container_id": "chart_container_home",
        "datafeed": new Datafeeds.UDFCompatibleDatafeed(siteurl+"/chart"+'/'+pair),
        "library_path": library_path,
        "withdateranges": true,
        "allow_symbol_change": false,
        "interval": "5",
        "locale": "en",
        "theme" : "light",
        "height": "72px",
        "save_image": false,
        "hideideas": true,
        "debug": false,
        "show_popup_button": true,
        "locale": "en",
        "drawings_access": { type: 'black', tools: [ { name: "Regression Trend" } ] },
        "disabled_features": ["use_localstorage_for_settings","dome_widget","display_market_status","display_header_toolbar_chart","header_compare","header_undo_redo","compare_symbol","header_settings","study_dialog_search_control","caption_buttons_text_if_possible","header_screenshot","volume_force_overlay","header_widget","left_toolbar"],
        "overrides": {
          
          "paneProperties.background": backClr,
          "paneProperties.horzGridProperties.color": gridClr,
          "paneProperties.vertGridProperties.color": gridClr,
          "symbolWatermarkProperties.transparency": 90,
          "scalesProperties.textColor" : textClr
        },
        "time_frames": [],
    });
}

function search_filter(val)
{
	if(val == 1){
	 var page = ''
	}else{
	 var page = '?page='+val;
	}
	 var search_box = $('#news_search').val().trim();

	 window.location.replace("{{URL::to('/news')}}"+page);

}


function front_back(val){

   if(val == 'front'){
    var form = parseInt(page_view)+parseInt(1);
   }
   else{
     var form = parseInt(page_view)-parseInt(1);
   }
   window.location.replace("{{URL::to('/news')}}"+'?page='+form);
}

$('#news_search').on('keyup', function(e){
    var query = $.trim($('#news_search').val()).toLowerCase();
    $('div.div_news').each(function(){
         var $this = $(this);
         if($this.text().toLowerCase().indexOf(query) === -1)
             $this.closest('div.div_news').fadeOut();
        else $this.closest('div.div_news').fadeIn();
    });
});
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


if(gcapcha){

   var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });
        
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
        
    };
    $.ajax({
        
        url: siteurl+"/captcha",
        type: "get",
        dataType: "json",
        success: function (data) {
            
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                new_captcha: data.new_captcha,
                product: "embed", 
                lang: 'en',
                offline: !data.success 
            }, handlerEmbed);
        }
    });

}




</script> 
<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit" async defer></script> 
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

</script> 

<link href="{{asset('/').('public/assets/css/owl.carousel.css')}}" rel="stylesheet">
<script src="{{asset('/').('public/assets/js/owl.carousel.js')}}"></script> 
<script type="text/javascript">
    $('.owl-three').owlCarousel({
      loop: true,
      autoplay: true,
      autoplayTimeout:false,
      margin: 5,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
         400: {
          items: 2,
          nav: true
        },
        600: {
          items:3,
          nav: false
        },
        1000: {
          items:4,
          nav: true,
          loop: true
        },
        1200: {
          items:5,
          nav: true,
          loop: true
        }
      }
    });

  </script> 
<script>
	$('.cryp-coin-status-row .dropdown-menu a').click(function(){

	var picurl= "{{asset('/').('public/images/admin_currency/')}}";
    $('#selected').html($(this).html());
    var to_currency = $('#selected .s_cur').html();
    var to_currency_val = $('#selected .value').html();
    var big_coin = picurl+ $('#big_size').val();

    $("#attr_src").attr('src' , big_coin);
    $('#attr_src').css({'width' : '48px' , 'height' : '48px'});
    $('.usd_value').html(to_currency_val);
    });
</script> 
<script>
  $('.cryp-coin-status-row .dropdown-menu a.dropdown-item').click(function(){
    $(this).parent().removeClass('show');
});


$('th.pair_tab').click(function(){

    var table = $(this).parents('table').eq(0);

    var id = $(this).parents('table').attr('id');

    if($(this).parents('table').find('tbody').hasClass('m-btc-o')){
      classname = '.mCSB_container';
    }
    else if($(this).parents('table').find('tbody').hasClass('m-eth-o')){
      classname = '.mCSB_container';
    }
    else if($(this).parents('table').find('tbody').hasClass('m-usdt-o')){
      classname = '.mCSB_container';
    }else{
      classname = '.mCSB_container';
    }

    $('#'+id+' thead th').removeClass('headerSortUp');
    $('#'+id+' thead th').removeClass('headerSortDown');

    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))

    this.asc = !this.asc
    if(this.asc){
      $(this).removeClass('headerSortUp');
      $(this).addClass('headerSortDown');
    }else{
      $(this).addClass('headerSortUp');
      $(this).removeClass('headerSortDown');
    }

    if (this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){
      $(classname).append(rows[i])
    }
});


function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){
 return $(row).children('td').eq(index).text()
}


</script> 
<script>
AOS.init();
</script> 
<script>
  var width = $(window).width();

    if (width > 1024) {
    
    $(".ht-220").mCustomScrollbar({
      scrollButtons: {
      enable: false
      },

      scrollbarPosition: 'inside',
      autoExpandScrollbar: true,
      theme: 'minimal-dark',
      axis: "y",
      setWidth: "auto"
    });

    } else {
    $(".ht-220").mCustomScrollbar({
      scrollButtons: {
      enable: false
      },

      scrollbarPosition: 'inside',
      autoExpandScrollbar: true,
      theme: 'minimal-dark',
      axis: "x",
      setWidth: "auto"
    });

    }
 </script> 
<script src="{{asset('/').('public/assets/js/jquery.sticky.js')}}"></script> 
<script type="text/javascript">
$(document).ready(function(){
  if($(window).width() >= 1024){
  $(".header-grids").sticky({ topSpacing: 0 });
}
});
</script> 
<script>
$(window).on('keydown',function(event)
    {
    if(event.keyCode==123)
    {
        return false;
    }
    else if(event.ctrlKey && event.shiftKey && event.keyCode==73)
    {
        return false;  
    }
    else if(event.ctrlKey && event.keyCode==73)
    {
        return false;  
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
</body>
</html>
