<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

<div class="container-fluid log_in_page">
    <div class="login-page" data-aos="fade-up" data-aos-duration="800">
      <div class="form">
        {!! Form::open(array('class'=>'login-form', 'id'=>'login','url'=>'login','method'=>'POST','onsubmit'=>'login_load()')) !!}
         <div class="log_new_class"><h2 class="form-heading">{{trans('app_lang.login') }}</h2> 
        </div>
        <div class="log_new">
          <input type="text" name="email" class="email white-space-is-dead" placeholder="{{trans('app_lang.email') }}"/>
          <span class="el-input__prefix"><i class="fa fa-envelope" aria-hidden="true"></i></span></div>
          <div class="log_new" style="margin-bottom: 10%;">
          <input type="password"  name="password" placeholder="{{trans('app_lang.password') }}"/>
          <span class="el-input__prefix"><i class="fa fa-unlock-alt"></i></span></div>

        <div class="mb-10 capcha">
        <div class="g-recaptcha" id="tmaitb_cap" name="tmaitb_cap" data-sitekey="<?php echo getSiteKey(); ?>" data-callback="tychrecaptcharCallback">
        </div>
        <span id="log_captcha" class="log_captcha error captcha_error" style="display:none;"></span>
        </div>
         

          
          <button type="submit" id="embed-submit" class="login_sub">{{trans('app_lang.login') }}</button>
           <div class="login_whole clearfix">
          <p class="message fgt-pwd left-message"><a href="<?php echo url('forgotpassword'); ?>">{{trans('app_lang.forgot_password') }}?</a></p>
          <p class="message right-message">{{trans('app_lang.not_tych') }} <a href="<?php echo url('register'); ?>">{{trans('app_lang.signup') }}</a></p>
        </div>
        {!! Form::close() !!}
      </div>
      <div class="form-page-footer-cnt">
        <div class="form-page-footer">
                 
        </div>
      </div>
    </div>
</div>
@include('front.common.footer')


<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
// Register Validation
  var field_require_log ="{{trans('app_lang.field_require') }}";   
  var valid_email_log ="{{trans('app_lang.enter_valid_email') }}";   
  var gcapcha = 'login';
  var login ="{{trans('app_lang.login') }}";

  var tychrecaptcharCallback = function() {
  grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
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
