<?php 

                                            foreach ($query as $key => $value) {
							                  if ($key < 1) continue;
 					                             
 					                             //User Content
							                   if($value['admin_name']){
											?>
		<div class="message-row-cnt ">
			<div class="message-prof-pic-cnt">
				<div class=""><img src="{{asset('/').('public/assets/images/')}}avatar.png" class="message-prof-pic mCS_img_loaded"></div>
			</div>
			<div class="message-txt-cnt">
				<div class="message-date-cnt"><span><?php echo $value['created_at'];?></span></div>
				<div class="message-txt"><?php echo $value['message'];?></div>
				<?php if($value['image']){
					$img = $value['id'];
			    ?>
				<div class="message-image">
				
					<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
				</div>	
				<?php }?>								
			</div>
		</div>
                                               <?php } else{?>
												
	<div class="message-row-cnt admin-conv">
		<div class="message-prof-pic-cnt">
		<?php $profil = $value['admin_name']?admin_image($value['admin_name']):$profile;?>
			<div class=""><img src="{{$profil}}" class="message-prof-pic mCS_img_loaded"></div>
		</div>
		<div class="message-txt-cnt">
			<div class="message-date-cnt"><span><?php echo $value['created_at'];?></span></div>
			<div class="message-txt"><?php echo $value['message'];?></div>					
			<?php if($value['image']){
				  $img = $value['id'];
		    ?>
			<div class="message-image">
				
				<img id="img_{{$img}}" onclick="return showPopupImage('img_{{$img}}');" class="ticket-img mCS_img_loaded" src="{{$value['image']}}" width="100" height="100">
			</div>	
			<?php }?>	
		</div>
	</div>
												<?php }}?>