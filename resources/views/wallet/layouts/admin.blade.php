<!doctype html>
<html>
<head>
    @include('wallet.layouts.head')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('wallet.layouts.header')

    @include('wallet.layouts.sidebar')
    
    <div class="content-wrapper">

            @yield('content')

    </div>

    <footer>
        @include('wallet.layouts.footer')
    </footer>

</div>
</body>
</html>

