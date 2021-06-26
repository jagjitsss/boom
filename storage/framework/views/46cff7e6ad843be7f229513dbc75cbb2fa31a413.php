<!DOCTYPE html>
<html lang="en">
  <head>
  <?php getHeaders();
  $site =
	getSite();?>
  <title><?php echo $site->site_name; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo e($site->site_favicon); ?>" type="image/png">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/bootstrap.min.css')); ?>">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
  
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/style.css')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/animate.css')); ?>">
  <link href="<?php echo e(asset('/').('public/assets/css/fileinput.css')); ?>" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/jquery-ui.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/notifIt.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/')); ?>dataTables.bootstrap4.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/')); ?>jquery.dataTables.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/')); ?>responsive.dataTables.min.css" type="text/css">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/assets/css/')); ?>chosen.css"  type="text/css">
  <link href="<?php echo e(asset('/').('public/assets/css/')); ?>jquery.mCustomScrollbar.css" rel="stylesheet">
  
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('/').('public/build/css/')); ?>intlTelInput.css">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <style type="text/css">
  .error {color: red !important;}
  .hide {display: none;}
  </style>
</head>
<body class="dsb-body load_site">
<div class="loader-bg" id="load">
  <div class="loader"> 
    <img src="<?php echo e(asset('/').('public/assets/images/')); ?>loader.gif" >
  </div>
  </div>
  <div class="header-section dashboard-header-sec">
  

    <div id="header-nav-container" class="container-fluid">
  <div class="container index-navigation px-0">

  <nav class="navbar navbar-expand-md justify-content-center index-header dashboard-header"> 
    <a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>">
      <img src="<?php echo e($site->site_logo); ?>"class="img-fluid">
    </a>
    <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2"> <span class="navbar-toggler-icon fa fa-fw fa-align-justify"></span> </button>
    <div class="container-fluid">
        
          <div class="navbar-collapse collapse justify-content-right" id="collapsingNavbar2">
            
              <ul class="navbar-nav text-right justify-content-right d-flex" style="margin: 0 0 0 auto;">

              <li class="nav-item"> <a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="overview-link">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-overview-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.overview')); ?></div>
                </a> </li>

                 <li class="nav-item">
                <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="<?php echo e(url('buy-sell')); ?>" id="funds-link">
                  <div class="tab-link-txt"><?php echo e(trans('app_lang.exchange')); ?></div>
                </a>
              </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="<?php echo e(url('trade')); ?>" id="funds-link">
              <div class="tab-link-txt">Trade</div>
            </a>
          </li>


              <li class="nav-item"> <a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="funds-link">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-funds-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.api')); ?></div>
                </a> </li>
              <li class="nav-item"> <a class="nav-link <?php echo $page == 3 ? 'active' : ' ' ?>" href="<?php echo url('funds'); ?>" id="funds-link">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-funds-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.funds')); ?></div>
                </a> </li>
              <li class="nav-item"> <a class="nav-link <?php echo $page == 4 ? 'active' : ' ' ?>"  href="<?php echo url('referral'); ?>" id="referals-link">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-referal-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.refer')); ?></div>
                </a> </li>
              
              <li class="nav-item"> <a class="nav-link <?php echo $page == 6 ? 'active' : ' ' ?>" href="<?php echo url('support'); ?>" id="">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-support1-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.support')); ?></div>
                </a> </li>
              <li class="nav-item"> <a class="nav-link <?php echo $page == 7 ? 'active' : ' ' ?>" href="<?php echo url('bankwire/USD'); ?>" id="">
                <div class="select-arrow"></div>
                <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-support1-icon.png"></div>
                <div class="tab-link-txt"><?php echo e(trans('app_lang.bankwire')); ?></div>
                </a>
              </li>
            </ul>
            <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap right-nav-links dsb-header-tab ">
              <li class="nav-item notify_msg"> <i class="fa fa-bullhorn" aria-hidden="true"></i>
                  <div class="notification">
                  <h3>News</h3>
                  <div class="notify-content">
                      <div class="notify_blk">
                      <?php                
                            foreach($news as $new) { 
                              $d = strtotime($new->updated_at);
                              //$url = URL::to('/newsdetails/'.$new->id);
                              $url = Config::get('domain.url').'newsdetails/'.$new->id;
                              ?>
                      <p><a href="<?php echo $url;?>">【Announcement】<?php echo news_lang_content('0',session('language'),$new->id);?> on <?php echo date("d/m/Y", $d);?></a></p>
                      <?php }  ?>
                    </div>
                    </div>
                  <div class="notify-footer"> 
                    <a href="<?php echo Config::get('domain.url').'news' ?>">View all</a>
                  </div>
                </div>
              </li>
              
                <?php
                $p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
                ?>
                <li class="nav-item dashboard-header-links dash-header-icon-link dash-noti">
                    <div class="dropdown"> <a href="javascript:;" onclick="shownotify1()">
                      <button class="profile-dd dropdown-toggle" style="cursor: pointer"> <img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-header-notification-icon.png" class="img-fluid"  title="Notification" id="notif_icon"> </button>
                      </a> </div>
                    <div class="notif-counter"><?php echo notification_list(); ?></div>
                  </li>
                
                <li class="nav-item dashboard-header-links">
                    <div class="dropdown right-dd">
                    <button type="button" class="profile-dd dropdown-toggle" data-toggle="dropdown">
                      <?php $name = session::get('tmaitb_profile');
                      if ($name) {
                        ?>
                      <div class="header-profile-icon">
                      <?php
                      if ($user->profile == '') {?>
                      <img src="<?php echo e(asset('/').('public/assets/images/')); ?>avatar.png" class="header-profile-icon">
                      <?php } else {?>
                      <img src="<?php echo e($user->profile); ?>" class="header-profile-icon">
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
                        <a class="" href="<?php echo url('logout'); ?>"><?php echo e(trans('app_lang.logout')); ?></a> </div>
                        <ul>
                        <li class="drop_new_blk"> <a class="dropdown-item" href="<?php echo url('dashboard'); ?>"> <img src="<?php echo e(asset('/').('public/assets/images/')); ?>men_img.png"> <?php echo e(trans('app_lang.dashboard')); ?></a> </li>
                      </ul>
                      </div>
                  </div>
                  </li>
            </ul>
        </div>
    </div>
  </nav>
</div>
</div>

<div id="mySidenav" class="sidenav"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" id="close-click">&times;</a>
    <ul class="col-xs-12 nav nav-tabs dashboard-tabs">
    <li class="nav-item"> <a class="nav-link " href="<?php echo url('dashboard'); ?>" id="overview-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-overview-icon.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.overview')); ?></div>
      </a> </li>
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('buy-sell'); ?>" id="exchange-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-exchange-icon.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.exchange')); ?></div>
      </a> </li>

       <li class="nav-item"> <a class="nav-link" href="<?php echo url('trade'); ?>" id="exchange-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-exchange-icon.png"></div>
      <div class="tab-link-txt">Trade</div>
      </a> </li>


    <li class="nav-item"> <a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="overview-link"> <?php echo e(trans('app_lang.api')); ?></a> </li>
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('advance_trade'); ?>" id="exchange-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>advanced-trade.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.advance_trade')); ?></div>
      </a> </li>
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('funds'); ?>" id="funds-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-funds-icon.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.funds')); ?></div>
      </a> </li>
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('referral'); ?>" id="referals-link">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-referal-icon.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.refer')); ?></div>
      </a> </li>
    
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('support'); ?>" id="settings-profile-tab">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-support1-icon.png"></div>
      <div class="tab-link-txt"><?php echo e(trans('app_lang.support')); ?></div>
      </a> </li>
    <li class="nav-item"> <a class="nav-link" href="<?php echo url('bankwire/USD'); ?>" id="settings-profile-tab">
      <div class="tab-link-icon"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-support1-icon.png"></div>
      <div class="tab-link-txt">Bankwire</div>
      </a> </li>
  </ul>
  </div>
<div class="mobile-menu-cnt"><span onclick="openNav()" class="mobile-menu"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>white-menu.png">Tab Menu</span></div>
<?php if (!isset($page)) {$page = '';}?>

<?php if($page == 6) { ?>
<div class="container support-tabs-cnt">
    <?php } ?>
    <div class="container dashboard-tabs-cnt">
    <div class=""> 
        
        
        <div class=""> 
        
        <?php if($editprofile == 0): ?>
        <?php echo $__env->make($viewsource, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
        
        <?php if($editprofile == 1): ?> 
        
        <?php $session_name = Session::get('tmaitb_profile');?>
        <div class="container dashboard-tabs-cnt inner-sec-top-menu">
   

       

        
        </div>
        <div class="tab-pane container no-padding active min-height-cnt" id="settings-profile">
            
            <div class="row">
            <div class="col-xs-12 col-sm-12 card-div">
                <div class="st-box">
              
                  
              </div>
              </div>
            <?php echo $__env->make($vieweditsource, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </div>
          </div>
        <?php endif; ?>
        
        <?php if($editprofile == 2): ?>
        
        <?php echo $__env->make($vieweditsource, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        
        <?php endif; ?> </div>
      </div>
  </div>
    <?php if($page == 6) { ?>
  </div>
<?php } ?>
<?php echo $__env->make('front.common.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script src="<?php echo e(asset('/').('public/assets/js/jquery.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/popper.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/bootstrap.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/bootstrap-datepicker.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/viewportchecker.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/script.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/fileinput.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/jquery.validate.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/additional-methods.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/notifIt.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/jquery.dataTables.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/')); ?>dataTables.responsive.min.js"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/')); ?>dataTables.buttons.min.js"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/')); ?>pdfmake.min.js"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/')); ?>vfs_fonts.js"></script> 


<script src="<?php echo e(asset('/').('public/assets/js/diacritics.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/bootstrap-dropdown-filter.js')); ?>"></script> 

<script src="<?php echo e(asset('/').('public/assets/js/')); ?>buttons.html5.min.js"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/')); ?>chosen.jquery.js"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/jquery.mCustomScrollbar.concat.min.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/jquery-ui.js')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/build/js/')); ?>intlTelInput.js"></script> 
<script type="text/javascript">
  siteurl = "<?php echo e(URL::to('/')); ?>";
  user_id = '<?php echo Session::has('tmaitb_user_id') ? Session::get('tmaitb_user_id') : '0 '; ?>';
</script> 
<script src="<?php echo e(asset('/').('public/assets/js/custom_script.js')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>"></script> 
<script src="<?php echo e(asset('/').('public/assets/js/common_script.js')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>"></script> 
<script type="text/javascript">
	var currencyArray = '<?php echo isset($allcurr) ? json_encode($allcurr) : ''; ?>';
	if(currencyArray== '')
		currencyArray = '';
	else
		currencyArray = $.parseJSON(currencyArray);
				
</script>

<script type="text/javascript">
var SITE_URL = "<?php echo e(url('/')); ?>";
</script>
<script src="<?php echo e(asset('/').('public/assets/js/asdfksdowlslslsl.js')); ?>"></script> 

<script src="<?php echo e(asset('/').('public/assets/js/transaction_script.js')); ?>?<?php echo e(date('Y-m-d h:i:s')); ?>"></script> 
<script>
setTimeout(function () {
        document.getElementById("load").style.display = "none";
        $('body').removeClass('load_site');
}, 2000);

$( function() {
	var date = new Date();
	var currentMonth = date.getMonth();
	var currentDate = date.getDate();
	var currentYear = date.getFullYear();
	$( "#datepicker" ).datepicker({
		dateFormat : 'dd/mm/yy',
		changeMonth : true,
		maxDate: new Date(currentYear -18, currentMonth, currentDate),
		changeYear : true,
		defaultDate : "-18Y",
		yearRange: "-80:-18",
	});
  } );

$('#settings-profile-link').on('click', function(){
  $('.nav-tabs a[href="#settings-profile"]').click();
});



function openNav() {
    document.getElementById("mySidenav").style.width = "250px";

}


function closeNav() {
    document.getElementById("mySidenav").style.width = "0";

}
$('.nav-link').on('click', function(){
  $('#close-click').click();
});

$(document).ready(function() {
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
$( function() {
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
} );

</script> 
<script>
var only_files_allow = "<?php echo e(trans('app_lang.only_files')); ?>";
var profile_details  = "<?php echo e(trans('app_lang.fill_your_profile_details')); ?>";

  (function ($) {
	$(window).on("load", function () {
    var funds = '<?php echo isset($dashboard); ?>';
    var pairid = '<?php echo $pairid; ?>';
    if(funds)
      showMarketTab(1,pairid);

	  var width = $(window).width();

	  if (width > 1024) {
		//    big screen
		$(".tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .notify-table-ht, .chat-row-cnt, .ht-283, .tb-174, .tb-84").mCustomScrollbar({
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
		$(".tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .notify-table-ht, .chat-row-cnt, .ht-283, .tb-174, .tb-84").mCustomScrollbar({
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
    });
  }
  function shownotify(){
  	notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+profile_details+" ", type: "error" });
  }

  function shownotify1(){
  	var ses = "<?php echo Session::get('tmaitb_profile'); ?>";
  	if(ses == ' ')
       notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+profile_details+" ", type: "error" });
  	else
  		window.location.href = "<?php echo e(url('/notification_list')); ?>";
  		//window.scrollBy(0, 50);
    }


	$(".trans_select").chosen();
function readURL(input,id) {

     var validExtensions = ['jpg','png','jpeg'];
     var fileName = input.files[0].name;

     var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);

     if ($.inArray(fileNameExt, validExtensions) == -1) {
     	    $("#"+id).show();
     	    $("#"+id).html(only_files_allow);
     	    $('.'+id).hide();
            return false;
        }

	 if (input.files && input.files[0]) {
	 var reader = new FileReader();
	 reader.onload = function(e) {

	 $('#'+id).hide();
	 $('.'+id).show();
	 $('.'+id).attr('src', e.target.result);
	 }

	 reader.readAsDataURL(input.files[0]);
	 }
 }
 function showimage_edit(e,id){
	 readURL(e,id);
 }
  $('#from_date').datepicker({
         autoclose: true,
	     dateFormat: 'yy-mm-dd',
         endDate: new Date()
  });
  $('#to_date').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });
   $('#from_date_withdraw').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });
  $('#to_date_withdraw').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });

   $('#from_date_fiat_withdraw').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });
  $('#to_date_fiat_withdraw').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });

   $('#from_fiat_date').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });
  $('#to_fiat_date').datepicker({
     autoclose: true,
     dateFormat: 'yy-mm-dd',
     endDate: new Date()
  });


