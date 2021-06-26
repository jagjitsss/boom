<?php $support = getSite();?>
<?php $i1 = app('request')->input('name');?>

<div class="tab-pane container-fluid active no-padding" id="overview">
  <div class="inner_banner">
    <div class="inner-sec-top-menu">
      <div class="container">
        <ul class="inner-sec-menu">
          <li><a href="<?php echo url('/dashboard'); ?>" ><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li>
          <li><a href="<?php echo url('/buy-sell'); ?>" ><i class="fa fa-arrows-h" aria-hidden="true"></i> Buy/Sell</a></li>
          <li><a href="<?php echo url('/bankwire/USD'); ?>"><i class="fa fa-folder" aria-hidden="true"></i> Bank</a></li>

          <li><a href="<?php echo url('/editprofile'); ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>

            <li><a href="<?php echo url('/profile'); ?>" class="active"><i class="fa fa-user" aria-hidden="true"></i>Profile</a></li>

        </ul>
      </div>
    </div>
  </div>
  <div class="dash-buy-sell-sec">
    <div class="container">
      <div class="row">
		
      	<div class="col-xs-12 col-sm-12 breadcrumb overview_tab"> <?php echo e(trans('app_lang.overview')); ?>

					<ul class="nav nav-tabs settings-tab">

							
								<li class="nav-item">
									<a class="nav-link navtpr <?php if ($i1 != 'verification') {echo 'active show';}?>" data-toggle="tab" data-target="#my-profile" href="javascript:;"><?php echo e(trans('app_lang.account')); ?></a>
								</li>
								
								<li class="nav-item">
									<a class="nav-link navt" href="<?php echo url('referral'); ?>"><?php echo e(trans('app_lang.invitation')); ?> →</a>
								</li>

								
							</ul>
				</div>




				<div class="tab-content">
				<div class="row tab-pane <?php if ($i1 != 'verification') {echo 'active';}?>" id="my-profile">
					<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 xl-p-r card-div profile-section">

					
						<div class="card-div-cnt">

							<div class="row pt-40 pb-40">
								<div class="col-lg-6 col-12 api-docs-left">
								<div class="col dashboard-profile-icon-cnt">
								<div>
									<?php if ($user->profile == '') {?>
									<img class="dashboard-profile-icon" src="<?php echo e(asset('/').('public/assets/images/')); ?>avatar.png">
									<?php } else { ?>
									<img class="dashboard-profile-icon" src="<?php echo e($user->profile); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="col dsb-profile-details">
								<div class="dashboard-profile-name">
									<span class="dsb-profile-name"><?php $name = session::get('tmaitb_profile');
									echo $name ? $name : '';?></span>
									<p class="isname">
								
										<span>
											<?php $url = url('/dashboard?name=verification'); ?>
											<?php if ($user->id_status == 2 || $user->selfie_status == 2) 
											{
												echo '<a href="'.$url.'"><b style="color:red;">' . trans('app_lang.rejected') . '</b></a>';
											} 
											else if ($user->id_status == 1 || $user->selfie_status == 1) 
											{
												echo '<a href="'.$url.'"><b style="color:#EEA503;">' . trans('app_lang.pending') . '</b></a>';
											} 
											else if ($user->verified_status == 0) 
											{

											} 
											else 
											{

											}?>
										</span>
									</p>
								</div>
								<?php if ($name) {?>
								<div class="dashboard-profile-phone"><span><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-profile-phone-icon-temp.png"></span><?php echo e($user->mobile); ?></div>
								<?php }?>
								<div class="dashboard-profile-mail"><span><img src="<?php echo e(asset('/').('public/assets/images/')); ?>dashboard-profile-msg-icon-temp.png"></span><?php echo session::get('tmaitb_user_email'); ?></div>

								

							</div>
							
						
							<ul class="logg_in">


								<?php $recent_login = last_recent_login(Session::get('tmaitb_user_id'));
								$recent_login_ip = last_recent_login_ip(Session::get('tmaitb_user_id'));
								if ($recent_login && $recent_login_ip) {
								?>
								<li class="nav-item dashboard-header-links">
									<p class="nav-link last-login-detail-txt" href="javascript:;"><?php echo e(trans('app_lang.last_logged_in')); ?> : <?php echo $recent_login; ?> <span>IP： <?php echo $recent_login_ip; ?></span></p>
								</li>
								<?php }?>
							</ul>
								</div>
							</div>
							
						</div>
						<div class="col-xs-12 col-sm-12 breadcrumb overview_tab over_profile">
						<div class="tab-pane container-fluid no-padding" id="my-profile">
<?php echo Form::open(array('onsubmit'=>'profile_load()','id'=>'profile','url'=>'editprofile','method'=>'POST', 'enctype' => "multipart/form-data" )); ?>

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 xl-p-r lg-p-r md-p-r card-div d-flex align-item-center">
<div class="card-div-cnt">
<div class="profile-name"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></div>
<div class="profile-pic-cnt">
<div class="profile-pic">

<?php if ($user->profile == '') {?>
		   <img src="<?php echo e(asset('/').('public/assets/images/')); ?>avatar.png" class="profile-pic thumbnil_profile">
		<?php } else {?>
		   <img src="<?php echo e($user->profile); ?>" class="profile-pic thumbnil_profile">
	<?php }?>

</div>
</div>
<div class="upload-prof-pic-cnt">


<div class="add-coin-txtbox-label"><span><?php echo e(trans('app_lang.profile_image')); ?></span>
	<!-- <span style="color: red">*</span> -->
	<span class="tltp-span">
		<a href="javascript:;" class="tooltip-icon" data-original-title="Upload coin logo"><img src="<?php echo e(asset('/').('public/assets/images/')); ?>tooltip-question-icon.png"></a>
		<span class="tltp-cnt"><?php echo e(trans('app_lang.upload_img_format')); ?></span>
	</span>
</div>


	<label for="file" class="custom-file-upload new_Btn" >
	<?php echo e(trans('app_lang.upload_image')); ?>

	</label>
	<input id="file" type="file" name="file" <?php echo $user->profile ? '' : 'required' ?>  onchange="showimage_edit(this,'thumbnil_profile')"/>
	<label for="file" class="error hide" id="thumbnil_profile">only files with jpg,png,jpeg extension are allowed</label>


</div>

</div>

</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-9 xl-p-l lg-p-l md-p-l card-div">
<div class="card-div-cnt">
<div class="row profile-info-cnt">
<div class="col-xs-12 col-sm-12 col-md-12 profile-info-heading"><?php echo e(trans('app_lang.personal_inform')); ?></div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">Name<span style="color: red">*</span></div>
<input name="first_name" type="text" placeholder="<?php echo e(trans('app_lang.first_name')); ?>" class="profile-txtbox" value="<?php echo e($user->first_name?$user->first_name:''); ?>" required>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label"><?php echo e(trans('app_lang.email_address')); ?><span style="color: red">*</span></div>
<input type="text" placeholder="<?php echo e(trans('app_lang.email_address')); ?>" class="profile-txtbox" value="<?php echo session::get('tmaitb_user_email'); ?>" disabled>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label"><?php echo e(trans('app_lang.mobile_number')); ?><span style="color: red">*</span></div>
<input name="mobile" type="text" value="<?php echo e($user->mobile); ?>" placeholder="<?php echo e(trans('app_lang.mobile_number')); ?>" class="profile-txtbox" disabled>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label"><?php echo e(trans('app_lang.zipcode')); ?><span style="color: red">*</span></div>
<input name="pincode" type="text" placeholder="<?php echo e(trans('app_lang.zipcode')); ?>" class="profile-txtbox" value="<?php echo e($user->pincode); ?>" required>
</div>

<div class="profile-info-cnt col-sm-12">
<button type="submit" id="profile_update" class="dsb-blue-btn profile_update"><?php echo e(trans('app_lang.update_profile')); ?></button>
</div>


<div class="row profile-info-cnt">


</div>

</div>


</div>
<?php echo Form::close(); ?>


</div>

</div>	

					
						
						</div>
						<?php /*
						<div class="col-xs-12 col-sm-12 dsb-acc-status-cnt pad_zero">
							<div class="card-div-cnt">
								<div class="dsb-acc-status-heading">{{trans('app_lang.api_key') }}
										<?php if($user->api_status==3){?>
									<span style="padding-left: 26px;">
													<i class="fa fa-info-circle" aria-hidden="true"></i>	<b style="color:#a61a00;"><?php echo "Disabled" ?></b>						</span>
                                          <?php } ?>
												</div>
							
							<div class="google_auth">
								<img class="img_new_class" src="{{asset('/').('public/assets/images/')}}apikey12.png">
								<span>
                                 {{trans('app_lang.enable_api')}}</span>
								<p class="safecommon-text"> {{trans('app_lang.access_api')}}</p>
								<?php if($user->api_status==0 || $user->api_status==3){?>				
								<div class="center-btn-cnt"><a href="<?php echo url('/enable_key') ?>" class="bordered-btn"><?php echo trans('app_lang.enable_lng'); ?></a></div>
								<?php } else if($user->api_status==2) {?>

								<div class="center-btn-cnt">
                                    <span>
													<i class="fa fa-info-circle" aria-hidden="true"></i>	<b style="color:#b98319e6;"><?php echo "Request pending" ?></b>						</span>
									</div>
								
								<?php } else if($user->api_status==1) {?>

								    <div class="center-btn-cnt">
                                    <span>
													<i class="fa fa-check-circle" aria-hidden="true"></i>	<b style="color:#00A65A;"><?php echo "Enabled" ?></b>						</span>
									</div>
								<?php } ?>

							</div>
                            <?php if($user->api_status==1){ ?>
							<div class="google_auth">
								<span>
                                 {{trans('app_lang.api_key')}}</span>
								<p class="safecommon-text">
                                <input name="api_key" type="text" placeholder="First Name" class="profile-txtbox" value="{{$user->api_key}}" required="" disabled>
								 </p>
							</div>
							<div class="google_auth">
								<span>
                                 {{trans('app_lang.api_secret')}}</span>
								<p class="safecommon-text"> 
                                <input name="api_secretkey" type="text" placeholder="First Name" class="profile-txtbox" value="{{$user->api_secret}}" required="" disabled>
								</p>
							</div>
							 <?php  } ?>
						</div>
						</div> */ ?>


					</div>
			

					<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 xl-p-r card-div dsb-cust-supp-cnt">
						<div class="card-div-cnt">
							<div class="card-heading-container">
								<div class="recent-login-heading"><?php echo e(trans('app_lang.recent_login')); ?></div>
								
							</div>
							<div class="table-responsive">
							<table>
							<thead>
							  <tr>
								<th class="color-grey ts-12"><?php echo e(trans('app_lang.date')); ?></th>								
								<th class="color-grey ts-12"><?php echo e(trans('app_lang.browser')); ?></th>
								<th class="color-grey ts-12"><?php echo e(trans('app_lang.ip_address')); ?></th>
								<th class="color-grey ts-12"><?php echo e(trans('app_lang.location')); ?></th>
							  </tr>
							</thead>
							<tbody>
								<?php $__currentLoopData = $logins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $login): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
								<td><?php echo e($login->created_at); ?></td>
								<td><?php echo e($login->browser_name); ?></td>
								<td><?php echo e($login->ip_address); ?></td>
								<td><?php echo e($login->country); ?></td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							
							</tbody>

						  </table>
						</div>
						</div>
					</div>

				</div>
				
			</div>



  </div>
  </div>
  </div>


  </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	var no_records   = "<?php echo e(trans('app_lang.no_records_found')); ?>";
	var submit = "<?php echo e(trans('app_lang.submit')); ?>";
	var profile_btn= "<?php echo e(trans('app_lang.update_profile')); ?>";
</script>
