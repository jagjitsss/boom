<?php $support = getSite();?>
<?php $i1 = app('request')->input('name');?>

	<div class="tab-pane container-fluid active no-padding" id="overview">
		<div class="inner_banner">

			<?php if(!empty($banner->image_url)) { ?>

			<img src="{{$banner->image_url}}" height="60" width="1200">
			<?php } else { ?>
			<img src="{{asset('/').('public/assets/images/')}}banner.png" height="60" width="1200">
			<?php } ?>

		</div>
				<div class="col-xs-12 col-sm-12 breadcrumb overview_tab"> {{trans('app_lang.overview') }}
					<ul class="nav nav-tabs settings-tab">

							
								<li class="nav-item">
									<a class="nav-link navtpr <?php if ($i1 != 'verification') {echo 'active show';}?>" data-toggle="tab" data-target="#my-profile" href="javascript:;">{{trans('app_lang.account') }}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link navt <?php if ($i1 == 'verification') {echo 'active show';}?>" data-toggle="tab" data-target="#Security" href="javascript:;">{{trans('app_lang.profile') }}</a>
									
								</li>
								<li class="nav-item">
									<a class="nav-link navt" href="<?php echo url('referral'); ?>">{{trans('app_lang.invitation') }} →</a>
								</li>

								
							</ul>
				</div>
				<div class="tab-content">
				<div class="row tab-pane <?php if ($i1 != 'verification') {echo 'active';}?>" id="my-profile">
					<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 xl-p-r card-div profile-section">
						<div class="card-div-cnt">

							<div class="col dashboard-profile-icon-cnt">
							<div>
								<?php if ($user->profile == '') {?>
								<img class="dashboard-profile-icon" src="{{asset('/').('public/assets/images/')}}avatar.png">
								<?php } else { ?>
								<img class="dashboard-profile-icon" src="{{$user->profile}}">
								<?php } ?>
							</div>
							</div>
							<div class="col dsb-profile-details">
								<div class="dashboard-profile-name"><span class="dsb-profile-name"><?php $name = session::get('tmaitb_profile');
echo $name ? $name : '';?></span>
<p class="isname"><i class="fa fa-info-circle" aria-hidden="true"></i> 
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
								echo '<a href="'.$url.'"><b style="color:blue;">' . trans('app_lang.unverified') . '</b></a>';
							} 
							else 
							{
								echo '<a href="'.$url.'"><b style="color:#00A65A;">' . trans('app_lang.verified') . '</b></a>';
							}?>
							</span>
							</p></div>
								<?php if ($name) {?>
								<div class="dashboard-profile-phone"><span><img src="{{asset('/').('public/assets/images/')}}dashboard-profile-phone-icon-temp.png"></span>{{$user->mobile}}</div>
								<?php }?>
								<div class="dashboard-profile-mail"><span><img src="{{asset('/').('public/assets/images/')}}dashboard-profile-msg-icon-temp.png"></span><?php echo session::get('tmaitb_user_email'); ?></div>
							</div>
						
							<ul class="logg_in">


				<?php $recent_login = last_recent_login(Session::get('tmaitb_user_id'));
				 $recent_login_ip = last_recent_login_ip(Session::get('tmaitb_user_id'));
if ($recent_login && $recent_login_ip) {
	?>
				<li class="nav-item dashboard-header-links">
					<p class="nav-link last-login-detail-txt" href="javascript:;">{{trans('app_lang.last_logged_in') }} : <?php echo $recent_login; ?> <span>IP： <?php echo $recent_login_ip; ?></span></p>
				</li>
				<?php }?>
			</ul>
						</div>
						<div class="col-xs-12 col-sm-12 breadcrumb overview_tab over_profile">
						<div class="tab-pane container-fluid no-padding" id="my-profile">
{!! Form::open(array('onsubmit'=>'profile_load()','id'=>'profile','url'=>'editprofile','method'=>'POST', 'enctype' => "multipart/form-data" )) !!}
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 xl-p-r lg-p-r md-p-r card-div d-flex align-item-center">
<div class="card-div-cnt">
<div class="profile-name">{{$user->first_name}} {{$user->last_name}}</div>
<div class="profile-pic-cnt">
<div class="profile-pic">

