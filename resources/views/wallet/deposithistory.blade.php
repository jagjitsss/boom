@extends('wallet.layouts/admin')
@section('content')

<ul class="breadcrumb cm_breadcrumb">
	<li><a href="{{ URL::to('kR75XYrcJNZx7X92$5Rb69FUtDyAh6d') }}">Home</a></li>
	<li><a href="#">Deposit History</a></li>
	</ul>
	<div class="inn_content">
	<form class="cm_frm1 verti_frm1">
	  <div class="cm_head1">
	    <h3>Deposit History</h3>
	  </div>
	   <?php if (Session::has('success')) {?>
	  <div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Success!</strong><?php echo Session::get('success'); ?> </div>
	  <?php }?>

	  <?php if (Session::has('error')) {?>
	  <div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
	  <?php }?>

	  <div class="cm_tablesc1 dep_tablesc mb-20">

	    <div class="dataTables_wrapper form-inline dt-bootstrap">
	      <div class="row">
	        <div class="col-sm-12">
	          <div class="cm_tableh3 table-responsive">
	            <table class="table m-0" id="data_table_">
	              <thead>
	                <tr>
	                  <th>S.No.<span class="fa fa-sort"></span></th>
                      <th>To Address <span class="fa fa-sort"></span></th>
                      <th>Amount<span class="fa fa-sort"></span></th>
                      <th>Transactionid<span class="fa fa-sort"></span></th>
                      <th>Status<span class="fa fa-sort"></span></th>
	                </tr>
	              </thead>
	              <tbody>
	              <?php if ($deposit) {
	$ii = 1;foreach ($deposit as $depo) {
		$refNo = ($depo->transaction_id != "") ? $depo->transaction_id : "--";
		?>
	                <tr>
	                  <td>{{$ii}}</td>
	                  <td>{{$depo->address}}</td>
                      <td>{{$depo->amount}} {{$depo->currency}}</td>
                      <td>{{$refNo}}</td>
                      <td>
                      	<span class="clsCtlr <?php if ($depo->status == "Completed") {echo "clsActive";} elseif ($depo->status == "Pending") {echo "clsNotVerify";} else {echo "clsDeactive";}?>"><?php echo ucfirst(strip_tags($depo->status)); ?></span>
                      </td>
	                  </td>
	                </tr>
	               <?php $ii++;}}?>
	              </tbody>
	            </table>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</form>
</div>


<script>
$(document).ready(function(){
    $('#data_table_').DataTable();
});
</script>

@stop