<aside class="main-sidebar">
    <div class="sidebar">

      <ul class="sidebar-menu" id="accord_side">
        <li class="hidden-xs">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="fa fa-bars hdt_cnt">Menu</span> </a>
        </li>
        <?php
$currentRoute = \Route::getCurrentRoute()->getActionName();
$explodeRoute = explode('@', $currentRoute);
$uri = $explodeRoute[1];
?>

       <?php if (Session::get('walletId') != "") {?>

        <li class="<?php if ($uri == "index") {echo "active";}?>">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}" class="mn_catgcur fa fa-tachometer"><span>Dashboard</span> </a>
        </li>

        <li class="<?php if ($uri == "walletDeposit") {echo "active";}?>">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/viewAdminDeposit') }}" class="mn_catgcur fa fa-hand-lizard-o"><span>Deposit</span> </a>
        </li>

        <li class="<?php if ($uri == "walletWithdraw") {echo "active";}?>">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/viewAdminWithdraw') }}" class="mn_catgcur fa fa-shopping-cart"><span>Withdraw</span> </a>
        </li>

        
        <li class="<?php if ($uri == "viewDepositHistory") {echo "active";}?>">
            <a href="{{ URL::to('kR75XYrcJNZx7X92$5Rb69FUtDyAh6d/viewDepositHistory') }}" class="mnsub_catg fa fa-download">Deposit History</a>
        </li>

        <li class="<?php if ($uri == "walletWithdrawHist") {echo "active";}?>">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/walletWithdrawHist') }}" class="mnsub_catg fa fa-cloud-download">Withdraw History</a>
        </li>

        <li class="<?php if ($uri == "walletProfit") {echo "active";}?>">
            <a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/viewAdminProfit') }}" class="mn_catgcur fa fa-cart-plus"><span>Profit</span> </a>
        </li>

        <?php }?>
      </ul>
    </div>
</aside>