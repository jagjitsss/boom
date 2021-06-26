@extends('wallet.layouts/admin')
@section('content')

<ul class="breadcrumb cm_breadcrumb">
<li><a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}">Home</a></li>
<li><a href="#">change Password</a></li>
</ul>
<div class="inn_content">
<div class="cm_frm1 verti_frm1">
  <div class="cm_head1">
    <h3>Change Password</h3>
  </div>
  <?php if (Session::has('success')) {?>
  <div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Success!</strong><?php echo Session::get('success'); ?> </div>
    <?php }?>

    <?php if (Session::has('error')) {?>
    <div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
    <?php }?>

   {!! Form::open(array('url' => 'HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/updateChangePassword', 'id'=>'pwd_form')) !!}

  	<div class="form-group row clearfix">
    <div class="col-sm-6 col-xs-12 cls_resp50">
      <label class="form-control-label">Current Password</label>
      <input type="password" class="form-control" placeholder="***************" name="current_pwd" id="current_pwd">
    </div>
    <div class="col-sm-6 col-xs-12 cls_resp50 xrs_mat10">
      <label class="form-control-label">New Password</label>
      <input type="password" class="form-control" placeholder="***************" name="new_pwd" id="new_pwd">
    </div>
  	</div>
  	<div class="form-group row clearfix">
    <div class="col-sm-6 col-xs-12 cls_resp50">
      <label class="form-control-label">Confirm New Password</label>
      <input type="password" class="form-control" placeholder="***************" name="confirm_pwd" id="confirm_pwd">
    </div>
  </div>
  	<div cass="form-group row clearfix">
  		<button type="submit" class="cm_blacbtn1">Submit</button>
  	</div>
  {!! Form::close() !!}
</div>
</div>

<script>
 jQuery.validator.addMethod("notEqualTo",function(value, element, param) {
    var notEqual = true;
    value = $.trim(value);
    for (i = 0; i < param.length; i++) {
        if (value == $.trim($(param[i]).val())) { notEqual = false; }
    }
    return this.optional(element) || notEqual;
},
"Enter different password."
);


  $('#pwd_form').validate({
    rules:{
      current_pwd:{
        required:true,
        remote: {
            url: "{{URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/checkPassword')}}",
            type: 'GET',
            data: {
                current_pwd: function() {
                    return $('#pwd_form #current_pwd').val();
                }
            }
         }
      },
      new_pwd:{
        required:true,
        minlength:8,
        notEqualTo: ['#current_pwd'],
      },
      confirm_pwd:{
        required:true,
        minlength:8,
        equalTo : '[name="new_pwd"]',
      },
    },
    messages:{
      current_pwd:{
        remote:"Wrong Password",
      }
    }
  })

///////
</script>

@stop