
		<div class="table-responsive border-portlet-table">
			<table class="table table-hover table-borderless market-table">
				<tbody class="tb-299 wallet_tab">
				@foreach($all_cur as $item)
				  <tr>
					
					<td class="icon-td"><img class="portlet-table-cc-icon" src="{{asset('/').('public/images/admin_currency/').$item['image']}}"></td>
					<td class="wd-50">{{$item['symbol']}}</td>
					<td id="{{$item['symbol']}}_bal" class="{{$item['symbol']}}balance">{{$item['balance']}}</td>
					<td class="icon-td"><a href="{{url('/funds?name=deposit&currency=')}}{{$item['symbol']}}" class="portlet-table-add-icon" title="Deposit"></a></td>
					<?php 
					if(isset($user) && ($user->id_status == 2 || $user->selfie_status == 2 || $user->id_status == 0 || $user->selfie_status == 0)){
						?>

	<td class="icon-td"><a href="{{url('/dashboard?name=verification')}}" class="portlet-table-minus-icon"></a></td>
		<?php } else {?>

					<td class="icon-td"><a href="{{url('/funds?name=withdraw&currency=')}}{{$item['symbol']}}" class="portlet-table-minus-icon" title="Withdraw"></a></td> <?php } ?>

				  </tr>
				  @endforeach

				</tbody>
			</table>
		</div>

