<style type="text/css">
.fund_dep_left_new .form-group .enbtn {
	background-color: #659aea;
	color: #ffffff;
	cursor: pointer;
	font-weight: 600;
	height: 36px;
	padding: 0 16px;
	font-size: 14px;
	width: 396px;
}
.enbtn {
	display: inline-block;
	font-weight: 400;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	border: 1px solid transparent;
 	padding: .375rem .75rem;
	font-size: 1rem;
	line-height: 1.5;
 	border-radius: .25rem;
	transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
[type=reset], [type=submit], button, html [type=button] {
 -webkit-appearance: button;
}
</style>
<div class="tab-pane container no-padding active min-height-cnt fund_page" id="funds">
  <div class="funds-main-box">
	  <div class="col-xs-12 col-sm-12 breadcrumb fund_blk">
		<p class="head">{{trans('app_lang.funds') }} </p>
		<div class="col-xs-10 col-md-10 col-lg-10 text-right dsb-funds-header-section">
		  <div class="card-div-cnt">
			<ul class="nav nav-tabs">
			  <li class="nav-item"> <a class="nav-link active linktab" data-toggle="tab" data-target="#funds-wallet-balance" href="javascript:;" id="walbalance">{{trans('app_lang.wallet_balance') }}</a> </li>
			  <li class="nav-item"> <a class="nav-link linktab" data-toggle="tab" data-target="#funds-history" id="funds-historyls" href="javascript:;">{{trans('app_lang.crypto_history') }}</a> </li>
			  <li class="nav-item"> <a class="nav-link linktab" data-toggle="tab" data-target="#fiat-funds-history" id="fiat-funds-historyls" href="javascript:;">{{trans('app_lang.fiathistory') }}</a> </li>

			  <li class="nav-item">
			  	<a class="nav-link linktab" data-toggle="tab" data-target="#exchange-bs-history" id="exchange-bs-historyls" href="javascript:;">
			  		Exchange History
				</a>
				</li>


			</ul>
		  </div>
		</div>
	  </div>
	  <div class="row">
		<div class="col-xs-12 col-md-12 col-lg-12 card-div dsb-funds-container-section">
		  <div class="tab-content">
			<div class="tab-pane container-fluid no-padding active linktabcontent" id="funds-wallet-balance">
			  <div class="col-xs-12 col-sm-12 col-md-12 no-padding funds_new">
				<div class="card-div-cnt">
				  <div class="dsb-wallet-table">
					<div class="dsb-wallet-search-cnt"> <i class="fa fa-search" aria-hidden="true"></i>
					  
					  <input type="search" id="funds_tbl_search" class="table-search funds_tbl_search" placeholder="{{trans('app_lang.coin_symbol') }}">
					</div>
					<?php $tychid = session::get('tmaitb_user_id');?>
					<div class="estimate_blk">
					  <div data-v-078df311=""> <i class="fa fa-eye" aria-hidden="true"></i> <span data-v-078df311="">{{trans('app_lang.Estimated_Value') }}</span> <span class="color-primary"><b class="tw-b">≈
	  
	  
						{{number_format($estimatebtc, 8, '.', ',')}} BTC</b> /{{number_format($estimateinr, 2, '.', ',')}} €</span></div>
					</div>
					<div class="table-responsive">
					  <table class="table table-hover table-borderless fund_table">
						<thead>
						  <tr>
							<th>{{trans('app_lang.coin_symbol') }}</th>
							<th>{{trans('app_lang.coin_name') }}</th>
							<th class="text-right">{{trans('app_lang.total_balance') }} </th>
							<th class="text-right">{{trans('app_lang.available_balance') }} </th>
							<th class="text-right">{{trans('app_lang.inorder') }}</th>
							<th class="text-right">{{trans('app_lang.value') }} BTC/EUR</th>
							<th class="text-right">Operation</th>
						  </tr>
						</thead>
						<div class="header-grid" style="display:none;">
						  <ul>
							<li style="width: 124px;">{{trans('app_lang.coin_symbol') }}</li>
							<li style="width: 140px;">{{trans('app_lang.coin_name') }}</li>
							<li class="text-right" style="width: 120px;">{{trans('app_lang.total_balance') }}
							  <div class="form-group"> <span>Balance ></span>
								<input type="text" name="">
							  </div>
							</li>
							<li class="text-right" style="width: 139px;">{{trans('app_lang.available_balance') }}
							  <div class="form-group"> <span>Available ></span>
								<input type="text" name="">
							  </div>
							</li>
							<li class="text-right" style="width: 120px;">{{trans('app_lang.inorder') }}
							  <div class="form-group"> <span>InOrde ></span>
								<input type="text" name="">
							  </div>
							</li>
							<li class="text-right" style="width:240px">Value BTC/EUR
							  <div class="form-group" style="left: unset; right: 0;"> <span>Value ></span>
								<input type="text" name="">
							  </div>
							</li>
							<li class="text-right" style="width:245px;">Operation</li>
						  </ul>
						</div>
						<tbody id="funds_tbl" class="funds_tbl">
						  <?php  $i=1; ?>
						@foreach($allcurr as $curr)
						<?php if($curr['symbol']=="EUR" || $curr['symbol']=="USD" || $curr['symbol']=="GBP"){
								$numfm = 2 ;
							}else{
								$numfm = 8 ;
							}
								?>
						<tr class="div_sear">
						  <td class="sear_curname">
						  	<span class="dsb-cc-icons">
						  		<img style="width: 16px; height: 16px; margin-right: 5px;" src="{{asset('/').('public/images/admin_currency/').$curr['image']}}">{{$curr['symbol']}}
						  </span>
						  </td>
						  <td class="bit_blk">{{$curr['name']}}</td>
						  <td class="text-right"><?php echo number_format($curr['total'], $numfm, '.', ',')?></td>
						  <td class="text-right"><?php echo number_format($curr['balance'], $numfm, '.', ',')?></td>
						  <td class="text-right"><?php echo number_format($curr['inorders'], $numfm, '.', ',')?></td>
						  <td class="text-right"><?php echo number_format($curr['btctotal'], $numfm, '.', ',')?>/
						  	€<?php echo number_format($curr['inrtotal'], 2, '.', ',')?></td>
						  <td class="text-right"><?php $symbol = $curr['symbol'];  $type = $curr['type']; ?>
							<span class="fund_sign" href="javascript:;"> <img src="{{asset('/').('public/assets/images/')}}img-222.png">
							<ul class="fund_coins">
							  <?php $viewpairs=showpair($symbol);?>
							  @foreach($viewpairs as $pair)
							  @if($pair->status == '1')
							  <li><a href="{{url('/trade/')}}/{{$pair->to_symbol}}_{{$pair->from_symbol}}">{{$pair->to_symbol}}/{{$pair->from_symbol}}</a></li>
							  @endif
							  @endforeach
							</ul>
							</span>
							<div class="dep_with_click"> <a href="javascript:;" class="orange-bordered-btn verifykyc{{$i}}" onclick="deposit({{$i}},'{{$symbol}}','{{$type}}')">{{trans('app_lang.deposit') }}</a> <a href="javascript:;" class="green-bordered-btn verifynew{{$i}}" onclick="withdraw({{$i}},'{{$symbol}}','{{$type}}')">{{trans('app_lang.Withdraw') }}</a> </div></td>
						<tr class="tr-expand{{$i}}" style="display: none;">
						  <td class="cryptodepositview" colspan="8" id="cryptodepositview{{$i}}"><div class="down-recharge stdep{{$symbol}}">
							  <div class="fund_dep_left">
								<label><span id="cyname{{$i}}">{{$symbol}} </span> {{trans('app_lang.deposit') }} {{trans('app_lang.address') }}</label>
								<div class="fund_text">
								  <input type="text" name="ss" placeholder="" class="form-control addressshow{{$symbol}}" id="copyAddr{{$symbol}}" disabled>
								  <button onclick="copyAddress('#copyAddr{{$symbol}}')" class="copy_addr">{{trans('app_lang.copy_address') }}</button>
								  <button class="fund_qr_img"><i class="fa fa-qrcode" aria-hidden="true"></i>
								  <div class="qr_code_img"> <img src="{{asset('/').('public/assets/images/')}}index.png" class="addressshow_url{{$symbol}}"> </div>
								  </button>
								</div>
							  </div>
							  <div class="fund_dep_ryt"> 
							  	<?php echo getStaticContent('Deposit_'.$symbol)->content; ?>
							  	<?php //echo getcurrencynotice('content','0',session('language'),$symbol);?>
								<ul class="transfer-in_text text-left ts-12 color--grey">
								  
								  <li data-v-2d8774f4="" class="item">
								  	<?php
								  		echo getStaticContent('Deposit_SMALLL_CONTENT')->content;
								  	?>
								  	<!-- After deposited, you can track process on the history page. -->
								  	<a href="javascript:;" class="a-theme" onclick="viewtab('{{$type}}');">Deposits History</a>
								</li>

								  
								</ul>
							  </div>
							</div>
							<div class="down-recharge stdepmaintance{{$symbol}}" style="display:none;">
							  <ul class="transfer-in_text text-left ts-12 color--grey">
								
								<li data-v-2d8774f4="" class="item depmaintance{{$symbol}}"></li>
							  </ul>
							</div></td>
						  <td class="" colspan="8" id="fiatdepositview{{$i}}" style="display:none;"><div class="stdep{{$symbol}}">
							  <form  id="fiatdeposit_form{{$symbol}}" method="POST" action="fiatdeposit" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="down-recharge">
								<div class="fund_dep_left_new">
								  <div class="form-group">
									<label>{{trans('app_lang.select_payment') }} :</label>
									<select name="payment" class="form-control">
									  <option value="bankwire">{{trans('app_lang.bankwire') }}</option>
									</select>
									<input type="hidden" id="currencyname{{$i}}" name="currency" value="{{$symbol}}">
								  </div>
								  <?php if(isset($adminbank->bankname)){ ?>
								  <div class="form-group">
									<label>{{trans('app_lang.select_account') }} :</label>
									<select name="account" id="account" class="form-control ">
									  <option value="{{$adminbank->id}}">{{$adminbank->bankname}}</option>
									</select>
									<input type="hidden" id="adbid" name="adbid" value="{{$adminbank->id}}">
								  </div>
								<?php } ?>
								  <div class="form-group">
									<label>{{trans('app_lang.deposit_amount') }} :</label>
									<input type="text" id="depositamount" name="depositamount" class="form-control">
								  </div>
								  <div class="form-group">
									<label>{{trans('app_lang.trans_id') }} :</label>
									<input type="text" id="ref_no{{$i}}" name="ref_no" class="form-control">
								  </div>
								  <div class="form-group file_upload">
									<label>{{trans('app_lang.upload_proof') }} :</label>
									<label  for="file" class="files_label"> {{trans('app_lang.upload_image') }}</label>
									<input type="file" class="custom-file-upload new_Btn"  id="file" name="file" onchange="showimage_edit(this,'ref_proof')" required/>
									<img src="" class="ref_proof hide" height="100" width="100"/> </div>
								  <div class="form-group">
									<label></label>
									<button id="validate" type="submit" class="enbtn wit_depbtn{{$symbol}}" onclick="newfiatdeposit('{{$symbol}}','{{$i}}')">Submit</button>
									<img src="{{asset('/').('public/assets/images/fundsloader.gif')}}" class="btn_deplow{{$symbol}}" style="display: none;"> </div>
								</div>
								<div class="fund_dep_ryt">
								<ul class="transfer-in_text text-left ts-12 color--grey">
								
									<a onclick="viewtab('{{$type}}');" href="javascript:;">Deposit</a>

									<?php echo getStaticContent('Deposit_'.$symbol)->content; ?>

								  
								
								<?php /* <h6>{{trans('app_lang.admin_bank') }}:</h6>


								<ul class="transfer-in_text text-left ts-12 color--grey">
								  <li class="item"> {{trans('app_lang.accountname') }}<span class="colon">:</span><span class="value"><span class="admin_accountname1"></span></span></li>
								  <li class="item">{{trans('app_lang.accountnumber') }}<span class="colon">:</span><span class="value"><span class="admin_accno1"></span></span></li>
								  <li class="item">{{trans('app_lang.bankname') }}<span class="colon">:</span><span class="value"><span class="adminbankname1"></span></span></li>
								  <li class="item">{{trans('app_lang.swift') }}<span class="colon">:</span><span class="value"><span class="swift1"></span></span></li>
								  <li class="item">{{trans('app_lang.bankaddress') }}<span class="colon">:</span><span class="value"><span class="adminbankaddr1"></span></span></li>
								</ul>
								<h6>Notice:</h6>
								<?php echo getcurrencynotice('content','0',session('language'),$symbol);
								*/ ?>
								
								</div>
								</div>
							  </form>
							</div>
							<div class="down-recharge stdepmaintance{{$symbol}}" style="display:none;">
							  <ul class="transfer-in_text text-left ts-12 color--grey">
								
								<li data-v-2d8774f4="" class="item depmaintance{{$symbol}}"></li>
							  </ul>
							</div></td>
						</tr>
						<tr class="tr-expand_new{{$i}}" style="display: none;">

						  
	  
						  <td id="cryptowithdrawview{{$i}}" class="" colspan="8" style="display:none;"><div class="stwith{{$symbol}}">
							  <form  id="withdraw_form{{$symbol}}" method="POST" action="withdraw">
								<input type="hidden" value="" name="mincrypto{{$i}}" id="mincrypto{{$i}}" >
								<input type="hidden" value="" name="maxcrypto{{$i}}" id="maxcrypto{{$i}}" >
								{{ csrf_field() }}
								<div class="down-recharge">
								  <div class="fund_dep_left_new">
									<div class="form-group">
									  <label ><span id="curwith{{$i}}">BTC</span> {{trans('app_lang.Withdrawal_Address') }}</label>
									  <div class="blk_one">
										<input type="hidden" id="withdrawname{{$i}}" name="withdrawname" value="{{$symbol}}">
										<input type="text" name="address" id="curwithname{{$i}}"  placeholder="Enter the BTC withdrawal address" class="form-control amount" >
									  </div>
									</div>
									<div class="form-group">
									  <label>{{trans('app_lang.remark') }}</label>
									  <div class="blk_one">
										<input type="text" name="remark" placeholder="Enter the remark" class="form-control">
										<input type="hidden" value="" name="feetype{{$i}}" id="feetype{{$i}}" >
									  </div>
									</div>
									<div class="form-group">
									  <label>{{trans('app_lang.withdraw_amount') }}</label>
									  <div class="blk_one">
										<input type="text" name="amount" id="curplaceholder{{$i}}" placeholder="The maximum withdrawal amount 0.00000000" class="form-control amount{{$i}}" onkeyup="calamount('{{$i}}')" onkeypress="return isNumberKey(event)" onchange="validateFloatKeyPresscrypto(this);">
										<div class="blk_two"> </div>
									  </div>
									</div>
									<div class="form-group">
									  <label>{{trans('app_lang.enter_2fa') }}</label>
									  <div class="blk_one">
										<input type="text" placeholder="{{trans('app_lang.tfa_small') }}" class="form-control"  name="tfa" id="tfa">
									  </div>
									</div>
									<div class="form-group">
									  <label></label>
									  <div class="text-center blk_one"> <span>Transaction Fee (<span class="fee_per{{$i}}"></span><span class="fee_perinc{{$i}}"></span>)：<span class="withfee{{$i}}"></span></span> <span>Actual Amount：<span class="total{{$i}}">0</span></span> </div>
									</div>
									<div class="form-group">
									  <label></label>
									  <button id="validate{{$symbol}}" type="submit" class="enbtn wit_btn{{$symbol}}" onclick="newval('{{$symbol}}','{{$i}}')">Submit</button>
									  <img src="{{asset('/').('public/assets/images/fundsloader.gif')}}" class="btn_low{{$symbol}}" style="display: none;"> </div>
								  </div>
								  <div class="fund_dep_ryt">
									<ul class="transfer-in_text text-left ts-12 color--grey">
									  
									  <li>{{trans('app_lang.Minimum_withdrawal') }}:<span class="min_amt{{$i}}">0.005</span></li>
									  <li data-v-2d8774f4="" class="item">
										  
										  <?php echo getStaticContent('WITHDRAW_SMALLL_CONTENT')->content; ?>	
										  	<a href="javascript:;" onclick="viewwithdrawtab('{{$type}}')">Withdrawals</a> 
										  
									  </li>
									</ul>
								  </div>
								</div>
							  </form>
							</div>
							<div class="down-recharge stwithmaintance{{$symbol}}" style="display:none;">
							  <ul class="transfer-in_text text-left ts-12 color--grey">
								
								<li data-v-2d8774f4="" class="item withmaintance{{$symbol}}"></li>
							  </ul>
							</div>
						 </td>
						  <td id="fiatwithdrawview{{$i}}" class="" colspan="8" style="display:none;"><div class="stwith{{$symbol}}">
							  <form  id="fiatwithdraw_form{{$symbol}}" method="POST" action="fiatwithdraw">
								{{ csrf_field() }}
								<input type="hidden" value="" name="minfiat{{$i}}" id="minfiat{{$i}}" >
								<input type="hidden" value="" name="maxfiat{{$i}}" id="maxfiat{{$i}}" >
								<?php if(!empty($userbank) && isset($bankDetailsByFiat[$symbol]))
						  {
	  
							  
							  ?>
								<div class="down-recharge">
								  <div class="fund_dep_left_new">
									<div class="form-group">
									  <label>{{trans('app_lang.withdraw_bank') }} :</label>
									  <select class="form-control" name="withbank" id="withbank" >
										<option value="<?php 
										 echo isset($bankDetailsByFiat[$symbol]['id'])?$bankDetailsByFiat[$symbol]['id']:'';
									 ?>">
										<?php 
										 echo isset($bankDetailsByFiat[$symbol]['bankname'])?$bankDetailsByFiat[$symbol]['bankname']:'';
									 ?>
										</option>
									  </select>
									  <input type="hidden" id="withdrawname{{$i}}" name="fiatcurrency" value="{{$symbol}}">
									</div>
									<div class="form-group">
									  <label>{{trans('app_lang.withdraw_amount') }}</label>
									  <div class="blk_one">
										<input type="text" name="fiatamount" id="curfiatplaceholder{{$i}}" placeholder="The maximum withdrawal amount 0.00000000" class="form-control amount{{$i}}" onkeyup="calamountfiat('{{$i}}')" onkeypress="return isNumberKey(event)" onchange="validateFloatKeyPress(this);">
										<div class="blk_two"> </div>
									  </div>
									  <input type="hidden" value="" name="feetype{{$i}}" id="feetype{{$i}}" >
									</div>
									<div class="form-group">
									  <label>{{trans('app_lang.enter_2fa') }}</label>
									  <div class="blk_one">
										<input type="text" placeholder="{{trans('app_lang.tfa_small') }}" class="form-control"  name="tfa" id="tfa">
									  </div>
									</div>
									<div class="form-group">
									  <label></label>
									  <div class="text-center blk_one"> <span>Transaction Fee (<span class="fee_per{{$i}}"></span><span class="fee_perinc{{$i}}"></span>)：<span class="withfee{{$i}}"></span></span> <span>Actual Amount：<span class="total{{$i}}">0</span></span> </div>
									</div>
									<div class="form-group">
									  <label></label>
									  <button id="validate" type="submit" class="enbtn wit_btn{{$symbol}}" onclick="newfiatwithdraw('{{$symbol}}','{{$i}}')">Submit</button>
									  <img src="{{asset('/').('public/assets/images/fundsloader.gif')}}" class="btn_low{{$symbol}}" style="display: none;"> </div>
								  </div>
								  <div class="fund_dep_ryt">
									<ul class="transfer-in_text text-left ts-12 color--grey">
									  
									  <li>{{trans('app_lang.Minimum_withdrawal') }}:<span class="min_amt{{$i}}">0.005</span></li>
									  <li data-v-2d8774f4="" class="item">{{trans('app_lang.you_can') }} <a href="javascript:;" onclick="viewwithdrawtab('{{$type}}')">Withdrawals</a> {{trans('app_lang.history_view') }}</li>
									</ul>
								  </div>
								</div>
								<?php } else  { ?>
								<div class="form-group">
								  <label>Please add your bankwire details</label>
								  <a href="{{url('/bankwire/USD')}}">
								  <button id="validate" type="button" class="enbtn wit_btn">{{trans('app_lang.bankwire') }}</button>
								  </a> </div>
								<?php } ?>
							  </form>
							</div>
							<div class="down-recharge stwithmaintance{{$symbol}}" style="display:none;">
							  <ul class="transfer-in_text text-left ts-12 color--grey">
								
								<li data-v-2d8774f4="" class="item withmaintance{{$symbol}}"></li>
							  </ul>
							</div>
						</td>
			
						</tr>
						  </tr>
	  
						<?php  $i++; ?>
						@endforeach
						  </tbody>
	  
					  </table>
					  <li class="no_recorder" style="display: none"> {{trans('app_lang.no_records_found') }} </li>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane container-fluid no-padding fade linktabcontent" id="funds-deposit">
			  <div class="row mb-3">
				<div class="col-xs-12 col-sm-12 col-md-4 xl-p-r lg-p-r md-p-r d-flex align-items-stretch">
				  <div class="card-div-cnt fund-left-cnt">
					<div class="deposit-heading"> </div>
					<div class="deposit-dropdown-cnt">
					  <div class="dropdown coin-dd">
						<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="coin-dd-ico pr-2"><img class="deposit_small_coin_image" src=""></span><span class="coin-dd-val deposit_coin_symbol"></span> <span class="caret"></span> </button>
						<ul class="dropdown-menu coin-dd-menu" role="menu" aria-labelledby="dropdownMenu1" data-filter >
						  @foreach($allcurr as $curr)
						  <?php $src = getCurrencyImage($curr['symbol']) ;?>
						  <li role="presentation" onclick="trig_deposit('<?php echo $curr['symbol'] ;?>','<?php echo $curr['type'] ;?>');return false;"><a role="menuitem" tabindex="-1" href="javascript:;" class="coin-dd-link"><span class="coin-dd-inn-ico pr-2"><img src="{{asset('/').('public/images/admin_currency/').$src}}"></span>{{$curr['symbol']}}</a></li>
						  @endforeach
						</ul>
					  </div>
					</div>
					<div class="deposit-currency-info-cnt">
					  <div class="deposit-currency-info-top">
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.total_balance') }}</span><span class="colon">:</span><span class="value"><span class="deposit_total_balance"></span> <span class="deposit_currency_name"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.available_balance') }}</span><span class="colon">:</span><span class="value"><span class="deposit_avail_balance"></span> <span class="deposit_currency_name"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.in_order') }}</span><span class="colon">:</span><span class="value"><span class="deposit_order_balance"></span> <span class="deposit_currency_name"></span></span></div>
					  </div>
					</div>
					<div class="deposit-currency-info-cnt" id="adminbank" style="display:none;">
					  <label><b>{{trans('app_lang.admin_bank') }}</b></label>
					  <div class="deposit-currency-info-top">
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.accountname') }}</span><span class="colon">:</span><span class="value"><span class="admin_accountname"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.accountnumber') }}</span><span class="colon">:</span><span class="value"><span class="admin_accno"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.bankname') }}</span><span class="colon">:</span><span class="value"><span class="adminbankname"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.swift') }}</span><span class="colon">:</span><span class="value"><span class="swift"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.bankaddress') }}</span><span class="colon">:</span><span class="value"><span class="adminbankaddr"></span></span></div>
					  </div>
					</div>
					<div class="deposit-currency-info-cnt" id="adminnotice" style="display:none;">
					  <label><b>{{trans('app_lang.important_notice') }}</b></label>
					  <div class="deposit-currency-info-top">
						<div class="deposit-currency-info-row"><span class="notice"></span></div>
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 xl-p-l lg-p-l md-p-l d-flex align-items-stretch">
				  <div class="card-div-cnt fund-deposit-right-cnt" id="crypdeposit">
					<div class="deposit_coin_name coin-name"></div>
					<div class="coin-cnt"><img class="deposit_coin_image" src=""></div>
					<div class="make_warning hide"> {!! Form::open(array('id'=>'alert_coin','url'=>'alert_coin','method'=>'POST','onsubmit'=>'deposit_load()')) !!}
					  <div class="alert_content"> </div>
					  <div class="add-coin-form-row">
						<div class="custom-controls-stacked d-block my-3">
						  <label class="custom-control fill-checkbox">
							<input type="checkbox" class="fill-control-input"
											   name="iagree_coin" id="iagree_coin" onchange="isChecked(this, 'continue_btn')"  required>
							<span class="fill-control-indicator"></span> <span class="alert_check fill-control-description"></span> </label>
						  <label for="iagree_coin" class="iagree_coin_er error hide">{{trans('app_lang.field_require') }}</label>
						</div>
					  </div>
					  <div class="col-sm-12 d-flex">
						<button type="submit" class="dsb-blue-btn continue_btn mx-auto deposit_create" id="continue_btn">{{trans('app_lang.continue') }}</button>
					  </div>
					  {!! Form::close() !!} </div>
					<div class="deposit_details">
					  <div class="qr-cnt"><img class ="address_url"></div>
					  <div class="col-sm-12 d-flex justify-content-center flex-row mb-2">
						<div class="pr-2 pt-2"><span class="address" id="copyAddr"> </span></div>
						<div class=""><a href="javascript:;" onclick="copyAddress('#copyAddr')" class="dsb-blue-btn copy_addr">{{trans('app_lang.copy_address') }}</a></div>
					  </div>
					  <div class="link-cnt xrp_tag hide"><span class="tag" id="tag"></span></div>
					  <div class="btn-cnt xrp_tag hide"><a href="javascript:;" onclick="copyAddress('#tag')" class="dsb-blue-btn copy_addr">{{trans('app_lang.copy_tag') }}</a></div>
					  <div class="notice-cnt">{{trans('app_lang.important_notice') }}</div>
					  <div class="notice-txt-cnt deposit_content"></div>
					</div>
					<div class="notice-txt-cnt deposit_main_content"> </div>
				  </div>
				  <div class="card-div-cnt fund-deposit-right-cnt" id="fiatdeposit" style="display:none;">
					<div class="deposit_coin_name coin-name"></div>
					<div class="coin-cnt"><img class="deposit_coin_image" src=""></div>
					{!! Form::open(array('id'=>'fiat_deposit','url'=>'fiatdeposit','method'=>'POST','enctype' => 'multipart/form-data','onsubmit'=>'fiatdeposit_load()')) !!}
					<?php if(isset($adminbank) && !empty($adminbank)) { ?>
					{{ csrf_field() }}
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.select_payment') }} :</label>
					  <select name="payment" class="form-control">
						<option value="bankwire">{{trans('app_lang.bankwire') }}</option>
					  </select>
					</div>
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.select_account') }} :</label>
					  <select name="account" id="account" class="form-control ">
						<option value="{{$adminbank->id}}">{{$adminbank->bankname}}</option>
					  </select>
					  <input type="hidden" id="adbid" name="adbid" value="{{$adminbank->id}}">
					</div>
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.select_currency') }} :</label>
					  <select name="currency" id="currency" class="form-control">
						
							  @foreach($fiatcurr as $fiat)
						<option value="{{$fiat->id}}">{{$fiat->symbol}}</option>
						
								@endforeach
					  </select>
					</div>
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.deposit_amount') }} :</label>
					  <input type="text" id="depositamount" name="depositamount" class="form-control">
					</div>
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.trans_id') }} :</label>
					  <input type="text" id="ref_no" name="ref_no" class="form-control">
					</div>
					<div class="col-sm-6 col-xs-12 cls_resp50">
					  <label>{{trans('app_lang.upload_proof') }} :</label>
					  <label  for="file" class="custom-file-upload new_Btn" > {{trans('app_lang.upload_image') }}</label>
					  <input type="file" id="file" name="file" onchange="showimage_edit(this,'ref_proof')" required/>
					  <img src="" class="ref_proof hide" height="100" width="100"/> </div>
					<br>
					<div>
					  <div class="btn-cnt d-flex">
						<button type="submit" class="dsb-blue-btn continue_btn mx-auto fiatdeposit_create">{{trans('app_lang.deposit') }}</button>
					  </div>
					</div>
					<?php } else { ?>
					<center>
					  <label>Admin not yet added Bankwire details.</label>
					</center>
					<?php } ?>
					{!! Form::close() !!} </div>
				</div>
			  </div>
			</div>
			<div class="tab-pane container-fluid no-padding fade linktabcontent" id="funds-withdraw">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 xl-p-r lg-p-r md-p-r d-flex align-items-stretch">
				  <div class="card-div-cnt fund-left-cnt">
					<div class="deposit-heading"></div>
					<div class="deposit-dropdown-cnt">
					  <div class="dropdown coin-dd">
						<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true"> <span class="coin-dd-ico pr-2"><img class="withdraw_small_coin_image" src=""></span><span class="coin-dd-val withdraw_coin_symbol"></span> <span class="caret"></span> </button>
						<ul class="dropdown-menu coin-dd-menu" role="menu" aria-labelledby="dropdownMenu1" data-filter >
						  @foreach($allcurr as $curr)
						  <?php $src = getCurrencyImage($curr['symbol']) ;?>
						  <li role="presentation" onclick="trig_withdraw('<?php echo $curr['symbol'] ;?>','<?php echo $curr['type'] ;?>');return false;" ><a role="menuitem" tabindex="-1" href="javascript:;" class="coin-dd-link"><span class="coin-dd-inn-ico pr-2"><img src="{{asset('/').('public/images/admin_currency/').$src}}"></span>{{$curr['symbol']}}</a></li>
						  @endforeach
						</ul>
					  </div>
					</div>
					<div class="deposit-currency-info-cnt withdraw-info-cnt">
					  <div class="deposit-currency-info-top withdraw-info-top">
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.total_balance') }}</span><span class="colon">:</span><span class="value"><span class="withdraw_total_balance"></span> <span class="withdraw_currency_name"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.available_balance') }}</span><span class="colon">:</span><span class="value"><span class="withdraw_avail_balance"></span> <span class="withdraw_currency_name"></span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.in_order') }}</span><span class="colon">:</span><span class="value"><span class="withdraw_order_balance"></span> <span class="withdraw_currency_name"></span></span></div>
					  </div>
					  <div class="deposit-currency-info-bottom">
						<div class="deposit-currency-info-heading withdraw-currency-info-heading">{{trans('app_lang.withdraw_limit') }}</div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.minimum_amount') }} </span><span class="colon">:</span><span class="value"><span class="min_amt"></span> <span class="currency withdraw_currency_name"> </span></span></div>
						<div class="deposit-currency-info-row"><span class="pretext">{{trans('app_lang.maximum_amount') }} </span><span class="colon">:</span><span class="value"><span class="max_amt"></span> <span class="currency withdraw_currency_name"> </span></span></div>
						<input type="hidden" value="" name="feetype" id="feetype" >
					  </div>
					</div>
				  </div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 xl-p-l lg-p-l md-p-l d-flex align-items-stretch">
				  <div class="card-div-cnt fund-deposit-right-cnt" id="cryptowithdraw" >
					<div class="withdraw_coin_name coin-name"></div>
					<div class="coin-cnt"><img class="withdraw_coin_image" src=""></div>
					{!! Form::open(array('id'=>'withdraw_form','url'=>'withdraw','method'=>'POST','class'=>'withdraw-form','onsubmit'=>'withdraw_load()')) !!}
					<div class="withdraw-form-cnt">
					  <div class="withdraw_form">
						<input type="hidden" name="currency" class="withdraw_currency_name">
						<label class="withdraw-form-label">{{trans('app_lang.withdraw_amount') }}<span style="color: red">*</span></label>
						<div class="withdraw-form-textbox-cnt">
						  <input type="text" name="amount" id="amount" placeholder="{{trans('app_lang.amount') }}" onkeypress="return isNumberKey(event)"  class="withdraw-form-label-textbox" required>
						  <span class="withdraw-box-label withdraw_currency_name">BTC</span> </div>
						<label class="withdraw-form-label">{{trans('app_lang.your') }} <span class="withdraw_currency_name"></span> {{trans('app_lang.address') }}<span style="color: red">*</span></label>
						<div class="withdraw-form-textbox-cnt">
						  <input type="text" placeholder="{{trans('app_lang.address') }}" name="address" id="address" class="withdraw-form-textbox"  required>
						</div>
						<label class="withdraw-form-label w-tag hide">{{trans('app_lang.your_tag') }}</label>
						<div class="withdraw-form-textbox-cnt w-tag hide">
						  <input type="text" placeholder="{{trans('app_lang.tag') }}" class="withdraw-form-textbox"  name="tag" id="tag" >
						</div>
						<label class="withdraw-form-label">{{trans('app_lang.enter_2fa') }}</label>
						<div class="withdraw-form-textbox-cnt">
						  <input type="text" placeholder="{{trans('app_lang.tfa_small') }}" class="withdraw-form-textbox"  name="tfa" id="tfa" >
						</div>
						<div class="withdraw-info-row">
						  <div class="withdraw-info-row-label">{{trans('app_lang.fees') }} (<span class="fee_per"></span><span class="fee_perinc"></span>) <span class="withfee"></span> </div>
						  <div class="withdraw-info-row-value "><span class="fee_amt"></span> <span class="withdraw_currency_name"></span></div>
						</div>
						<div class="withdraw-info-row">
						  <div class="withdraw-info-row-label">{{trans('app_lang.given_amount') }}</div>
						  <div class="withdraw-info-row-value"><span class="total"></span> <span class="withdraw_currency_name"></span></div>
						</div>
					  </div>
					  <div class="withdraw_main_content notice-txt-cnt"> </div>
					</div>
					<div class="withdraw_form">
					  <div class="btn-cnt d-flex">
						<button type="submit" class="dsb-blue-btn wit_btn mx-auto withdraw_create">{{trans('app_lang.withdraw_now') }}</button>
					  </div>
					  <div class="notice-cnt">{{trans('app_lang.important_notice') }}</div>
					  <div class="notice-txt-cnt withdraw_cont"></div>
					</div>
					{!! Form::close() !!} </div>
				  <div class="card-div-cnt fund-deposit-right-cnt" id="fiatwithdraw" style="display:none;">
					<div class="withdraw_coin_name coin-name"></div>
					<div class="coin-cnt"><img class="withdraw_coin_image" src=""></div>
					{!! Form::open(array('id'=>'fiat_withdraw','url'=>'fiatwithdraw','method'=>'POST','class'=>'withdraw-form','onsubmit'=>'fiatwithdraw_load()')) !!}
					<?php if(!empty($userbank)) { ?>
					<div class="withdraw-form-cnt">
					  <div class="withdraw_form">
						<div class="withdraw-form-textbox-cnt">
						  <label>{{trans('app_lang.withdraw_bank') }} :</label>
						  <select name="withbank" id="withbank" class="form-control">
							<option value="{{$userbank->id}}">{{$userbank->bankname}}</option>
						  </select>
						  <label>{{trans('app_lang.select_currency') }} :</label>
						  <select name="fiatcurrency" id="fiatcurrency" class="form-control">
							
								  @foreach($fiatcurr as $fiat)
							<option value="{{$fiat->id}}">{{$fiat->symbol}}</option>
							
									@endforeach
						  </select>
						</div>
						<label class="withdraw-form-label">{{trans('app_lang.withdraw_amount') }}<span style="color: red">*</span></label>
						<div class="withdraw-form-textbox-cnt">
						  <input type="text" name="fiatamount" id="fiatamount" placeholder="{{trans('app_lang.amount') }}" onkeypress="return isNumberKey(event)"  class="withdraw-form-label-textbox" required>
						  <span class="withdraw-box-label withdraw_currency_name">BTC</span> </div>
						<label class="withdraw-form-label w-tag hide">{{trans('app_lang.your_tag') }}</label>
						<div class="withdraw-form-textbox-cnt w-tag hide">
						  <input type="text" placeholder="{{trans('app_lang.tag') }}" class="withdraw-form-textbox"  name="tag" id="tag" >
						</div>
						<label class="withdraw-form-label">{{trans('app_lang.enter_2fa') }}</label>
						<div class="withdraw-form-textbox-cnt">
						  <input type="text" placeholder="{{trans('app_lang.tfa_small') }}" class="withdraw-form-textbox"  name="tfa" id="tfa" >
						</div>
						<div class="withdraw-info-row">
						  <div class="withdraw-info-row-label">{{trans('app_lang.fees') }} (<span class="fee_per"></span>%)</div>
						  <div class="withdraw-info-row-value "><span class="fee_amt"></span> <span class="withdraw_currency_name"></span></div>
						</div>
						<div class="withdraw-info-row">
						  <div class="withdraw-info-row-label">{{trans('app_lang.given_amount') }}</div>
						  <div class="withdraw-info-row-value"><span class="total"></span> <span class="withdraw_currency_name"></span></div>
						</div>
					  </div>
					  <div class="withdraw_main_content notice-txt-cnt"> </div>
					</div>
					<div class="withdraw_form">
					  <div class="btn-cnt d-flex">
						<button type="submit" class="dsb-blue-btn continue_btn mx-auto fiatwithdraw_create">{{trans('app_lang.withdraw_now') }}</button>
					  </div>
					  <div class="notice-cnt">{{trans('app_lang.important_notice') }}</div>
					  <div class="notice-txt-cnt withdraw_cont"></div>
					</div>
					{!! Form::close() !!}
					<?php } else  { ?>
					<div>
					  <center>
						<label>Please add your bankwire details</label>
					  </center>
					  <a href="{{url('/bankwire/USD')}}">
					  <div class="btn-cnt d-flex">
						<button type="button" class="dsb-blue-btn wit_btn mx-auto">{{trans('app_lang.bankwire') }}</button>
					  </div>
					  </a> </div>
					<?php } ?>
				  </div>
				</div>
			  </div>
			</div>
			<?php
	  
			  foreach($withdraw as $key=>$cont){ ?>
			<input type="hidden" id='{{$key}}content' value="{{$cont['content']}}"  />
			<input type="hidden" id='{{$key}}maintenance' value="{{$cont['maintenance']}}"  />
			<?php } ?>
			<div class="tab-pane container-fluid no-padding fade linktabcontent fund_histry" id="funds-history">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
				  <div class="card-div-cnt">
					<ul class="nav nav-tabs funds-history-tabs">
					  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" data-target="#deposit-history" id="crpdepo-history" href="javascript:;">{{trans('app_lang.deposit_history') }}</a> </li>
					  <li class="nav-item"> <a class="nav-link" data-toggle="tab" data-target="#Withdraw-history" id="crpwith-history" href="javascript:;">{{trans('app_lang.withdraw_history') }}</a> </li>
					</ul>
					<div class="tab-content">
					  <div class="tab-pane container-fluid no-padding active" id="deposit-history">
						<div class="dsb-transaction-table">
						  <div class="calendar-download-cnt">
							<div class="calendar-cnt">
							  <div class="calendar">
								<input type="text" id="from_date" placeholder="{{trans('app_lang.from_date') }}" class="calendar-field">
							  </div>
							  <div class="calendar">
								<input type="text" id="to_date" placeholder="{{trans('app_lang.to_date') }}" class="calendar-field">
							  </div>
							</div>
						  </div>
						  <div class="tab-content history-table-cnt">
							<div class="tab-pane container-fluid no-padding active" id="deposit-history">
							  <div class="col-sm-12 no-padding">
								<table class="table table-borderless" id="deposit_history_tbl">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>{{trans('app_lang.currency') }}</th>
									  <th>{{trans('app_lang.transaction_id') }}</th>
									  <th>{{trans('app_lang.amount') }}</th>
									  <th>{{trans('app_lang.address') }}</th>
									  <th>{{trans('app_lang.date_time') }}</th>
									  <th>Confirmation</th>
									  <th>{{trans('app_lang.status') }}</th>
									</tr>
								  </thead>
								</table>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="tab-pane container-fluid no-padding fade" id="Withdraw-history">
						<div class="dsb-transaction-table">
						  <div class="calendar-download-cnt">
							<div class="calendar-cnt">
							  <div class="calendar">
								<input type="text" id="from_date_withdraw" placeholder="{{trans('app_lang.from_date') }}" class="calendar-field">
							  </div>
							  <div class="calendar">
								<input type="text" id="to_date_withdraw" placeholder="{{trans('app_lang.to_date') }}" class="calendar-field">
							  </div>
							</div>
						  </div>
						  <div class="tab-content history-table-cnt">
							<div class="tab-pane container-fluid no-padding active" id="withdraw-history">
							  <div class="col-sm-12 no-padding">
								<table class="table table-borderless" id="withdraw_history_tbl">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>{{trans('app_lang.currency') }}</th>
									  <th>{{trans('app_lang.transaction_id') }}</th>
									  <th>{{trans('app_lang.amount') }}</th>
									  <th>{{trans('app_lang.fees') }}</th>
									  <th>{{trans('app_lang.address') }}</th>
									  <th>{{trans('app_lang.date_time') }}</th>
									  <th>{{trans('app_lang.status') }}</th>
									</tr>
								  </thead>
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
			</div>
			<div class="tab-pane container-fluid no-padding fade linktabcontent fund_histry" id="fiat-funds-history">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 no-padding">
				  <div class="card-div-cnt padd10">
					<ul class="nav nav-tabs funds-history-tabs">
					  <li class="nav-item"> <a class="nav-link active" data-toggle="tab" data-target="#fiat-deposit-history" id="fiatdepo-history" href="javascript:;">{{trans('app_lang.deposit_history') }}</a> </li>
					  <li class="nav-item"> <a class="nav-link" data-toggle="tab" data-target="#fiat-Withdraw-history" id="fiatwith-history" href="javascript:;">{{trans('app_lang.withdraw_history') }}</a> </li>
					</ul>
					<div class="tab-content">
					  <div class="tab-pane container-fluid no-padding active" id="fiat-deposit-history">
						<div class="dsb-transaction-table">
						  <div class="calendar-download-cnt">
							<div class="calendar-cnt">
							  <div class="calendar">
								<input type="text" id="from_fiat_date" placeholder="{{trans('app_lang.from_date') }}" class="calendar-field">
							  </div>
							  <div class="calendar">
								<input type="text" id="to_fiat_date" placeholder="{{trans('app_lang.to_date') }}" class="calendar-field">
							  </div>
							</div>
						  </div>
						  <div class="tab-content history-table-cnt">
							<div class="tab-pane container-fluid no-padding active" id="deposit-history">
							  <div class="col-sm-12 no-padding">
								<div class="table-responsive">
								  <table class="table table-borderless" id="fiat_deposit_history_tbl">
									<thead>
									  <tr>
										<th>#</th>
										<th>{{trans('app_lang.currency') }}</th>
										<th>{{trans('app_lang.transaction_id') }}</th>
										<th>{{trans('app_lang.amount') }}</th>
										<th id="proofatbale">{{trans('app_lang.proof') }}</th>
										<th>{{trans('app_lang.date_time') }}</th>
										<th>{{trans('app_lang.status') }}</th>
									  </tr>
									</thead>
								  </table>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="tab-pane container-fluid no-padding fade" id="fiat-Withdraw-history">
						<div class="dsb-transaction-table">
						  <div class="calendar-download-cnt">
							<div class="calendar-cnt">
							  <div class="calendar">
								<input type="text" id="from_date_fiat_withdraw" placeholder="{{trans('app_lang.from_date') }}" class="calendar-field">
							  </div>
							  <div class="calendar">
								<input type="text" id="to_date_fiat_withdraw" placeholder="{{trans('app_lang.to_date') }}" class="calendar-field">
							  </div>
							</div>
						  </div>
						  <div class="tab-content history-table-cnt">
							<div class="tab-pane container-fluid no-padding active" id="withdraw-history">
							  <div class="col-sm-12 no-padding">
								<div class="table-responsive">
								  <table class="table table-borderless" id="fiat_withdraw_history_tbl">
									<thead>
									  <tr>
										<th>#</th>
										<th>{{trans('app_lang.currency') }}</th>
										<th>{{trans('app_lang.transaction_id') }}</th>
										<th>{{trans('app_lang.amount') }}</th>
										<th>{{trans('app_lang.fees') }}</th>
										<th>{{trans('app_lang.transferamount') }}</th>
										<th>{{trans('app_lang.date_time') }}</th>
										<th>{{trans('app_lang.status') }}</th>
									  </tr>
									</thead>
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
			  </div>
			</div>

			<div class="tab-pane container-fluid no-padding fade linktabcontent fund_histry" id="exchange-bs-history">
			  <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 no-padding">
				  <div class="card-div-cnt padd10">
					<div class="tab-content">
					  <div class="tab-pane container-fluid no-padding active" id="exchange-bs-history-inner">
						<div class="dsb-transaction-table">

						  <div class="tab-content history-table-cnt">
							<div class="tab-pane container-fluid no-padding active" id="deposit-history">
							  <div class="col-sm-12 no-padding">
								<div class="table-responsive">
								  <table class="table table-borderless" id="fiat_deposit_history_tbl">
									<thead>
									  <tr>
										<th>#</th>
										<th>Date</th>
										<th>Type</th>
										<th>From</th>
										<th>To</th>
										<th>Price</th>
										<th>Fees</th>
										<th>Total</th>
										<th>Status</th>
									  </tr>
									</thead>
									<tbody class="funds_tbl">
										<?php
										foreach($dataRecords as $data)
										{
											?>
										
								  	<tr class="div_sear">
						  				<td class="sear_curname">{{$data[0]}}</td>
						  				<td class="sear_curname">{{$data[1]}}
						  				</td>
						  				<td class="sear_curname">{{$data[2]}}
						  				</td>
						  				<td class="sear_curname">{{$data[3]}}
						  				</td>
						  				<td class="sear_curname">{{$data[4]}}
						  				</td>

						  				<td class="sear_curname">{{$data[5]}}
						  				</td>

						  				<td class="sear_curname">{{$data[6]}}
						  				</td>
						  				<td class="sear_curname">{{$data[7]}}
						  				</td>

						  				<td class="sear_curname">
						  				<?php 
						  				if($data[8] == 'Completed') {
											$completed = URL::to('public/assets/tick.png');
											$status = '<img src="'.$completed.'" alt="Completed" title="Completed"> Completed';
										} else if($data[8] == 'pending') {
											$pending = URL::to('public/assets/pending.png');
											$status = '<img src="'.$pending.'" alt="Pending" title="Pending"> Pending';
										} else {
											$cancel = URL::to('public/assets/cancel.png');
											$status = '<img src="'.$cancel.'" alt="Cancelled" title="Cancelled"> Cancelled';
										}
										echo $status;
						  				 ?>
						  				</td>

								  	</tr>
								  <?php }
								   ?>
								  </tbody>
								  </table>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="tab-pane container-fluid no-padding fade" id="fiat-Withdraw-history">
						<div class="dsb-transaction-table">
						 
						  
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>

		  </div>
		</div>
	  </div>
  </div>

