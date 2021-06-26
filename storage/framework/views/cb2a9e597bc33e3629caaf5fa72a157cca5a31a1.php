<nav class="navbar navbar-expand-md justify-content-center index-header dashboard-header">

<a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>">
	<img src="<?php echo e($site->tradesite_logo); ?>" class="img-fluid mobile-logo">
</a>


<button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
	<span class="navbar-toggler-icon fa fa-fw fa-bars"></span>
</button>

<div class="container-fluid">
	<div class="navbar-collapse collapse justify-content-between align-items-center w-100 adv-theme-header" id="collapsingNavbar2">

		<ul class="navbar-nav col-sm-12 no-padding center-nav-links adv-li">
			<a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>">
				<img src="<?php echo e($site->tradesite_logo); ?>" class="img-fluid device-logo">
			</a>
			<div class="ad_top_new_link">
				<?php if (Session::has('tmaitb_user_id'))
				{ ?>
					<li>
						<a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="exchange-link"><?php echo e(trans('app_lang.overview')); ?></a>
					</li>
					
					 <li class="nav-item">
		                <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="<?php echo e(url('buy-sell')); ?>" id="funds-link">
		                  <div class="tab-link-txt"><?php echo e(trans('app_lang.exchange')); ?></div>
		                </a>
		              </li>


		          <li class="nav-item">
		            <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="<?php echo e(url('trade')); ?>" id="funds-link">
		              <div class="tab-link-txt">Trade</div>
		            </a>
		          </li>

					<li>
						<a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="funds-link">
							<?php echo e(trans('app_lang.api')); ?>

						</a>
					</li>
					<li>
						<a class="nav-link <?php echo $page == 3 ? 'active' : ' ' ?>" href="<?php echo url('funds'); ?>" id="exchange-link"><?php echo e(trans('app_lang.funds')); ?></a>
					</li>
					<li>
						<a class="nav-link <?php echo $page == 4 ? 'active' : ' ' ?>" href="<?php echo url('referral'); ?>" id="exchange-link"><?php echo e(trans('app_lang.refer')); ?></a>
					</li>
					<li style="display:none;">
						<a class="nav-link <?php echo $page == 5 ? 'active' : ' ' ?>" href="<?php echo url('addCoins'); ?>" id="exchange-link"><?php echo e(trans('app_lang.add_coin')); ?></a>
					</li>
					<li>
						<a class="nav-link <?php echo $page == 6 ? 'active' : ' ' ?>" href="<?php echo url('support'); ?>" id="exchange-link"><?php echo e(trans('app_lang.support')); ?></a>
					</li>
					<li><a class="nav-link <?php echo $page == 7 ? 'active' : ' ' ?>" href="<?php echo url('bankwire/USD'); ?>" id="exchange-link"><?php echo e(trans('app_lang.bankwire')); ?></a></li>
				<?php } else { ?>
					

					<li>
						<a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="<?php echo e(url('trade')); ?>" id="funds-link">
							<div class="tab-link-txt"><?php echo e(trans('app_lang.exchange')); ?></div>
						</a>
					</li>
					
					<li>
						<a class="nav-link <?php echo $page == 8 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="funds-link">
							<?php echo e(trans('app_lang.api')); ?>

						</a>
					  </li>
				<?php } ?>
			</div>

			

			
<div class="new_div">
<?php $log = Session::get('tmaitb_user_id');?>

