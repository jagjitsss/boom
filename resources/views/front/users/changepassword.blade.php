 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<div class="container changepassword-box row">
    <div class="return">
        <div class="returnBox">
            <a href="<?php echo url('dashboard'); ?>" class="color-theme"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></i>
           {{trans('app_lang.back_to') }} {{trans('app_lang.account') }}
            </a>
            <p class="titled color-primary">{{trans('app_lang.change_password') }}</p>
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 align-self-stretch d-flex xl-p-l xl-p-r lg-p-r lg-p-l md-p-r">
    
        {!! Form::open(array('id'=>'change_password','url'=>'updatePassword','method'=>'POST','class'=>'')) !!}
        <div class="settings-security-cnt-height flex-column">
        <div class="row security-qr-cnt">
    
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
                <div class="profile-txtbox-label">{{trans('app_lang.current_password') }}<span style="color: red">*</span></div>
                <input type="password" name="oldpassword" id="oldpassword" placeholder="{{trans('app_lang.current_password') }}" class="profile-txtbox">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
                <div class="profile-txtbox-label">{{trans('app_lang.new_password') }}<span style="color: red">*</span></div>
                <input type="password" id="password" name="password" placeholder="{{trans('app_lang.new_password') }}" class="profile-txtbox">
                <div class="sc_vice">{{trans('app_lang.pwd_hint') }}</div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
                <div class="profile-txtbox-label">{{trans('app_lang.confirm_password_new') }}<span style="color: red">*</span></div>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{trans('app_lang.confirm_password_new')}}" class="profile-txtbox">
    
            </div>
        <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt"><div class="profile-txtbox-label"></div><button type="submit" class="dsb-blue-btn" id="passbut">{{trans('app_lang.update_password') }}</button></div>
    </div>
</div>

</div>
{!! Form::close() !!}
</div>


<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
    $('#passbut').attr('disabled',true);
  
    $('#password').keyup(function(){
        if($(this).val().length !=0)
            $('#passbut').attr('disabled', false); 
            //$('#passbut').css( 'cursor', 'hand' );            
        else
            $('#passbut').attr('disabled',true);
    });
    $('#password_confirmation').keyup(function(){
        if($(this).val().length !=0)
            $('#passbut').attr('disabled', false); 
           // $('#passbut').css( 'cursor', 'hand' );            
        else
            $('#passbut').attr('disabled',true);
    });
});
</script>