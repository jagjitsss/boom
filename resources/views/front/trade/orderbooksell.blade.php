<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 p-l p-r">
		<div class="portlet">
			<div class="portlet-header">
				<span class="text-danger">{{trans('app_lang.sell_orders') }}</span>
				
			</div>
		   
		   <div class="portlet-content">
				<div class="table-responsive border-portlet-table">
					<table class="table table-hover table-borderless market-table">
						<thead>
						  <tr>
							<th>{{trans('app_lang.price') }} <span class="from_cur"></span></th>
							<th>{{trans('app_lang.amount') }} <span class="to_cur"></span></th>
							<th>{{trans('app_lang.total') }} <span class="from_cur"></span></th>
						  </tr>
						</thead>
						<tbody class="tb-289 sellOrdersTable">
						</tbody>
					</table>
				</div>  
		   </div>
		   
		</div>
	</div>