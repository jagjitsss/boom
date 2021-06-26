<div class="tab-pane container-fluid active trade-w-box" id="exchange">
  <?php $log = Session::get('tmaitb_user_id');?>
		<div class="row">
      <?php echo $__env->make('front/trade/chart', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 trade_left_side_blk" style="float: left;">
          <?php echo $__env->make('front/trade/createbuyorder', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <?php echo $__env->make('front/trade/createsellorder', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 trade-left-table-cnt">
          <div class="row column">
            <div class="col-xs-12 col-sm-12 col-lg-12 card-div no-padding">
						  <div id="chart_container" class="basic_trade" style="margin-bottom: 10px;"></div>
						</div>
					<?php echo $__env->make('front/trade/orderbookbuy', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
			</div>
			<?php echo $__env->make('front/trade/tradehistory', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	 
  <?php echo $__env->make('front/trade/myorders', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</div>
<script>
  var stop_price_above      = "<?php echo e(trans('app_lang.enter_stop_price_above')); ?>";
  var stop_price_below      = "<?php echo e(trans('app_lang.enter_stop_price_below')); ?>";
  var valid_amount          = "<?php echo e(trans('app_lang.enter_valid_amount')); ?>";
  var valid_price           = "<?php echo e(trans('app_lang.enter_valid_price')); ?>";
  var stop_greater          = "<?php echo e(trans('app_lang.stop_greater_zero')); ?>";
  var insufficient_bal      = "<?php echo e(trans('app_lang.insufficient_bal')); ?>";
  var unable_place_order    = "<?php echo e(trans('app_lang.unable_place_order')); ?>";
  var valid_stop_price      = "<?php echo e(trans('app_lang.enter_valid_stop_price')); ?>";
  var order_success          = "<?php echo e(trans('app_lang.order_placed')); ?>";
  var invalid_pair          = "<?php echo e(trans('app_lang.invalid_pair')); ?>";
  var order_cancel          = "<?php echo e(trans('app_lang.order_cancelled')); ?>";
  var error_try             = "<?php echo e(trans('app_lang.error_try_again')); ?>";
  var cancel_order          = "<?php echo e(trans('app_lang.want_cancel_order')); ?>";
  var enter_amount_more_than= "<?php echo e(trans('app_lang.enter_amount_more_than')); ?>";
  var enter_price_more_than = "<?php echo e(trans('app_lang.enter_price_more_than')); ?>";
  var no_buy_orders         = "<?php echo e(trans('app_lang.no_buy_orders')); ?>";
  var no_sell_orders        = "<?php echo e(trans('app_lang.no_sell_orders')); ?>";
  var no_trade_history      = "<?php echo e(trans('app_lang.no_trade_history')); ?>";
  var no_open_order_available= "<?php echo e(trans('app_lang.no_open_order_available')); ?>";
  var no_stop_orders       = "<?php echo e(trans('app_lang.no_stop_orders')); ?>";
  var no_data_found        = "<?php echo e(trans('app_lang.no_data_found')); ?>";
  var profile_error        = "<?php echo e(trans('app_lang.fill_your_profile_details')); ?>";
</script>