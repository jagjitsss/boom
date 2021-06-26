jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z_ ]+$/i.test(value);
}, "Letters only please");

//Register Form Check
 if (require_field_reg === null || email_exist_reg === null || pwd_min_reg === null || check_pwd_reg === null || same_pwd_reg === null)
 {
    var require_field_reg = '';
    var email_exist_reg = '';
    var pwd_min_reg = '';
    var check_pwd_reg = '';
    var same_pwd_reg = '';

 }

 //Login Form Check
 if(field_require_log === null || valid_email_log === null){
   var field_require_log = '' ;
   var valid_email_log = '';
 }

 //Forgot Form Check
 if(field_require_for === null || user_exist_for === null || valid_email_for === null){
  var field_require_for = '';
  var user_exist_for = '';
  var valid_email_for = '';
 }

 //Reset Form Check
 if(field_require_res === null || pwd_min_res === null || check_pwd_res === null || same_pwd_res === null || conf_pwd_min_res == null){
  var field_require_res = '';
  var pwd_min_res = '';
  var check_pwd_res = '';
  var same_pwd_res = '';
  var conf_pwd_min_res = '';
 }

  //TFA Form Check
 if(require_field_tfa === null || numbers_only_tfa === null || max_6_tfa === null || min_6_tfa === null){
   var require_field_tfa = '';
   var numbers_only_tfa = '';
   var max_6_tfa = '';
   var min_6_tfa = '';
 }

 //Add Coin Form Check
 if(require_field_cos === null || coin_min_cos === null || coin_max_cos === null || file_cos === null || valid_url === null){
   var require_field_cos = '';
   var coin_min_cos = '';
   var coin_max_cos = '';
   var file_cos = '';
   var valid_url = '';
 }

 //Referral Form Check
 if(require_field_ref === null || valid_email_ref === null || address_copy == null || referral_email_sent == null || empty_msg === null){
   var require_field_ref = '';
   var valid_email_ref = '';
   var address_copy = '';
   var referral_email_sent = '';
   var empty_msg = '';
 }

 //Support Form Check
 if(require_field_sup === null || only_files_sup === null || ticket_updated == null || ticket_closed == null){
  var require_field_sup = '';
  var only_files_sup = '';
  var ticket_closed = '';
  var ticket_updated = '';
 }

 //Change Password Check
 if(require_field_chp === null || current_pwd_chp === null || new_pwd_chp === null || same_pwd_chp === null || cur_pwdmin_chp === null || new_pwdmin_chp === null){
  var require_field_chp = '';
  var current_pwd_chp = '';
  var new_pwd_chp = '';
  var same_pwd_chp = '';
  var cur_pwdmin_chp = '';
  var new_pwdmin_chp = '';
 }

 //Notification Check
 if(success_notfy === null || error_notfy === null || trade_msg == null || tfa_msg == null || password_msg == null || device_msg == null || success_notfy_dis == null){
  var success_notfy = '';
  var error_notfy = '';
  var trade_msg = '';
  var tfa_msg  = '';
  var password_msg = '';
  var device_msg = '';
  var success_notfy_dis = '';
 }

 //Profile Form Check
 if(profile_btn == null|| min_3_prof === null || max_15_prof === null || valid_no_prof === null || letter_space_prof === null || min_5_prof === null || min_6_mob_prof === null || max_12_mob_prof === null || files_prof === null){
  var min_3_prof = '';
  var max_15_prof = '';
  var valid_no_prof = '';
  var letter_space_prof = '';
  var min_5_prof = '';
  var min_6_mob_prof = '';
  var max_12_mob_prof = '';
  var files_prof = '';
  var profile_btn  = '';
  
 }

 //tfa
 if(enter_6_char === null || upto_6_char === null){
  var enter_6_char = '';
  var upto_6_char = '';
 }



  $('#register').validate({  
    rules: {      
      email: {
        email: true,
        required: true,
        remote:{
        url: "validatemail",
        type: 'GET',
        data: {
          email: function() {
            return $('#register #email').val();
          },
          type: function() {
            return 1;
          }
        }
      }
      },
      iagree: {
        required: true,
      },
      country: {
        required: true,
      },
     
       password: {
        required: true,
        minlength:8,
        checkpassword:true,
      },
        password_confirmation: {
        required: true,
        minlength: 8,
        equalTo:'#password'
      },
      /*otp_num:{
         required: true,
         remote: {
          url: siteurl+'/checkotp',
          type: "post",
          data: {
            otpcode: function() {
              return $("#otp_num").val();
            },
             mobileno: function() {
              return $("#mobileno").val();
            }
          }
        }

      },*/
      mobileno:{
         required: true,
     
          number:true,
          minlength:8,
          maxlength:12,
        remote: {
          url: siteurl+'/checkmobile',
          type: "post",
          data: {
            mobile_otp: function() {
            return $("#mobileno").val();
            }
          },          
          /*success: function(data) {
                var mob_num = $.trim($("#mobileno").val());
                // if(mob_num != '' || mob_num != null || mob_num.length  < 8 || mob_num.length  > 12){}
                    if(data == "true" && (mob_num != '' || mob_num != null || mob_num.length  < 8 || mob_num.length  > 12))
                    {
                      console.log("-----------> true"+data);
                      // console.log(data);
                      //$('#moberr').hide();
                      $('#mobileno').attr('disabled', true);
                    }
                    else
                    {
                      console.log("------------>false"+data);
                      $('#mobileno').attr('disabled', true);
                      // $('#mobileno').attr('disa bled', false);
                      //$('#moberr').show();
                      //$('#moberr').html('This mobile number already exsits');

                    }
                }*/
        }
      },
      iagree: {
                required: true
            },
             "hidden-grecaptcha": {
              required: true,
              minlength: "255"
            },
         
    }, 
    messages: {
          
          email: {
              required: require_field_reg,
              remote: email_exist_reg,
          },
          password: {
            required: require_field_reg,
            minlength:pwd_min_reg,
            checkpassword: check_pwd_reg,
          },
          password_confirmation: {
            required: require_field_reg,
            equalTo: same_pwd_reg,
            minlength:pwd_min_reg,
          },
          iagree:{
            required: require_field_reg,
          },
           mobileno: {
            required: require_field_reg,
     
      
      remote : "This mobile number already exsits"
    
      }
     /* otp_num:{
        required : require_field_reg,
        remote: "Invalid OTP",
      },*/
         
      },
      submitHandler: function(form) {
          $('.show_errmsg').html('');
      
          if($('#otp_num').val()=='')
          {
            $('.show_errmsg').html('<div class="alert alert-danger show_error1"><a href="javascript:;" class="close" data-dismiss="alert" aria-label="close">&times;</a><span>Please Enter your OTP</span></div>');
          }
          else{
            form.submit();
         
      }
     
    }
  });


