<?php $support = getSite();?>
<?php
$i1 = app('request')->input('name');

if(isset($user->set_default_currency)){

	if($user->set_default_currency == 'EUR')
	{
		$currency ='€';	
	}
	elseif($user->set_default_currency == 'USD')
	{
		$currency ='$';	
	}
	else
	{
		$currency ='£';
	}	
}

?>


<div class="tab-pane container-fluid active no-padding" id="overview">
  <div class="inner_banner">
    <div class="inner-sec-top-menu">
      <div class="container">
        <ul class="inner-sec-menu">
          <li><a href="<?php echo url('/dashboard'); ?>" class="active" ><i class="fa fa-th-large" aria-hidden="true"></i> Dashboard</a></li>
          <li><a href="<?php echo url('/buy-sell'); ?>" ><i class="fa fa-arrows-h" aria-hidden="true"></i> Buy/Sell</a></li>
          <li><a href="<?php echo url('/bankwire/USD'); ?>"><i class="fa fa-folder" aria-hidden="true"></i> Bank</a></li>
          <li><a href="<?php echo url('/editprofile'); ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
          <li><a href="<?php echo url('/profile'); ?>" ><i class="fa fa-user" aria-hidden="true"></i>Profile</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="dash-buy-sell-sec dbsc">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 breadcrumb overview_tab"> {{trans('app_lang.overview') }}
          <ul class="nav nav-tabs settings-tab">
            <li class="nav-item"> <a class="nav-link navtpr <?php if ($i1 != 'verification') {echo 'active show';}?>" data-toggle="tab" data-target="#my-profile" href="javascript:;"> Your Portfolio </a> </li>
            <li class="nav-item"> <a class="nav-link navt" href="<?php echo url('referral'); ?>">{{trans('app_lang.invitation') }} →</a> </li>
          </ul>
        </div>
        <div class="tab-content">
          <div class="row tab-pane active" id="my-profile">
		  	 
		  		<div class="col-md-12 col-sm-12 col-lg-12">
				  	<div class="row">
						<div class="col-md-12 col-sm-6 col-lg-6">
							<!--- Price Chart -->
							<div class="card-div-cnt dash-top-l dt1">
								<h2>Price Charts</h2>
								<div class="row pt-40 pb-40">
								<div class="col-lg-12 col-12 api-docs-left">
									<div class="col-12 marginbottowIncrese">
									<div class="pricechartBalance">
										<h1 id="text_price_euro_total">0.00{{$currency}}</h1>
										<!-- <ul style="display: none;">
											<li><a href="">BTC</a></li>
											<li><a href="" class="active">ETH</a></li>
											<li><a href="">LTC</a></li>
										</ul> -->
									</div>
									<div class="pricechartBalanceleft">
										<?php $selected = isset($_GET['report'])?$_GET['report']:'1M'; ?>
										<a href="<?php echo url("dashboard/"); ?>?report=1D" class=""> Today </a> 
										<a href="<?php echo url("dashboard/"); ?>?report=1M" class=""> This Month </a> 
										<a href="<?php echo url("dashboard/"); ?>?report=1Y" class="lastaHrefTag"> This Year </a> 
									</div>
									</div>
									<br/>
                    			<script>
								window.onload = function()
								{

									var dataPoints = [];
									var text_price = 0.00;
									
									var chart = new CanvasJS.Chart("chartContainer", {
										animationEnabled: true,
										theme: "light2",
										zoomEnabled: true,
										title:
										{
											text: ""
										},
										axisY:
										{
											title: "",
											titleFontSize: 24,
											prefix: "{{$currency}}"
										},
										data: [{
											type: "line",
											yValueFormatString: "{{$currency}} #,##0.00",
											dataPoints: dataPoints
										}]
									});

									function addData(data)
									{
										var dps = data.price_usd;
										var tots = data.total;
										$("#text_price_euro_total").html(tots+"{{$currency}}");
										for (var i = 0; i < dps.length; i++)
										{
											dataPoints.push({
												x: new Date(dps[i][0]),
												y: dps[i][1]
											});
										}
										 setTimeout(function(){ chart.render(); }, 2000);
									}
									<?php
										$report = (isset($_GET['report']) && ($_GET['report'] == '1M' || $_GET['report'] == '1D' || $_GET['report'] == '1Y'))?$_GET['report']:'1M';
										$urlsss =  url("depositchart/").'?report='.$report; 
									?>
									var jsonURL = "<?php echo $urlsss; ?>";
									$.getJSON(jsonURL, addData);
								}
								
								</script>
								<div class="chartContainer-box"> <b></b>
								<div id="chartContainer" style="height: 220px; width: 100%;"><b></b></div>
								<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script> 
								<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script> 
								</div>
                  </div>
                </div>
              </div>
					  	</div>
						<div class="col-md-12 col-sm-6 col-lg-6">
						 <!--- Price Chart -->
              
						 <div class="card-div-cnt card-div dash-top-l dt2">
						 <h2>Your Portfolio</h2>
						 <h1 class="portbalanceH1 text-center">{{number_format($estimateinr,2)}}{{$currency}}</h1>
							<p class="font-weight-normal text-center">Total Balance</p>
                <div class="table-responsive col-md-12">
                  <table class="col-md-12">
                    <thead>
                      <tr>
                        <th class="color-grey ts-12 text-center" colspan="3"> 
							
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                    
                    @foreach($portFolioList as $curr)
                    <tr class="tr_border_here"> 
                      <td colspan="2"><img class="fixedWidthImg" src="{{url('public/images/admin_currency')}}/{{$curr['img']}}"/> {{ucfirst($curr['name'])}} </td>
                      <td  class="tdcols1here"><div class="td_cryptospan"> {{$curr['balance']}} {{$curr['symbol']}} </div>
                        <div class="td_fiatspan">{{number_format($curr['amtvalue'], 4)}}{{$currency}}</div></td>
                    </tr>
                    @endforeach 
                    
                      </tbody>
                    
                  </table>
                </div>
              </div>
					  	</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-lg-12">
					<div class="row">
						<div class="col-md-12 col-sm-6 col-lg-6">
						<div class="card-div-cnt dash-top-l">
                  <h2>Recent Activity</h2>
                  <div class="table-responsive recent-activity-box">
                    <table>
                      
                      <tbody>
                      
                      @foreach ($deposit as $dpst)
                      <tr>
                        <td><?php echo date("M",strtotime($dpst->updated_at)); ?> <br/>
                          <b><?php echo date("d", strtotime($dpst->updated_at)); ?></b></td>
                        <td><div class="dep-box">Deposit <br>{{$dpst->currency}} <span> {{$dpst->amount}}</span></div></td>
                        <td> {{$dpst->currency}} <br/>
						<b class="text-green">{{$dpst->status}}</b> </td>
                      </tr>
                      @endforeach
                      @foreach ($fiatdeposit as $fitdpst)
                      <tr>
                        <td><?php echo date("M",strtotime($fitdpst->updated_at)); ?> <br/>
                          <b><?php echo date("d", strtotime($fitdpst->updated_at)); ?></b></td>
                        <td><div class="dep-box">Deposit <br>{{$fitdpst->currency}} {{$fitdpst->amount}}</div></td>
                        <td> {{$fitdpst->currency}} <br/>
						<b class="text-green">{{$fitdpst->status}}</b> </td>
                      </tr>
                      @endforeach
                        </tbody>
                      
                    </table>
                     </div>
					 <div class="text-center view-acc"><a href="{{url('/funds')}}">View your accounts</a></div>
                </div>
					  	</div>
						<div class="col-md-12 col-sm-6 col-lg-6">
						<div class="card-div-cnt dash-top-l">
                  			<h2><?php echo getStaticContent('Dashboard_Recommended_For_You_Ads')->title; ?></h2>
                  			<div class="table-responsive"> 
								<div class="download-section-r d-flex">
									<?php echo getStaticContent('Dashboard_Recommended_For_You_Ads')->content; ?>
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
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	var no_records   = "{{trans('app_lang.no_records_found') }}";
	var submit = "{{trans('app_lang.submit')}}";
	var profile_btn= "{{trans('app_lang.update_profile') }}";
</script>
<style type="text/css">
	.portbalanceH1
	{
		/*padding: 5% 0% 10% 0%;*/
	}
	.td_fiatspan
	{
		font-size: 13px;
		color: #717171;
	}
	.tr_border_here
	{
		border-top: 1px solid #dddcdc;
		border-bottom: 1px solid #dddcdc;
	}
	.tdcols1here
	{
		text-align: right;
	}
	.pricechartBalance{
		text-align: center;
		width: 0;
		float: left;
		margin-top: -10px;
	}
	.marginbottowIncrese{
		margin-bottom: 5%;
	}
	.pricechartBalanceleft {
	    float: right;
		width: 50%;
	}
	.pricechartBalanceleft a
	{
		padding: 0% 4% 0% 4%;
		border-right: 1px solid #d0c7c7;
		font-size: 13px;
	}
	.pricechartBalanceleft a.lastaHrefTag{
		border-right: none;
	}
	#text_price_euro_total
	{
		font-size: 30px;
	}
	.fixedWidthImg{
		width: 12%;
		padding: 1%;
	}
</style>
