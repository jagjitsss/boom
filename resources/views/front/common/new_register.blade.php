<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">

<div class="container-fluid reg_page">
    <div class="login-page" data-aos="fade-up" data-aos-duration="800">
      <div class="form">
     
          {!! Form::open(array('class'=>'register-form', 'id'=>'register','url'=>'register','method'=>'POST')) !!}
           <div class="log_new_class"><h2 class="form-heading">Mobile phone</h2>
            <a data-v-a6dccb7a="" href="<?php echo url('register'); ?>" class="ts-14 a-theme"><i class="fa fa-envelope-o" aria-hidden="true"></i>
      Email </a>
           </div>
           <div class="log_new">
          <input type="email" name="email" id="email" class="email white-space-is-dead pad_new_log" placeholder="phone number" required />
          
        </div>
         <div class="log_new log_verify_code">
          <input type="email" name="email" id="email" class="email white-space-is-dead pad_new_log" placeholder="Please enter verification code" required />
           <button>Get verification code</button>
          

        </div>
        <div class="log_new">
          <input type="password" class="pad_new_log" name="password" id="password"  placeholder="Set Password, 8-20 characters which are numbers, letters or the combination" required />
       
        </div>
         <div class="log_new">
          <input type="password" class="pad_new_log" name="password_confirmation" id="cpassword"  placeholder="{{trans('app_lang.confirm_password') }}" required />
          
        </div>
       

          <p class="message form-check">
            <label class="custom-check">{{trans('app_lang.accept') }} <a target="_blank"  href="<?php echo url('/pages/terms');?>">{{trans('app_lang.terms') }}</a>
              <input type="checkbox" name="iagree" required checked="checked">
              <span class="checkmark"></span>
            </label>
            </p>
         



          <div class="mb-10 capcha">
          <div class="g-recaptcha" id="tmaitb_cap"  data-sitekey="<?php echo getSiteKey(); ?>" data-callback="tychrecaptcharCallback">
          </div>
          <span id="newreg_captcha" class="reg_captcha error captcha_error"></span>
          </div>

          <button type="submit" id="embed-submit" class="mt-3">{{trans('app_lang.join_now') }}</button>
<p class="message right-message">{{trans('app_lang.already_registered') }}? <a href="<?php echo url('login'); ?>">{{trans('app_lang.login') }}</a></p>
        {!! Form::close() !!}
      </div>
      <div class="form-page-footer-cnt">
        <div class="form-page-footer">
           
        </div>
      </div>
    </div>
</div>
<div class="login_blk">
      <p>Boompay.com and LinkCoin.pro account are interoperable.， You can use any registered account to log in directly to the site.</p>
    </div>
    <div class="login_footer">
      <p><?php echo getcopyright(); ?></p>
    </div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
// Register Validation
  var email_exist_reg ="{{trans('app_lang.email_exist') }}"; 
  var require_field_reg ="{{trans('app_lang.field_require') }}";
  var pwd_min_reg ="{{trans('app_lang.min_8_char') }}";
  var check_pwd_reg ="{{trans('app_lang.min_8_char_validate') }}";
  var same_pwd_reg ="{{trans('app_lang.enter_same_password') }}";
  var gcapcha = 'newreg';
  var form = document.getElementById('register');
  form.addEventListener("submit", function(event)
  {
    if (grecaptcha.getResponse() === '') 
    {                            
      event.preventDefault();
      alert('Please check the recaptcha');
      $('#newreg_captcha').show();  
    }
  }, false);

  var tychrecaptcharCallback = function() {
  grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
};

</script>