$(document).on('click','.changemob',function()
{
  //$('#mobileno').attr('disabled',false);
  $("#mobileno").attr("readonly", false); 
  //$('.mobmess').html('To change your mobile number ');              
  //$('.changemob').html('click here');
  $('.changemob').hide();
  $('.mobmess').hide();
});

$(document).on('click','.sendotp',function()
{

    var mobileno=$('#mobileno').val();
   
if(mobileno!=''){
    $.ajax({
        type:'post',
        url: siteurl+'/sendmobileotp',
        data:{'key':mobileno},
        beforeSend: function() {
            $('#loaddd').html(" <i style='font-size:18px' class='fa fa-spinner fa-pulse fa-spin'></i>");
            $('#loaddd').attr('disabled',true);
        },
    
        success:function(data)
        {
             $('#loaddd').attr('disabled',false);
             $('#loaddd').html("");
            
            if(data=='1')
            {

              $(this).html('Resend OTP');
               
            $('.otp_enable').show();
            $('#otp').show();
            $('#otp_enable_icon').show();

            }
            else
            {
                $('.show_error span').html('Invalid Mobile number');
               $('.show_error').show();
            }
        }
       });
}
else
{
   $('.show_error span').html('Enter Mobile number');
               $('.show_error').show();
}

});

  $('#sendotpbut').click(function(){
    var mobileno=$('#mobileno').val();
    var country=$('#country').val();
    if(mobileno!='') {
      if(country!=''){
        //setTimeout(function(){
        if($('#mobileno').valid()){
          //$('#mobileno').attr('disabled', true);
          $("#mobileno").attr("readonly", true); 
          $.ajax({
              type:'post',
              url: siteurl+'/sendmobileotp',
              data:{'key':mobileno,'country':country},
              beforeSend: function() {
                  $('#sendotpbut').attr('disabled',true);
                  $('#sendotpbut').html('Loading <i class="fa fa-spinner fa-spin"></i>');               
              },        
              success:function(data)
              {
                $('#sendotpbut').attr('disabled',false);
                $('#sendotpbut').html(sendotp);
                if(data!='')
                {             
                 /* $('.sendotp').html('Resend OTP');
                  $('.sendotp').show();
                  $('.mobmess').html('To change your mobile number ');              
                  $('.changemob').html('click here');
                  $('.changemob').show();
                  $('.mobmess').show();*/
                  $('.otp_enable').show();
                  $('#otp_enable_icon').show();
                }
                else
                {
                  $('.show_error span').html('Invalid Mobile number');
                  $('.show_error').show();
                }
              }
          });
        }    
        else
        {
          //$('#mobileno').attr('disabled', false);
          //$("#mobileno").attr("readonly", false); 
          
        }
        //}, 1000);
      }
      else
      {
        //alert('Choose Country');
        $('#coun_error').html('Choose country');
        $('#coun_error').show();
      }
    }
    else
    {
      //alert('Enter mobile number');
      $('#mob_error').html('Enter mobile number');
      $('#mob_error').show();
    }
  });
  $('#reset').validate({  
    rules: {      
       password: {
        required: true,
        minlength: 8,
        checkpassword:true,
      },
        password_confirmation: {
        required: true,
        minlength: 8,
        equalTo:'#password'
      },
   
    }, 
    messages: {
          
          password: {
            required: field_require_res,
            minlength:pwd_min_res,
            checkpassword: check_pwd_res,
          },
          password_confirmation: {
            required: field_require_res,
            equalTo: same_pwd_res,
            minlength:conf_pwd_min_res,
          },
         
      }
  });
  
  $('#login').validate({  
    rules: {      
      email: {
        email: true,
        required: true,
      },
      password: {
        required: true,
        minlength:8,
        checkpassword:true
      },
      tmaitb_cap: {
        required: true,
      },
    },
    messages: {
          email: {
            required: field_require_log,
            email:valid_email_log,
          },
          password:{
            required: field_require_log,
            checkpassword: current_pwd_chp
          },
          tmaitb_cap:{
            required: field_require_log,
          },
      }



  });
  $('#tfa').validate({  
    rules: {      
      tfa: {
        required: true,
        number: true,
        minlength: 6,
        maxlength: 6,
      }
    },
    messages: {
          tfa: {
          required:require_field_tfa,  
          number:numbers_only_tfa,
          maxlength:max_6_tfa,
          minlength:min_6_tfa,
        }
      }
   
  });
  function forget_load(){
 
      if ($('#forgot').valid() == true) {
    
        $('.forget_sub').attr('disabled',true);
        $('.forget_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
    
    }else{
        $('.forget_sub').attr('disabled',false);
        $('.forget_sub').html(forgot);
    }
  
  }
  $('#forgot').validate({  
    rules: {      
      email: {
        required: true,
        email: true,
        remote:{
        url: "validatemail",
        type: 'GET',
        data: {
          email: function() {
            return $('#forgot #email').val();
          },
          type: function() {
            return 2;
          }
        }
      }
      }
    },
    messages: {
          email: {
            required: field_require_for,
            remote:user_exist_for,
            email:valid_email_for,
        }
      }
   
  });
  
  /*jQuery.validator.addMethod("checkpassword", function(value, element) {
    return (/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/.test(value));
  }, "Password must have minimum 8 characters at least 1 Alphabet, 1 Number and 1 Special Character");
*/
  $.validator.addMethod("checkpassword", function (value)
  {
    return /[\@#\$\%\^\&*()_+!]/.test(value) && /[a-z]/.test(value) && /[0-9]/.test(value) && /[A-Z]/.test(value)
  },"Password must have minimum 8 characters at least 1 Uppercase,1 Lowercase, 1 Number and 1 Special Character");
  

  $('.white-space-is-dead').change(function() {    
      $(this).val($(this).val().replace(/ /g,""));
  });

$('#change_password').validate({  
    rules: {      
       oldpassword: {
        required: true,
        minlength: 8,
        checkpassword:true,
      },
       password: {
        required: true,
        minlength: 8,
        checkpassword:true,
      },
       password_confirmation: {
        required: true,
        minlength: 8,
        equalTo:'#password'
      },
   
    }, 
    messages: {
      
      oldpassword: {
        required: require_field_chp,
        minlength: cur_pwdmin_chp,
        checkpassword: current_pwd_chp,
      },
      password: {
        required: require_field_chp,
        minlength:new_pwdmin_chp,
        checkpassword: new_pwd_chp,
      },
      password_confirmation: {
        required: require_field_chp,
        equalTo: same_pwd_chp,
        minlength:new_pwdmin_chp,
      },
      }
  });

$('#add_support').validate({  
    rules: {      
      category: {
        required: true,
      },
       subject: {
        //alphanumeric:true,
        required: true,
      },
      description: {
        required: true,
      },
      email: {
        email: true,
        required: true,
      },
      file: {
        accept: "jpg,png,jpeg",
      },
   
    }, 
    messages: {
          file: {
            accept: only_files_sup,
          },
          category: {
            required: require_field_sup,
          },
          subject: {
            required: require_field_sup,
          },
          description:{
            required: require_field_sup,
          },
          email:{
            required: require_field_sup,
          },
      }
    
  });
 $("#bank_wire").validate({
    rules: {
      accountholdername: {
        required: true,
        lettersonly: true,
        },
      accountnumber: {
        required: true,
        number: true,
      },
      swift: {
        required: true,
      },
      routing: {
        required: true,
      },
      bankname: {
        required: true,
        lettersonly:true,
      },
      bankaddress: {
          required: true,
      },
    },
  });
$('#referral').validate({  
    rules: {      
       referral_email: {
        required: true,
        email: true,
      },
   
    }, 
    messages: {
          
          referral_email: {
            required: require_field_ref,
            email: valid_email_ref,
          },
         
      },
    submitHandler: function(form) {

      var formData = new FormData(form);
      $('#submitBtnref').html('Loading <i class="fa fa-spinner fa-spin"></i>');
      $('#submitBtnref').attr('disabled',true);
      $.ajax({
      url : siteurl+"/referral_request",
      data : formData,
      method : "POST",
      // async:true,
      cache:false,
      contentType:false,
      processData:false,
      
      success : function(data) {
        $('#referral')[0].reset();
        $('#submitBtnref').html('Send');
        $('#submitBtnref').removeAttr('disabled');
        if(data == 1){
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+referral_email_sent+" ", type: "success" });
        }else{
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+data, type: "error" });
        }

      },
      error : function(error) {
        notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" Error! Please try again'", type: "error" });
        $('#referral')[0].reset();
        $('#submitBtnref').html('Send');
        $('#submitBtnref').removeAttr('disabled');
      }
      });

    } 
    
  });

