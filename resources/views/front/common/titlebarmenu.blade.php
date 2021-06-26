<?php $uri = Request::segment(1); ?>


<div class="inner-sec-top-menu">
  <div class="container">
    <ul class="inner-sec-menu">
      <li><a href="<?php echo url('/dashboard'); ?>" class="<?php echo $class = ($uri == 'dashboard')?'active':''; ?>">
      <i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li>
      <li><a href="<?php echo url('/buy-sell'); ?>" class="<?php echo $class = ($uri == 'buy-sell')?'active':''; ?>"><i class="fa fa-arrows-h" aria-hidden="true"></i> Buy/Sell</a></li>
      <li><a href="<?php echo url('/bankwire/USD'); ?>"><i class="fa fa-folder" aria-hidden="true"></i> Bank</a></li>

      <li><a href="<?php echo url('/editprofile'); ?>" class="<?php echo $class = ($uri == 'editprofile')?'active':''; ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
    </ul>
  </div>
</div>