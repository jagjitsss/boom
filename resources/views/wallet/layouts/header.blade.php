<header class="main-header">
  <?php $getSite = App\Model\User::getSiteLogo();?>

    <div class="tp_layer1"> <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}" class="logo">

      <span class="logo-mini">

      <img src="{{$getSite->site_logo }}" alt="logo_small"> </span> <span class="logo-lg">

      <img src="{{$getSite->site_logo }}" alt="logo_large"> </span> </a> <a href="javascript:;" class="sidebar-toggle hidden-norm" data-toggle="offcanvas" role="button"> <span class="fa fa-bars hdt_cnt">Dashboard</span> </a> </div>

    <nav class="navbar navbar-static-top">
      <div class="mn_righ">
        <div class="mn_rightp fd_rw">
          <div class="tp_sear1">

          </div>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-right">
              
              <li class="dropdown dropdown-user"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                <div class="user-image"><span class="hidden-xs">Hi Admin,</span> <img src="{{asset('/').('public/admin_assets/images/mn_imusr.png')}}" class="img-responsive" alt="User"> </div>
                </a>
                <ul class="dropdown-menu usr_drpmn">
                  
                  <li class="user-header">
                    <div class="usr_mask">
                      <p> Wallet Admin </p>
                    </div>
                    <div class="admin_profile_icon">
                      <img src="{{asset('/').('public/admin_assets/images/default-avatar.png')}}" class="img-responsive" alt="">
                    </div>
                  </li>
                  
                  <li class="user-body">
                    <div class="">

                      <div class=""> <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/changePassword') }}" class="active">Change Password</a> </div>
                    </div>
                    
                  </li>
                  
                  <li class="user-footer text-center"> <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/logout') }}" class="btn btn-flat center-block">Logout</a> </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>