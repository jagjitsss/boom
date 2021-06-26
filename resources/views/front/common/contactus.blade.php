<div class="container-fluid static-container static-content-section contact-section no-padding lt-grn-bg">
	<div class="contact-cnt">

		<div class="contact-heading">{{trans('app_lang.contact_us') }}</div>
		<div class="contact-head d-sm-flex justify-content-center">
			<div class="contact-head-cnt col"> 
				<div class="contact-head-icon d-flex justify-content-center"><img src="{{asset('/').('public/assets/images/contact-phone-icon.png')}}"></div>
				<div class="contact-head-txt">
				 <?php echo getSiteaddress('contact_number') ;?>
				</div>
			</div>
			<div class="contact-head-cnt col">
				<div class="contact-head-icon d-flex justify-content-center"><img src="{{asset('/').('public/assets/images/contact-marker-icon.png')}}"></div>
				<div class="contact-head-txt">
					<?php echo getSiteaddress('contact_address').','.getSiteaddress('city').','.getSiteaddress('country') ;?>
				</div>
			</div>
			<div class="contact-head-cnt col">
				<div class="contact-head-icon d-flex justify-content-center"><img src="{{asset('/').('public/assets/images/contact-msg-icon.png')}}"></div>
				<div class="contact-head-txt"><?php $email = getSiteaddress('site_email');echo insep_decode($email);?></div>
			</div>
		</div>
	</div>
	
	<div class="contact-page">
	  <div class="form">
		{!! Form::open(array('class'=>'register-form contact_captcha', 'id'=>'contactus','url'=>'contactus','method'=>'POST','onsubmit'=>'contact_load()')) !!}
			<div class="contact-half-txtbox">
				<input type="text" name="full_name" placeholder="{{trans('app_lang.your_full_name') }}" class=""/>
	      		<label for="full_name" class="error" style="display: none">This field is required</label>
			</div>
			<div class="contact-half-txtbox contact-half-rtbox">
				<input type="text" name="email_address" placeholder="{{trans('app_lang.email_address') }}" class=""/><br>
		  		<label for="email_address" class="error" style="display: none">This field is required</label>
			</div>
			<div class="contact-half-txtbox1">
				<input type="text" name="subject" placeholder="{{trans('app_lang.subject') }}"/>
		  		<label for="subject" class="error" style="display: none">This field is required</label>
			</div>
			
			<div class="contact-half-txtbox1">
			<textarea  placeholder="{{trans('app_lang.message') }}" name="message"></textarea>
			<label for="message" class="error" style="display: none">This field is required</label>
			</div>

       
   	  
       	<div class="mb-10 capcha">
        <div class="g-recaptcha" id="tmaitb_cap"  data-sitekey="<?php echo getSiteKey(); ?>" data-callback="tychrecaptcharCallback">
        </div>
        <span id="c_captcha" class="c_captcha error captcha_error"></span>
        </div>

       

		
		<div class="coins-btn">
			<button class="btn-change3 contact_sub" id="embed-submit" type="submit" >{{trans('app_lang.send_enquiry') }}</button>
		</div>
		{!! Form::close() !!}
	  </div>
	</div>
</div>
<div class="container-fluid marquee-cnt">
	<div class="body-content">
		<div id="demo4" class="scroll-img">
		  <ul>
		  <?php $currency_pairs_details = currency_pairs_details();
           
		  ?>
		  @foreach($currency_pairs_details as $pair_details)
		  <?php
if ($pair_details) {
	$today_open = $pair_details->last_price;
	$bitcoin_rate = $pair_details->yesterday_price;
	$daily_change = $today_open - $bitcoin_rate;
	$arrow = ($today_open > $bitcoin_rate) ? "+" : "";
	$class = ($today_open >= $bitcoin_rate) ? "up" : "down";
	?>
			<li><a href="{{url('/trade')}}/{{$pair_details->to_symbol}}_{{$pair_details->from_symbol}}" target="_blank">
				<span class="crypcoss-icon"><img src="{{asset('/').('public/images/admin_currency/').$pair_image[$pair_details->to_symbol]}}"> {{$pair_details->to_symbol}} /</span>
				<span>{{$pair_details->from_symbol}}</span>
				<span><i class="fa fa-fw fa-caret-{{$class}}"></i></span>
				<?php 
				$price = $pair_details->last_price;?>
				<span><?php echo number_format($price,8,'.','');?></span>
			</a>
			</li>
			<?php } ?>
			@endforeach
			
		  </ul>
		</div>
	</div>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
<script>


  var require_field_con ="{{trans('app_lang.field_require') }}";
  var valid_email_con ="{{trans('app_lang.enter_valid_email') }}";
  var letter_space_con ="{{trans('app_lang.letter_space_allowed') }}";
  var min_4_char_con ="{{trans('app_lang.enter_4_char') }}";
  var gcapcha = "contact";

  var form = document.getElementById('contactus');
  form.addEventListener("submit", function(event)
  {
    if (grecaptcha.getResponse() === '') 
    {                            
      event.preventDefault();
      alert('Please check the recaptcha');
      $('#c_captcha').show();  
    }
  }, false);

   var tychrecaptcharCallback = function() {
   grecaptcha.render('tmaitb_cap', {'sitekey' : '<?php echo getSiteKey(); ?>'});
  };
</script>