<?php if ($user->profile == '') {?>
		   <img src="{{asset('/').('public/assets/images/')}}avatar.png" class="profile-pic thumbnil_profile">
		<?php } else {?>
		   <img src="{{$user->profile}}" class="profile-pic thumbnil_profile">
	<?php }?>

</div>
</div>
<div class="upload-prof-pic-cnt">


<div class="add-coin-txtbox-label"><span>{{trans('app_lang.profile_image') }}</span><span style="color: red">*</span>
	<span class="tltp-span">
		<a href="javascript:;" class="tooltip-icon" data-original-title="Upload coin logo"><img src="{{asset('/').('public/assets/images/')}}tooltip-question-icon.png"></a>
		<span class="tltp-cnt">{{trans('app_lang.upload_img_format') }}</span>
	</span>
</div>


	<label for="file" class="custom-file-upload new_Btn" >
	{{trans('app_lang.upload_image') }}
	</label>
	<input id="file" type="file" name="file" <?php echo $user->profile ? '' : 'required' ?>  onchange="showimage_edit(this,'thumbnil_profile')"/>
	<label for="file" class="error hide" id="thumbnil_profile">only files with jpg,png,jpeg extension are allowed</label>


</div>

</div>

</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-9 xl-p-l lg-p-l md-p-l card-div">
<div class="card-div-cnt">
<div class="row profile-info-cnt">
<div class="col-xs-12 col-sm-12 col-md-12 profile-info-heading">{{trans('app_lang.personal_inform') }}</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.first_name') }}<span style="color: red">*</span></div>
<input name="first_name" type="text" placeholder="{{trans('app_lang.first_name') }}" class="profile-txtbox" value="{{$user->first_name?$user->first_name:''}}" required>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.last_name') }}<span style="color: red">*</span></div>
<input name="last_name" type="text" value="{{$user->last_name}}" placeholder="{{trans('app_lang.last_name') }}" class="profile-txtbox" required>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.email_address') }}<span style="color: red">*</span></div>
<input type="text" placeholder="{{trans('app_lang.email_address') }}" class="profile-txtbox" value="<?php echo session::get('tmaitb_user_email'); ?>" disabled>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.mobile_number') }}<span style="color: red">*</span></div>
<input name="mobile" type="text" value="{{$user->mobile}}" placeholder="{{trans('app_lang.mobile_number') }}" class="profile-txtbox" disabled>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.gender') }}<span style="color: red">*</span></div>
<div class="form-group">
  <label class="wrap">
	  <select class="form-control dropdown" name="gender" id="sel2" >
	    <option value="">Please select</option>
		<option <?php echo $user->gender == 'Male' ? 'selected' : ''; ?>  value="Male">{{trans('app_lang.male') }}</option>
		<option <?php echo $user->gender == 'Female' ? 'selected' : ''; ?> value="Female">{{trans('app_lang.female') }}</option>
	  </select>
  </label>
  <label for="sel2" class="error" style="display: none">This field is required.</label>
</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.dob') }}<span style="color: red">*</span></div>
<input  name="dob" id="datepicker" value="{{$user->dob}}" type="text" placeholder="{{trans('app_lang.select_date') }}" class="profile-txtbox" required autocomplete="off">
</div>
</div>