</div>
<script>
 if(window.location.search!='')
 {

  var type  ="<?php if(isset($_GET['type'])){echo $_GET['type'];}?>";
  var name  ="<?php if(isset($_GET['name'])){echo $_GET['name'];}?>";
  var coin  ="<?php if(isset($_GET['currency'])){echo $_GET['currency'];}?>";
 }

  var require_field_fun  ="{{trans('app_lang.field_require') }}";
  var check_bal_fun      ="{{trans('app_lang.check_balance_error') }}";
  var grater_min_amo     ="{{trans('app_lang.enter_greate_min_amount') }}";
  var enter_10_char      ="{{trans('app_lang.enter_10_char') }}";
  var address            ="{{trans('app_lang.address_copy') }}";
  var empty_text         ="{{trans('app_lang.no_records_available') }}";
  var error_try          ="{{trans('app_lang.error_try_again') }}";
  var select_from_date   ="{{trans('app_lang.select_date_less_equalt') }}";
  var wit_cancel_success ="{{trans('app_lang.withdraw_cancel_success_lng') }}";
  var pls_try            ="{{trans('app_lang.please_try_again') }}";
  var resend_email       ="{{trans('app_lang.resend_email') }}";
  var wit_confm_success  ="{{trans('app_lang.withdraw_confm_success') }}";
  var search             ="{{trans('app_lang.search') }}";
  var minvalue           = "";
  var min_coin ;
  var deposit       	 = "{{trans('app_lang.deposit') }}";
  var withdraw 			 = "{{trans('app_lang.withdraw_now') }}";
</script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script>

  	function deposit(id,coin,type){
  		
  		$(".tr-expand"+id).toggle();
  		$('#cyname'+id).text(coin);	
  		trig_deposit(coin,type,id);
        $(".tr-expand_new"+id).hide();
  	}
    

</script> 
<script>

function withdraw(id,coin,type){
        $(".tr-expand_new"+id).toggle();
  		$('#curwith'+id).text(coin);	
  		$('#withdrawname'+id).val(coin);	
  		 $('#curwithname'+id).attr("placeholder", "Enter the "+coin+" withdrawal address");
        $(".tr-expand"+id).hide();
        trig_withdraw(coin,type,id);

  	}
</script> 
<script>
$(document).ready(function(){
  $(".verifykyc").click(function(){
    $(".tr-expand_new").hide();
  });
  $(".verifynew").click(function(){
    $(".tr-expand").hide();
  });
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
	 if($(window).width() > 1100){
  $(".header-grid").sticky({ topSpacing: 0 });
}
});
</script>

<style type="text/css">
#fiat_deposit_history_tbl tr td img
{
    display: none;
}
#proofatbale{
	display: none;
}
table#fiat_deposit_history_tbl td:nth-child(5) {
    display: none;
}
</style>