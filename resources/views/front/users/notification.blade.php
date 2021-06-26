<div class="return">
		<div class="returnBox"><a href="<?php echo url('dashboard'); ?>" class="color-theme"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></i>
       {{trans('app_lang.back_to') }} {{trans('app_lang.account') }}
    </a> <p class="titled color-primary">{{trans('app_lang.notification') }}</p>
</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 card-div align-self-stretch d-flex xl-p-l lg-p-l md-p-l notify_new">
<div class="settings-security-cnt-height">
<div class="row">

	<div class="col-xs-12 col-sm-12 setting-security-subheading">{{trans('app_lang.request_email_notf') }}</div>
	<div class="col-xs-12 col-sm-12 security-notif-row-cnt">
	
		<div class="col-xs-12 col-sm-12 security-notif-row d-flex">
			<span>{{trans('app_lang.2fa') }}</span>
			<label class="switch ml-auto">
			  <input type="checkbox" <?php echo $user->tfa ? 'checked' : ''; ?> onclick="notification('2fa')">
			  <span class="slider round"></span>
			</label>
		</div>
		<div class="col-xs-12 col-sm-12 security-notif-row d-flex">
			<span>{{trans('app_lang.for_change_password') }}</span>
			<label class="switch ml-auto">
			  <input type="checkbox" <?php echo $user->change_password ? 'checked' : ''; ?>  onclick="notification('password')">
			  <span class="slider round"></span>
			</label>
		</div>
		<div class="col-xs-12 col-sm-12 security-notif-row d-flex">
			<span>{{trans('app_lang.for_new_device') }}</span>
			<label class="switch ml-auto">
			  <input type="checkbox" <?php echo $user->new_device_login ? 'checked' : ''; ?>  onclick="notification('device')">
			  <span class="slider round"></span>
			</label>
		</div>

	</div>
</div>

</div>
</div>
<script type="text/javascript">
  // Notification Validation
  var success_notfy ="{{trans('app_lang.notification_update') }}";
  var success_notfy_dis ="{{trans('app_lang.notification_update_dis') }}";

  var error_notfy ="{{trans('app_lang.please_try_again') }}";

  var trade_msg    = "{{trans('app_lang.trade_msg') }}";
  var tfa_msg      = "{{trans('app_lang.tfa_msg') }}";
  var password_msg = "{{trans('app_lang.pwd_msg') }}";
  var device_msg   = "{{trans('app_lang.device_msg') }}";
</script>