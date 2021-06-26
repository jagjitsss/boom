<!DOCTYPE html>
<html lang="en">
<head>
 <?php
if (headers_sent()) {
	foreach (headers_list() as $header) {
		header_remove($header);
	}

}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: X-PINGARUNER');
header('Access-Control-Max-Age: 1728000');
header("Content-Length: 0");
header("Content-Type: text/plain");
$site = getSite();
$log = Session::get('tmaitb_user_id');
?>
<title><?php echo $site->site_name; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="<?php echo e($site->site_favicon); ?>" type="image/png">
<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/bootstrap.min.css')); ?>">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/style.css')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/trade-style.css')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/animate.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/jquery-ui.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/notifIt.css')); ?>">
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<link href="<?php echo e(asset('/').('public/assets/css/')); ?>jquery.mCustomScrollbar.css" rel="stylesheet">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body class="dsb-body dark-theme adv-theme">
<div class="loader-bg" id="load">
<div class="loader">
<img src="<?php echo e(asset('/').('public/assets/images/')); ?>loader.gif" ></div>
</div>

	<?php echo $__env->make('front/advance_trade/topbar-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php echo $__env->make('front/advance_trade/sidebar-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<div class="mobile-menu-cnt"><span onclick="openNav()" class="mobile-menu"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>white-menu.png"><?php echo e(trans('app_lang.tab_menu')); ?></span></div>


<div class="container dashboard-tabs-cnt">
<div class="row">

	
	<div class="col-xs-12 col-sm-12 tab-content no-padding">

		
		<div class="tab-pane container-fluid active" id="exchange">
			<div class="adv-trade-wrapper">
			<div class="row">
			<div class="col-xs-12 col-sm-12 card-div adv-trade-status-cnt lg-p-l lg-p-r">
							<div class="card-div-cnt d-md-flex justify-content-md-between">
								<div class="dropdown exc-dd coinDrop">
								  <button type="button" class="exc-topbar-dd dropdown-toggle">
									<span class="exc-dd-heading cur_pair"></span>
								  </button>
								  <?php echo $__env->make('front/advance_trade/popup-mobile-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
								</div>

								<div class="exc-data-container">
									<div class="exc-data-cnt">
										<span class="lgtxt"><?php echo e(trans('app_lang.last_price')); ?></span>
										<span class="lastprice"></span>
									</div>
									<div class="exc-data-cnt">
										<span class="lgtxt">24h <?php echo e(trans('app_lang.change')); ?></span>
										<span class="change" ></span>
									</div>
									<div class="exc-data-cnt">
										<span class="lgtxt">24h <?php echo e(trans('app_lang.high')); ?></span>
										<span class="high"></span>
									</div>
									<div class="exc-data-cnt">
										<span class="lgtxt">24h <?php echo e(trans('app_lang.low')); ?></span>
										<span class="low"></span>
									</div>
									<div class="exc-data-cnt">
										<span class="lgtxt">24h <?php echo e(trans('app_lang.volume')); ?> (BTC)</span>
										<span class="volume"></span>
									</div>
								</div>
							</div>
						</div>
						

				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 xl-p-r lg-p-l lg-p-r">
					<div class="row">
						<div class="col-xs-12 col-sm-12 card-div xl-p-l xl-p-r" >
							<div id="chart_container"></div>
						</div>
				<?php echo $__env->make('front/advance_trade/myhistory-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					</div>
				</div>
				<?php echo $__env->make('front/advance_trade/orderbook-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			</div>
			</div>

		</div>


	</div>
</div>
</div>

  
  <div class="modal fade" id="atrLogin" role="dialog">
    <div class="modal-dialog">


      <div class="modal-content">

        <div class="modal-body">
          <p>Please <a href="<?php echo e(URL::to('/login')); ?>">login</a> to continue</p>
        </div>

      </div>

    </div>
  </div>
<script>
   var unable_place_order   = "<?php echo e(trans('app_lang.unable_place_order')); ?>";
   var stop_price_above     = "<?php echo e(trans('app_lang.enter_stop_price_above')); ?>";
   var stop_price_below     = "<?php echo e(trans('app_lang.enter_stop_price_below')); ?>";
   var valid_amount         = "<?php echo e(trans('app_lang.enter_valid_amount')); ?>";
   var valid_price          = "<?php echo e(trans('app_lang.enter_valid_price')); ?>";
   var stop_greater         = "<?php echo e(trans('app_lang.stop_greater_zero')); ?>";
   var insufficient_bal     = "<?php echo e(trans('app_lang.insufficient_bal')); ?>";
   var valid_stop_price     = "<?php echo e(trans('app_lang.enter_valid_stop_price')); ?>";
   var order_placed_success = "<?php echo e(trans('app_lang.order_placed')); ?>";
   var invalid_pair         = "<?php echo e(trans('app_lang.invalid_pair')); ?>";
   var cancel_order         = "<?php echo e(trans('app_lang.want_cancel_order')); ?>";
   var order_cancel         = "<?php echo e(trans('app_lang.order_cancelled')); ?>";
   var error_try            = "<?php echo e(trans('app_lang.error_try_again')); ?>";
   var enter_amount_more_than= "<?php echo e(trans('app_lang.enter_amount_more_than')); ?>";
   var enter_price_more_than = "<?php echo e(trans('app_lang.enter_price_more_than')); ?>";
   var no_buy_orders         = "<?php echo e(trans('app_lang.no_buy_orders')); ?>";
   var no_sell_orders        = "<?php echo e(trans('app_lang.no_sell_orders')); ?>";
   var no_trade_history      = "<?php echo e(trans('app_lang.no_trade_history')); ?>";
   var no_open_order_available = "<?php echo e(trans('app_lang.no_open_order_available')); ?>";
   var no_stop_orders      = "<?php echo e(trans('app_lang.no_stop_orders')); ?>";
   var no_data_found        = "<?php echo e(trans('app_lang.no_data_found')); ?>";
   var profile_error        = "<?php echo e(trans('app_lang.fill_your_profile_details')); ?>";

</script>

<script src="<?php echo e(asset('/').('public/assets/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/bootstrap.min.js')); ?>"></script>

<script src="<?php echo e(asset('/').('public/assets/js/viewportchecker.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/script.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/notifIt.min.js')); ?>"></script>

<script src="<?php echo e(asset('/').('public/assets/js/jquery.mCustomScrollbar.concat.min.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/jquery-ui.js')); ?>"></script>
<script type="text/javascript">
	siteurl = "<?php echo e(URL::to('/')); ?>";
	user_id = '<?php echo Session::has('tmaitb_user_id') ? Session::get('tmaitb_user_id') : '0 '; ?>';
	update_user_id = 0;
	if(user_id != '0'){
		update_user_id = '<?php echo insep_encode(Session::get("tmaitb_user_id")) ?>';
	}
	library_path = "<?php echo e(asset('/').('public/charting_library/')); ?>";
</script>
<script type="text/javascript" src="<?php echo e(asset('/').('public/charting_library/charting_library.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/').('public/datafeeds/udf/dist/polyfills.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/').('public/datafeeds/udf/dist/bundle.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('/').('public/assets/js/socket.io.min.js')); ?>"></script>
<script src="<?php echo e(asset('/').('public/assets/js/advance-tradescript.js')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>"></script>
<script>
setTimeout(function () {
        document.getElementById("load").style.display = "none";
}, 2000);
$(document).ready(function() {
	pairData = {};
	getPairdetails('<?php echo e($pair_symbol); ?>',8);
	advanceChart('<?php echo e($pair_symbol); ?>');
	showadvanceBalance();
	showMarket();

   $('[data-toggle="tooltip"]').tooltip();
<?php if (Session::has('success')) {?>
  var sucess= "<?php echo e(Session::get('success')); ?>";
  notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+sucess, type: "success" });
<?php }?>
<?php if (session()->has('error')) {?>
  var error= "<?php echo e(Session::get('error')); ?>";
  notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" "+error, type: "error" });
<?php }?>
});

