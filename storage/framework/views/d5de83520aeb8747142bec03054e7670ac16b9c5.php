<style>
	.show_tic{
		background-color: #EDEDED;
	}
</style>


			<div class="tab-pane container no-padding active min-height-cnt support_page" id="support">
				<div class="col-xs-12 col-sm-12 breadcrumb"><span><?php echo e(trans('app_lang.support_tickets')); ?></span>
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo url('/viewsupport');?>" id="viewtick"><?php echo e(trans('app_lang.to_view')); ?></a>
						</li>
					</ul>


				</div>
			
<div class="support_blk">
						<h1 class="sup_head"><?php echo e(trans('app_lang.submit_support')); ?> </h1>
						<?php echo Form::open(array('id'=>'add_support','class'=>'add-coin-form-cnt','url'=>'add_support','method'=>'POST', 'enctype' => "multipart/form-data",'onsubmit'=>'support_load()')); ?>

							<div class="form-group">
								<label><?php echo e(trans('app_lang.select_issue')); ?></label>
								<select name="category" class="form-control" id="sel1">
									<option value=''>Select</option>
									<?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option id="<?php echo e($item->id); ?>"><?php echo e($item->category); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
								<label for="sel1" class="error" style="display: none">This field is required</label>
							</div>
							<div id="supporthide" style="display: none">
							<div class="form-group">
								<label><?php echo e(trans('app_lang.your_email')); ?></label>
								<input type="text" name="email" class="form-control">
								<label for="email" class="error" style="display: none">This field is required</label>
							</div>
							<div class="form-group">
								<label><?php echo e(trans('app_lang.subject')); ?></label>
								<input type="text" id="subject" name="subject" class="form-control" placeholder="<?php echo e(trans('app_lang.subject')); ?>">
								<label for="subject" class="error" style="display: none">This field is required</label>
							</div>
							<div class="form-group">
								<label><?php echo e(trans('app_lang.message')); ?></label>
								<textarea name="description" class="form-control" rows="5" id="comment"></textarea>
								<label for="description" class="error" style="display: none">This field is required</label>

								<?php echo cms_lang('content','0',session('language'),41);?>
							</div>
							<div class="form-group">
								<label><?php echo e(trans('app_lang.attachment')); ?></label>
								<div id="upload-dropzone" class="upload-dropzone">
									  <input type="file" multiple="true" id="request-attachments" name="file" data-fileupload="true" data-dropzone="upload-dropzone" data-error="upload-error" data-create-url="/hc/en-us/request_uploads" data-name="request[attachments][]" data-pool="request-attachments-pool" data-delete-confirm-msg="">
									  <span>
									    <a><?php echo e(trans('app_lang.add_file')); ?></a> <?php echo e(trans('app_lang.drop_file')); ?>

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
							<button class="support_create"><?php echo e(trans('app_lang.submit')); ?></button>
							
						</div>
						<?php echo Form::close(); ?>

						</div>

				<div class="row">


				</div>
			</div>
			
	

	
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  
</div>

<script>

  var require_field_sup   = "<?php echo e(trans('app_lang.field_require')); ?>";
  var only_files_sup      = "<?php echo e(trans('app_lang.only_files')); ?>";
  var upload_img          = "<?php echo e(trans('app_lang.upload_image')); ?>";
  var ticket_updated      = "<?php echo e(trans('app_lang.ticket_updated_success')); ?>";
  var ticket_closed       = "<?php echo e(trans('app_lang.ticket_closed_success')); ?>";
  var user_id             = "<?php echo e(Session::get('tmaitb_user_id')); ?>"
  var support        	  = "<?php echo e(trans('app_lang.create_ticket_now')); ?>";
</script>
