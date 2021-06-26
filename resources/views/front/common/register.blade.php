<?php ob_start(); ?>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<div class="header-section">
  <div class="container-fluid reg_page index-navigation">
    <div class="login-page regis-form" data-aos="fade-up" data-aos-duration="800">
      <div class="form">
        {!! Form::open(array('class'=>'register-form', 'id'=>'register','url'=>'register','method'=>'POST','onsubmit'=>'signup_load()')) !!}
          <div class="log_new_class">
            <h2 class="form-heading">{{trans('app_lang.register') }}</h2>
          </div>
           <div class="log_new">
          <input type="email" name="email" id="email" class="email white-space-is-dead" placeholder="{{trans('app_lang.email_address') }}" required />
          <span class="el-input__prefix"><i class="fa fa-envelope" aria-hidden="true"></i></span>
        </div>
        <div class="log_new">
          <input type="password" name="password" id="password"  placeholder="{{trans('app_lang.password') }}" required />
          <span class="el-input__prefix"><i class="fa fa-lock"></i></span>
        </div>
          <div class="log_new">
          <input type="password" name="password_confirmation" id="cpassword"  placeholder="{{trans('app_lang.confirm_password') }}" required />
          <span class="el-input__prefix"><i class="fa fa-lock"></i></span>
        </div>
        <div class="log_new">

          <select class="form-control" name="country" id="country">
              <option value="">Country</option>
              <?php if(!empty($countrydetails)) { foreach ($countrydetails as $row) { ?>
                <option value="<?php echo $row->phonecode; ?>"><?php echo $row->country_name; ?></option>
              <?php } } ?>
          </select>
          <span class="el-input__prefix"><i class="fa fa-globe" aria-hidden="true"></i>
          </span>
          <label for="country" id="coun_error" class="error" style="display: none;"></label>
        </div>
          <div class="log_new opt_position">
          <input type="text" name="mobileno" id="mobileno" placeholder="{{trans('app_lang.mobileno') }}" onkeypress="return isNumberKey(event)" required />
          <label for="mobileno" id="mob_error" class="error" style="display: none;"></label>
          <span class="el-input__prefix"><i class="fa fa-mobile-phone"></i></span>
          
         </div>
         

            
    
          

      <div class="log_new">
          <input type="hidden" value="<?php echo $refid;?>" name="refer_id" placeholder="{{trans('app_lang.referal_code') }}"/>
          </div>

  <div class="log_new">
          <p class="message form-check">
            <label class="custom-check">I've read and agreed  <a target="_blank"  href="<?php echo url('/pages/privacy');?>">Privacy Policy</a> with  <a target="_blank"  href="<?php echo url('/pages/terms');?>">Terms and Conditions</a>
              <input type="checkbox" name="iagree" required checked="checked">
              <span class="checkmark"></span>
            </label>
            </p>
           
</div>
           
<div class="mb-10 capcha">

             <div class="g-recaptcha-first" data-sitekey="<?php echo getSiteKey(); ?>" id="RecaptchaField2"></div>
      
         <input id="hidden-grecaptcha" name="hidden-grecaptcha" type="text" style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;"/>
    <label for="hidden-grecaptcha" class="error"></label>
              
              </div>


          <button type="submit" id="embed-submit" class="signup_sub">{{trans('app_lang.signup') }}</button>
  <p class="message right-message">{{trans('app_lang.already_registered') }}?  <a href="<?php echo url('login'); ?>">{{trans('app_lang.login') }}</a></p>

        {!! Form::close() !!}
      </div>
      <div class="form-page-footer-cnt">
        <div class="form-page-footer">
                    
        </div>
      </div>
    </div>
</div>
               </div>

               @include('front.common.footer')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

 <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> 
<script>

  var email_exist_reg ="{{trans('app_lang.email_exist') }}"; 
  var require_field_reg ="{{trans('app_lang.field_require') }}";
  var pwd_min_reg ="{{trans('app_lang.min_8_char') }}";
  var check_pwd_reg ="{{trans('app_lang.min_8_char_validate') }}";
  var same_pwd_reg ="{{trans('app_lang.enter_same_password') }}";
  var gcapcha = 'reg';
  var signup = "{{trans('app_lang.signup') }}";
  var otp_wrong = "{{trans('app_lang.otp_wrong') }}";
  var sendotp = "{{trans('app_lang.sendotp') }}";
  var otp_require = "{{trans('app_lang.otp_require') }}";

  var form = document.getElementById('register');
 

 var tychrecaptcharCallback = function() {
  grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
};

var onloadCallback = function() {
     var captcha_key = "<?php echo getSiteKey(); ?>";
    var widget1;
   
    widget1 = grecaptcha.render('RecaptchaField2', {'sitekey' : captcha_key, 'callback' : correctCaptcha_second});
    
    };
   var correctCaptcha_second = function(response) {
        $("#hidden-grecaptcha").val(response);
    };



</script>


<script>

  $(document).on('focusout', ':input', function() {
    var str = $(this).val();
     var res = str.replace(/\</g, "");
    var res1 = res.replace(/\>/g, "");
    $(this).val(res1);
  });
  </script>

  <script type="text/javascript">
var SITE_URL = "{{url('/')}}";
</script>
<script src="{{asset('/').('public/assets/js/asdfksdowlslslsl.js')}}"></script> 