$('#search_ticket').keyup(function(){
   var query = $.trim($('#search_ticket').val()).toLowerCase();
    $('b.div_searc').each(function(){
          var $this = $(this);
          if($this.text().toLowerCase().indexOf(query) === -1)
          {
            $this.closest('div.card').fadeOut();
            setTimeout(function(){
              if($('.div_searc:visible').length == 0)
                $('.no_recorder1').show();
                $('.no_recorder').hide();
             }, 500);
          }
          else
          {
            $this.closest('div.card').fadeIn();
            setTimeout(function(){
            if($('.div_searc:visible').length != 0)
              $('.no_recorder1').hide();
              $('.no_recorder').hide();
           }, 500);
          }
    });
});



function copy_text_tag(id){
  $('#'+id).select();
  document.execCommand('copy');
  notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i> '+address_copy+" ", type: "success" });
}
$('#kyc').validate({  
  ignore: "not:hidden",
    rules: {      
  file1:{
      required:true,
      accept: "jpg,png,jpeg",
    },
  file2:{
      required:true,
      accept: "jpg,png,jpeg",
    },
    file3:{
        required:true,
        accept: "jpg,png,jpeg",
      },
   
    }, 
    messages: {
          
          file1: {
            required: require_field_chp,
            accept: files_prof,
          },
          file2: {
            required: require_field_chp,
            accept: files_prof,
          },
          file3: {
            required: require_field_chp,
            accept: files_prof,
          },
         
      }
  });

