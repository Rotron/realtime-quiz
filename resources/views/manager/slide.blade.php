<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Quiz</title>
  <link rel="stylesheet" href="{{asset('assets/css/superslides.css')}}">
</head>
<body>
<div id="slides">
  <div class="slides-container">
    @foreach($files as $f)
      <img src="{{asset($f)}}" width="1024">
    @endforeach
  </div>
</div>

<script src="{{asset('assets/js/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.easing.1.3.js')}}"></script>
<script src="{{asset('assets/js/jquery.animate-enhanced.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.superslides.min.js')}}" type="text/javascript" charset="utf-8"></script>
<script>
  $(function() {
    $('#slides').superslides({
      play: 3000
    });
  });
</script>
</body>
</html>
