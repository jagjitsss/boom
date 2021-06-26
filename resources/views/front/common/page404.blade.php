<!DOCTYPE html>
<html>
<head>
  <title>Page404</title>
</head>
<body>
	<input type="hidden" id="segemnt" value="{{ Request::url(1) }}">
  <center><b>Page not found</b></center>
</body>
</html>


<script src="{{asset('/').('public/assets/js/jquery.min.js')}}"></script>
 <script>
 var base_url = '<?php echo URL::to("/"); ?>';
    var segment =$('#segemnt').val();

  
    errorhandler();
    function errorhandler(){
    	  $.ajax({
      url:base_url+"/userhiddenact",
      method:"POST",
      data:  {'segment':segment },
          success:function(res) {
            console.log(res);

          }
    });


 }
</script>