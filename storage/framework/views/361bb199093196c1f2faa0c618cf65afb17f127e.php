<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 lg-p-l adv_trade_ryt">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 xl-p-r md-p-r lg-p-r over_book_ad_blk">
<div class="portlet adv-buysell-order-cnt">
<div class="portlet-header">
<ul class="nav nav-tabs d-flex">
<li class="nav-item">
<a class="nav-link active" data-toggle="tab" data-target="#advtrade-allorder" href="javascript:;"><img style="width: 16px; height: 16px;" src="<?php echo e(asset('/').('public/assets/images/')); ?>1.png"></a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" data-target="#advtrade-buyorder" href="javascript:;"><img style="width: 16px; height: 16px;" src="<?php echo e(asset('/').('public/assets/images/')); ?>2.png"></a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" data-target="#advtrade-sellorder" href="javascript:;"><img style="width: 16px; height: 16px;" src="<?php echo e(asset('/').('public/assets/images/')); ?>3.png"></a>
</li>

<li class="nav-item">
<form>
	<label style="color:white;">Group</label>
	<select id="dec_value">
		<option value=8>8DEC</option>
		<option value=4>4DEC</option>
		<option value=2>2DEC</option>
		<option value=1>1DEC</option>		
	</select>
</form>
</li>

</ul>

</div>

<div class="portlet-content">
<div class="tab-content">

<div class="tab-pane container active" id="advtrade-allorder">
										<div class="">
											<table class="table table-hover table-borderless market-table">
												<thead>
												  <tr>
			<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
												  </tr>
												</thead>
												<tbody class="tb-84 selltb"></tbody>
											</table>

											<div class="tb-seperator">
												<span class="lastprice"></span>
											</div>

											<table class="table table-hover table-borderless market-table">
												<tbody class="tb-84 buytb">
												</tbody>
											</table>
										</div>
									</div>
<div class="tab-pane container fade" id="advtrade-buyorder">
<div class="table-responsive">
	<table class="table table-hover table-borderless market-table">
		<thead>
		  <tr>
			<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
		  </tr>
		</thead>
		<tbody class="tb-200 buyOrdersTable">

		</tbody>
	</table>
</div>
</div>

<div class="tab-pane container fade" id="advtrade-sellorder">
<div class="table-responsive">
	<table class="table table-hover table-borderless market-table">
		<thead>
		  <tr>
			<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
			<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
		  </tr>
		</thead>
		<tbody class="tb-200 sellOrdersTable">	</tbody>
	</table>
</div>
</div>
</div>
</div>

</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 xl-p-r xl-p-l lg-p-l md-p-l lg-p-r recent_trade_new_blk">
<div class="portlet">
<div class="portlet-header">
<span class="hd"><?php echo e(trans('app_lang.tradehistory')); ?></span>
</div>

<div class="portlet-content">
	
				<div class="tab-content">
					<div class="tab-pane container active" id="trade-market">									   										   	
<div class="table-responsive border-portlet-table">
<table class="table table-hover table-borderless market-table">
<thead>
  
</thead>
<tbody class="tb-223" id="tradeHistory">
</tbody>
</table>
</div>
</div>
</div>
</div>

</div>
</div>

