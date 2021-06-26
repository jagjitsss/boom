@extends('wallet.layouts/admin')
@section('content')
<?php 
$allwed = App\Model\SubAdmin::getAllowed(Session::get('adminId'));
$type_allo = json_decode($allwed);
?>
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/paginate_admin.css')}}">
<ul class="breadcrumb cm_breadcrumb">
  <li><a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}">Home</a></li>
  <li><a href="#">Admin Profit</a></li>
  <li><a href="#">Profit List</a></li>
</ul>
<div class="inn_content">
  <form class="cm_frm1 verti_frm1">
    <div class="cm_head1">
      <h3>Profit List</h3>
    </div>
    <?php if (Session::has('success')) {?>
    <div role="alert" class="alert alert-success" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php echo Session::get('success'); ?> </div>
    <?php }?>
    <?php if (Session::has('error')) {?>
    <div role="alert" class="alert alert-danger" style="height:auto;"><button type="button"  class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Oh!</strong><?php echo Session::get('error'); ?> </div>
    <?php }?>
    <div class="cm_tablesc1 dep_tablesc mb-20">
      <div class="mb-20">
        <button type="button" class="btn cm_blacbtn" id="view_admin_profit">View Admin Profit</button>
      </div>
      <a href="{{ URL::to($redirectUrl.'/csv/profit') }}"><button type="button" class="btn btn-info">Download csv</button></a>

      <div class="row search_area transaction">
        <input type="text" id="datepicker1" name="datepicker1" value="{{$start_date}}" placeholder="Start Date"> - <input type="text" id="datepicker2" name="datepicker1" value="{{$end_date}}" placeholder="End Date">

        <button class="btn" onclick="search_filter()" type="button">Search</button>
        <a class="btn" href="{{ URL::to($redirectUrl.'/viewAdminProfit') }}" >Reset</a>
      </div>

      <div class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <div class="cm_tableh3 table-responsive">
              <table class="table m-0" id="data_table1212_">
                <thead>
                  <tr>
                    <th>S-No</th>
                    <th>User Name</th>
                    <th>Profit amount</th>
                    <th>Currency</th>
                    <th>Type</th>
                    <th>Datetime</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($result) {
	$ii = 1;foreach ($result as $res) {
		$getUser = App\Model\User::getProfile($res['user_id']);
		$username = $getUser;
		$profitCurrency = strip_tags($res['theftCurrency']);
		$profitAmount = number_format(strip_tags($res['theftAmount']), 8, '.', '');
		?>
                  <tr>
                    <td>{{$ii}}</td>
                    <td>{{$username}}</td>
                    <td>{{$profitAmount}}</td>
                    <td>{{$profitCurrency}}</td>
                    <td>{{$res['type']}}</td>
                    <td>{{$res['created_at']}}</td>
                  </tr>
                  <?php $ii++;}}?>
                  <?php if ($ii <= 1) {?>
                  <tr>
                    <td style="text-align: center;" colspan="8">No data found in table.</td>
                  </tr>
                  <?php }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if (!empty($result)) {?>
    <div class="paging">

      {{ $result->links('admin.layouts.pagination') }}
    </div>
    <?php }?>
  </form>
</div>
<div id="ticketAlert" class="modal fade modalPop" role="dialog">
  <div class="modal-dialog">
    
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Admin Profit</h4>
        <table>
          <thead><th>Currency</th><th>Amount</th></thead>
          <tbody>
        <?php if ($datas) {
	foreach ($datas as $key => $value) {
		echo "<tr>";
		echo '<td>' . $key . '</td><td>' . $value . '</td></tr>';
	}
}
?>
</tbody></table>
        <button type="button" class="close" data-dismiss="modal"><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
$('#data_table_').DataTable({

"footerCallback": function ( row, data, start, end, display ) {
var api = this.api(), data;
// converting to interger to find total
var intVal = function ( i ) {
return typeof i === 'string' ?
i.replace(/[\$,]/g, '')*1 :
typeof i === 'number' ?
i : 0;
};
// computing column Total of the complete result
var priceCol = api
.column( 3 )
.data()
.reduce( function (a, b) {
return intVal(a) + intVal(b);
}, 0 );
// Update footer by showing the total with the reference of the column index
$( api.column( 0 ).footer() ).html('Total');
$( api.column( 3 ).footer() ).html(priceCol.toFixed(8));
},

});

$("#datepicker1").datepicker({
    // changeMonth: true,
    dateFormat : "yy-mm-dd",
    onSelect: function(selected) {
            $("#datepicker2").datepicker("option","minDate", selected)
          }
  });
$("#datepicker2").datepicker({
    // changeMonth: true,
    dateFormat : "yy-mm-dd",
    onSelect: function(selected) {
            $("#datepicker1").datepicker("option","minDate", selected)
          }
  });
  $('#datepicker1,#datepicker2').keypress(function(e) {
  e.preventDefault();
  });
});
$('#view_admin_profit').click(function(){
$('#ticketAlert').modal('show');
})


function search_filter(){
  var data1       = $('#datepicker1').val();
  var data2       = $('#datepicker2').val();
  var date        = data1+'_'+data2;
  window.location.href = "{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/viewAdminProfit') }}"+'/'+date;
}
</script>

@stop