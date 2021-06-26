<div class="container-fluid static-content-section">
	<div class="container static-content">
		<div class="static-heading"><?php echo cms_lang('title','0',session('language'),$page_id);?></div>
		<div class="row abt_blk">
		
		<div class="mb-10 static-content-para col-xs-12 col-sm-8 col-lg-8">
			
			<?php echo cms_lang('content','0',session('language'),$page_id);?>
		</div>
		<div class="post-slideUp col-xs-12 col-sm-4 col-lg-4 pad_zero">
			<div class="about-us-banner"><img src="{{asset('/').('public/assets/images/about-us-banner.png')}}" class="img-fluid"></div>
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