jQuery.validator.addMethod("lettersspace", function(value, element) {
    return this.optional(element) || /^[a-z," "]+$/i.test(value);
   }, "Letter Space only allowed");


function signup_load(){
 
      if ($('#register').valid() == true) {    
        $('#otp_num').show();
        $('#otp_enable_icon').show();
        var otp = $('#otp_num').val();
        if(otp != '')
        {        
          $('.signup_sub').attr('disabled',true);
          $('.signup_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
        }
        else
        {
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+otp_require+' ', type: "error" });
        }
    
    }else{
        //$('#mobileno').attr('disabled', false);
        //$("#mobileno").attr("readonly", false); 
        $('.signup_sub').attr('disabled',false);
        $('.signup_sub').html(signup);

    }
  
  }
function login_load(){
if (grecaptcha.getResponse() === '') 
    {                            
      event.preventDefault();
       $('.captcha_error').show(); 
      $('.captcha_error').html('Please check the recaptcha');
      $('#log_captcha').show();  
    }else{
      if ($('#login').valid() == true) {
        $('.captcha_error').html('');
        $('.login_sub').attr('disabled',true);
        $('.login_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');


        }else{
              $('.login_sub').attr('disabled',false);
              $('.login_sub').html(login);
        }
    }
}
function profile_load(){
  /*if ($('#profile').valid() == true) {
        $('.profile_update').attr('disabled',true);
        $('.profile_update').html('Loading <i class="fa fa-spinner fa-spin"></i>');
       
   }else{
        $('.profile_update').attr('disabled',false);
        $('.profile_update').html(profile_btn);
   }*/
   
}
function support_load(){
  if ($('#add_support').valid() == true) {
        $('.support_create').attr('disabled',true);
        $('.support_create').html('Loading <i class="fa fa-spinner fa-spin"></i>');
  }else{
        $('.support_create').attr('disabled',false);
        $('.support_create').html(support);
   }
   
}
function kyc_load(){
  if ($('#kyc').valid() == true) {
        $('.kyc_sub').attr('disabled',true);
        $('.kyc_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
  }else{
        $('.kyc_sub').attr('disabled',false);
        $('.kyc_sub').html(submit);
   }
   
}
function bank_load(){
  if ($('#bank_wire').valid() == true) {
        $('.bankwire_sub').attr('disabled',true);
        $('.bankwire_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
  }else{
        $('.bankwire_sub').attr('disabled',false);
        $('.bankwire_sub').html(bankwire);
   }
   
}
$('#profile').validate({  
  ignore: "not:hidden",
    rules: {      
       first_name: {
        required: true,
        minlength: 3,
        maxlength:15,
        lettersspace: true,
      },
      pincode:{
        required:true,
        minlength:5
      },
    }, 
    messages: {
        first_name: {
            required: require_field_chp,
            minlength: min_3_prof,
            maxlength: max_15_prof,
            lettersspace: letter_space_prof,
          },
          pincode:{
            required: require_field_chp,
            minlength: min_5_prof,
          },
      }
  });
 /* $(document).ready(function(){
      var dialcode = $('#code').val();
      var code = dialcode.split("+");
      var flagcode = code[1];
      $(".iti-arrow").trigger("click");
      $(".country-list").find("li").filter('[data-dial-code="'+flagcode+'"]').click();
    });
  $("#mobile").intlTelInput({
      utilsScript: siteurl + "/public/build/js/utils.js"
    });*/
  $('#datepicker').on('change', function() {
        $(this).valid();  
   });

  function tfa_load(){
  if ($('#tfa').valid() == true) {
        $('.tfa_sub').attr('disabled',true);
        $('.tfa_sub').html('Loading <i class="fa fa-spinner fa-spin"></i>');
  }else{
        $('.tfa_sub').attr('disabled',false);
        $('.tfa_sub').html(submit);
   }
   
}
$('#edittfa').validate({  
    rules: {      
       onecode: {
        required: true,
        number: true,
        minlength: 6,
        maxlength:6,
      },
      psswd: {
         required: true,
         /*remote: {
          url: siteurl+'/checkpassword',
          type: "post",
          data: {
            password: function() {
              return $("#psswd").val();
            }
          }
        }*/
      },
  },
  messages: {
          onecode: {
            required: require_field_chp,
            minlength: enter_6_char,
            maxlength: upto_6_char,
            number:valid_no_prof,
          },
          psswd: {
            required: require_field_chp,
            //remote: pass_wrong,
          },
        }
});



$('#addcoins').validate({  
  ignore:'',
    rules: {

       coin_name: {
        required: true,
        minlength: 3,
        maxlength:20,
      },
      coin_symbol: {
        required: true,
        minlength: 3,
        maxlength:20,
      },
      file: {
        required: true,
        accept: "jpg,png,jpeg",
       
      },
      coin_website:{
        required: true,url:true
      },
      coin_chat:{
        required: true,url:true
      },
      coin_git:{
        required: true,url:true
      },
      coin_explorer:{
        required: true,
        url:true
      },
      iagree:{
        required: true,
      }
  
    }, 
    messages: {

          file: {
            required: require_field_cos,
            accept: file_cos,
          },
          coin_name: {
            required: require_field_cos,
            minlength: coin_min_cos,
            maxlength: coin_max_cos,
          },
          coin_symbol: {
            required: require_field_cos,
            minlength: coin_min_cos,
            maxlength: coin_max_cos,
          },
          coin_website: {
            required: require_field_cos,
            url: valid_url,
          },
          coin_chat: {
            required: require_field_cos,
            url: valid_url,
          },
          coin_git: {
            required: require_field_cos,
            url: valid_url,
          },
          coin_explorer: {
            required: require_field_cos,
            url: valid_url,
          },
          iagree:{
            required: require_field_cos,
          },
        
    },
    submitHandler: function(form) {

      if(fees == 'yes')
        $('#myModal').modal('show');
      else
        form.submit();

     } 
    
  });

$('#accept').click(function(){ 
   
   if ($("#addcoins").valid()) 
          {
            $('#addcoins')[0].submit();
            $('.wit_btn').attr('disabled',true);
          }

});

$('#cancel').click(function(){ 
   $('#addcoins')[0].reset();
   $('.thumbnil_coin_logo').hide();
});

function notification(type){
  var _token = $("input[name='_token']").val();
  if(type == 'trade'){
     var text = trade_msg;
  }else if(type == '2fa'){
    var text = tfa_msg;
  }else if(type == 'password'){
    var text = password_msg;
  }else if(type == 'device'){
    var text = device_msg;
  }
  $.ajax({
      url:"updateAlert",
      method:"POST",
          headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
          data: { 'type':type ,'_token':_token },
          success:function(res) {
            var res = res.replace(/(\r\n|\n|\r)/gm,"");

            if(res == "1") {
              notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+text+" "+success_notfy_dis+" ", type: "success" });
            }
            else if(res == "2") {
              notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+text+" "+success_notfy+" ", type: "success" });
            } 
             else {
              notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+error_notfy+" ", type: "error" });
            }
          },
          error:function() {
        notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+error_notfy+" ", type: "error" });
      }
    });
}
if(user_id!=0){
   $('#login_notification').DataTable
         ({
          "destroy": true,
          "sServerMethod": "GET",
           "columns": [
          { className: "table-sno-cnt" },
            null,
            null,
            null,
            null
            ],
          //"pageLength": 1,
          "processing": true,
          "serverSide": true,
          oLanguage: {sProcessing: "<div id='loader'><i style='font-size:30px' class='fa fa-spinner fa-pulse fa-spin'></i></div>"},
              "ajax": {
            "url": siteurl+"/getDevices"
          },
          
        });
         $('#login_notification_length').css('display','none');
         $('#login_notification_info').css('display','none');
         // $('#login_notification_paginate').css('display','none');
         $('#login_notification_filter').css('display','none');
          $.fn.dataTable.ext.errMode = 'none';
       $('#login_notification,#refer_history_tbl').on( 'error.dt', function ( e, settings, techNote, message ) {
         // location.reload(); 
      console.log( 'An error has been reported by DataTables: ', message );
    });
        $('#refer_history_tbl').DataTable
         ({
          "destroy": true,
          "sServerMethod": "GET",
          //"pageLength": 1,
          "processing": true,
          "serverSide": true,
          oLanguage: {sProcessing: "<div id='loader'><i style='font-size:30px' class='fa fa-spinner fa-pulse fa-spin'></i></div>",sEmptyTable: empty_msg},
              "ajax": {
            "url": "referalHistory"
          },
          
        });
         $('#refer_history_tbl_length').css('display','none');
         $('#refer_history_tbl_info').css('display','none');
         $('#refer_history_tbl_filter').css('display','none');
          $.fn.dataTable.ext.errMode = 'none';
          $('#ref').DataTable
         ({
          "destroy": true,
          "sServerMethod": "GET",
          //"pageLength": 1,
          "processing": true,
          "serverSide": true,
          oLanguage: {sProcessing: "<div id='loader'><i style='font-size:30px' class='fa fa-spinner fa-pulse fa-spin'></i></div>",sEmptyTable: empty_msg},
              "ajax": {
            "url": "referalList"
          },
          
        });
         $('#ref_length').css('display','none');
         $('#ref_info').css('display','none');
         $('#ref_filter').css('display','none');
          $.fn.dataTable.ext.errMode = 'none';
     }
  function show_ticket(ticketno){

    $('#'+ticketno+' .mCSB_container').html('<i class="fa fa-spinner fa-spin"></i>');
  $.ajax({
      url:siteurl+"/ticketdetails"+'/'+ticketno,
      method:"GET",
          headers: {'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
          success:function(res) {
              $('.submitBtnsupport').html('<i class="fa fa-fw fa-paper-plane fa-minus-circle"></i>');
              $('.submitBtnsupport').removeAttr('disabled');


            // $('deetails').mCustomScrollbar({
            //       scrollButtons: {
            //       enable: false
            //       },

            //       scrollbarPosition: 'inside',
            //       autoExpandScrollbar: true,
            //       theme: 'minimal-dark',
            //       axis: "y",
            //       setWidth: "auto"
            //     });



           $('#'+ticketno+' .mCSB_container').html(res);
           $('.message-cnt-ht').mCustomScrollbar('scrollTo','bottom');
          },
          error:function() {
        notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+"Please try again", type: "error" });
      }
    });
  }

