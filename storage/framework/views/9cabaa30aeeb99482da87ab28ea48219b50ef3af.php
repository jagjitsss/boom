<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 p-l p-r my_order_blk mob2">
		<div class="portlet mob-inn1">
			<div class="portlet-header">
				<ul class="nav nav-tabs d-flex">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" data-target="#trade-myorder" href="javascript:;"><?php echo e(trans('app_lang.open_orders')); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" data-target="#stoporder" href="javascript:;"><?php echo e(trans('app_lang.stop_orders')); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" data-target="#trade-history" href="javascript:;"><?php echo e(trans('app_lang.trade_history')); ?></a>
					</li>
				</ul>
			</div>
		   
			<div class="portlet-content mob-inn-ms1">
				<div class="tab-content">
					<div class="tab-pane container active" id="trade-myorder">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table trade-openorder">
								<thead>
								  <tr>
									<th><?php echo e(trans('app_lang.date_time')); ?></th>
									<th><?php echo e(trans('app_lang.order_type')); ?></th>
									<th><?php echo e(trans('app_lang.type')); ?></th>
									<th><?php echo e(trans('app_lang.amount')); ?>(<span class="to_cur"></span>)</th>
									<th><?php echo e(trans('app_lang.price')); ?>(<span class="from_cur"></span>)</th>
									<th><?php echo e(trans('app_lang.total')); ?>(<span class="from_cur"></span>)</th>
									<th><?php echo e(trans('app_lang.action')); ?></th>
								  </tr>
								</thead>
								<tbody class="tb-357" id="openOrdersTable">
								</tbody>
							

										
							</table>
						</div>
					</div>
					<div class="tab-pane container fade" id="stoporder">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table">
								<thead>
								  <tr>
										<th><?php echo e(trans('app_lang.date_time')); ?></th>
										<th><?php echo e(trans('app_lang.order_type')); ?></th>
										<th><?php echo e(trans('app_lang.type')); ?></th>
										<th><?php echo e(trans('app_lang.amount')); ?>(<span class="to_cur"></span>)</th>
										<th><?php echo e(trans('app_lang.price')); ?>(<span class="from_cur"></span>)</th>
										<th><?php echo e(trans('app_lang.price_stop')); ?>(<span class="from_cur"></span>)</th>
										<th><?php echo e(trans('app_lang.total')); ?>(<span class="from_cur"></span>)</th>
									
									    <th><?php echo e(trans('app_lang.action')); ?></th>
								  </tr>
								</thead>
								<tbody class="tb-357" id="stopOrdersTable">
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane container fade"  id="trade-history">
						<div class="table-responsive">
						<table class="table table-hover table-borderless market-table">
						   <thead>
						     <tr>
								<th><?php echo e(trans('app_lang.date_time')); ?></th>
								<th><?php echo e(trans('app_lang.order_type')); ?></th>
								<th><?php echo e(trans('app_lang.type')); ?></th>
								<th><?php echo e(trans('app_lang.amount')); ?>(<span class="to_cur"></span>)</th>
								<th><?php echo e(trans('app_lang.price')); ?>(<span class="from_cur"></span>)</th>
							
								<th><?php echo e(trans('app_lang.total')); ?>(<span class="from_cur"></span>)</th>
								<th><?php echo e(trans('app_lang.status')); ?></th>
						     </tr>
						   </thead>
								<tbody class="tb-357" id="myTradeHistory">
								 
								 
								</tbody>
					    </table>
						</div>
					</div>
						
				</div>
			</div>  
		</div>
		   
	</div>
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 p-l p-r my_order_blk mob2">
  <div class="portlet limit-portlet-cnt mob-inn2">
    <div class="portlet-header ul-buy-sell d-flex">
      <ul class="nav nav-tabs d-flex">
	  	<li class="nav-item"><a href="javascript:void(0);" data-type="buy" onclick="onclick_to_trde_type(this)" class="nav-link active">Buy</a></li>
	  	<li class="nav-item"><a href="javascript:void(0);" data-type="sell" onclick="onclick_to_trde_type(this)" class="nav-link">Sell</a></li>
      </ul>
	  <ul class="nav nav-tabs d-flex">
        
        <li class="ul_li_buy nav-item"> 
        	<a id="ul_li_buy_a_limit" class="nav-link advbuylimit show active" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('limit','buy')"   href="javascript:;"><?php echo e(trans('app_lang.limit')); ?></a>
        </li>
        
        <li class="ul_li_buy nav-item">
        	<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('market','buy')"   href="javascript:;"><?php echo e(trans('app_lang.market')); ?></a>
        </li>
        
        <li class="ul_li_buy nav-item">
        	<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('stop','buy')"   href="javascript:;"><?php echo e(trans('app_lang.stop_limit')); ?></a>
        </li>


        <li class="ul_li_sell nav-item" style="display: none;"> 
        	<a id="ul_li_sell_a_limit" class="nav-link advbuylimit show active" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('limit','sell')"   href="javascript:;"><?php echo e(trans('app_lang.limit')); ?></a>
        </li>
        
        <li class="ul_li_sell nav-item" style="display: none;">
        	<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('market','sell')"   href="javascript:;"><?php echo e(trans('app_lang.market')); ?></a>
        </li>
        
        <li class="ul_li_sell nav-item" style="display: none;">
        	<a class="nav-link advbuyform" data-toggle="tab" data-target="#advtrade-limit" onclick="show_adv_order('stop','sell')"   href="javascript:;"><?php echo e(trans('app_lang.stop_limit')); ?></a>
        </li>

      </ul>
    </div>
    <div class="portlet-content ">
      <div class="tab-content">
        <div class="tab-pane container active" id="advtrade-order">

          <div id="basic_buy_trade_parent" class="adv-buycnt adv-buycnt-full">
            <div class="table-responsive">
              <div class="d-flex justify-content-center">
                <div class="adv-info-row">
                  <div class="info-row"> 
					  
                    <?php if ($log) {?>
                    <p class="ava_bal"><span class="from_cur"></span><span> Available:</span><span class="from_bal"></span></p>
                    <?php }?>
                  </div>
                </div>
              </div>
              <input type="hidden" class="buytype" value="limit">
              <div class="portlet-txtbox-cnt d-flex">
				<b>Price</b>
				<span class="minus-one">
					<a href="javascript:void(0);" data-type="buy" data-input="input_one" onclick="return decrement(this);">-</a>
				</span>
                <input id="input_one" type="text" onkeyup="calculation('buy');"  class="amount buyamount" placeholder="<?php echo e(trans('app_lang.amount')); ?>" onkeypress="return isNumberKey(event)" >

                <span class="plus-one">
                	<a href="javascript:void(0);" data-type="buy" data-input="input_one" onclick="return increment(this);">+</a>
                </span>
				<span class="bcc-indicator to_cur"></span> 
			  </div>
              <div class="portlet-txtbox-cnt d-flex">
			  	<b>Amount</b>
				<span class="minus-one">
					<a href="javascript:void(0);" data-type="buy" data-input="input_two" onclick="return decrement(this);">-</a>
				</span>

                <input  id="input_two" type="text" onkeyup="calculation('buy');"  class="price buyprice" placeholder="<?php echo e(trans('app_lang.price')); ?>" onkeypress="return isNumberKey(event)">
				<span class="plus-one">
					<a href="javascript:void(0);" data-type="buy" data-input="input_two" onclick="return increment(this);">+</a>
				</span>


				<span class="bcc-indicator price from_cur"></span>
			  </div>

              <div id="stop_buy_section" class="portlet-txtbox-cnt stop buystop hide d-flex" style="display: none !important;">
			  	<b>Price</b>
				<span class="minus-one"><a href="javascript:void(0);" data-type="buy" data-input="input_three" onclick="return decrement(this);">-</a></span>
                <input id="input_three" type="text" onkeyup="calculation('buy');" class="buystopprice" placeholder="<?php echo e(trans('app_lang.price_stop')); ?>" onkeyup="calculation('buy');"  onkeypress="return isNumberKey(event)" >
				<span class="plus-one"><a href="javascript:void(0);" data-type="buy" data-input="input_three" onclick="return increment(this);">+</a></span>
				<span class="bcc-indicator from_cur"></span> 
			</div>

             <div class="coupon-cnt" id="couponcnt"> <span class="coupon btnPerc" type="buy" id="buy25">25%</span> <span class="coupon btnPerc" type="buy" id="buy50">50%</span> <span class="coupon btnPerc" type="buy" id="buy75">75%</span> <span class="coupon btnPerc" type="buy" id="buy100">100%</span> </div>
             <div class="clearfix"></div>
			  <div class="d-flex full-md-total">
			 	<div class="full-wd-info-cnt nomarket"> <?php echo e(trans('app_lang.total')); ?><span>
			 		<span class="tot buytot">0.00000000 </span>
			 		<span class="from_cur"></span></span>
			 	</div>
				 <div class="portlet-btn-cnt">

				 <p class="text-center green-text-m font10">
				 	<span class="to_cur"></span> to be received : <br>
				 	<span class="to_be_received_buy">0.00000000 </span>
				 </p>
                <?php if ($log) {
				$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
				if($p == 'empty') { ?>
                <button type="button" class="buybtn" id="probuy"><?php echo e(trans('app_lang.buy')); ?></button>
                <?php } else { ?>
                <button type="button" class="buybtn" onclick="return order_placed('buy',this)" ><?php echo e(trans('app_lang.buy')); ?></button>
                <?php } } else { ?>
                <p class="b4login-p"><a class="green-link" href="<?php echo url('login'); ?>"><?php echo e(trans('app_lang.login')); ?></a>
                  <?php } ?>
              </div>
			 </div> 
			</div>
          </div>







          <div id="basic_sell_trade_parent" class="adv-sellcnt adv-sellcnt-full">
            <div class="d-flex justify-content-center">
              <div class="adv-info-row">
                <div class="info-row"> 
					
                  <?php if ($log) {?>
                  <p class="ava_bal"><span class="to_cur"></span><span> Available:</span><span class="to_bal"></span></p>
                  <?php }?>
                </div>
              </div>
            </div>
            <input type="hidden" class="selltype" value="limit">
            <div class="portlet-txtbox-cnt d-flex">
				<b>Price</b>
				<span class="minus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_four" onclick="return decrement(this);">-</a></span>
              <input id="input_four" type="text" onkeyup="calculation('sell');" placeholder="<?php echo e(trans('app_lang.amount')); ?>" class="amount sellamount" onkeypress="return isNumberKey(event)" >
             <span class="plus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_four" onclick="return increment(this);">+</a></span> 
			  <span class="bcc-indicator to_cur"></span> </div>
            <div class="portlet-txtbox-cnt d-flex">
				<b>Price</b>
				<span class="minus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_five" onclick="return decrement(this);">-</a></span>
              <input id="input_five" type="text" onkeyup="calculation('sell');" placeholder="<?php echo e(trans('app_lang.price')); ?>" class="sellprice price" onkeypress="return isNumberKey(event)" >
             <span class="plus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_five" onclick="return increment(this);">+</a></span> 
			  <span class="bcc-indicator price from_cur"></span> </div>


            <div id="stop_sell_section" class="portlet-txtbox-cnt stop sellstop hide d-flex" style="display: none!important;">
				<b>Price</b>
				<span class="minus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_six" onclick="return decrement(this);">-</a></span>
              <input id="input_six" type="text" onkeyup="calculation('sell');" class="sellstopprice" placeholder="<?php echo e(trans('app_lang.price_stop')); ?>" onkeyup="calculation('sell');"  onkeypress="return isNumberKey(event)" >
             <span class="plus-one"><a href="javascript:void(0);" data-type="sell" data-input="input_six" onclick="return increment(this);">+</a></span> 
			  <span class="bcc-indicator from_cur"></span>
			</div>


            <div class="coupon-cnt"> <span class="coupon btnPerc" type="sell" id="sell25">25%</span> <span class="coupon btnPerc" type="sell" id="sell50">50%</span> <span class="coupon btnPerc" type="sell" id="sell75">75%</span> <span class="coupon btnPerc" type="sell" id="sell100">100%</span> </div>
            <div class="clearfix"></div>
			<div class="d-flex full-md-total">
			<div class="full-wd-info-cnt nomarket"> <?php echo e(trans('app_lang.total')); ?><span><span class="tot selltot">0.00000000 </span> <span class="from_cur"></span></span> </div>
            <div class="portlet-btn-cnt">
				

				<p class="text-center green-text-m font10">
				 	<span class="from_cur"></span> to be received : <br>
				 	<span class="tot selltot to_be_received_sell">0.00000000 </span>
				 </p>

              <?php if ($log) {
				$p = session::get('tmaitb_profile') == ' ' ? 'empty' : 'fill';
				if($p == 'empty') { ?>
              <button type="button" class="portlet-red-btn sellbtn" id="prosell"><?php echo e(trans('app_lang.sell')); ?></button>
              <?php } else { ?>
              <button type="button"  onclick="return order_placed('sell',this)"  class="portlet-red-btn sellbtn"><?php echo e(trans('app_lang.sell')); ?></button>
              <?php } } else { ?>
              <p class="b4login-p"><a class="green-link" href="<?php echo url('login'); ?>"><?php echo e(trans('app_lang.login')); ?></a>
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

<div id="wallet" style="display: none !important;"></div>


<style type="text/css">
#basic_buy_trade_parent{
	display: block;
}

