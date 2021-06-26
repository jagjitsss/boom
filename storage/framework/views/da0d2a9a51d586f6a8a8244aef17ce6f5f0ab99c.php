<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 xl-p-r xl-p-l">
							<div class="portlet">
								<div class="portlet-header">
									<ul class="nav nav-tabs d-flex">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" data-target="#advtrade-myorder" href="javascript:;"><?php echo e(trans('app_lang.open_orders')); ?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" data-target="#advstop-myorder" href="javascript:;"><?php echo e(trans('app_lang.stop_orders')); ?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" data-target="#advtrade-history" href="javascript:;"><?php echo e(trans('app_lang.trade_history')); ?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" data-target="#advtrade-funds" href="javascript:;"><?php echo e(trans('app_lang.funds')); ?></a>
										</li>
									</ul>
								</div>
							   
								<div class="portlet-content">
									<div class="tab-content xs-cat-cnt">
										<div class="tab-pane container active" id="advtrade-myorder">
											<div class="table-responsive">
												<table class="table table-hover table-borderless market-table">
													<thead>
													  <tr>
														<th><?php echo e(trans('app_lang.date_time')); ?></th>
														<th><?php echo e(trans('app_lang.order_type')); ?></th>
														<th><?php echo e(trans('app_lang.type')); ?></th>
														<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
														<th class="text-center"><?php echo e(trans('app_lang.action')); ?></th>
													  </tr>
													</thead>
													<tbody class="tb-174" id="openOrdersTable">
													 
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane container " id="advstop-myorder">
											<div class="table-responsive">
												<table class="table table-hover table-borderless market-table">
													<thead>
													  <tr>
														<th><?php echo e(trans('app_lang.date_time')); ?></th>
														<th><?php echo e(trans('app_lang.order_type')); ?></th>
														<th><?php echo e(trans('app_lang.type')); ?></th>
														<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.stop_price')); ?> (<span class="from_cur"></span>)</th>
														
														<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
														<th class="text-center"><?php echo e(trans('app_lang.action')); ?></th>
													  </tr>
													</thead>
													<tbody class="tb-174" id="stopOrdersTable">
													 
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane container fade"  id="advtrade-history">
											<div class="table-responsive">
												<table class="table table-hover table-borderless market-table">
													<thead>
													  <tr>
														<th><?php echo e(trans('app_lang.date_time')); ?></th>
														<th><?php echo e(trans('app_lang.order_type')); ?></th>
														<th><?php echo e(trans('app_lang.type')); ?></th>
														<th><?php echo e(trans('app_lang.amount')); ?> (<span class="to_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.price')); ?> (<span class="from_cur"></span>)</th>
														<th><?php echo e(trans('app_lang.total')); ?> (<span class="from_cur"></span>)</th>
														<th class="text-center"><?php echo e(trans('app_lang.status')); ?></th>
													  </tr>
													</thead>
													<tbody class="tb-174" id="myTradeHistory">
													  
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane container fade"  id="advtrade-funds">
											<div class="table-responsive">
												<table class="table table-hover table-borderless market-table">
													<thead>
													  <tr>
														<th><?php echo e(trans('app_lang.coin')); ?></th>
														<th><?php echo e(trans('app_lang.balance')); ?></th>
														<th><?php echo e(trans('app_lang.total_balance')); ?></th>
														<th class="text-center"><?php echo e(trans('app_lang.in_order')); ?></th>
													  </tr>
													</thead>
													<tbody class="tb-174" id="balance">
													  
													</tbody>
												</table>
											</div>
										</div>
											
									</div>
								</div>  
							</div>
							   
						</div>	