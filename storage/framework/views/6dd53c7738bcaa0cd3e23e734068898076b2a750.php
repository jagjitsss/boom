<div class="container-fluid log_in_page">
    <div class="login-page post-fadeUp">
      <div class="form">
        
        <?php echo Form::open(array('class'=>'login-form', 'id'=>'tfa','url'=>'twofa','method'=>'POST','onsubmit'=>'tfa_load()')); ?>

        <div class="log_new_class">
       
              <div class="form-heading"><h2 class="form-heading"><?php echo e(trans('app_lang.google_authentication')); ?></h2></div>
           
        </div>
        <div class="log_new">
          <input type="text" name="tfa" class="tfa white-space-is-dead" placeholder="Enter 2FA code"/>
        </div>
          <button type="submit" class="tfa_sub"><?php echo e(trans('app_lang.submit')); ?></button>
        
          <?php echo Form::close(); ?>

      </div>
      <div class="form-page-footer-cnt">
        <div class="form-page-footer">
            <p class="message left-message"><?php echo e(trans('app_lang.back_to')); ?> <a href="<?php echo url('/');?>"><?php echo e(trans('app_lang.home')); ?></a></p>
        </div>
      </div>
    </div>
</div>

<?php echo $__env->make('front.common.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<script>

  var require_field_tfa ="<?php echo e(trans('app_lang.field_require')); ?>";
  var numbers_only_tfa ="<?php echo e(trans('app_lang.enter_number_only')); ?>";
  var max_6_tfa ="<?php echo e(trans('app_lang.max_6_digits')); ?>";
  var min_6_tfa ="<?php echo e(trans('app_lang.min_6_digits')); ?>";
   
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