<?php if ($log) {?>
			<li class="nav-item notify_msg">
					<i class="fa fa-bullhorn" aria-hidden="true"></i>
					<div class="notification">
						<h3>News</h3>
							<div class="notify-content">
								<div class="notify_blk">
								<?php                
				                foreach($newsdetails as $new) { 
				                  $d = strtotime($new->updated_at);
				                  //$url = URL::to('/newsdetails/'.$new->id);
				                  $url = Config::get('domain.url').'newsdetails/'.$new->id;
				                  ?>
				                  <p><a href="<?php echo $url;?>">【Announcement】<?php echo news_lang_content('0',session('language'),$new->id);?> on <?php echo date("d/m/Y", $d);?></a></p>
				               <?php }  ?>						
						</div>
						</div>
						<div class="notify-footer">
							<a href="<?php echo Config::get('domain.url').'news' ?>">View all</a>

						</div>
					</div>
				</li>
				 
			<li class="nav-item dashboard-header-links dash-header-icon-link">
				<div class="dropdown">
				   	<a href="<?php echo e(url('/notification_list')); ?>">
						<button class="profile-dd dropdown-toggle" style="cursor: pointer">
							<div class="notif-icon" id="notif_icon" title="Notification"></div>
						</button>
					</a>
				</div>
				<div class="notif-counter"><?php echo notification_list(); ?></div>
			</li>
<?php }?>
<?php if (!$log) {?>
       
		<li class="nav-item">
		
		 <a class="nav-link dashboard-header-links dash-header-icon-link dash-header-txt-link index-header-link" href="<?php echo url('login'); ?>"><?php echo e(trans('app_lang.login')); ?></a>
		</li>
		<li class="nav-item">
			
			<a class="nav-link dashboard-header-links dash-header-icon-link dash-header-txt-link index-header-link nav-register" href="<?php echo url('register'); ?>" style="color:white;"><?php echo e(trans('app_lang.signup')); ?></a>
		</li>

        
				<li class="nav-item notify_msg">
					<i class="fa fa-bullhorn" aria-hidden="true"></i>
					<div class="notification">
						<h3>News</h3>
							<div class="notify-content">
								<div class="notify_blk">
								<?php                
				                foreach($newsdetails as $new) { 
				                  $d = strtotime($new->updated_at);
				                  //$url = URL::to('/newsdetails/'.$new->id);
				                  $url = Config::get('domain.url').'newsdetails/'.$new->id;
				                  ?>
				                  <p><a href="<?php echo $url;?>">【Announcement】<?php echo news_lang_content('0',session('language'),$new->id);?> on <?php echo date("d/m/Y", $d);?></a></p>
				               <?php }  ?>						
						</div>
						</div>
						<div class="notify-footer">
							<a href="<?php echo Config::get('domain.url').'news' ?>">View all</a>
						</div>
					</div>
				</li>

				<?php }?>
			


			<?php if (Session::has('tmaitb_user_id')) {
	?>

				 <li class="nav-item dashboard-header-links">
          <div class="dropdown right-dd">
            <button type="button" class="profile-dd dropdown-toggle" data-toggle="dropdown">
            <?php $name = session::get('tmaitb_profile');
if ($name) {
   $user_profile = getUserImage();
  ?>
            <div class="header-profile-icon">
      <?php
if ($user_profile == '') {?>
           <img src="<?php echo e(asset('/').('public/assets/images/')); ?>avatar.png" class="header-profile-icon">
        <?php } else {?>
           <img src="<?php echo e($user_profile); ?>" class="header-profile-icon">
      <?php }?>
            </div>
            
           
            </button>
            <div class="dropdown-menu">
              <div class="pro_name clearfix">
                
                <?php }?>
            <div class="header-profile-name"><?php
echo $name ? $name : 'Hi ';
?></div><a class="" href="<?php echo url('logout'); ?>"><?php echo e(trans('app_lang.logout')); ?></a>
              </div>
              <ul>
                <li class="drop_new_blk">
                
            <a class="dropdown-item" href="<?php echo url('dashboard'); ?>"> <img src="<?php echo e(asset('/').('public/assets/images/')); ?>men_img.png"> <?php echo e(trans('app_lang.dashboard')); ?></a>
          </li>
            </ul>
            </div>
          </div>
        </li>
<div class="new_div">
			<?php } ?>
				
			


		</ul>
	</div>
</div>
</nav>

<div class="pos_abs">
<?php if (Session::has('tmaitb_user_id')) {?>
				
			<?php }?>

			<div class="dropdown exc-dd dropdown coinDrop"><img style="width: 25px; height: 25px;" src="<?php echo e(asset('/').('public/assets/images/')); ?>bitcoin-icon.png">
			  <button type="button" class="exc-topbar-dd dropdown-toggle">
				<span class="exc-dd-heading cur_pair"></span>
			  </button>
			   <?php echo $__env->make('front/advance_trade/popup-advanced', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


			</div>
			<div class="exc-data-cnt">
				<span class="lgtxt "><?php echo e(trans('app_lang.last_price')); ?></span>
				<span class="lastprice"></span>
			</div>
			<div class="exc-data-cnt">
				<span class="lgtxt">24h <?php echo e(trans('app_lang.change')); ?></span>
				<span class="change" ></span>
			</div>
			<div class="exc-data-cnt">
				<span class="lgtxt">24h <?php echo e(trans('app_lang.high')); ?></span>
				<span class="high"></span>
			</div>
			<div class="exc-data-cnt">
				<span class="lgtxt">24h <?php echo e(trans('app_lang.low')); ?></span>
				<span class="low"></span>
			</div>
			<div class="exc-data-cnt">
				<span class="lgtxt">24h <?php echo e(trans('app_lang.volume')); ?>  (<span class="from_cur"></span>)</span>
				<span class="volume"></span>
			</div>
			</div>