<nav class="navbar navbar-expand-md justify-content-center index-header dashboard-header"> 
  <a class="navbar-brand" href="<?php echo Config::get('domain.url'); ?>"> 
<img src="{{$site->site_logo}}" class="img-fluid"> </a>
  <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2"> <span class="navbar-toggler-icon fa fa-fw fa-align-justify"></span> </button>
  <div class="container-fluid">
    <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
      <ul class="col-xs-12 nav nav-tabs dashboard-tabs head_menu">
        <li class="nav-item"> <a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('dashboard'); ?>" id="overview-link">
          <div class="select-arrow"></div>
          <div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-overview-icon.png"></div>
          <div class="tab-link-txt">{{trans('app_lang.overview') }}</div>
          </a> </li>


        <li class="nav-item">
                <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('buy-sell')}}" id="funds-link">
                  <div class="tab-link-txt">{{trans('app_lang.exchange') }}</div>
                </a>
              </li>


          <li class="nav-item">
            <a class="nav-link <?php echo $page == 2 ? 'active' : ' ' ?>" href="{{url('trade')}}" id="funds-link">
              <div class="tab-link-txt">Trade</div>
            </a>
          </li>
          
        <li class="nav-item"> <a class="nav-link <?php echo $page == 1 ? 'active' : ' ' ?>" href="<?php echo url('api/api_document'); ?>" id="overview-link"> {{trans('app_lang.api') }}</a> </li>
        <li class="nav-item"> <a class="nav-link <?php echo $page == 3 ? 'active' : ' ' ?>" href="<?php echo url('funds'); ?>" id="funds-link">
          <div class="select-arrow"></div>
          <div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-funds-icon.png"></div>
          <div class="tab-link-txt">{{trans('app_lang.funds') }}</div>
          </a> </li>
        <li class="nav-item"> <a class="nav-link <?php echo $page == 4 ? 'active' : ' ' ?>"  href="<?php echo url('referral'); ?>" id="referals-link">
          <div class="select-arrow"></div>
          <div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-referal-icon.png"></div>
          <div class="tab-link-txt">{{trans('app_lang.refer') }}</div>
          </a> </li>

        <li class="nav-item"> <a class="nav-link <?php echo $page == 6 ? 'active' : ' ' ?>" href="<?php echo url('support'); ?>" id="">
          <div class="select-arrow"></div>
          <div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
          <div class="tab-link-txt">{{trans('app_lang.support') }}</div>
          </a> </li>
        <li class="nav-item"> <a class="nav-link <?php echo $page == 7 ? 'active' : ' ' ?>" href="<?php echo url('bankwire'); ?>" id="">
          <div class="select-arrow"></div>
          <div class="tab-link-icon"><img src="{{asset('/').('public/assets/images/')}}dashboard-support1-icon.png"></div>
          <div class="tab-link-txt">{{trans('app_lang.bankwire') }}</div>
          </a> </li>
      </ul>
      <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap right-nav-links dsb-header-tab ">
        <li class="nav-item notify_msg"> <i class="fa fa-bullhorn" aria-hidden="true"></i>
          <div class="notification">
            <h3>News</h3>
            <div class="notify-content">
              <div class="notify_blk">
                <?php                
								foreach($news as $new) { 
									$d = strtotime($new->updated_at);
									//$url = URL::to('/newsdetails/'.$new->id);
                  $url = Config::get('domain.url').'newsdetails/'.$new->id;
									?>
                <p><a href="<?php echo $url;?>">【Announcement】<?php echo news_lang_content('0',session('language'),$new->id);?> on <?php echo date("d/m/Y", $d);?></a></p>
                <?php }  ?>
              </div>
            </div>
            <div class="notify-footer"> <a href="<?php echo Config::get('domain.url').'news' ?>">View all</a> </div>
          </div>
        </li>
        <?php
					$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
					?>
        <li class="nav-item dashboard-header-links dash-header-icon-link">
          <div class="dropdown"> <a href="javascript:;" onclick="shownotify1()">
            <button class="profile-dd dropdown-toggle" style="cursor: pointer"> <img src="{{asset('/').('public/assets/images/')}}dashboard-header-notification-icon.png" class="img-fluid"  title="Notification" id="notif_icon"> </button>
            </a> </div>
          <div class="notif-counter"><?php echo notification_list(); ?></div>
        </li>
        
        <li class="nav-item dashboard-header-links2">
          <div class="dropdown right-dd">
            <button type="button" class="profile-dd dropdown-toggle" data-toggle="dropdown">
            <?php $name = session::get('tmaitb_profile');
								if ($name) {
									?>
            <div class="header-profile-icon">
              <?php
										if ($user->profile == '') {?>
              <img src="{{asset('/').('public/assets/images/')}}avatar.png" class="header-profile-icon">
              <?php } else {?>
              <img src="{{$user->profile}}" class="header-profile-icon">
              <?php }?>
            </div>
            <?php }?>
            <div class="header-profile-name">
              <?php
									echo $name ? $name : 'Hi ';
									?>
            </div>
            <i class="fa fa-fw fa-angle-down dd-arrow dd-profile-arrow"></i> </button>
            <div class="dropdown-menu"> <a class="dropdown-item" href="<?php echo url('dashboard'); ?>">{{trans('app_lang.dashboard') }}</a> <a class="dropdown-item" href="<?php echo url('logout'); ?>">{{trans('app_lang.logout') }}</a> </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
