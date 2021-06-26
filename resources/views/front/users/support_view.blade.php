<div class="row" id="viewtickets">
					<div class="col-sm-12 ticket-tabs-cnt mt-3">
						<div class="search-cnt">
							<input id="search_ticket" type="text" class="support-search" placeholder="{{trans('app_lang.search_ticket') }}">
						</div>
						<ul class="nav nav-tabs tickets-tab">
							<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" data-target="#opened-ticket" href="#">{{trans('app_lang.open_tickets') }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link  " data-toggle="tab" data-target="#closed-ticket" href="#">{{trans('app_lang.close_tickets') }}</a>
							</li>
						</ul>
					</div>

					<div class="col-sm-12 tab-content">
						<div id="opened-ticket" class="col-sm-12 tab-pane container active p-0">
							<div class="accordion col-sm-12 ticket-accordion-cnt p-0" id="accordionExample">


						@if(count($tickets_active))
						@foreach($tickets_active as $item)
								<div class="card">
                 				  <b class="div_searc">
									<div class="card-header" id="headingOne">
										<h5 class="mb-0">
											<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$item->reference_no}}" aria-expanded="true" aria-controls="collapseOne">
												<i class="fa fa-fw fa-plus-circle"></i>
												<span>{{trans('app_lang.id') }} : TKT{{$item->reference_no}}</span>
												<span class="category">{{trans('app_lang.category') }} : <label><?php echo getsupportcategory($item->reference_no); ?></label></span>
												<span class="date">{{$item->created_at}}</span>
												<span class="subject">{{$item->subject}}</span>
											</button>
										  </h5>
									</div>
									</b>

									<div id="collapse{{$item->reference_no}}" class="collapse  fade" aria-labelledby="headingOne" data-parent="#accordionExample">
										<div class="card-body">
											<div class="ticket-message">{{$item->message}}</div>

											<?php
if ($item->image) {
	$img = $item->reference_no;?>
                                            <div class="message-image">
                                            	
                                            	<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$item->image}}" width="100" height="100">
                                            </div>
                                            <?php }?>

											
											<div class="ticket-conversation-cnt message-cnt-ht deetails" id="{{insep_encode($item->reference_no)}}">
											<?php $val = ticket_details($item->reference_no);

foreach ($val as $key => $value) {
	if ($key < 1) {
		continue;
	}

	//User Content
	if ($value['admin_name']) {
		?>
		<div class="message-row-cnt">
			<div class="message-prof-pic-cnt">
				<div class=""><img src="{{asset('/').('public/assets/images/')}}avatar.png" class="message-prof-pic mCS_img_loaded"></div>
			</div>
			<div class="message-txt-cnt">
				<div class="message-date-cnt"><span><?php echo $value['created_at']; ?></span></div>
				<div class="message-txt"><?php echo $value['message']; ?></div>
				<?php if ($value['image']) {
			$img = $value['id'];
			?>
				<div class="message-image">
					
					<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
				</div>
				<?php }?>
			</div>
		</div>
                                               <?php } else {
		?>

	<div class="message-row-cnt admin-conv">
		<div class="message-prof-pic-cnt">
		<?php 

		$profil = $value['admin_name'] ? admin_image($value['admin_name']) : $profile;?>
			<div class=""><img src="{{$profil}}" class="message-prof-pic mCS_img_loaded"></div>
		</div>
		<div class="message-txt-cnt">
			<div class="message-date-cnt"><span><?php echo $value['created_at']; ?></span></div>
			<div class="message-txt"><?php echo $value['message']; ?></div>
			<?php if ($value['image']) {
			$img = $value['id'];
			?>
				<div class="message-image">
					
					<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
				</div>
			<?php }?>
		</div>
	</div>
												<?php }}?>
											</div>

											
											<div class="col-xs-12 col-sm-12 close-tick-cnt p-0">
	{!! Form::open(array('class'=>'edit_support', 'enctype' => "multipart/form-data" )) !!}

													<div class="custom-controls-stacked d-block my-1">
															<label class="custom-control fill-checkbox">
																<input type="checkbox" class="fill-control-input" name="close_ticket" id="close_ticket">
																<span class="fill-control-indicator"></span>
																<span class="fill-control-description">{{trans('app_lang.close_ticket') }}</span>
															</label>
														</div>

		<div class="message-action-cnt">
			
			 <textarea rows="4" cols="50" class="message-txt-box comment_txt" name="comment" placeholder="{{trans('app_lang.writing_reply') }}" required="" style="height: 52px;"></textarea>
			<input type="hidden" name="edit_ref_no"  class="edit_ref_no" value="{{insep_encode($value['reference_no'])}}">
			<div class="attachment">
				<label for="file-attach">
					<img src="{{asset('/').('public/assets/images/')}}attachment-icon.png" class="mCS_img_loaded">

				</label>

			
				<input class="html_btn" type="file" id="file-attach" onchange="showimage_edit(this,'thumbnil_tiks')" name="file">

			</div>
			<label for="comment" class="error hide thumbnil_tiks" id="thumbnil_tiks">This field is required</label>
			<img class="ticket-img thumbnil_tiks hide mCS_img_loaded" width="100" height="100">
			<button type="submit" class="submitBtnsupport attch-send-icon"><i class="fa fa-fw fa-paper-plane"></i></button>

		</div>
		{!! Form::close() !!}
											</div>
										</div>
									</div>
								</div>
						@endforeach
						@else
								<li class="no_recorder">
									{{trans('app_lang.no_tickets_available') }}
								</li>
						@endif
								

								<li class="no_recorder1" style="display: none;">
									{{trans('app_lang.no_tickets_available') }}
								</li>



							</div>
						</div>

						<div id="closed-ticket" class="col-sm-12 tab-pane container p-0">
							<div class="accordion col-sm-12 ticket-accordion-cnt p-0" id="accordionExample">
                            @if(count($tickets_inactive))
							@foreach($tickets_inactive as $item)
								<div class="card">
								 <b class="div_searc">
									<div class="card-header" id="headingOne">
										<h5 class="mb-0">
											<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$item->reference_no}}" aria-expanded="true" aria-controls="collapseOne">
												<i class="fa fa-fw fa-plus-circle"></i>
												<span>ID : TKT{{$item->reference_no}}</span>
												<span class="category">Category : <label><?php echo getsupportcategory($item->reference_no); ?></label></span>
												<span class="date">{{$item->created_at}}</span>
												<span class="subject">{{$item->subject}}</span>
											</button>
										  </h5>
									</div>
								  </b>

									<div id="collapse{{$item->reference_no}}" class="collapse  fade" aria-labelledby="headingOne" data-parent="#accordionExample">
										<div class="card-body">
											<div class="ticket-message">{{$item->message}}</div>
											<?php
