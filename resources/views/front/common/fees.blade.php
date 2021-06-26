<div class="container-fluid static-content-section">
	<div class="container static-content">
		<div class="static-heading">{{trans('app_lang.fees') }}</div>
		<div class="row post-slideUp">
			<div class="about-us-banner col-xs-12 col-sm-12"><img src="{{asset('/').('public/assets/images/about-us-banner.png')}}" class="img-fluid"></div>
		</div>
		<div class="row mb-10 static-content-para">
			


<div class="static-page-para-heading col-xs-12 col-sm-12">Effective March 20, 2019</div>

<p class="static-content-para col-xs-12 col-sm-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt labore dolore magna aliqua. Enim minim veniam, quis nostrud exercitation ullamco adipiscing eiusmod.</p>

<div class="static-page-para-heading col-xs-12 col-sm-12">What are the new fees ?</div>

<p class="static-content-para col-xs-12 col-sm-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt labore dolore magna aliqua. Enim minim veniam, quis nostrud exercitation ullamco adipiscing elit, sed do eiusmod. labore dolore magna aliqua. minim veniam, quis nostrud exercitation ullamco eiusmod tempor incididunt labore dolore magna aliqua.</p>

<div class="row fees-table col-sm-12 justify-content-center">
<div class="col-sm-8">
<ul class="nav nav-tabs justify-content-center">
	<li class="nav-item"><a class="nav-link active" data-target="#btc" data-toggle="tab" href="javascript:;">{{trans('app_lang.trading_fees') }}</a></li>
	<li class="nav-item"><a class="nav-link" data-target="#sfcp" data-toggle="tab" href="javascript:;">{{trans('app_lang.withdraw_fees') }}</a></li>
</ul>

<div class="card-div-cnt no-padding">
<div class="table-responsive dsb-wallet-table">
<div class="tab-content">
<div class="tab-pane container active" id="btc">
<table class="table table-hover table-borderless">
	<thead>
		<tr>
			<th>{{trans('app_lang.pair') }}</th>
			<th>{{trans('app_lang.maker_fee') }}%</th>
			<th>{{trans('app_lang.taker_fee') }}%</th>
			<th>{{trans('app_lang.refer_fee') }}%</th>
		</tr>
	</thead>
	<tbody>
		@foreach($tradefee as $trade)
		<tr>
			<td>{{$trade->to_symbol}}/{{$trade->from_symbol}}</td>
			<td>{{$trade->trade_fee}}</td>
			<td>{{$trade->taker_trade_fee}}</td>
			<td>{{$trade->refer_fee}}</td>
		</tr>
		@endforeach		
	</tbody>
</table>
</div>

<div class="tab-pane container fade" id="sfcp">
<table class="table table-hover table-borderless">
	<thead>
		<tr>
			<th>{{trans('app_lang.currency') }}</th>
			<th>{{trans('app_lang.withdraw_fees') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($withfee as $with)
		<tr>
			<td>{{$with->symbol}}</td>
			<td>{{$with->with_fee}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>		
</div>
</div>
<div class="container-fluid marquee-cnt">
	<div class="body-content">
		<div id="demo4" class="scroll-img">
		  <ul>
		  <?php $currency_pairs_details = currency_pairs_details();?>
		  @foreach($currency_pairs_details as $pair_details)
			<li><a href="{{url('/trade')}}/{{$pair_details->to_symbol}}_{{$pair_details->from_symbol}}" target="_blank">
				<span class="crypcoss-icon"><img src="{{asset('/').('public/images/admin_currency/').$pair_image[$pair_details->to_symbol]}}"> {{$pair_details->to_symbol}}/</span>
				<span>{{$pair_details->from_symbol}}</span>
				<span><i class="fa fa-fw fa-caret-down"></i></span>
				<?php 
				$price = $pair_details->last_price;?>
				<span><?php echo number_format($price,8,'.','');?></span>
			</a>
			</li>
			@endforeach
			
		  </ul>
		</div>
	</div>
</div>