function checkKyc(){
	
	window.location.href = "<?php echo e(URL::to('/dashboard?name=verification')); ?>";
	var msgs = '<?php echo trans("app_lang.verify_kyc") ?>';
	notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+msgs, type: "error" });

}


</script> 
<script>

 	$(".ht-216, .ht-195").mCustomScrollbar({
		  scrollButtons: {
			enable: false
		  },

		  scrollbarPosition: 'inside',
		  autoExpandScrollbar: true,
		  theme: 'minimal-dark',
		  axis: "y",
		  setWidth: "auto",
		});


var chatbox = $(".message-cnt-ht");
for(var si=0; si<chatbox.length;si++){
	
chatbox.mCustomScrollbar({
scrollButtons: {
enable: false
},

scrollbarPosition: 'inside',
autoExpandScrollbar: true,
theme: 'dark',
axis: function(){ if(width > 768) { return "y"; } else { return "x"; } },
setWidth: "auto",
setTop:'360px'
});
}
$('.mb-0 [data-toggle="collapse"]').click(function(){
	setTimeout(function(){ $('.message-cnt-ht').mCustomScrollbar('scrollTo','bottom'); },500);
});

 </script> 
<script>
 	var width = $(window).width();

	  if (width > 576) {
		
		$(".ht-290").mCustomScrollbar({
		  scrollButtons: {
			enable: false
		  },

		  scrollbarPosition: 'inside',
		  autoExpandScrollbar: true,
		  theme: 'minimal-dark',
		  axis: "y",
		  setWidth: "auto"
		});

	  }
 </script> 
