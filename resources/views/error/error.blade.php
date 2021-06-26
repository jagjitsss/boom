  <link rel="stylesheet" href="{{asset('/').('public/assets/css/bootstrap.min.css')}}">
     <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-
awesome.min.css" type="text/css" rel="stylesheet">   
 <link rel="stylesheet" href="{{asset('/').('public/assets/css/style.css')}}?{{date('Y-m-d h:i:s')}}">
<?php 
$img = asset('/').('public/assets/images/404-banner.png');
echo '<img src="'.$img.'" style="max-width:100%;height:auto">';

    ?>
    <a href="{{url('/')}}" class="gohome text-center">Go To Home</a>