
		<div class="table-responsive border-portlet-table">
			<table class="table table-hover table-borderless market-table">
				<tbody class="tb-299 wallet_tab">
				<?php $__currentLoopData = $all_cur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				  <tr>
					
					<td class="icon-td"><img class="portlet-table-cc-icon" src="<?php echo e(asset('/').('public/images/admin_currency/').$item['image']); ?>"></td>
					<td class="wd-50"><?php echo e($item['symbol']); ?></td>
					<td id="<?php echo e($item['symbol']); ?>_bal" class="<?php echo e($item['symbol']); ?>balance"><?php echo e($item['balance']); ?></td>
					<td class="icon-td"><a href="<?php echo e(url('/funds?name=deposit&currency=')); ?><?php echo e($item['symbol']); ?>" class="portlet-table-add-icon" title="Deposit"></a></td>
					<?php 
					if(isset($user) && ($user->id_status == 2 || $user->selfie_status == 2 || $user->id_status == 0 || $user->selfie_status == 0)){
						?>

	<td class="icon-td"><a href="<?php echo e(url('/dashboard?name=verification')); ?>" class="portlet-table-minus-icon"></a></td>
		<?php } else {?>

					<td class="icon-td"><a href="<?php echo e(url('/funds?name=withdraw&currency=')); ?><?php echo e($item['symbol']); ?>" class="portlet-table-minus-icon" title="Withdraw"></a></td> <?php } ?>

				  </tr>
				  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

				</tbody>
			</table>
		</div>

