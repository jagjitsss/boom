<div class="container-fluid static-content-section" id="accordion-style-1">
	<div class="container static-content">
		<div class="static-heading">{{trans('app_lang.frequently_asked') }}</div>
		<section class="faq-cnt">
			<div class="row">
				<div class="col-10 mx-auto">
					<div class="accordion" id="accordionExample">

						<?php $i=1;
						foreach($faq as $faqcontent){
						 ?>
						 	<div class="card">

							<div class="card-header" id="heading{{$i}}">
								<h5 class="mb-0">
									<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$i}}" aria-expanded="true" aria-controls="collapse{{$i}}">
										<i class="fa fa-fw fa-plus-circle"></i><?php echo $faqcontent['question'];?>
									</button>
								  </h5>
							</div>

							

							<div id="collapse{{$i}}" class="collapse <?php echo $i==1?'show':'';?> fade" aria-labelledby="heading{{$i}}" data-parent="#accordionExample">
								<div class="card-body"><?php echo $faqcontent['description'];?>
								</div>
							</div>

							</div>
							<?php  $i++;
							} ?>
						
					</div>
				</div>	
			</div>
		</section>
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