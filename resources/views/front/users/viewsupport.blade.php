<?php $user_name = session::get('tmaitb_profile');?>

							<?php foreach ($query as $key => $value) {
	if ($key < 1) {
		continue;
	}

	?>
								<div class="message-row-cnt ">
									<div class="message-prof-pic-cnt">
									<?php $profil = $value['admin_name'] ? asset('/') . ('public/assets/images/profile-pic.png') : $profile;?>
										<div class=""><img src="<?php echo $profil; ?>" class="message-prof-pic"
										></div>
									</div>
									<div class="message-txt-cnt ">
										<div class="message-name-cnt"><?php echo $value['admin_name'] ? $value['admin_name'] : $user_name; ?><span><?php echo $value['created_at']; ?></span></div>
										<div class="message-txt"><?php echo $value['message']; ?></div>
										<?php if ($value['image']) {?>
										<div ><a href="{{$query[0]['image']}}" target="_blank"><img class="ticket-img" src="{{$value['image']}}" height="100" width="100"></a></div><?php }?>
									</div>
								</div>
					<?php }?>