/*$(document.body).on('click', '.submitBtnsupport', function(){
       $("#edit_support").validate({
        rules: {      
         comment: {
          required: true,
        },
        file:{
           accept: "jpg,png,jpeg",
        },
        },
      messages: {
          
          comment: {
            required : require_field_sup,
          },
          file: {
            accept: only_files_sup,
          },
         
      },
       submitHandler: function(form) {
         var formData = new FormData(form);
         $('.submitBtnsupport').html('<i class="fa fa-spinner"></i>');
            $('.submitBtnsupport').attr('disabled');
      $.ajax({
      url : siteurl+"/edit_support",
      data : formData,
      method : "POST",
      async:false,
      cache:false,
      contentType:false,
      processData:false,
      success : function(data) {
        if(data == 1){
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+ticket_updated+" ", type: "success" });
          show_ticket($('#edit_ref_no').val());

        }else if(data == 2){
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+ticket_closed+" ", type: "success" });
          show_ticket($('#edit_ref_no').val());
        }
        else{
              $('#edit_support')[0].reset();
              $('.submitBtnsupport').html('Send');
                $('.submitBtnsupport').removeAttr('disabled');
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+data, type: "error" });
        }

      

      },
      error : function(error) {
        notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" Error! Please try again'", type: "error" });
         $('#edit_support')[0].reset();
           $('.submitBtnsupport').html('Send');
               $('.submitBtnsupport').removeAttr('disabled');

      }
      });

         } 
    });
 });*/


