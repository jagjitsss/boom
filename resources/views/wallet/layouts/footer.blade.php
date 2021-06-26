
<script src="{{asset('/').('public/admin_assets/js/jquery-ui.js')}}"> </script>


<script src="{{asset('/').('public/admin_assets/js/bootstrap.min.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/dashboard.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/lc_switch.js')}}"> </script>

<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/lc_switch.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/jquery.dataTables.min.css')}}">

<script src="{{asset('/').('public/admin_assets/js/jquery.dataTables.min.js')}}"> </script>

<script type="text/javascript">

$('.showresult').click(function(){
$('.searc_drop').css('display','block');
});



$(document).mouseup(function(e)
{
var container = $(".searc_drop");

if (!container.is(e.target) && container.has(e.target).length === 0)
{
 $('.searc_drop').css('display','none');
}
});



$(document).ready(function(e) {
$('.setting_drp input').lc_switch();


$('body').delegate('.lcs_check', 'lcs-statuschange', function() {
var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
console.log('field changed status: '+ status );
});


$('body').delegate('.lcs_check', 'lcs-on', function() {
console.log('field is checked');
});


$('body').delegate('.lcs_check', 'lcs-off', function() {
console.log('field is unchecked');
});
});

</script>

<script>
$(document).ajaxSuccess(function(event, jqXHR, settings) {
    resetAjaxToken(jqXHR);
});

function resetAjaxToken(jqXHR)
{
   var token = jqXHR.getResponseHeader("CI-CSRF-Token");
   $('input[name="csrf_test_name"]').val(token);
}

$(document).ready(function(){
  setTimeout(function() {
    $('.alert').fadeOut('fast');
  }, 3000); 
});

$(document).ready(function() {
    (function(seconds) {
      var refresh,
        intvrefresh = function() {
            clearInterval(refresh);
            refresh = setTimeout(function() {
               location.href = "{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/logout') }}";
            }, seconds * 60000);
        };
    $(document).on('keypress click mousemove', function() { intvrefresh() });
    intvrefresh();
    }(15));

    window.onbeforeunload = function () {
        location.href = "{{ URL::to('HmcUW6TlOEEQtXRq/BoR1l96bJXsUj7ai/logout') }}";
    };

  });
</script>

</script>