</script>
<script>

$('#settings-profile-link').on('click', function(){
$('.nav-tabs a[href="#settings-profile"]').click();
});




function openNav() {
document.getElementById("mySidenav").style.width = "150px";

}


function closeNav() {
document.getElementById("mySidenav").style.width = "0";

}
$('.nav-link').on('click', function(){
$('#close-click').click();
});

(function ($) {

$( ".column" ).sortable({
connectWith: ".column",
handle: ".portlet-header",
cancel: ".portlet-toggle",

});

$( ".portlet" )
.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
.find( ".portlet-header" )
.addClass( "ui-widget-header ui-corner-all" )
.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

$( ".portlet-toggle" ).on( "click", function() {
var icon = $( this );
icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
});



$(window).on("load", function () {
  var width = $(window).width();

  if (width > 1024) {
    //    big screen
    $("#openOrdersTable,#balance,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.buytb,.selltb,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .tb-340, .tb-311, .tb-200, .tb-223, .tb-174, .tb-315, .tb-298,.tb-84, .mc-1").mCustomScrollbar({
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
    $("#openOrdersTable,#balance,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.buytb,.selltb,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .tb-340, .tb-311, .tb-200, .tb-223, .tb-174, .tb-315, .tb-298,.tb-84").mCustomScrollbar({
      scrollButtons: {
        enable: false
      },

      scrollbarPosition: 'inside',
      autoExpandScrollbar: true,
      theme: 'dark',
      axis: "x",
      setWidth: "auto"
    });

  }
});
})(jQuery);

 function setlang(lan)
	{
		var fiat = lan;
		var link = "<?php echo e(URL::to('/setlanguage')); ?>"+'/'+fiat;
		$.ajax({
			url:link,
			method:"GET",
			data:{
			"_token": "<?php echo e(csrf_token()); ?>",
			"lan": fiat
			},
			success:function(msg) {
			  location.reload();
			}
		})
	}

</script>

<script>
function chart_wrap() {
var height = $('.adv-trade-wrapper').height();
var chart_wrap = (50 * height) / 100;

chart_wrap = parseInt(chart_wrap) + 'px';
$("#chart_container iframe").css('height',chart_wrap);
}

function orders_wrap() {
var height = $('.adv-trade-wrapper').height();
var orders_wrap = (50 * height) / 100;
var orders_wrap = orders_wrap - 85;
orders_wrap = parseInt(orders_wrap) + 'px';
$(".tb-174").css('height',orders_wrap);
}

function advtrade_wrap() {
var height = $('.adv-trade-wrapper').height();
var advtrade_wrap = (50 * height) / 100;
var advtrade_wrap = advtrade_wrap - 60;
advtrade_wrap = parseInt(advtrade_wrap) + 'px';
$(".tb-223").css('height',advtrade_wrap);
}

function buysell_wrap() {
var height = $('.adv-trade-wrapper').height();
var buysell_wrap = (50 * height) / 100;
var buysell_wrap = buysell_wrap - 90;
var buysell_wrap = buysell_wrap / 2;
buysell_wrap = parseInt(buysell_wrap) + 'px';
$(".tb-84").css('height',buysell_wrap);
}

function buysell_wrap1() {
var height = $('.adv-trade-wrapper').height();
var buysell_wrap1 = (50 * height) / 100;
var buysell_wrap1 = buysell_wrap1 - 60;
buysell_wrap1 = parseInt(buysell_wrap1) + 'px';
$(".tb-200").css('height',buysell_wrap1);
}

function limitnew_wrap() {
var height = $('.adv-trade-wrapper').height();
var limitnew_wrap = (50 * height) / 100;
var limitnew_wrap = limitnew_wrap - 74;
limitnew_wrap = parseInt(limitnew_wrap) + 'px';
$(".limit_new_class .portlet-content").css('height',limitnew_wrap);
}

$(document).ready(function() {
if($(window).width() >= 1200) {

chart_wrap();
$(window).bind('resize', chart_wrap);

orders_wrap();
$(window).bind('resize', orders_wrap);

advtrade_wrap();
$(window).bind('resize', advtrade_wrap);

buysell_wrap();
$(window).bind('resize', buysell_wrap);

buysell_wrap1();
$(window).bind('resize', buysell_wrap1);

limitnew_wrap();
$(window).bind('resize', limitnew_wrap);

}

});
</script>
</body>
</html>