// new form validate
$(document.body).on('click', '.submitBtnsupport', function(){
$('.edit_support').each( function(){

var form = $(this);
// alert($(this).find('input #edit_ref_no').val());
form.validate({

        rules: {      
         comment: {
          required: true,
        },
        file:{
           accept: "jpg,png,jpeg",
        },
        },
      messages: {
          
          comment: {
            required : require_field_sup,
          },
          file: {
            accept: only_files_sup,
          },
         
      },
       submitHandler: function(form) {
         var formData = new FormData(form);

        
      $.ajax({
      url : siteurl+"/edit_support",
      data : formData,
      method : "POST",
      async:false,
      cache:false,
      contentType:false,
      processData:false,
      beforeSend: function() {
        $('.submitBtnsupport').html('<i class="fa fa-spinner fa-spin"></i>');
         $('.submitBtnsupport').attr('disabled');
     },
      success : function(data) {
           var data=JSON.parse(data);
           var status=data.status;
           var check_no=data.check_no;
           $('.comment_txt').val('');
           $('.thumbnil_tiks').hide();
        if(status == 1){
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+ticket_updated+" ", type: "success" });
          show_ticket(check_no);

        }else if(status == 2){
          notif({ msg: '<i class="fa fa-check-circle" aria-hidden="true"></i>'+" "+ticket_closed+" ", type: "success" });
          // show_ticket(check_no);
          location.reload();
        }
        else{
              $('.message-txt-box').val(' ');
              $('.submitBtnsupport').html('<i class="fa fa-fw fa-paper-plane fa-minus-circle"></i>');
              $('.submitBtnsupport').removeAttr('disabled');
          notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+data, type: "error" });
        }

       $('.edit_support')[0].reset();

      },
      error : function(error) {
        notif({ msg: '<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'+" Error! Please try again'", type: "error" });
         $('.edit_support')[0].reset();
           $('.submitBtnsupport').html('<i class="fa fa-fw fa-paper-plane fa-minus-circle"></i>');
               $('.submitBtnsupport').removeAttr('disabled');

      }
      });

         } 
    
})
});

});

