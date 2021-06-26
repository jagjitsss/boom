
<?php
$getSite = App\Model\User::getSiteLogo();
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>BoomCoin Wallet</title>

<link rel="icon" href="{{$getSite->site_favicon }}" type="image/x-icon">

<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/style.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/style_dashbard.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/dash_responsive.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/font-awesome.min.css')}}">


<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/fullcalendar.print.min.css')}}">


<link rel="stylesheet" href="{{asset('/').('public/admin_assets/css/bootstrap.min.css')}}">

<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<script src="{{asset('/').('public/admin_assets/js/jquery.min.js')}}"> </script>
<script src="{{asset('/').('public/admin_assets/js/jquery.validate.min.js')}}"> </script>

<style type="text/css">
	.error{
		color:red;
	}
</style>