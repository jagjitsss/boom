<div class="return">
		<div class="returnBox"><a href="<?php echo url('dashboard'); ?>" class="color-theme"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></i>
      <?php echo e(trans('app_lang.back_to')); ?> <?php echo e(trans('app_lang.account')); ?>

    </a> <p class="titled color-primary"><?php echo e(trans('app_lang.reset_google')); ?></p> <?php echo cms_lang('content','0',session('language'),42);?>
</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 card-div align-self-stretch d-flex xl-p-r lg-p-r">
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 tfa_blk_one">
		<div class="tfa_blk_ones">
		<span class="new_hd">1. <?php echo e(trans('app_lang.scan_qr')); ?></span> 
<?php echo Form::open(array('id'=>'edittfa','url'=>'updatetfa','method'=>'POST','onsubmit'=>'tfa_load()')); ?>

<div class="align-self-stretch d-flex settings-security-cnt-height tfa_blk">
<div class="row security-qr-cnt">

<?php if ($user->randcode == 1) {
	$button = trans('app_lang.disable_only');

	?>
<div class="qr-cnt text-center"><?php echo e(trans('app_lang.disable_tfa')); ?></div>
<?php } else {
	$button = trans('app_lang.enable_only');
?>

<div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">

<div class="qr-cnt text-center"><img src="<?php echo strip_tags($tfa_url); ?>" style=""></div>

<div class="form-group">
	<label><?php echo e(trans('app_lang.key')); ?>:</label>
	<input type="next" name="" value="<?php echo e($secret); ?>" placeholder="<?php echo e($secret); ?>" readonly>
	<input type="hidden" name="secret" id="secret" value="<?php echo e($secret); ?>">
	</div>
</div>
<?php }?>
</div>
</div>
</div>
</div>
<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 tfa_blk_two">

		<span class="new_hd">2. <?php echo e(trans('app_lang.complete_setting')); ?></span> 
		
		<div class="form-group">
			<label><?php echo e(trans('app_lang.login_password')); ?>：</label>
			<div class="form_control">
				<input type="password" name="psswd" id="psswd" placeholder="<?php echo e(trans('app_lang.login_password')); ?>">
			</div>
			
		</div>
		<div class="form-group">
			<label><?php echo e(trans('app_lang.authentic_code')); ?>：</label>
			<div class="form_control">
				<input type="text" name="onecode" id="onecode" placeholder="<?php echo e(trans('app_lang.6_digit_code')); ?>">
			</div>
		</div>
		<div class="form-group">
			<label></label>
			<div class="form_control">
				<button type="submit" class="dsb-blue-btn tfabtn" id="tfabut" disabled="disabled"><?php echo e(trans('app_lang.confirm')); ?></button>
			</div>
		</div>
		<?php echo Form::close(); ?>

		</div>
</div>
<script src="<?php echo e(asset('/').('public/assets/js/jquery.min.js')); ?>"></script>
<script type="text/javascript">

	var require_field_chp ="<?php echo e(trans('app_lang.field_require')); ?>";
	var enter_6_char      = "<?php echo e(trans('app_lang.enter_6_char')); ?>";
   	var upto_6_char       = "<?php echo e(trans('app_lang.upto_6_char')); ?>";
    var valid_no_prof     = "<?php echo e(trans('app_lang.valid_number')); ?>";
	var pass_wrong        = "<?php echo e(trans('app_lang.pass_wrong')); ?>";

	$(document).ready(function(){
    $('#onecode').keyup(function(){
        if($(this).val().length !=0)
            $('#tfabut').attr('disabled', false); 
        else
            $('#tfabut').attr('disabled',true);
    })
});
</script>