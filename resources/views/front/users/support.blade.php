<style>
	.show_tic{
		background-color: #EDEDED;
	}
</style>


			<div class="tab-pane container no-padding active min-height-cnt support_page" id="support">
				<div class="col-xs-12 col-sm-12 breadcrumb"><span>{{trans('app_lang.support_tickets') }}</span>
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo url('/viewsupport');?>" id="viewtick">{{trans('app_lang.to_view') }}</a>
						</li>
					</ul>


				</div>
			
<div class="support_blk">
						<h1 class="sup_head">{{trans('app_lang.submit_support') }} </h1>
						{!! Form::open(array('id'=>'add_support','class'=>'add-coin-form-cnt','url'=>'add_support','method'=>'POST', 'enctype' => "multipart/form-data",'onsubmit'=>'support_load()')) !!}
							<div class="form-group">
								<label>{{trans('app_lang.select_issue') }}</label>
								<select name="category" class="form-control" id="sel1">
									<option value=''>Select</option>
									@foreach($category as $item)
									<option id="{{$item->id}}">{{$item->category}}</option>
									@endforeach
								</select>
								<label for="sel1" class="error" style="display: none">This field is required</label>
							</div>
							<div id="supporthide" style="display: none">
							<div class="form-group">
								<label>{{trans('app_lang.your_email') }}</label>
								<input type="text" name="email" class="form-control">
								<label for="email" class="error" style="display: none">This field is required</label>
							</div>
							<div class="form-group">
								<label>{{trans('app_lang.subject') }}</label>
								<input type="text" id="subject" name="subject" class="form-control" placeholder="{{trans('app_lang.subject') }}">
								<label for="subject" class="error" style="display: none">This field is required</label>
							</div>
							<div class="form-group">
								<label>{{trans('app_lang.message') }}</label>
								<textarea name="description" class="form-control" rows="5" id="comment"></textarea>
								<label for="description" class="error" style="display: none">This field is required</label>

								<?php echo cms_lang('content','0',session('language'),41);?>
							</div>
							<div class="form-group">
								<label>{{trans('app_lang.attachment') }}</label>
								<div id="upload-dropzone" class="upload-dropzone">
									  <input type="file" multiple="true" id="request-attachments" name="file" data-fileupload="true" data-dropzone="upload-dropzone" data-error="upload-error" data-create-url="/hc/en-us/request_uploads" data-name="request[attachments][]" data-pool="request-attachments-pool" data-delete-confirm-msg="">
									  <span>
									    <a>{{trans('app_lang.add_file') }}</a> {{trans('app_lang.drop_file') }}
									  </span>
									  <label id="file-error" class="error" for="Big_logo" style="display:none;">This field is required.</label>
								</div>
							</div>
							<div class="form-group" id="uplist" style="display: none">
							
								<li class="upload-item" data-upload-item="" aria-busy="false">
								 
								  <p class="upload-path" id="upimg" data-upload-path=""></p>
								  <p class="upload-path" data-upload-size=""></p>
								  <p data-upload-issue="" class="notification notification-alert notification-inline" aria-hidden="true"></p>
								  <span class="upload-remove" id="upload-remove"  data-upload-remove=""></span>
								  <div class="upload-progress" data-upload-progress="" style="width: 100%;"></div>
								</li>
							</div>
							<button class="support_create">{{trans('app_lang.submit') }}</button>
							
						</div>
						{!! Form::close() !!}
						</div>

				<div class="row">


				</div>
			</div>
			
	

	
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  
</div>

<script>

  var require_field_sup   = "{{trans('app_lang.field_require') }}";
  var only_files_sup      = "{{trans('app_lang.only_files') }}";
  var upload_img          = "{{trans('app_lang.upload_image') }}";
  var ticket_updated      = "{{trans('app_lang.ticket_updated_success') }}";
  var ticket_closed       = "{{trans('app_lang.ticket_closed_success') }}";
  var user_id             = "{{Session::get('tmaitb_user_id')}}"
  var support        	  = "{{trans('app_lang.create_ticket_now')}}";
</script>
