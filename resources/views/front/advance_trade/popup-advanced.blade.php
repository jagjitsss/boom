<div class="dropdown-menu dropdown-menu1 dd-table-cnt">
		<div class="dd-table">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link tab-pane-trade site_tet" data-toggle="tab" data-target="#dd-trade-fav" href="javascript:;">{{trans('app_lang.favorite') }}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active tab-pane-trade site_tet" data-toggle="tab" data-target="#dd-trade-btc" href="javascript:;">BTC</a>
				</li>
		
				<input type="text" placeholder="{{trans('app_lang.search') }}" class="adv_search portlet-header-search" style="color:white;">
			</ul>
			<div class="tab-content">
				<div class="tab-pane container fade tab-pane-trade" id="dd-trade-fav">
					<div class="table-responsive">
						<table class="table table-hover table-borderless market-table adv-market">
							<thead>
							  <tr>
								<th class="portlet-star-cnt"></th>
								<th>{{trans('app_lang.pair') }}</th>
								<th>{{trans('app_lang.price') }}</th>
								<th>24h {{trans('app_lang.change') }}</th>
								<th>24h {{trans('app_lang.volume') }}</th>
							  </tr>
							</thead>
							<tbody class="tb-174 m-fav">
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="tab-pane container active tab-pane-trade" id="dd-trade-btc">
					<div class="table-responsive">
						<table class="table table-hover table-borderless market-table  adv-market">
							<thead>
							  <tr>
								<th class="portlet-star-cnt"></th>
								<th>{{trans('app_lang.pair') }}</th>
								<th>{{trans('app_lang.price') }}</th>
								<th>24h {{trans('app_lang.change') }}</th>
								<th>24h {{trans('app_lang.volume') }}</th>
							  </tr>
							</thead>
							<tbody class="tb-174  m-btc" id="m-btc">
							</tbody>
						</table>
					</div>
				</div>
				
			
			</div>
		</div>
	  </div>