<div class="col-xs-12 col-sm-12 xl-p-r lg-p-r limit_new_class">
<div class="portlet limit-portlet-cnt">
<div class="portlet-header">
<ul class="nav nav-tabs d-flex">
<li class="nav-item">
<a class="nav-link active advbuylimit" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('limit','buy')"   href="javascript:;"><?php echo e(trans('app_lang.limit')); ?></a>
</li>
<li class="nav-item">
<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('market','buy')"   href="javascript:;"><?php echo e(trans('app_lang.market')); ?></a>
</li>
<li class="nav-item">
<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('stop','buy')"  href="javascript:;"><?php echo e(trans('app_lang.stop_limit')); ?></a> 
</li>
</ul>
</div>
<div class="portlet-content mc-1">
<div class="tab-content">
<div class="tab-pane container active" id="advtrade-limit">
<div class="adv-buycnt tb-298">
	<div class="table-responsive">
		<div class="d-flex justify-content-center">
			<div class="adv-info-row">
				<div class="info-row">
					<span class="adv-buy-heading"><?php echo e(trans('app_lang.buy')); ?> <span class="to_cur"></span></span>
					<?php if ($log) {?>
					<p class="ava_bal"><span class="from_cur"></span><span> Available:</span><span class="from_bal"></span></p>
					<?php }?>
				</div>
			</div>
		</div>
		<div class="portlet-txtbox-cnt">
			<input type="text" onkeyup="calculation('buy');"  class="amount buyamount" placeholder="<?php echo e(trans('app_lang.amount')); ?>" onkeypress="return isNumberKey(event)" >
			<span class="bcc-indicator to_cur"></span>
		</div>
		<div class="portlet-txtbox-cnt">
			<input type="text" onkeyup="calculation('buy');"  class="price buyprice" placeholder="<?php echo e(trans('app_lang.price')); ?>" onkeypress="return isNumberKey(event)" >
			<span class="bcc-indicator price from_cur"></span>
		</div>
		<div class="portlet-txtbox-cnt stop buystop hide">
				<input type="text" onkeyup="calculation('buy');" class="buystopprice" placeholder="<?php echo e(trans('app_lang.price_stop')); ?>" onkeyup="calculation('buy');"  onkeypress="return isNumberKey(event)" >
				<span class="bcc-indicator from_cur"></span>
			</div>
		<div class="coupon-cnt">
			<span class="coupon btnPerc" type="buy" id="buy25">25%</span>
			<span class="coupon btnPerc" type="buy" id="buy50">50%</span>
			<span class="coupon btnPerc" type="buy" id="buy75">75%</span>
			<span class="coupon btnPerc" type="buy" id="buy100">100%</span>
		</div>

		<div class="full-wd-info-cnt nomarket">
			<?php echo e(trans('app_lang.total')); ?><span><span class="tot buytot">0.00000000 </span><span class="from_cur"></span></span>
		</div>
		<div class="portlet-btn-cnt">
			<?php if ($log) {
			$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
			if($p == 'empty') { ?>
				<button type="button" class="buybtn" id="probuy"><?php echo e(trans('app_lang.buy')); ?></button>		
			<?php } else { ?>
				<button type="button" class="buybtn" onclick="return order_placed('buy',this)" ><?php echo e(trans('app_lang.buy')); ?></button>					
			<?php } } else { ?>
				<p class="b4login-p"><a class="green-link" href="<?php echo url('login'); ?>">Login</a>
			<?php } ?>
		</div>
	</div>
</div>

<div class="adv-sellcnt tb-298">
	<div class="d-flex justify-content-center">
		<div class="adv-info-row">
			<div class="info-row">
				<span class="adv-sell-heading"><?php echo e(trans('app_lang.sell')); ?> <span class="to_cur"></span></span>
				<?php if ($log) {?>
				<p class="ava_bal"><span class="to_cur"></span><span> Available:</span><span class="to_bal"></span></p>
				<?php }?>
			</div>
		</div>
	</div>
	<input type="hidden" id="type" class="type" value="limit">
	<div class="portlet-txtbox-cnt">
			<input type="text" onkeyup="calculation('sell');" placeholder="<?php echo e(trans('app_lang.amount')); ?>" class="amount sellamount" onkeypress="return isNumberKey(event)" >
			<span class="bcc-indicator to_cur"></span>
		</div>
		<div class="portlet-txtbox-cnt">
			<input type="text" onkeyup="calculation('sell');" placeholder="<?php echo e(trans('app_lang.price')); ?>" class="sellprice price" onkeypress="return isNumberKey(event)" >
			<span class="bcc-indicator price from_cur"></span>
		</div>
	<div class="portlet-txtbox-cnt stop sellstop hide">
				<input type="text" onkeyup="calculation('sell');" class="sellstopprice" placeholder="<?php echo e(trans('app_lang.price_stop')); ?>" onkeyup="calculation('sell');"  onkeypress="return isNumberKey(event)" >
				<span class="bcc-indicator from_cur"></span>
			</div>
	<div class="coupon-cnt">
		<span class="coupon btnPerc" type="sell" id="sell25">25%</span>
		<span class="coupon btnPerc" type="sell" id="sell50">50%</span>
		<span class="coupon btnPerc" type="sell" id="sell75">75%</span>
		<span class="coupon btnPerc" type="sell" id="sell100">100%</span>
	</div>

	<div class="full-wd-info-cnt nomarket">
		<?php echo e(trans('app_lang.total')); ?><span><span class="tot selltot">0.00000000 </span> <span class="from_cur"></span></span>
	</div>
	<div class="portlet-btn-cnt">
		
		<?php if ($log) {
		$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
		if($p == 'empty') { ?>
			<button type="button" class="portlet-red-btn sellbtn" id="prosell"><?php echo e(trans('app_lang.sell')); ?></button>			
		<?php } else { ?>
			<button type="button"  onclick="return order_placed('sell',this)"  class="portlet-red-btn sellbtn"><?php echo e(trans('app_lang.sell')); ?></button>				
		<?php } } else { ?>
			<p class="b4login-p"><a class="green-link" href="<?php echo url('login'); ?>">Login</a>
		<?php } ?>
	</div>
</div>
</div>


</div>
</div>
</div>
</div>

</div>
</div>
