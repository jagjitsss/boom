<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 trade-right-table-cnt">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 p-r">
		<div class="portlet">
			<div class="portlet-header ph-trade-history">
				<span class="trade_hd"><?php echo e(trans('app_lang.history_trade')); ?></span>
			</div>
			<div class="portlet-content">
				<div class="tab-content">
					<div class="tab-pane container active bpt2" id="trade-market">
						<div class="table-responsive border-portlet-table">
							<table class="table table-hover table-borderless market-table">
								
								<thead>
												  <tr>
													<th>Time</th>
													<th>Amount</th>
													<th>Price</th>
												  </tr>
												</thead>
												 <tbody class="" id="tradeHistory"> 
													</tbody>
												
							</table>
						</div>
					</div>
					<div class="tab-pane container" id="stoporder_my">
						<div class="table-responsive border-portlet-table">
							<table class="table table-hover table-borderless market-table">
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>