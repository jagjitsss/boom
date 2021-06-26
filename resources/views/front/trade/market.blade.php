<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 p-l p-r">
		<div class="portlet">
			<div class="portlet-header">
				<span>{{trans('app_lang.markets') }}</span>
			</div>
		   
		   <div class="portlet-content nav-div">
				<ul class="nav nav-tabs justify-content-between">
					<input type="text" placeholder="{{trans('app_lang.search') }}" class="portlet-header-search market_search">
				    <li class="nav-item">
						<a class="nav-link tab-pane-trade active" data-toggle="tab" data-target="#trade-fav" href="javascript:;">Fav</a>
					</li>
					<li class="nav-item">
						<a class="nav-link tab-pane-trade" data-toggle="tab" data-target="#trade-btc" href="javascript:;">BTC</a>
					</li>
					<li class="nav-item">
						<a class="nav-link tab-pane-trade" data-toggle="tab" data-target="#trade-eth" href="javascript:;">ETH</a>
					</li>
					<li class="nav-item">
						<a class="nav-link tab-pane-trade" data-toggle="tab" data-target="#trade-usdt" href="javascript:;">USDT</a>
					</li>
				</ul>
				<div class="tab-content">
				<div class="tab-pane tab-pane-trade container-fluid active p-0" id="trade-fav">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table" id="m-fav">
								<thead>
								  <tr>
									<th class="portlet-star-cnt"></th>
									<th>{{trans('app_lang.pair') }}</th>
									<th>{{trans('app_lang.price') }}</th>
									<th>{{trans('app_lang.change') }}</th>
								  </tr>
								</thead>
								<tbody class="tb-265 fav_tab">
								  
								 
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane tab-pane-trade container-fluid fade p-0" id="trade-btc">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table" id="m-btc">
								<thead>
								  <tr>
									<th class="portlet-star-cnt"></th>
									<th>{{trans('app_lang.pair') }}</th>
									<th>{{trans('app_lang.price') }}</th>
									<th>{{trans('app_lang.change') }}</th>
								  </tr>
								</thead>
								<tbody class="tb-265 btc_tab">
								  
								 
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="tab-pane tab-pane-trade container-fluid fade p-0" id="trade-eth">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table"  id="m-eth">
								<thead>
								  <tr>
									<th class="portlet-star-cnt"></th>
									<th>{{trans('app_lang.pair') }}</th>
									<th>{{trans('app_lang.price') }}</th>
									<th>{{trans('app_lang.change') }}</th>
								  </tr>
								</thead>
								<tbody class="tb-265 eth_tab">
								
								 
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="tab-pane tab-pane-trade container-fluid fade p-0" id="trade-usdt">
						<div class="table-responsive">
							<table class="table table-hover table-borderless market-table"   id="m-usdt">
								<thead>
								  <tr>
									<th class="portlet-star-cnt"></th>
									<th>{{trans('app_lang.pair') }}</th>
									<th>{{trans('app_lang.price') }}</th>
									<th>{{trans('app_lang.change') }}</th>
								  </tr>
								</thead>
								<tbody class="tb-265 usdt_tab">
								  
								</tbody>
							</table>
						</div>
					</div>
				</div>  
		   </div>
		   
		</div>
	</div>