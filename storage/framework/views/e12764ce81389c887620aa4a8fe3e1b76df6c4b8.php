 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

<div class="container-fluid log_in_page">
  <div class="login-page post-fadeUp">
    <div class="form">
    
    <?php echo Form::open(array('class'=>'login-form', 'id'=>'reset','url'=>'resetpassword','method'=>'POST')); ?>

    <div class="log_new_class">
       
      <div class="form-heading"><h2 class="form-heading"><?php echo e(trans('app_lang.reset_password')); ?></h2></div>
    </div>
    <div class="log_new">
      <input name="password"  type="password" id="password" placeholder="<?php echo e(trans('app_lang.password')); ?>"/>
      <span class="el-input__prefix"><i class="fa fa-lock"></i></span>
    </div>
    <div class="log_new">
      <input name="password_confirmation"  type="password" id="password_confirmation" placeholder="<?php echo e(trans('app_lang.confirm_password')); ?>"/>
      <span class="el-input__prefix"><i class="fa fa-lock"></i></span>
    </div>
      <input type="hidden" name="id" value="<?php echo $id;?>" />
      <input type="hidden" name="userdata" value="<?php echo $userid;?>" />
      
      
        <div class="mb-10 capcha">
        <div class="g-recaptcha" id="tmaitb_cap"  data-sitekey="<?php echo getSiteKey(); ?>" data-callback="tychrecaptcharCallback">
        </div>
        <span id="r_captcha" class="r_captcha error captcha_error"></span>
        </div>



      <button type="submit" id="embed-submit" class="mt-3"><?php echo e(trans('app_lang.submit')); ?></button>
    <?php echo Form::close(); ?>

    </div>
    <div class="form-page-footer-cnt">
    <div class="form-page-footer">
      <p class="message left-message"><?php echo e(trans('app_lang.back_to')); ?> <a href="<?php echo url('/'); ?>"><?php echo e(trans('app_lang.home')); ?></a></p>
    </div>
    </div>
  </div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
// Register Validation
  var field_require_res ="<?php echo e(trans('app_lang.field_require')); ?>"; 
  var check_pwd_res ="<?php echo e(trans('app_lang.min_8_char_validate')); ?>"; 
  var pwd_min_res ="<?php echo e(trans('app_lang.min_8_char')); ?>"; 
  var conf_pwd_min_res ="<?php echo e(trans('app_lang.conf_min_8_char')); ?>"; 
  var same_pwd_res ="<?php echo e(trans('app_lang.enter_same_password')); ?>"; 
  var gcapcha = 'reset';

  var form = document.getElementById('reset');
  form.addEventListener("submit", function(event)
  {
    if (grecaptcha.getResponse() === '') 
    {                            
      event.preventDefault();
      alert('Please check the recaptcha');
      $('#r_captcha').show();  
    }
  }, false);
  var tychrecaptcharCallback = function() {
   grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
  };
</script>

