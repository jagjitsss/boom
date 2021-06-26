<style>
option {color: black;}
</style>
<div class="col-xs-12 col-sm-12">
 
 <div class="tab-content" id="myTabContent">
 
  <?php $i1 = app('request')->input('name');?>




  <div class="tab-pane fade" id="defaultcurrency" role="tabpanel" aria-labelledby="defaultcurrency-tab2">
    <div class="card-div tab-content col-xs-12 col-sm-12 no-padding">
      <div class="tab-pane container-fluid no-padding active mb-3" id="defaultcurrencyhistory">
        <div class="row">
          <div class="col-xs-12 col-sm-12 dsb-acc-status-cnt pad_zero">

            {!!Form::open(array('id'=>'defaultCurrencyForm','url'=>'choose-currency','method'=>'POST','class'=>'d-flex'))!!}

              <div class="card-div-cnt">

                
                  <div class="dsb-acc-status-heading">
                    Default Currency 
                    <span style="padding-left: 26px;"></span>
                  </div>
                

                 <div class="google_auth form-check">
                   <span>Choose Currency</span>
                   <p class="safecommon-text">
                      <select name="defaultcurrencyUpdate" id="defaultcurrencyUpdate">
                        <option value="1" <?php echo $select = ($user->set_default_currency == "USD")?"selected='selected'":""; ?>>USD</option>
                        <option value="2" <?php echo $select1 = ($user->set_default_currency == "EUR")?"selected='selected'":""; ?>>EUR</option>
                        <option value="3" <?php echo $select2 = ($user->set_default_currency == "GBP")?"selected='selected'":""; ?>>GBP</option>
                      </select>
                   </p>
                   <p class="safecommon-text">Price Charts & Portfolio currency</p>
                </div>

                <div class="google_auth">
                  <span></span>

                  <p class="safecommon-text"> 
                   <button type="submit" class="dsb-blue-btn" style="float: left;">Save</button>
                  </p>

                </div>
              
                            
            </div>
            {!! Form::close() !!}
            </div>
        </div>
      </div>
    </div>
  </div>






    <div class="tab-pane fade" id="apisecurity" role="tabpanel" aria-labelledby="apisecurity-tab2">
    <div class="card-div tab-content col-xs-12 col-sm-12 no-padding">
      <div class="tab-pane container-fluid no-padding active mb-3" id="apihistory">
        <div class="row">
          <div class="col-xs-12 col-sm-12 dsb-acc-status-cnt pad_zero">
              <div class="card-div-cnt">
                <div class="dsb-acc-status-heading">{{trans('app_lang.api_key') }}
                    <?php if($user->api_status==3){?>
                  <span style="padding-left: 26px;">
                          <i class="fa fa-info-circle" aria-hidden="true"></i>  <b style="color:#a61a00;"><?php echo "Disabled" ?></b>            </span>
                                          <?php } ?>
                        </div>
              
              <div class="google_auth">
                <img class="img_new_class" src="{{asset('/').('public/assets/images/')}}apikey12.png">
                <span>
                                 {{trans('app_lang.enable_api')}}</span>
                <p class="safecommon-text"> {{trans('app_lang.access_api')}}</p>
                <?php if($user->api_status==0 || $user->api_status==3){?>       
                <div class="center-btn-cnt"><a href="<?php echo url('/enable_key') ?>" class="bordered-btn"><?php echo trans('app_lang.enable_lng'); ?></a></div>
                <?php } else if($user->api_status==2) {?>

                <div class="center-btn-cnt">
                                    <span>
                          <i class="fa fa-info-circle" aria-hidden="true"></i>  <b style="color:#b98319e6;"><?php echo "Request pending" ?></b>           </span>
                  </div>
                
                <?php } else if($user->api_status==1) {?>

                    <div class="center-btn-cnt">
                                    <span>
                          <i class="fa fa-check-circle" aria-hidden="true"></i> <b style="color:#00A65A;"><?php echo "Enabled" ?></b>           </span>
                  </div>
                <?php } ?>

              </div>
                            <?php if($user->api_status==1){ ?>
              <div class="google_auth">
                <span>
                                 {{trans('app_lang.api_key')}}</span>
                <p class="safecommon-text">
                                <input name="api_key" type="text" placeholder="First Name" class="profile-txtbox" value="{{$user->api_key}}" required="" disabled>
                 </p>
              </div>
              <div class="google_auth">
                <span>
                                 {{trans('app_lang.api_secret')}}</span>
                <p class="safecommon-text"> 
                                <input name="api_secretkey" type="text" placeholder="First Name" class="profile-txtbox" value="{{$user->api_secret}}" required="" disabled>
                </p>
              </div>
               <?php  } ?>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  

  <div class="tab-pane fade show active" id="access" role="tabpanel" aria-labelledby="access-tab2">
    <div class="card-div tab-content col-xs-12 col-sm-12 no-padding">
      <div class="tab-pane container-fluid no-padding active mb-3" id="history">
        <div class="row">
          <div class="col-xs-12 col-sm-12 card-div">
            <div class="card-div-cnt">
              <div class="table-responsive dsb-wallet-table">
                <table id="login_notification" class="table table-hover table-borderless" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="table-sno-cnt">#</th>
                      <th>{{trans('app_lang.ip_address') }}</th>
                      <th>{{trans('app_lang.browser') }}</th>
                      <th>{{trans('app_lang.date_time') }}</th>
                      <th>{{trans('app_lang.location') }}</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab2">

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 card-div align-self-stretch xl-p-r lg-p-r"> {!! Form::open(array('id'=>'edittfa','url'=>'updatetfa','method'=>'POST','class'=>'d-flex')) !!}
        <div class="card-div-cnt align-self-stretch d-flex">
          <div class="row security-qr-cnt">
            <div class="col-xs-12 col-sm-12 setting-security-heading">{{trans('app_lang.TFA') }}</div>
            <?php if ($user->randcode == 1) {
            $button = trans('app_lang.disable_only');

            ?>
            <div class="qr-cnt text-center">{{trans('app_lang.disable_tfa') }}</div>
            <?php } else {
            $button = trans('app_lang.enable_only');
            ?>
            <div class="qr-cnt"><img src="{{$tfa_url}}" style=""></div>
            <div class="col-xs-12 col-sm-12 security-qr-value">{{$secret}}</div>
            <?php }?>
            <div class="upload-prof-pic">
              <div class="google-auth-cnt"><span>{{trans('app_lang.GAC') }}<a style="color: red">*</a></span> </div>
            </div>
             <div class="col-xs-12 col-sm-12 profile-txt-box-cnt">
              <input type="text" name="psswd" placeholder="Current Password" class="profile-txtbox">
            </div>
            <div class="col-xs-12 col-sm-12 profile-txt-box-cnt">
              <input type="text" name="onecode" placeholder="{{trans('app_lang.6_digit_code') }}" class="profile-txtbox">
              <input type="hidden" name="secret" value="<?php echo $secret; ?>">
            </div>
            <div class="btn-cnt d-flex mt-auto">
              <button class="dsb-blue-btn mx-auto" type="submit"><?php echo $button; ?></button>
            </div>
          </div>
        </div>
        {!! Form::close() !!} </div>
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 card-div align-self-stretch xl-p-l xl-p-r lg-p-r lg-p-l md-p-r"> {!! Form::open(array('id'=>'change_password','url'=>'updatePassword','method'=>'POST','class'=>'d-flex')) !!}
        <div class="card-div-cnt settings-security-cnt-height d-flex flex-column">
          <div class="row security-qr-cnt">
            <div class="col-xs-12 col-sm-12 setting-security-heading">{{trans('app_lang.change_password') }}</div>
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
              <div class="profile-txtbox-label">{{trans('app_lang.current_password') }}<span style="color: red">*</span></div>
              <input type="password" name="oldpassword" placeholder="{{trans('app_lang.current_password') }}" class="profile-txtbox">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
              <div class="profile-txtbox-label">{{trans('app_lang.new_password') }}<span style="color: red">*</span></div>
              <input type="password" id="password" name="password" placeholder="{{trans('app_lang.new_password') }}" class="profile-txtbox">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 profile-txt-box-cnt">
              <div class="profile-txtbox-label">{{trans('app_lang.confirm_password_new') }}<span style="color: red">*</span></div>
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{trans('app_lang.confirm_password_new')}}" class="profile-txtbox">
            </div>
          </div>
          <div class="btn-cnt abs-btn-cnt mt-auto">
            <button type="submit" class="dsb-blue-btn">{{trans('app_lang.update_password') }}</button>
          </div>
        </div>
        {!! Form::close() !!} </div>
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 card-div align-self-stretch xl-p-l lg-p-l md-p-l">
        <div class="card-div-cnt settings-security-cnt-height">
          <div class="row">
            <div class="col-xs-12 col-sm-12 setting-security-heading">{{trans('app_lang.notification') }}</div>
            <div class="col-xs-12 col-sm-12 setting-security-subheading">{{trans('app_lang.request_email_notf') }}</div>
            <div class="col-xs-12 col-sm-12 security-notif-row-cnt">
              <div class="col-xs-12 col-sm-12 security-notif-row d-flex"> <span>{{trans('app_lang.2fa') }}</span>
                <label class="switch ml-auto">
                  <input type="checkbox" <?php echo $user->tfa ? 'checked' : ''; ?> onclick="notification('2fa')">
                  <span class="slider round"></span> </label>
              </div>
              <div class="col-xs-12 col-sm-12 security-notif-row d-flex"> <span>{{trans('app_lang.for_change_password') }}</span>
                <label class="switch ml-auto">
                  <input type="checkbox" <?php echo $user->change_password ? 'checked' : ''; ?>  onclick="notification('password')">
                  <span class="slider round"></span> </label>
              </div>
              <div class="col-xs-12 col-sm-12 security-notif-row d-flex"> <span>{{trans('app_lang.for_new_device') }}</span>
                <label class="switch ml-auto">
                  <input type="checkbox" <?php echo $user->new_device_login ? 'checked' : ''; ?>  onclick="notification('device')">
                  <span class="slider round"></span> </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12 card-div">
        <div class="card-div-cnt settings-tab-cnt">
          <ul class="nav nav-tabs settings-tab">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" data-target="#notify" href="#">{{trans('app_lang.notification') }}</a> </li>
          </ul>
        </div>
      </div>
      <div class="card-div tab-content col-xs-12 col-sm-12">
        <div class="tab-pane container-fluid no-padding active notifycontent" id="notify">
          <div class="row">
            <div class="col-xs-12 col-sm-12 card-div">
              <div class="card-div-cnt">
                <div class="table-responsive dsb-wallet-table notify-table">
                  <table class="table table-hover table-borderless">
                    <thead>
                      <tr>
                        <th class="table-sno-cnt">#</th>
                        <th>{{trans('app_lang.content') }}</th>
                        <th>{{trans('app_lang.date_time') }}</th>
                      </tr>
                    </thead>
                    <tbody class="notify-table-ht">
                      <?php
                        $i = 0;
                        foreach ($notification as $list) {
                          $i++;
                          ?>
                      <tr>
                        <td class="table-sno-cnt"><?php echo $i; ?></td>
                        <td>
                          
                          <?php
                            $str = explode("-", $list['message']);

                              if ($list['message'] == 'You have updated your kyc details') {
                                echo trans('app_lang.you_have_update_kyc_details');
                              } else if ($list['message'] == 'You have updated your profile details') {
                                echo trans('app_lang.you_have_update_profile_details');
                              } else if ($list['message'] == 'selfie Proof has been Rejected by Admin.Submit Valid Proof') {
                                echo trans('app_lang.selfie_proof_rejected_admin');
                              } else if ($list['message'] == 'Id Proof has been Rejected by Admin.Submit Valid Proof') {
                                echo trans('app_lang.id_proof_rejected_admin');
                              } else if ($list['message'] == 'You have activated 2FA status') {
                                echo trans('app_lang.activated_2FA_status');
                              } else if ($list['message'] == 'You have deactivated 2FA status') {
                                echo trans('app_lang.deactivated_2FA_status');
                              } else if ($list['message'] == 'You have changed your password') {
                                echo trans('app_lang.changed_your_password');
                              } else if ($list['message'] == 'selfie Proof has been Verified by Admin.') {
                                echo trans('app_lang.selfie_proof_verified_admin');
                              } else if ($list['message'] == 'Id Proof has been Verified by Admin.') {
                                echo trans('app_lang.id_proof_verified_admin');
                              } else if ($list['message'] == 'You have requested a withdraw resend link') {
                                echo trans('app_lang.withdraw_resend_link');
                              } else if ($list['message'] == 'You have updated your Passcode') {
                                echo trans('app_lang.update_passcode');
                              } else if ($list['message'] == 'You have try to new device login,If you not please contact to support') {
                                echo trans('app_lang.new_device_login');
                              } else if ($str[0] == 'You have added support ticket TKT') {
                                echo trans('app_lang.added_support_ticket') . $str[1];
                              } else if ($str[0] == 'You have updated support ticket TKT') {
                                echo trans('app_lang.updated_support_ticket') . $str[1];
                              } else if ($str[0] == 'You have added a withdraw request for ') {
                                echo trans('app_lang.added_withdraw_request') . $str[1];
                              } else if ($str[0] == 'You have cancelled your withdraw request for ') {
                                echo trans('app_lang.cancelled_withdraw_request') . $str[1];
                              } else if ($str[0] == 'Withdraw request completed transaction hash is ') {
                                echo trans('app_lang.withdraw_requested_completed_transaction') . $str[1];
                              } else if ($str[0] == 'You have updated your ticket details TKT') {
                                echo trans('app_lang.update_ticket_details') . $str[1];
                              } else if ($str[0] == 'Admin cancelled your withdraw request for') {
                                echo trans('app_lang.fiat_withdraw_cancel') . $str[1];
                              }else {
                                echo $list['message'];
                              }

                              ?>
                          
</td>
                        <td><?php
                        $d = strtotime($list['updated_at']);
                        echo date("d/m/Y h:i:s A", $d);
                        ?></td>
                      </tr>
                      <?php
                      }?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  
