<div class="tab-pane container-fluid no-padding active" id="addcoin">
				
				{!! Form::open(array('id'=>'addcoins','url'=>'addCoins','class'=>'add-coin-form-cnt','method'=>'POST', 'enctype' => "multipart/form-data")) !!}
				<div class="container coins-page-box">
					<div class="add-coin-cnt padd-top-20">
						<div class="add-coin-container bank-page-box">
							<div class="add-coin-header text-left">{{trans('app_lang.add_new_coin') }}</div>
							<div class="add-coin-header-label text-left">{{trans('app_lang.please_provide_details_all_information') }}</div>
								<?php
								if ($coin_info->new_coin_fee_status == 1) {?>
								<div class="add-coin-form-row">
									<span class="fill-control-description" style="color: red"><b>{{trans('app_lang.note') }}*</b> {{trans('app_lang.you_must') }} <b><?php echo $coin_info->new_coin_fee ;?> BTC</b> {{trans('app_lang.new_coin') }}</span>
								</div>
								<?php } ?>
								<div class="add-coin-form-row">
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">{{trans('app_lang.coin_full_name') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text" name="coin_name" placeholder="{{trans('app_lang.coin_full_name') }}"></div>
									</div>	
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">{{trans('app_lang.coin_ticker_symbol') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text"  name="coin_symbol" placeholder="{{trans('app_lang.coin_ticker_symbol') }}"></div>
									</div>				
								</div>
								<div class="add-coin-form-row">
									<div class="add-coin-txtbox-label"><span>{{trans('app_lang.coin_logo') }}<a style="color: red">*</a></span>
										<span class="tltp-span">
											<a href="javascript:;" class="tooltip-icon" data-original-title="Upload coin logo"><img src="{{asset('/').('public/assets/images/')}}tooltip-question-icon.png"></a>
											<span class="tltp-cnt"><?php echo strip_tags(cms_lang('content','0',session('language'),13));?></span>
										</span>
									</div>
									<div class="new_Btn add-cn">{{trans('app_lang.upload_image') }}</div>
									<input class="html_btn" type="file" name="file" onchange="showimage_edit(this,'thumbnil_coin_logo')">
									<img src="" class="thumbnil_coin_logo hide mt-10" height="100" width="100"/>
								</div>
								<label for="file" class="error hide" id="thumbnil_coin_logo">{{trans('app_lang.field_require') }}</label>
								<div class="add-coin-txtbox-label">{{trans('app_lang.coin_type') }}</div>
								<div class="add-coin-form-row">
									<div class="add-coin-form-radio-cnt">
										<div class="add-coin-radio-row">
											<input type="radio" id="test1" name="coin_type" value="Bitcoin RPC Interface" checked>
											<label for="test1">{{trans('app_lang.btc_rpc') }}</label>
										</div>
										<div class="add-coin-radio-row">
											<input type="radio" value="ERC20 Token" id="test2" name="coin_type">
											<label for="test2">{{trans('app_lang.erc_token') }}</label>
										</div>
										<div class="add-coin-radio-row">
											<input type="radio" id="test3" name="coin_type" value="Monero RPC Interface">
											<label for="test3">{{trans('app_lang.monero_rpc') }}</label>
										</div>
										<div class="add-coin-radio-row">
											<input type="radio" id="test4" name="coin_type" value="Non-Monero Cryptonote">
											<label for="test4">{{trans('app_lang.nonmonero_rpc') }}</label>
										</div>
										<div class="add-coin-radio-row">
											<input type="radio" id="test5" name="coin_type" value="Other Token">
											<label for="test5">{{trans('app_lang.other_token') }}</label>
											
										</div>
									</div>
								</div>
								<div class="add-coin-form-row">
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">{{trans('app_lang.coin_website') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text" name="coin_website" placeholder="{{trans('app_lang.coin_website') }}"></div>
									</div>	
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">Bitcoin {{trans('app_lang.talk_announcement') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text" name="coin_chat" placeholder="Bitcoin {{trans('app_lang.talk_announcement') }}"></div>
									</div>				
								</div>
								<div class="add-coin-form-row">
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">{{trans('app_lang.github_link') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text" name="coin_git" placeholder="{{trans('app_lang.github_link') }}"></div>
									</div>	
									<div class="add-coin-form-txtbox-cnt">
										<div class="add-coin-txtbox-label">{{trans('app_lang.block_explorer_link') }}<a style="color: red">*<a></div>
										<div class="add-coin-txtbox"><input type="text" name="coin_explorer" placeholder="{{trans('app_lang.block_explorer_link') }}"></div>
									</div>				
								</div>
								<?php
								if ($coin_info->new_coin_fee_status == 1) {?>
								<div class="add-coin-form-row">
									<div class="custom-controls-stacked d-block my-3">
										<label class="custom-control fill-checkbox">
											<input type="checkbox" class="fill-control-input" name="iagree" id="iagree">
											<span class="fill-control-indicator"></span>
											<span class="fill-control-description">{{trans('app_lang.understand_coin_swaps') }} <span style="color: red">*</span></span>
										</label>
										<label for="iagree" class="error hide">{{trans('app_lang.field_require') }}</label>
									</div>
								</div>
								<?php } ?>
								
								
								<div class="add-coin-form-row">
								<div class="sm-12 d-flex">
									<button type="submit" class="dsb-blue-btn mx-auto">{{trans('app_lang.submit_new_coin') }}</button>
								</div>
								</div>
							
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">


      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Attention</h4>
           
        </div>
        <div class="modal-body">
          <p id="messages">
          	You have to Pay <?php echo $coin_info->new_coin_fee ;?> BTC to submit a new coin
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" style="background-color:red; color:#ffffff" data-dismiss="modal" data-value="" id="cancel" >Cancel</button>
          <button type="button" class="btn btn-default" style="background-color:green; color:#ffffff" data-dismiss="modal" data-value="" id="accept" >Accept</button>
        </div>
      </div>

    </div>
  </div>

<?php 
  if ($coin_info->new_coin_fee_status == 1) { ?>
    <script>
  	 var fees = 'yes';
    </script>
  <?php } else {?>
    <script>
    	var fees = 'no';
    </script>
  <?php } ?>
<script>
// Coins Validation
  var require_field_cos = "{{trans('app_lang.field_require') }}";
  var coin_min_cos      = "{{trans('app_lang.coin_min_3') }}";
  var coin_max_cos      = "{{trans('app_lang.coin_max_20') }}";
  var file_cos          = "{{trans('app_lang.only_files') }}";
  var upload_img        = "{{trans('app_lang.upload_image') }}";
  var valid_url        = "{{trans('app_lang.valid_url') }}";
 
</script>