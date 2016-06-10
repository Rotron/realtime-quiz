<!DOCTYPE html>
<html>
<head>
  <title>@yield('title', 'Quiz App')</title>

  <link href='https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
  <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

  @yield('extend-plugin')

  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container" style="margin-top: 0px;">
    @yield('content')
  </div>
</body>

<script src="{{asset('assets/js/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.simple.timer.js')}}"></script>
{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>--}}
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

@yield('extend-js')
</html>