#basic_sell_trade_parent{
	display: none;
}
</style>


<script type="text/javascript">
function onclick_to_trde_type(thiz){
	var data_type = $(thiz).attr("data-type");
	$(".nav-link.active").removeClass("active");
	$(thiz).addClass("active");

	if(data_type == "sell")
	{
		$("#basic_sell_trade_parent").show();
		$("#basic_buy_trade_parent").hide();
		$(".ul_li_buy").attr("style", "display:none");
		$(".ul_li_sell").attr("style", "display:block");
		$("#ul_li_sell_a_limit").trigger("click");
	}
	else
	{
		$("#basic_sell_trade_parent").hide();
		$("#basic_buy_trade_parent").show();
		$(".ul_li_sell").attr("style", "display:none");
		$(".ul_li_buy").attr("style", "display:block");
		$("#ul_li_buy_a_limit").trigger("click");
	}
}
</script>
<script>
    function increment(thiz)
    {
    	var this_unique = $(thiz).attr("data-input");
    	var this_type = $(thiz).attr("data-type");
    	var prevval = $("#"+this_unique).val();
    	if(prevval > 0)
    	{
    		var new_tot = parseFloat(prevval) + parseFloat(0.10000000);
    		$("#"+this_unique).val(parseFloat(new_tot).toFixed(8));
    	}
    	else
    	{
    		$("#"+this_unique).val(parseFloat(0.10000000).toFixed(8));	
    	}
    	return calculation(this_type);
    }
    function decrement(thiz)
    {
        var this_unique = $(thiz).attr("data-input");
        var this_type = $(thiz).attr("data-type");
    	var prevval = $("#"+this_unique).val();
    	if(prevval >= 1)
    	{
    		var new_tot = parseFloat(prevval) - parseFloat(0.10000000);
    		
    		$("#"+this_unique).val(parseFloat(new_tot).toFixed(8));
    	}
    	else
    	{
    		$("#"+this_unique).val(parseFloat(0.10000000).toFixed(8));	
    	}

    	return calculation(this_type);
    }
</script>