<footer>
  <div class="container ">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-lg-12 footer-top">
        <h1><?php echo getStaticContent('footer_trade_at_anywhere')->title; ?>
      </h1>
        <p class="text-center">
          <?php echo getStaticContent('footer_trade_at_anywhere')->content; ?>
        </p>
        <p class="text-center">
          <a  class="btn-primary" href="{{url('/trade')}}">Trade Now</a>
          &nbsp;&nbsp;&nbsp;&nbsp;
           <?php 
          
          if(!Session::has('tmaitb_user_id'))
          {
            ?>
            <a href="<?php  echo url('/register'); ?>" class="btn-secondary">Register</a>
            <?php  
          }
          ?>
        </p>
      </div>
      @include('front.common.subscriber')

      <div class="col-6 col-md-3 col-sm-3 col-lg-3 wow fadeInRight foot-2" style="visibility: visible; animation-name: fadeInRight;">
        <h4><span>
          Support          </span></h4>
        <div class="footlks">
          <ul>
            <li><a href="<?php echo Config::get('domain.url'); ?>faq">FAQ</a></li>
            <li><a href="<?php echo Config::get('domain.url'); ?>fees">Fees</a></li>            
            <li><a href="<?php echo Config::get('domain.url'); ?>news">Blog</a></li>
            <li><a href="<?php echo Config::get('domain.url'); ?>pages/how_work">How It Works</a></li>
            <li><a style="color: grey;" href="https://forms.gle/PwtjdXfHjn7gwoiK8" target="_blank">Token Listing</a></li>
          </ul>
        </div>
      </div>
      <div class="col-6 col-md-3 col-sm-3 col-lg-3 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
        <h4><span>About Us</span></h4>
        <div class="footlks">
          <ul>
            <li><a href="<?php echo Config::get('domain.url'); ?>pages/about-us">About Us</a></li>
            <li><a href="<?php echo Config::get('domain.url'); ?>contactus">Contact us</a></li>

            
            <?php 
            if(Session::has('tmaitb_user_id'))
            {
              ?>
              <li><a href="<?php echo Config::get('domain.url'); ?>support">Support</a></li>            
            <?php
            }
            else
            {
              ?>
              <li><a href="<?php echo env('SUB_DOMAIN_URL').'login'; ?>">Support</a></li>            

              <?php 
            }
            ?>

            <li><a href="<?php echo Config::get('domain.url'); ?>pages/terms">Terms and Conditions</a></li>
            <li><a href="<?php echo Config::get('domain.url'); ?>pages/privacy">Privacy</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-2 col-sm-2 col-lg-2  wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
        <h4><span>Social Links</span></h4>
        <div class="footlks">
          <ul>
          
          <li><a href="{{$site->fb}}" target="_blank" >
            <i class="fa fa-facebook" aria-hidden="true"></i> Facebook
          </a></li>
          <li><a href="{{$site->twitter}}" target="_blank" >
            <i class="fa fa-twitter" aria-hidden="true"></i> Twitter
          </a></li>
          <li><a href="{{$site->gplus}}" target="_blank" >
            <i class="fa fa-instagram" aria-hidden="true"></i> Instagram
          </a></li>
          <li><a href="{{$site->linkedin}}" target="_blank" >
            <i class="fa fa-linkedin" aria-hidden="true"></i> Linkedin
          </a></li>


          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright text-center">
  <p class="text-center"><?php echo getcopyright(); ?></p>
</div>
</footer>