$(document).ready(function() {
   $('[data-toggle="tooltip"]').tooltip();
});

 $("#new-tick").click(function(){
       $('#support-newtick').show();
       $("#support").hide();
    });
   $("#new-tick1").click(function(){
       $("#support").show();
       $('#support-newtick').hide();

    });



     // $("#register").submit(function() {
      
     //  if(grecaptcha.getResponse()){
     //    $('.reg_captcha').html('');
     //  }else{
     //    $('.reg_captcha').html(require_field_reg);
     //    return false;
     //  }

      
     // });
     // $("#login").submit(function() {
      
     //  if(grecaptcha.getResponse()){
     //    $('.log_captcha').html('');
     //  }else{
     //    $('.log_captcha').html(field_require_log);
     //    return false;
     //  }
     // });

     // $("#forgot").submit(function() {
      
     //  if(grecaptcha.getResponse()){
     //    $('.f_captcha').html('');
     //  }else{
     //    $('.f_captcha').html(field_require_for);
     //    return false;
     //  }

      
     // });
     
     //  $("#reset").submit(function() {
      
     //  if(grecaptcha.getResponse()){
     //    $('.r_captcha').html('');
     //  }else{
     //    $('.r_captcha').html(field_require_res);
     //    return false;
     //  }
     // });

