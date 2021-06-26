<div class="container-fluid log_in_page">
    <div class="login-page post-fadeUp">
      <div class="form">
        
        {!! Form::open(array('class'=>'login-form', 'id'=>'tfa','url'=>'twofa','method'=>'POST','onsubmit'=>'tfa_load()')) !!}
        <div class="log_new_class">
       
              <div class="form-heading"><h2 class="form-heading">{{trans('app_lang.google_authentication') }}</h2></div>
           
        </div>
        <div class="log_new">
          <input type="text" name="tfa" class="tfa white-space-is-dead" placeholder="Enter 2FA code"/>
        </div>
          <button type="submit" class="tfa_sub">{{trans('app_lang.submit') }}</button>
        
          {!! Form::close() !!}
      </div>
      <div class="form-page-footer-cnt">
        <div class="form-page-footer">
            <p class="message left-message">{{trans('app_lang.back_to') }} <a href="<?php echo url('/');?>">{{trans('app_lang.home') }}</a></p>
        </div>
      </div>
    </div>
</div>

@include('front.common.footer')


<script>

  var require_field_tfa ="{{trans('app_lang.field_require') }}";
  var numbers_only_tfa ="{{trans('app_lang.enter_number_only') }}";
  var max_6_tfa ="{{trans('app_lang.max_6_digits') }}";
  var min_6_tfa ="{{trans('app_lang.min_6_digits') }}";
   
</script>

<script>
 

$(window).on('keydown',function(event)
    {
    if(event.keyCode==123)
    {
        return false;
    }
    else if(event.ctrlKey && event.shiftKey && event.keyCode==73)
    {
        return false;  
    }
    else if(event.ctrlKey && event.keyCode==73)
    {
        return false;  
    }
});
$(document).on("contextmenu",function(e)
{
e.preventDefault();
});



</script>


<script>
   $(document).on('focusout', ':input', function() {
    var str = $(this).val();
     var res = str.replace(/\</g, "");
    var res1 = res.replace(/\>/g, "");
    $(this).val(res1);
  });
</script>