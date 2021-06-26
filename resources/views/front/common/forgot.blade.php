 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<div class="container-fluid log_in_page">
  <div class="login-page post-fadeUp">
    <div class="form">
    <div class="form-logo-heading-cnt">
       <img src="{{$site->site_logo}}">
  
    </div>
    {!! Form::open(array('class'=>'login-form', 'id'=>'forgot','url'=>'forgotpassword','method'=>'POST','onsubmit'=>'forget_load()')) !!}
        <div class="form-heading">{{trans('app_lang.password_retrieval') }}</div>
      <span class="ts-12 tc-body"></span>
      <div class="log_new">
      <input name="email" class="email  white-space-is-dead pad_new_log" type="email" id="email" placeholder="{{trans('app_lang.email_enter_address') }}"/>
      
        </div>
      
        <div class="mb-10 capcha">
        <div class="g-recaptcha" id="tmaitb_cap"  data-sitekey="<?php echo getSiteKey(); ?>" data-callback="tychrecaptcharCallback">
        </div>
        <span id="f_captcha" class="f_captcha error captcha_error"></span>
        </div>

       

      <button type="submit" class="forget_sub" id="embed-submit" >{{trans('app_lang.confirm') }}</button>

    {!! Form::close() !!}
    </div>
    <div class="form-page-footer-cnt">
  
    </div>
  </div>
</div>

<div class="login_footer">
      <p><?php echo getcopyright(); ?></p>
    </div>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script>

  var field_require_for ="{{trans('app_lang.field_require') }}"; 
  var user_exist_for ="{{trans('app_lang.user_not_exists') }}"; 
  var valid_email_for ="{{trans('app_lang.enter_valid_email') }}"; 
  var gcapcha = 'forgot'; 
  var form = document.getElementById('forgot');
  form.addEventListener("submit", function(event)
  {
    if (grecaptcha.getResponse() === '') 
    {                            
      event.preventDefault();
      alert('Please check the recaptcha');
      $('#f_captcha').show();  
    }
  }, false);

  var tychrecaptcharCallback = function() {
   grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
  };
</script>