</div>
<style type="text/css">
#defaultcurrencyUpdate
{
    width: 30%;
    text-align: center;
    font-size: 15px;
    font-weight: bold;
}
</style>

<script>

  var require_field_chp ="{{trans('app_lang.field_require') }}";

  var current_pwd_chp ="{{trans('app_lang.curr_min_8_char_validate') }}";
  var new_pwd_chp ="{{trans('app_lang.min_8_char_validate') }}";
  var same_pwd_chp ="{{trans('app_lang.enter_same_password') }}";
  var cur_pwdmin_chp ="{{trans('app_lang.current_pwd_min_8_char') }}";
  var new_pwdmin_chp ="{{trans('app_lang.min_8_char') }}";


  var success_notfy ="{{trans('app_lang.notification_update') }}";
  var success_notfy_dis ="{{trans('app_lang.notification_update_dis') }}";

  var error_notfy ="{{trans('app_lang.please_try_again') }}";

  var trade_msg    = "{{trans('app_lang.trade_msg') }}";
  var tfa_msg      = "{{trans('app_lang.tfa_msg') }}";
  var password_msg = "{{trans('app_lang.pwd_msg') }}";
  var device_msg   = "{{trans('app_lang.device_msg') }}";


   var min_3_prof        = "{{trans('app_lang.coin_min_3') }}";
   var max_15_prof       = "{{trans('app_lang.limit_15_char') }}";
   var letter_space_prof = "{{trans('app_lang.letter_space_allowed') }}";
   var min_5_prof        = "{{trans('app_lang.min_5_char') }}";
   var valid_no_prof     = "{{trans('app_lang.valid_number') }}";
   var min_6_mob_prof     = "{{trans('app_lang.min_6_digits') }}";
   var max_12_mob_prof     = "{{trans('app_lang.max_12_digits') }}";
   var files_prof          = "{{trans('app_lang.only_files') }}";
   var upload_img          = "{{trans('app_lang.upload_image') }}";

   var enter_6_char          = "{{trans('app_lang.enter_6_char') }}";
   var upto_6_char          = "{{trans('app_lang.upto_6_char') }}";
   var profile_btn = "{{trans('app_lang.update_profile')}}";
   if(window.location.search!='')
   {
      var name  ="<?php if(isset($_GET['name'])){echo $_GET['name'];}?>";
   }
   
</script> 