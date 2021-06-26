<div class="tab-pane container no-padding active" id="referrals">
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12 card-div xl-p-l lg-p-l ref_top_blk">
					<div class="card-div-cnts">
						<div class="referal-pgm-right-cnt">
							<div class="referal-id-cnt"><p class="ur_id"><?php echo e(trans('app_lang.your_id')); ?> ： </p><span><?php echo e($user->referrer_name); ?></span></div>
							
							<div class="referal-link-cnt">
								<div class="referal-link-label"><?php echo e(trans('app_lang.referral_link')); ?> :</div>
								<div class="referal-link-txtbox">
									<input id="ref_link" type="text" placeholder="<?php echo e(trans('app_lang.referral_link')); ?>" readonly value="<?php  echo url('register/'.$user->referrer_name); ?>">
									<a href="javascript:;" onclick="copy_text_tag('ref_link');return false;" class="referal-link-copy"><?php echo e(trans('app_lang.copy_click')); ?></a>
								</div>
								<span class="or_blk">or</span>
								<div class="referal-pgm-right-cnt referal-pgm-right-cnts">
					<?php echo Form::open(array('id'=>'referral','url'=>'referral','method'=>'POST')); ?>

							<div class="referal-link-cnt">
								
								<div class="referal-link-txtbox">
									<input type="email" name="referral_email" required placeholder="<?php echo e(trans('app_lang.email_enter_address')); ?>">
									<button type="submit" class="dsb-blue-btn referal-link-copy" id="submitBtnref"><?php echo e(trans('app_lang.send')); ?></button>
								</div>
								
							</div>
							
							<?php echo Form::close(); ?>

						</div>
							</div>
							</div>
							</div>
		
					<div class="fR-l"><p class="marginNo text-center"><img src="public/assets/images/invite_img.png"> <span class="ts-20"><?php echo $refercount; ?></span></p> <p class="ts-12 marginNo" style="color: rgb(93, 108, 122);"><?php echo e(trans('app_lang.invite')); ?></p></div>
				</div>
				

				<div class="col-xs-12 col-md-12 col-lg-12 card-div align-content-stretch ref_get_link" id="referalinfo">
					<div class="card-div-cnts">
						<div class="referal-pgm-left-cnt clearfix">
							<div class="referal-pgm-left-row">
								<div class="referal-pgm-icon-cnt"><span class="referal-pgm-icon-num">1</span></div>
								<div class="referal-pgm-left-txt-cnt">
									<div class="referal-pgm-left-txt"><?php echo e(trans('app_lang.step_01')); ?></div>
								</div>
							</div>
							<div class="referal-pgm-left-row">
								<div class="referal-pgm-icon-cnt"><span class="referal-pgm-icon-num">2</span></div>
								<div class="referal-pgm-left-txt-cnt">
									<div class="referal-pgm-left-txt"><?php echo e(trans('app_lang.step_02')); ?></div>
								</div>
							</div>
							<div class="referal-pgm-left-row">
								<div class="referal-pgm-icon-cnt"><span class="referal-pgm-icon-num">3</span></div>
								<div class="referal-pgm-left-txt-cnt">
									<div class="referal-pgm-left-txt"><?php echo e(trans('app_lang.step_03')); ?></div>
								</div>
							</div>
						</div>
						<a class="close"><i class="fa fa-times" id="closediv" aria-hidden="true"></i></a>
					</div>
				</div>
				
				<div class="col-xs-12 col-md-12 col-lg-12 card-div ref_cont no-padding">
					<div class="card-div-cnt">
						<ul class="nav nav-tabs funds-history-tabs referral-tab">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" data-target="#refer_history" href="javascript:;"><?php echo e(trans('app_lang.referral_list')); ?></a>
							</li>
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane container active" id="deposit-history">
								<div class="table-responsive dsb-transaction-table">

									<div class="tab-content">
									  <div class="tab-pane container active" id="refer_history">
										<table id="ref" class="table table-hover table-borderless">
											<thead>
											  <tr>
												<th>#</th>
												<th><?php echo e(trans('app_lang.referral_email_address')); ?></th>
												<th><?php echo e(trans('app_lang.status')); ?></th>
											  </tr>
											</thead>
										</table>	
									  </div>
									 
									 
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-12 col-lg-12 card-div ref_cont no-padding">
					<div class="card-div-cnt">
						<ul class="nav nav-tabs funds-history-tabs referral-tab">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" data-target="#refer_history" href="javascript:;"><?php echo e(trans('app_lang.referral_history_lng')); ?></a>
							</li>
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane container active" id="deposit-history">
								<div class="table-responsive dsb-transaction-table">
									
									<div class="tab-content">
									  <div class="tab-pane container active" id="refer_history">
										<table id="refer_history_tbl" class="table table-hover table-borderless">
											<thead>
											  <tr>
												<th>#</th>
												<th><?php echo e(trans('app_lang.referral_email_address')); ?></th>
												<th><?php echo e(trans('app_lang.commission')); ?></th>
												<th><?php echo e(trans('app_lang.date_time')); ?></th>
											
											  </tr>
											</thead>
										</table>	
									  </div>
									 
									 
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>

				<div class="col-xs-12 col-md-12 col-lg-12 card-div ref_cont ref_rules no-padding">
						
						<div class="greatest-reward-rules">
							<ul class="nav nav-tabs funds-history-tabs referral-tab">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" data-target="#refer_history1" href="javascript:;"><?php echo e(trans('app_lang.reward_rule')); ?></a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane container active" id="refer_history1">
									<?php echo getStaticContent('greatest_rewards_rules')->content; ?>
									<?php /* echo cms_lang('content','0',session('language'),36);
									<p class="marginNo">* {{trans('app_lang.reward_note') }}</p>
									*/
									?>
								</div>
							</div>
						</div>
				</div>
					
			</div>	
		</div>

<script>
// Register Validation
  var require_field_ref ="<?php echo e(trans('app_lang.field_require')); ?>";
  var valid_email_ref ="<?php echo e(trans('app_lang.enter_valid_email')); ?>";
  var address_copy ="<?php echo e(trans('app_lang.referral_link_copied')); ?>";
  var referral_email_sent ="<?php echo e(trans('app_lang.referral_email_sent')); ?>";
  var empty_msg ="<?php echo e(trans('app_lang.no_data_found_table')); ?>";
</script>