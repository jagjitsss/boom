
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	if (headers_sent()) {
		foreach (headers_list() as $header) {
			header_remove($header);
		}

	}


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
	<link rel="stylesheet" href="{{asset('/').('public/assets/css/jquery-ui.css')}}">
	<link rel="stylesheet" href="{{asset('/').('public/assets/css/notifIt.css')}}">

	<link href="{{asset('/').('public/assets/css/')}}jquery.mCustomScrollbar.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="dsb-body lt load_site">
	<div class="loader-bg" id="load">
		<div class="loader">
			<img src="{{asset('/').('public/assets/images/')}}loader.gif" ></div>
		</div>

		<nav class="navbar navbar-expand-md justify-content-center index-header dashboard-header">

			<a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>">
				
				<input id="logo" value="<?php echo $site->site_logo;?>" style="display:none;">
				<input id="tradelogo" value="<?php echo $site->tradesite_logo;?>" style="display:none;">
				
				<img id="tlogo" src="{{$site->site_logo}}" class="img-fluid tlogo">
			</a>
			</a>
			<button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
				<span class="navbar-toggler-icon fa fa-fw fa-align-justify"></span>
			</button>
			<div class="container-fluid">
				<div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
				<div class="card-div-cnt chart_new_blk">
								<div class="dropdown exc-dd">
									
								  <button type="button" class="exc-topbar-dd dropdown-toggle" data-toggle="dropdown">
									<span class="exc-dd-heading cur_pair">
									</span>
								  </button>
								 
								  <div class="dropdown-menu">
								   @foreach($pairs as $pair)
								  	<a class="dropdown-item" href="{{url('trade')}}/{{$pair->to_symbol}}_{{$pair->from_symbol}}">{{$pair->to_symbol}}/{{$pair->from_symbol}}</a>
									@endforeach
								  </div>
								</div>
								
								<div class="exc-data-container">
									<div class="d-flex exc-data-m">
										<div class="exc-data-inn">
											<div class="exc-data-cnt">
												<span class="lgtxt ">{{trans('app_lang.last_price') }}</span>
												<span class="lastprice"></span>
											</div>
											<div class="exc-data-cnt">
												<span class="lgtxt">24h {{trans('app_lang.change') }}</span>
												<span class="change" ></span>
											</div>
										</div>
										<div class="exc-data-inn">
											<div class="exc-data-cnt">
												<span class="lgtxt">24h {{trans('app_lang.high') }}</span>
												<span class="high"></span>
											</div>
											<div class="exc-data-cnt">
												<span class="lgtxt">24h {{trans('app_lang.low') }}</span>
												<span class="low"></span>
											</div>
										</div>
										<div class="exc-data-inn exc-data-inn-last">
											<div class="exc-data-cnt">
												<span class="lgtxt">24h {{trans('app_lang.volume') }}  (<span class="from_cur"></span>)</span>
												<span class="volume"></span>
											</div>
											<div class="exc-data-cnt">
												<span class="lgtxt">24h {{trans('app_lang.volume') }}  (<span class="to_cur"></span>)</span>
												<span class="volume"></span>
											</div>
										</div>
									</div>
								</div>
							</div>	
					<ul class="col-xs-12 nav nav-tabs dashboard-tabs head_menu">
						<?php if (Session::has('tmaitb_user_id')) { ?>
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="overview-link">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-overview-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.overview') }}</div>
								</a>
							</li>


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

							<li class="nav-item">
								<a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="funds-link">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.api') }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 3 ? 'active' : ' ' ?>" href="<?php echo url('funds'); ?>" id="funds-link">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.funds') }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 4 ? 'active' : ' ' ?>"  href="<?php echo url('referral'); ?>" id="referals-link">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-referal-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.refer') }}</div>
								</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 6 ? 'active' : ' ' ?>" href="<?php echo url('support'); ?>" id="">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.support') }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 7 ? 'active' : ' ' ?>" href="<?php echo url('bankwire'); ?>/USD" id="">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.bankwire') }}</div>
								</a>
							</li>

							<?php } else { ?>
							
							<li class="nav-item">
            <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('trade')}}" id="funds-link">
              <div class="tab-link-txt">{{trans('app_lang.exchange') }}</div>
            </a>
          </li>
							
							<li class="nav-item">
								<a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="funds-link">
									<div class="select-arrow"></div>
									<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
									<div class="tab-link-txt">{{trans('app_lang.api') }}</div>
								</a>
							</li>
							<?php } ?>

					</ul>

						
						<ul class="nav navbar-nav flex-row justify-content-center flex-nowrap right-nav-links dsb-header-tab">
							

							<?php if (Session::has('tmaitb_user_id')) {
								?>
								

								<?php
								$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
								?>
								
								<li class="nav-item notify_msg">
									<i class="fa fa-bullhorn" aria-hidden="true"></i>
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
											<div class="notify-footer">
												<a href="<?php echo Config::get('domain.url').'news' ?>">View all</a>
											</div>
										</div>
									</li>
									
											
											<li class="nav-item dashboard-header-links dash-header-icon-link">
												<div class="dropdown">
													
													<a href="{{url('/notification_list')}}" onclick="view_notf('<?php echo $p; ?>')"><button  class="profile-dd dropdown-toggle" style="cursor: pointer">
														<div class="notif-icon" id="notif_icon" title="Notification"></div>
														
													</button></a>
												</div>
												<div class="notif-counter"><?php echo notification_list(); ?></div>
											</li>

											

											

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
																<div class="header-profile-name"><?php
																echo $name ? $name : 'Hi ';
																?></div><a class="" href="<?php echo url('logout'); ?>">{{trans('app_lang.logout') }}</a>
															</div>
															<ul>
																<li class="drop_new_blk">
																	
																	<a class="dropdown-item" href="<?php echo url('dashboard'); ?>"> <img src="{{asset('/').('public/assets/images/')}}men_img.png"> {{trans('app_lang.dashboard') }}</a>
																</li>
															</ul>
														</div>
													</div>
												</li>
												<li><div class="exc-head-icon-cnt">
									<a href="javascript:;" onclick="change_theme('dark')"><img id="lighticon" src="{{asset('/').('public/assets/images/')}}light_bg.png" class="img-fluid"></a>
									<a href="javascript:;" onclick="change_theme('light')"><img id="darkicon" src="{{asset('/').('public/assets/images/')}}dark_bg.png" class="img-fluid"></a>
								</div>	
																</li>
												<?php } else {?>
												<li class="nav-item">
													<a class="nav-link dashboard-header-links dash-header-icon-link dash-header-txt-link index-header-link" href="<?php echo url('login'); ?>">{{trans('app_lang.login') }}</a>
												</li>
												<li class="nav-item">
													<a class="nav-link dashboard-header-links dash-header-icon-link dash-header-txt-link index-header-link nav-register" href="<?php echo url('register'); ?>">{{trans('app_lang.signup') }}</a>
												</li>
												
														<li class="nav-item notify_msg">
															<i class="fa fa-bullhorn" aria-hidden="true"></i>
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
																	<div class="notify-footer">
																		<a href="<?php echo Config::get('domain.url').'news' ?>">View all</a>
																	</div>
																</div>
															</li>
															<li>
																

															


<?php }?>
</ul>
</div>
</div>
</nav>


											<div id="mySidenav" class="sidenav">
												<a href="javascript:void(0)" class="closebtn" onclick="closeNav()" id="close-click">&times;</a>
												<ul class="col-xs-12 nav nav-tabs dashboard-tabs">
													<li class="nav-item">
														<a class="nav-link" href="<?php echo url('advance_trade'); ?>" id="exchange-link">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}advanced-trade.png"></div>
															<div class="tab-link-txt">Advance Trade</div>
														</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" href="<?php echo url('funds'); ?>" id="funds-link">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
															<div class="tab-link-txt">Funds</div>
														</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" href="<?php echo url('referral'); ?>" id="referals-link">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-referal-icon.png"></div>
															<div class="tab-link-txt">Referrals</div>
														</a>
													</li>
							<li class="nav-item" style="display:none;">
														<a class="nav-link" href="<?php echo url('addCoins'); ?>" id="">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-coins-icon.png"></div>
															<div class="tab-link-txt">Add Coins</div>
														</a>
													</li>
													<li class="nav-item">
														<?php 
														if(Session::has('tmaitb_user_id'))
														{
														?>
														<a class="nav-link" href="<?php echo url('login'); ?>" id="settings-profile-tab">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
															<div class="tab-link-txt">Support</div>
														</a>
														<?php
														}
														else
														{
														?>
														<a class="nav-link" href="<?php echo url('support'); ?>" id="settings-profile-tab">
															<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
															<div class="tab-link-txt">Support</div>
														</a>

															<?php 
														}
														?>
														
													</li>
												</ul>
											</div>
											<?php if (Session::has('tmaitb_user_id')) { ?>
												<div class="mobile-menu-cnt"><span onclick="openNav()" class="mobile-menu"><img src="{{asset('/').('public/assets/images/')}}white-menu.png">Tab Menu</span></div>
												<?php } ?>
												<?php if (!isset($page)) {$page = '';}?>


												<div class="trade_container">
													<div class="">

														@include($viewsource)


																		</div>
																	</div>    
																</div>
															</div>

															<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
															<script src="{{asset('/').('public/assets/js/popper.min.js')}}"></script>
															<script src="{{asset('/').('public/assets/js/bootstrap.min.js')}}"></script>

															<script src="{{asset('/').('public/assets/js/viewportchecker.js')}}"></script>
															<script src="{{asset('/').('public/assets/js/script.js')}}"></script>
															<script src="{{asset('/').('public/assets/js/notifIt.min.js')}}"></script>

															<script src="{{asset('/').('public/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
															<script src="{{asset('/').('public/assets/js/jquery-ui.js')}}"></script>
															<script type="text/javascript">
																siteurl = "{{URL::to('/')}}";
																user_id = '<?php echo Session::has('tmaitb_user_id') ? Session::get('tmaitb_user_id') : '0 '; ?>';
																update_user_id = 0;
																if(user_id != '0'){
																	update_user_id = '<?php echo insep_encode(Session::get("tmaitb_user_id")) ?>';
																}
																library_path = "{{ asset('/').('public/charting_library/') }}";
															</script>
															<script type="text/javascript" src="{{asset('/').('public/charting_library/charting_library.min.js')}}"></script>
															<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/polyfills.js') }}"></script>
															<script type="text/javascript" src="{{asset('/').('public/datafeeds/udf/dist/bundle.js') }}"></script>
															<script type="text/javascript" src="{{asset('/').('public/assets/js/socket.io.min.js') }}"></script>
															<script src="{{asset('/').('public/assets/js/tradescript.js')}}?{{date('Y-m-d h:i:s')}}"></script>
															<script>
																setTimeout(function () {
																	document.getElementById("load").style.display = "none";
																	$('body').removeClass('load_site');
																}, 2000);

																
																var siteurl = "{{URL::to('/')}}";
																var theme = localStorage.getItem('trade_theme');
																var img = "";
																var logo = $('#logo').val();
																var tradelogo = $('#tradelogo').val();
																if(theme != '' && theme =='dark'){
																	document.body.className += ' ' + 'dark-theme';
    	//$('#tlogo').attr('src',img+tradelogo);
    	$('#tlogo').attr('src',logo);
    	$('#lighticon').hide();
    	$('#darkicon').show();
    }
    else{
    	localStorage.setItem('trade_theme','light');
    	$('#tlogo').attr('src',logo);
    	$('#lighticon').show();
    	$('#darkicon').hide();
    }
    $('#lighticon').click(function(){
    	$('#tlogo').attr('src',logo);
    });
    $('#darkicon').click(function(){
    	//$('#tlogo').attr('src',img+tradelogo);
    	$('#tlogo').attr('src',logo);
    });



    $(document).ready(function() {
    	pairData = {};
    	getPairdetails('{{$pair_symbol}}',8);
    	displayChart('{{$pair_symbol}}');
    	showBalance();
    	showMarket();
    	
    	$('[data-toggle="tooltip"]').tooltip();
    	<?php if (Session::has('success')) {?>
    		var sucess= "{{ Session::get('success') }}";
    		notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+sucess, type: "success" });
    		<?php }?>
    		<?php if (session()->has('error')) {?>
    			var error= "{{ Session::get('error') }}";
    			notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" "+error, type: "error" });
    			<?php }?>
    		});

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
	(function ($) {
		$(window).on("load", function () {

			var width = $(window).width();

			if (width > 1024) {
		
		$("#openOrdersTable,.wallet_tab,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .notify-table-ht, .chat-row-cnt").mCustomScrollbar({
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
		$("#openOrdersTable,.wallet_tab,.buyOrdersTable,.sellOrdersTable,#myTradeHistory,#myTradeTable,#tradeHistory,#stopOrdersTable,.tb-265, .tb-289, .tb-299, .tb-357, .tb-1170, .notify-table-ht, .chat-row-cnt").mCustomScrollbar({
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




<div class="modal fade" id="trLogin" role="dialog">
	<div class="modal-dialog">


		<div class="modal-content">

			<div class="modal-body">
				<p>Please <a href="{{URL::to('/login')}}">login</a> to continue</p>
			</div>

		</div>

	</div>
</div>
</body>
</html>