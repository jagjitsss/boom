<?php if (Session::has('tmaitb_user_id')) {?>
<div id="mySidenav" class="sidenav">
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()" id="close-click">&times;</a>
<ul class="col-xs-12 col-sm-1 nav nav-tabs dashboard-tabs adv-sidebar">
  <li class="nav-item">
	<a class="nav-link " href="{{url('/dashboard')}}" id="overview-link">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-overview-icon.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.overview') }}</div>
	</a>
  </li>
  <li class="nav-item">
	<a class="nav-link" href="{{url('/trade')}}" id="exchange-link">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-exchange-icon.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.exchange') }}</div>
	</a>
  </li>
  <li class="nav-item">
	<a class="nav-link active" href="<?php echo url('advance_trade'); ?>" id="exchange-link">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}advanced-trade.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.advance_trade') }}</div>
	</a>
  </li>
  <li class="nav-item">
	<a class="nav-link" href="{{url('/funds')}}" id="funds-link">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.funds') }}</div>
	</a>
  </li>
  <li class="nav-item">
	<a class="nav-link" href="{{url('/referral')}}" id="referals-link">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-referal-icon.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.refer') }}</div>
	</a>
  </li>
  <li class="nav-item" style="display:none;">
	<a class="nav-link" href="{{url('/addCoins')}}" id="">
		<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-coins-icon.png"></div>
		<div class="tab-link-txt">{{trans('app_lang.add_coin') }}</div>
	</a>
  </li>
   <li class="nav-item">
			<a class="nav-link" href="<?php echo url('support'); ?>" id="">
				<div class="select-arrow"></div>
				<div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
				<div class="tab-link-txt">{{trans('app_lang.support') }}</div>
			</a>
		  </li>
  <li class="nav-item invisible-link">
	<a class="nav-link" href="{{url('/editprofile')}}" id="settings-profile-tab">
		<div class="tab-link-txt">{{trans('app_lang.setting_profile') }}</div>
	</a>
  </li>
<li class="nav-item invisible-link">
	<a class="nav-link" href="{{url('/support')}}" id="settings-profile-tab">
		<div class="tab-link-txt">{{trans('app_lang.support') }}</div>
	</a>
  </li>
  <li class="nav-item invisible-link">
	<a class="nav-link" href="{{url('/bankwire/USD')}}" id="settings-profile-tab">
		<div class="tab-link-txt">{{trans('app_lang.bankwire') }}</div>
	</a>
  </li>
</ul>
</div>
<?php }?>