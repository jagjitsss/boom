<div class="portlet-header ph-trade">

	<ul class="nav nav-tabs d-flex order-head-box">
		<li class="nav-item">
			<div class="order-head">Order Book</div>
		</li>
		<li class="nav-item f-right">
			<a class="nav-link active" data-toggle="tab" data-target="#advtrade-allorder" href="javascript:;">Both</a>
		</li>
		<li class="nav-item f-right">
			<a class="nav-link" data-toggle="tab" data-target="#advtrade-buyorder" href="javascript:;">Buy</a>
		</li>
		<li class="nav-item f-right">
			<a class="nav-link" data-toggle="tab" data-target="#advtrade-sellorder" href="javascript:;">Sell</a>
		</li>
	</ul>
</div>
<div class="portlet-content">
<div class="tab-content">


<div class="tab-pane container active" id="advtrade-allorder">
										<div class="table-responsive">
											<table class="table table-hover table-borderless market-table">
												<thead>
												  <tr>
													<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
													<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
													<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
												  </tr>
												</thead>
												<tbody class="tb-84 selltb">

												</tbody>

											</table>
<hr>
											

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
		  	<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
		  	<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
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