<div class="row profile-info-cnt">
<div class="col-xs-12 col-sm-12 col-md-12 profile-info-heading">{{trans('app_lang.address_inform') }}</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.address_line_one') }}<span style="color: red">*</span></div>
<input name="address1"  type="text" value="{{$user->address1}}" placeholder="{{trans('app_lang.address_line_one') }}" class="profile-txtbox" required>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.address_line_two') }}</div>
<input value="{{$user->address2}}" name="address2" type="text" placeholder="{{trans('app_lang.address_line_two') }}" class="profile-txtbox">
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.country') }}<span style="color: red">*</span></div>
<div class="form-group">
  <label class="wrap">
	  <select class="form-control dropdown" name="country" id="sel1" required>
		<option value="">{{trans('app_lang.select_country') }}</option>
		@foreach($country as $item)
   	 <option <?php echo $user->country ? ($user->country == $item->country_name ? 'selected' : '') : ''; ?>> {{$item->country_name}} </option>
	    @endforeach

	  </select>
  </label><label for="sel1" class="error hide">This field is required.</label>
</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.city') }}<span style="color: red">*</span></div>
<input type="text" placeholder="{{trans('app_lang.city') }}" class="profile-txtbox" value="{{$user->city}}" name="city" required>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.state') }}<span style="color: red">*</span></div>
<input type="text" placeholder="{{trans('app_lang.state') }}" class="profile-txtbox" value="{{$user->state}}"  name="state" required>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 profile-txt-box-cnt">
<div class="profile-txtbox-label">{{trans('app_lang.zipcode') }}<span style="color: red">*</span></div>
<input name="pincode" type="text" placeholder="{{trans('app_lang.zipcode') }}" class="profile-txtbox" value="{{$user->pincode}}" required>
</div>
<div class="profile-info-cnt col-sm-12">
<button type="submit" id="profile_update" class="dsb-blue-btn profile_update">{{trans('app_lang.update_profile') }}</button>
</div>
</div>
</div>

</div>


</div>
{!! Form::close() !!}

</div>

