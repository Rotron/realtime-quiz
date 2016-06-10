@extends('layout.app')

@section('content')

  <video id="bgvid" autoplay loop>
    <source src="{{url('/assets/videos/background.mp4')}}" type="video/mp4">
  </video>
  <div class="row">
    <div class="col-md-12">
      <img src="{{asset('/assets/images/logo.png')}}" alt="" class="center-block" style="width: 120px;">
      <h1 style="margin-bottom: 50px;" class="big-title text-center">Hội thi An toàn, Vệ sinh viên giỏi</h1>
    </div>
    <div class="col-md-6">
      <img src="{{asset('/assets/images/home.png')}}" alt="" class="center-block" style="width: 65%; margin-top: -40px;">
    </div>
    <div class="col-md-6" style="margin-top: 60px">
      <div class="card-item card-hover grow" style="margin-bottom: 30px">
        <a href="{{route('manager.scene.show', ['id' => 'intro'])}}"><h1>Phần tự giới thiệu
            <img src="{{asset('/assets/images/part1.png')}}" alt="" class="tile-icon">
          </h1></a>
      </div>

      <div class="card-item card-hover grow" style="margin-bottom: 30px">
        <a href="{{route('manager.scene.show', ['id' => 'quiz'])}}" ><h1>Phần thi lý thuyết
            <img src="{{asset('/assets/images/part2.png')}}" alt="" class="tile-icon">
          </h1></a>
      </div>

      <div class="card-item card-hover grow">
        <a href="{{route('manager.scene.show', ['id' => 'discuss'])}}"><h1>Phần thi thực hành
            <img src="{{asset('/assets/images/part3.png')}}" alt="" class="tile-icon">
          </h1></a>
      </div>
    </div>
  </div>

  <div style="position: absolute; bottom: 5px; right: 5px;">
    <a href="{{route('manager.slide')}}">Slide</a>
  </div>
@endsection

@section('extend-js')
  <script>
    (function() {
      var vid = document.getElementById("bgvid");

      function vidFade() {
        vid.classList.add("stopfade");
      }

      vid.addEventListener('ended', function () {
        // only functional if "loop" is removed
        console.log('play')
        vid.play();
        // to capture IE10
//        vidFade();
      });
    })();
  </script>
@stop