/*if(gcapcha){

  var handlerEmbed = function (captchaObj) {
        $("#embed-submit").click(function (e) {
            var validate = captchaObj.getValidate();
            if (!validate) {
                $("#notice")[0].className = "show";
                setTimeout(function () {
                    $("#notice")[0].className = "hide";
                }, 2000);
                e.preventDefault();
            }
        });

        // Add the verification code to the element with id captcha, and there will be three input values: geetest_challenge, geetest_validate, geetest_seccode
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
        // More interface reference：http://www.geetest.com/install/sections/idx-client-sdk.html
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        //url: "../web/StartCaptchaServlet.php?t=" + (new Date()).getTime(), // Add random numbers to prevent caching
        url: siteurl+"/captcha",// Add random numbers to prevent caching
        type: "get",
        dataType: "json",
        success: function (data) {
            //console.log(data);
            // Use the initGeetest interface
            // Parameter 1: Configuration parameters
            // Parameter 2: Callback, the first parameter of the callback to validate the code object, which can then be used to do events like appendTo
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                new_captcha: data.new_captcha,
                product: "embed", // Product form, including: float, embed, popup. Note that only valid for PC version verification code
                lang: 'en',
                offline: !data.success // Indicates whether the user's background detection server is down, and generally does not need to pay attention
                // For more configuration parameters, please see: http://www.geetest.com/install/sections/idx-client-sdk.html#config
            }, handlerEmbed);
        }
    });
    
}*/


  $('.new_tic').on('click', function(){
    $("#subject").val(' ');
    $("#comment").val(' ');
    $("#sel1").val(' ');
});

 function showPopupImage(val) {
  // Get the modal
  var modal = document.getElementById('myModal');

  // Get the image and insert it inside the modal - use its "alt" text as a caption
  var img = document.getElementById(val);
  var modalImg = document.getElementById("img01");
  var captionText = document.getElementById("caption");
  // img.onclick = function(){
  //     modal.style.display = "block";
  //     modalImg.src = this.src;
  //     captionText.innerHTML = this.alt;
  // }

  modal.style.display = "block";
  modalImg.src = img.src;

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() { 
      modal.style.display = "none";
  }
}

  $('.comment_txt').on('keydown', function(event) {
    if (event.keyCode == 13)
    if (!event.shiftKey){
      $('.submitBtnsupport').click();
      return false;
    } 
  });



jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
}, "Special characters are not allowed");
  

$( document ).ready(function() {

   if(window.location.search!='')
   {
      if(name!='')
      { 
        if(name == 'notification')
        {
          $('html, body').animate({
              scrollTop: $("#notify").offset().top
          }, 500);
        }
      }
   }

});


//Captcha
if(gcapcha == null){
  var gcapcha = '';
 }

/*var recaptcharCallback = function() {
  grecaptcha.render('Captcha', {'sitekey' : ''});
};*/

$('#closediv').click(function(){
    $('#referalinfo').hide();
  });
  
$('#viewtick').click(function(){
$("#viewtickets").css("display", "block");
});


$('#sel1').change(function(){
$("#supporthide").css("display", "block");
});


$('#request-attachments').change(function(e){
  var fileName = e.target.files[0].name;
  $('#uplist').show();
  $('#upimg').html(fileName);
});
$('#upload-remove').click(function(){
  $('#uplist').hide();
});

function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : evt.keyCode
      if ((charCode > 34 && charCode < 41) || (charCode > 47 && charCode < 58) || (charCode == 46) || (charCode == 8) || (charCode == 9))
          return true;
      return false;
}

$('#file1').change(function(){
  $('#viewex1').hide();
});
$('#file2').change(function(){
  $('#viewex2').hide();
});
$('#file3').change(function(){
  $('#viewex3').hide();
});