</div>	

					
						<div class="col-xs-12 col-sm-12 dsb-acc-status-cnt pad_zero">
							<div class="card-div-cnt">
								<div class="dsb-acc-status-heading">{{trans('app_lang.TFA') }}</div>
							
							<div class="google_auth">
								<img class="img_new_class" src="{{asset('/').('public/assets/images/')}}img-101.png">
								<span>{{trans('app_lang.google_authentication')}}</span>
								<p class="safecommon-text">{{trans('app_lang.auth_place') }}</p>				
								<div class="center-btn-cnt"><a href="<?php echo url('/change_tfa') ?>" class="bordered-btn"><?php echo $user->randcode == 1 ? trans('app_lang.disable_lng') : trans('app_lang.enable_lng'); ?></a></div>
							</div>
						</div>
						</div>

						<div class="col-xs-12 col-sm-12 dsb-acc-status-cnt pad_zero">
							<div class="card-div-cnt">
								<div class="dsb-acc-status-heading">{{trans('app_lang.security_setting') }}</div>
								<div class="sms_auth sec_set">
									<img class="img_new_class" src="{{asset('/').('public/assets/images/')}}img-104.png">
								
								<span>{{trans('app_lang.security_setting') }}</span>
								<p class="safecommon-text">{{trans('app_lang.security_place') }}</p>
								<div class="center-btn-cnt"><a href="<?php echo url('/change_password') ?>" class="bordered-btn">{{trans('app_lang.change') }}</a></div>
							</div>
							<div class="google_auth">
								<img class="img_new_class" src="{{asset('/').('public/assets/images/')}}img-103.png">
								
								<span>{{trans('app_lang.email_setting') }}</span>
								<p class="safecommon-text">{{trans('app_lang.email_place') }}</p>
								<div class="center-btn-cnt"><a href="<?php echo url('/change_notification') ?>" class="bordered-btn">{{trans('app_lang.reset') }}</a></div>
							</div>
						</div>
						</div>
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
						</div>


					</div>
			

					<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 xl-p-r card-div dsb-cust-supp-cnt">
						<div class="card-div-cnt">
							<div class="card-heading-container">
								<div class="recent-login-heading">{{trans('app_lang.recent_login') }}</div>
								
							</div>
							<div class="table-responsive">
							<table>
							<thead>
							  <tr>
								<th class="color-grey ts-12">{{trans('app_lang.date') }}</th>								
								<th class="color-grey ts-12">{{trans('app_lang.browser') }}</th>
								<th class="color-grey ts-12">{{trans('app_lang.ip_address') }}</th>
								<th class="color-grey ts-12">{{trans('app_lang.location') }}</th>
							  </tr>
							</thead>
							<tbody>
								@foreach ($logins as $login)
								<tr>
								<td>{{$login->created_at}}</td>
								<td>{{$login->browser_name}}</td>
								<td>{{$login->ip_address}}</td>
								<td>{{$login->country}}</td>
							</tr>
							@endforeach
							
							</tbody>

						  </table>
						</div>
						</div>
					</div>

				</div>
				<div class="tab-pane <?php if ($i1 == 'verification') {echo 'active';}?>" id="Security">
					<div class="card-div-cnt pro_verify">
						<div class="card-t pd-b16">
							<p class="color-primary ts-16">{{trans('app_lang.profile') }} : 
							<?php if ($user->id_status == 2 || $user->selfie_status == 2) 
							{
								echo '<b style="color:red;">' . trans('app_lang.rejected') . '</b>';
							} 
							else if ($user->id_status == 1 || $user->selfie_status == 1) 
							{
								echo '<b style="color:#EEA503;">' . trans('app_lang.pending') . '</b>';
							} 
							else if ($user->verified_status == 0) 
							{
								echo '<b style="color:blue;">' . trans('app_lang.unverified') . '</b>';
							} 
							else 
							{
								echo '<b style="color:#00A65A;">' . trans('app_lang.verified') . '</b>';
							}?></p>

							<?php echo cms_lang('content','0',session('language'),38);?>
						</div>
						<div class="kyc_blk">
						{!! Form::open(array('id'=>'kyc','url'=>'updatekyc','method'=>'POST', 'enctype' => "multipart/form-data", 'class'=>'align-self-stretch d-flex flex-column col-sm-12','onsubmit'=>'kyc_load()')) !!}
						<div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
							<div class="profile-txtbox-label">{{trans('app_lang.id_type') }} :</div>
							
							<?php if ($user->id_status == 2) { ?>

								<select class="profile-txtbox" name="verifytype">
									<?php 
									foreach ($verificationtype as $row) { 
										if($row->category == $user->type) {
										$sel = "selected";
									}
									else {
										$sel = "";
									} ?>
									<option value="<?php echo $row->category;?>" <?php echo $sel;?> ><?php echo $row->category;?></option>
									<?php } ?>
								</select>							

							<?php } else if ($user->id_status == 1 || $user->selfie_status == 1) { ?>

								<select class="profile-txtbox" name="verifytype" disabled>

									<?php 
									foreach ($verificationtype as $row) { 
										if($row->category == $user->type) {
										$sel = "selected";
									}
									else {
										$sel = "";
									} ?>
									<option value="<?php echo $row->category;?>" <?php echo $sel;?> ><?php echo $row->category;?></option>
									<?php } ?>
								</select>

							<?php } else if($user->verified_status == 0) { ?>

								<select class="profile-txtbox" name="verifytype">

									<?php 
									foreach ($verificationtype as $row) { 
										if($row->category == $user->type) {
										$sel = "selected";
									}
									else {
										$sel = "";
									} ?>
									<option value="<?php echo $row->category;?>" <?php echo $sel;?> ><?php echo $row->category;?></option>
									<?php } ?>
								</select>
								
							<?php } else { ?>

								<select class="profile-txtbox" name="verifytype" disabled>
									<?php 
									foreach ($verificationtype as $row) { 
										if($row->category == $user->type) {
										$sel = "selected";
									}
									else {
										$sel = "";
									} ?>
									<option value="<?php echo $row->category;?>" <?php echo $sel;?> ><?php echo $row->category;?></option>
									<?php } ?>
								</select>

							<?php } ?>
						</div>
						<div class="el-form-item">
							<label class="el-form-item__label">{{trans('app_lang.front_page') }} :</label>
							<div class="el-form-item__content">
								
								<?php echo cms_lang('content','0',session('language'),39);?>
								<div element-loading-text="Uploading" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0,255,0,.3)" class="ww mg-t4 pull-left wfan">
									<span style="display: none;" class="error-position ts-12">Upload failed, please upload again</span> 
									<?php if ($user->id_proof_front && ($user->id_status == 1 || $user->id_status == 3)) {?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input type="file" name="file">
										</div>
									</div>
									<?php } else { ?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input id="file1" type="file" class="el-upload__input" name="file1" onchange="showimage_edit(this,'thumbnil_kyc_front')" />
											<label for="file1" class="error" style="display: none" id="thumbnil_kyc_front">{{trans('app_lang.field_require') }}</label>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php if ($user->id_proof_front && ($user->id_status == 1 || $user->id_status == 3)) {?>
								<div class="pull-left instance mg-l40">
									<img src="{{$user->id_proof_front}}" alt="" data-toggle="modal" data-target="#exampleModal"> 
									<div class="meng ts-16" data-toggle="modal" data-target="#exampleModal"></div>
								</div>
								<?php } else { ?>
								<div class="pull-left instance mg-l40">
									<img src="{{asset('/').('public/assets/images/')}}passportF.a3ad39b.jpg" class="thumbnil_kyc_front" alt=""> 
									<div class="meng ts-16" data-toggle="modal" data-target="#exampleModal"><span id="viewex1">view examples</span></div>
								</div>	
								<?php } ?>
							</div>
						</div>

						<div class="el-form-item mar_top_bot">
							<label class="el-form-item__label">{{trans('app_lang.bio_page') }} :</label>
							<div class="el-form-item__content">
								
								<div element-loading-text="Uploading" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0,255,0,.3)" class="ww mg-t4 pull-left wzheng">
									<span style="display: none;" class="error-position ts-12">Upload failed, please upload again</span> 
									<?php if ($user->id_proof_front && ($user->id_status == 1 || $user->id_status == 3)) {?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input type="file" name="file">
										</div>
									</div>
									<?php } else { ?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input id="file2" type="file" name="file2" class="el-upload__input" onchange="showimage_edit(this,'thumbnil_kyc_back')" />
	                						<label for="file2" class="error" style="display: none" id="thumbnil_kyc_back">{{trans('app_lang.field_require') }}</label>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php if ($user->id_proof_front && ($user->id_status == 1 || $user->id_status == 3)) {?>
								<div class="pull-left instance mg-l40">
									<img src="{{$user->id_proof_back}}" alt=""> 
									<div class="meng ts-16"  data-toggle="modal" data-target="#exampleModal3"></div>
								</div>
								<?php } else { ?>
								<div class="pull-left instance mg-l40">
									<img src="{{asset('/').('public/assets/images/')}}passportZ.dea8c5f.jpg" class="thumbnil_kyc_back" alt=""> 
									<div class="meng ts-16" data-toggle="modal" data-target="#exampleModal3"><span id="viewex2">view examples</span></div>
								</div>	
								<?php } ?>
								<?php if ($user->id_status == 2) {
									echo '<p class="error reason_txt"><span>Reason for rejection:<br/></span> ' . $user->id_reject . '</p>';									
								}?>
							</div>

						</div>

						<div class="el-form-item">
							<label class="el-form-item__label" style="margin-top: 8px;">{{trans('app_lang.bio_id_page') }}</label>
							<div class="el-form-item__content">
								
								<?php echo cms_lang('content','0',session('language'),40);?>
								<div element-loading-text="Uploading" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0,255,0,.3)" class="ww mg-t4 pull-left wshou">
									<span style="display: none;" class="error-position ts-12">Upload failed, please upload again</span> 
									<?php if ($user->selfie_proof && ($user->selfie_status == 1 || $user->selfie_status == 3)) {?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input type="file" name="file">
										</div>
									</div>
									<?php } else { ?>
									<div>
										<div tabindex="0" class="el-upload el-upload--text">
											<input id="file3" type="file" name="file3" class="el-upload__input" onchange="showimage_edit(this,'thumbnil_kyc_selfie')" />
	                						<label for="file3" class="error" style="display: none" id="thumbnil_kyc_selfie">{{ trans('app_lang.field_require') }}</label>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php if ($user->selfie_proof && ($user->selfie_status == 1 || $user->selfie_status == 3)) {?>
								<div class="pull-left instance mg-l40">
									<img src="{{$user->selfie_proof}}" alt="" > 
									<div class="meng ts-16" data-toggle="modal" data-target="#exampleModal1"></div>
								</div>
								<?php } else { ?>
								<div class="pull-left instance mg-l40">
									<img src="{{asset('/').('public/assets/images/')}}img-33.jpg" class="thumbnil_kyc_selfie" alt=""> 
									<div class="meng ts-16" data-toggle="modal" data-target="#exampleModal1"><span id="viewex3">view examples</span></div>
								</div>
								<?php } ?>
								<?php if ($user->selfie_status == 2) {
									echo '<p  class="error reason_txt"><span>Reason for rejection:</span><br/> ' . $user->selfie_reject . '</p>';
								}?>
							</div>
						</div>
							<div class="el-form-item kyc_btn">
							<label class="el-form-item__label"></label>
							<?php if (($user->selfie_status == 0 || $user->selfie_status == 2) || ($user->id_status == 0 || $user->id_status == 2)) {?>
							<div class="el-form-item__content">							
								<button class="bordered-btn kyc_sub">{{trans('app_lang.submit') }}</button>
							</div>
							<?php }?>
						</div>
						{!! Form::close() !!}

						</div>
					</div>
				</div>
			</div>
				
			</div>
		</div>
			</div>	</div>


<script>

var no_records   = "{{trans('app_lang.no_records_found') }}";
var submit = "{{trans('app_lang.submit')}}";
var profile_btn= "{{trans('app_lang.update_profile') }}";
</script>



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	
  <div class="modal-dialog" role="document">
  	<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div class="modal-content">
      <?php if ($user->selfie_proof && ($user->selfie_status == 1 || $user->selfie_status == 3)) {?>
      <img src="{{$user->id_proof_front}}">
 	 <?php } else { ?>
 	 	<img src="{{asset('/').('public/assets/images/')}}passportF.a3ad39b.jpg" alt=""> 
 	<?php } ?>
    </div>
   
  </div>
</div>

<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	
  <div class="modal-dialog" role="document">
  	<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div class="modal-content">
      <?php if ($user->selfie_proof && ($user->selfie_status == 1 || $user->selfie_status == 3)) {?>
      	<img src="{{$user->selfie_proof}}">
      <?php } else { ?>
 	 	<img src="{{asset('/').('public/assets/images/')}}img-33.jpg" alt=""> 
 	  <?php } ?>
    </div>
   
  </div>
</div>


<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	
  <div class="modal-dialog" role="document">
  	<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div class="modal-content">
      <?php if ($user->selfie_proof && ($user->selfie_status == 1 || $user->selfie_status == 3)) {?>
      	<img src="{{$user->id_proof_back}}">
      <?php } else { ?>
 	 	<img src="{{asset('/').('public/assets/images/')}}passportZ.dea8c5f.jpg" alt=""> 
 	  <?php } ?>
    </div>
    
  </div>
</div>



