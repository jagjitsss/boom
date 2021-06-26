<div class="col-md-3 col-sm-4 col-lg-4 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
    <div class="abt_txt">
      <h3> <img src="<?php echo url('/'); ?>/assets/front/images/logo.png" alt="" class="img-fluid"></h3>
      <p><?php echo getStaticContent('footer_email_widget_text')->content; ?></p>
      <form id="myform" action="<?php echo url('/subscribe'); ?>" method="post">
        <?php echo e(csrf_field()); ?>

      <div class="footer-email-address d-flex">
        
        <input type="text" name="email_address" id="email_address" value="" placeholder="Email Address">
        
        <button type="submit" name="onsendemail" id="onsendemail">
          <i class="fa fa-paper-plane"></i>
        </button>            
      </div>
      </form>
    </div>
  </div>