if ($item->image) {
	$img = $item->reference_no;?>
                                            <div class="message-image">
                                            	
                                            	<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$item->image}}" width="100" height="100">
                                            </div>
                                            <?php }?>
											<div class="ticket-conversation-cnt message-cnt-ht">
			<?php $val = ticket_details($item->reference_no);
foreach ($val as $key => $value) {
	if ($key < 1) {
		continue;
	}

	if ($value['admin_name']) {?>


			
	<div class="message-row-cnt ">
		<div class="message-prof-pic-cnt">

			<div class=""><img src="{{asset('/').('public/assets/images/')}}avatar.png" class="message-prof-pic mCS_img_loaded"></div>
		</div>
		<div class="message-txt-cnt">
			<div class="message-date-cnt"><span><?php echo $value['created_at']; ?></span></div>
			<div class="message-txt"><?php echo $value['message']; ?></div>
			<?php if ($value['image']) {?>
			<div class="message-image"><a href="" target="_blank">
				
				<img class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
			</a></div>
			<?php }?>
		</div>
	</div>

		<?php } else {?>
    
	<div class="message-row-cnt admin-conv">
		<div class="message-prof-pic-cnt">
		<?php $profil = $value['admin_name'] ? admin_image($value['admin_name']) : $profile;?>
			<div class=""><img src="{{$profil}}" class="message-prof-pic mCS_img_loaded"></div>
		</div>
		<div class="message-txt-cnt">
			<div class="message-date-cnt"><span><?php echo $value['created_at']; ?></span></div>
			<div class="message-txt"><?php echo $value['message']; ?></div>
			<?php if ($value['image']) {?>
			<div class="message-image"><a href="" target="_blank">
				
				<img class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
			</a></div>
			<?php }?>
		</div>
	</div>
	<?php }}?>
											</div>
											
										</div>
									</div>
								</div>
								@endforeach
								@else
								<li class="no_recorder">
									{{trans('app_lang.no_tickets_available') }}
								</li>
						@endif

						<li class="no_recorder1" style="display: none;">
							{{trans('app_lang.no_tickets_available') }}
						</li>
							</div>
						</div>
					</div>
				</div>

<script>
// Support Ticket Validation
  var require_field_sup   = "{{trans('app_lang.field_require') }}";
  var only_files_sup      = "{{trans('app_lang.only_files') }}";
  var upload_img          = "{{trans('app_lang.upload_image') }}";
  var ticket_updated      = "{{trans('app_lang.ticket_updated_success') }}";
  var ticket_closed       = "{{trans('app_lang.ticket_closed_success') }}";
  var user_id             = "{{Session::get('tmaitb_user_id')}}"
  var support             = "{{trans('app_lang.create_ticket_now')}}";
</script>