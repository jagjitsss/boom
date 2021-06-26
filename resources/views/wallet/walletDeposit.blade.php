@extends('wallet.layouts/admin')
@section('content')
<ul class="breadcrumb cm_breadcrumb">
  <li><a href="{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai') }}">Home</a></li>
  <li><a href="#">Deposit</a></li>
</ul>
<div class="inn_content">
  <form class="cm_frm1 verti_frm1">
    <div class="cm_head1">
      <h3>Admin Wallet Deposit</h3>
    </div>
    <br>
    <div class="form-group row clearfix">
      <div class="col-sm-9 col-xs-12">
        <label class="form-control-label">Select Currency :</label>
        <select class="form-control" name="currency" id="currency">
          <?php foreach ($currencies as $currency) {$sym = $currency->symbol;?>
          <option value="{{$sym}}">{{$sym}}</option>
          <?php }?>
        </select>
      </div>
    </div>
    <div class="cm_head1">
      <h4>Admin <span id="sel_cur"></span> deposit</h4>
    </div>
    <center>
      <h5>This is your receiving address as a QR code. It is possible to send
        <span id="sel_name"></span> to you from mobile wallets by scanning this code.
      </h5>
    </center>
    <center>
      <img id="sel_img" height="300px" width="300px">
    </center>
    <center>
      <h4 style="color:#000;" id="sel_addr"></h4>
    </center>
  </form>
</div>
<script type="text/javascript">
$('document').ready(function(){
  var curr = $('#currency').val();
  getCurrencyInfo(curr);
})

$("#currency").change(function() {
  var currency = $("#currency").val();
  getCurrencyInfo(currency);
});

function getCurrencyInfo(currency) {
  $("#sel_cur").text(currency);
  $("#sel_name").text(currency);
  $.ajax({
    url:"{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/getCryptoCurr') }}",
    method:"POST",
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    data:{ 'currency': currency },
    success:function(data) {
      data = $.parseJSON(data);
      if(data.success == 1) {
        $("#sel_addr").text(data.address);
        $("#sel_img").attr('src',data.url);
      } else {
        alert('Please try again!');
      }
    },
    error:function() {
      alert('Please try again!');
    }
  });
}
</script>
@stop