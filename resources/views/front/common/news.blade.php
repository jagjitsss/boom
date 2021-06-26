<?php 
  if($page == 'news'){
?>
<div class="container-fluid static-content-section">
	<div class="container static-content">
		<div class="static-heading">{{trans('app_lang.news') }}</div>
		<div class="row news-cnt">
			<div class="col-xs-12 col-sm-12 card-div">
				<div class="col-xs-12 col-sm-6 col-md-4 ml-auto p-0">
					<input type="text" class="news-search-txtbox" placeholder="Search" id="news_search">
				</div>
			</div>	
			<div class="col-xs-12 col-sm-12 card-div">
				<div class="no-padding">
					<div class="col-xs-12 col-sm-12 no-padding tickets-container news_listd">
						<div class="col-xs-12 col-sm-6 col-md-4 mb-3 p-0">
							<a href="{{ URL::to('/') }}" class="back-link"><span style="color:#f48720">Boompay </span></a><i class="fa fa-fw fa-angle-right"></i><span>News</span>
						</div>
						<ul class="col-xs-12 col-sm-12 nav nav-tabs tick-que-tab">

						 <?php 
						 $i = (isset($_REQUEST['page'])) ? ((float) $_REQUEST['page'] - 1) * 10 : 0;
						foreach($news as $newscontent){
							$newsId = $newscontent['id'];
							$i++;
						 ?>
                          <li class="nav-item news_list">
                          <div class="div_news">
								<a href="{{ URL::to('/newsdetails/'.$newsId) }}" class="nav-link ticket-row">
									<div class="tick-id-cnt"> Updated on <span>
                                    <?php 
                                       $d = strtotime($newscontent['updated_at']);
                                       echo date("d/m/Y h:i:s A", $d);
                                    ?>
									<span></div>
									<div class="tick-que">
									
								<?php echo news_lang_content('0',session('language'),$newscontent['id']);?>
									</div>
								</a>
								</div>
							</li>

						 <?php  
							}  ?>
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 news-pagination-cnt">
				<div class="pagination justify-content-center">

				  
					<a href="javascript:;" onclick="front_back('back')" id="back"><</a>
						<?php  $i =0;$j=0;
						foreach($link as $newscontent){
							$i++;
						if($i == 5){  $j++;?>
						<a href="javascript:;" class="page" id="fil_<?php echo $j;?>" onclick="search_filter('<?php echo $j ;?>')"><?php echo $j ;?></a>
						<?php $i=0;}  }

						if($i!=0){ $j++;?>
						<a href="javascript:;" class="page" id="fil_<?php echo $j;?>" onclick="search_filter('<?php echo $j ;?>')"><?php echo $j ;?></a>
						<?php } ?>
					<a href="javascript:;" onclick="front_back('front')" id="front">></a>


				</div>
			</div>
		</div>
		
	</div>
</div>

<?php }?>

<?php 
  if($page == 'details'){ ?>
        <div class="container-fluid static-content-section">
	<div class="container static-content">
		<div class="static-heading">News</div>
		<div class="row news-cnt">
			<div class="col-xs-12 col-sm-12 card-div">
				<div class="col-xs-12 col-sm-6 col-md-4 mb-3 p-0">
					<a href="{{ URL::to('/') }}" class="back-link"><span style="color:#f48720">Boompay</span></a><i class="fa fa-fw fa-angle-right"></i><a href="{{ URL::to('/news') }}" class="back-link"><span style="color:#f48720">News</span></a>
				</div>
			</div>	
			<div class="col-xs-12 col-sm-12 card-div">
				<div class="news-detail-cnt col-xs-12 col-sm-12">
					<?php 
					$lang = Session::get('language');
					if(empty($lang)) { ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title}}</div>
						<p>
							<?php echo $news->content; ?>
						</p>
					<?php } if($lang == "en") { ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title}}</div>
						<p>
							<?php echo $news->content; ?>
						</p>
					<?php } if($lang == "zh-CN") { ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title_CN}}</div>
						<p>
							<?php echo $news->content_CN; ?>
						</p>
					<?php } if($lang == "zh-TW") { ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title_TW}}</div>
						<p>
							<?php echo $news->content_TW; ?>
						</p>
					<?php } if($lang == "fr")	{ ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title_fr}}</div>
						<p>
							<?php echo $news->content_fr; ?>
						</p>
					<?php } if($lang == "es")	{ ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title_es}}</div>
						<p>
							<?php echo  $news->content_es; ?>
						</p>
					<?php } if($lang == "th")	{ ?>
						<div class="tick-id-cnt">【Announcement】Updated on <span>
						<?php 
							$d = strtotime($news->updated_at);
							echo date("d/m/Y h:i:s A", $d);
						?>
	                    <span></div>
						<div class="tick-que">{{$news->title_th}}</div>
						<p>
							<?php echo  $news->content_th; ?>
						</p>
					<?php }	?>			
				
				</div>
			</div>
		</div>
		
	</div>
</div>
  <?php } ?>

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

<?php 
    if(isset($_REQUEST['page'])){
       $page_view = $_REQUEST['page'];
    }else{
       $page_view = '1';
    } 
?>

<?php 
 if($page == 'news'){
?>
<script type="text/javascript">
	var page_view = '<?php echo $page_view;?>';
	var limit     = '<?php echo $j;?>';
</script>

<?php } ?>