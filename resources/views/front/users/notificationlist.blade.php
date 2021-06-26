<div class="row notify_page">

<div class="card-div tab-content col-xs-12 col-sm-12">
<div class="col-xs-12 col-sm-12 breadcrumb"></div>
<div class="tab-pane container no-padding active notifycontent" id="notify">
<div class="row">
	<div class="col-xs-12 col-sm-12 card-div">
		
		<div class="card-div-cnt">
			<h3>{{trans('app_lang.notification') }}</h3>
			<div class="table-responsive dsb-wallet-table notify-table">
				<table class="table table-hover table-borderless">
					<thead>
					  <tr>
						<th class="table-sno-cnt">#</th>
						<th>{{trans('app_lang.content') }}</th>
						<th>{{trans('app_lang.date_time') }}</th>
					  </tr>
					</thead>
					<tbody class="notify-table-ht">
						<?php
$i = 0;
foreach ($notification as $list) {
	$i++;
	?>
						<tr>
							<td class="table-sno-cnt"><?php echo $i; ?></td>
							<td>

					<?php
$str = explode("-", $list['message']);

	if ($list['message'] == 'You have updated your kyc details') {
		echo trans('app_lang.you_have_update_kyc_details');
	} else if ($list['message'] == 'You have updated your profile details') {
		echo trans('app_lang.you_have_update_profile_details');
	} else if ($list['message'] == 'selfie Proof has been Rejected by Admin.Submit Valid Proof') {
		echo trans('app_lang.selfie_proof_rejected_admin');
	} else if ($list['message'] == 'Id Proof has been Rejected by Admin.Submit Valid Proof') {
		echo trans('app_lang.id_proof_rejected_admin');
	} else if ($list['message'] == 'You have activated 2FA status') {
		echo trans('app_lang.activated_2FA_status');
	} else if ($list['message'] == 'You have deactivated 2FA status') {
		echo trans('app_lang.deactivated_2FA_status');
	} else if ($list['message'] == 'You have changed your password') {
		echo trans('app_lang.changed_your_password');
	} else if ($list['message'] == 'selfie Proof has been Verified by Admin.') {
		echo trans('app_lang.selfie_proof_verified_admin');
	} else if ($list['message'] == 'Id Proof has been Verified by Admin.') {
		echo trans('app_lang.id_proof_verified_admin');
	} else if ($list['message'] == 'You have requested a withdraw resend link') {
		echo trans('app_lang.withdraw_resend_link');
	} else if ($list['message'] == 'You have updated your Passcode') {
		echo trans('app_lang.update_passcode');
	} else if ($list['message'] == 'You have try to new device login,If you not please contact to support') {
		echo trans('app_lang.new_device_login');
	} else if ($str[0] == 'You have added support ticket TKT') {
		echo trans('app_lang.added_support_ticket') . $str[1];
	} else if ($str[0] == 'You have updated support ticket TKT') {
		echo trans('app_lang.updated_support_ticket') . $str[1];
	} else if ($str[0] == 'You have added a withdraw request for ') {
		echo trans('app_lang.added_withdraw_request') . $str[1];
	} else if ($str[0] == 'You have cancelled your withdraw request for ') {
		echo trans('app_lang.cancelled_withdraw_request') . $str[1];
	} else if ($str[0] == 'Withdraw request completed transaction hash is ') {
		echo trans('app_lang.withdraw_requested_completed_transaction') . $str[1];
	} else if ($str[0] == 'You have updated your ticket details TKT') {
		echo trans('app_lang.update_ticket_details') . $str[1];
	} else if ($str[0] == 'Admin cancelled your withdraw request for') {
		echo trans('app_lang.fiat_withdraw_cancel') . $str[1];
	}else {
		echo $list['message'];
	}

	?>

							</td>
							<td><?php
	

	echo $list['updated_at'];
	?>
							</td>
						</tr>
						<?php
}?>
					</tbody>
				  </table>
			  </div>
		</div>
	</div>

</div>
</div>
</div>
</div>