<script>

	$('table#deposit_history_tbl').wrap('<div class="table-responsive"></div>');
	$('table#withdraw_history_tbl').wrap('<div class="table-responsive"></div>');

	$('.new_Btn').bind("click" , function () {
        $('.html_btn').click();
    });

	
	$('.coin-dd .coin-dd-menu a.coin-dd-link').click(function(){
	    $('.coin-dd-menu').removeClass('show');
	});

	</script> 
<script>
		$(function () { $("body").on('click keypress mousemove', function () { ResetThisSession(); }); });
		var timeInSecondsAfterSessionOut = 600;
		var secondTick = 0; 
		function ResetThisSession() 
		{ 
			secondTick = 0; 
		}
		function StartThisSessionTimer() 
		{ 
    		secondTick++;
  			var timeLeft = ((timeInSecondsAfterSessionOut - secondTick) / 60).toFixed(0); // in minutes
  			timeLeft = timeInSecondsAfterSessionOut - secondTick;
  			//alert(timeLeft);
  			if (secondTick > timeInSecondsAfterSessionOut) 
    		{ 
      			clearTimeout(tick); 
     			window.location = "<?php echo e(URL::to('/logout')); ?>"; 
    		}
			tick = setTimeout("StartThisSessionTimer()", 1000); 
  		} StartThisSessionTimer();

		$(window).on("load", function(){
 		   	var session_id ="<?php echo Session::get('tmaitb_user_id');?>";
			
			function autologout()
			 {
			 	var link = "<?php echo e(URL::to('/autologout')); ?>";
				$.ajax({
				    type:'POST',
				    url: link,
				  	success:function(output) 
				    {
				        if(output==2)
				        {
				           location.reload();
				       	}                         
				    }
			  	});
			}
		});
	</script> 
<script src="<?php echo e(asset('/').('public/assets/js/jquery.sticky.js')); ?>"></script> 
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