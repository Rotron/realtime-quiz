@extends('layout.app')

@section('content')
  <video id="bgvid" autoplay>
    <source src="{{url('/assets/videos/background.mp4')}}" type="video/mp4">
  </video>
  <div class="row">
    <div class="col-md-12" style="min-height: 64px;">
    </div>
    <div class="col-md-12">
      <div class="card-item card-lg">
        <div>
          <div>
            <div class="center-block" style="width: 400px; margin-top: 100px;">
              <div class="clock" id="team_clock"></div>
              <button class="btn btn-danger btn-lg center-block stop-btn hidden" style="margin-top: 100px;">
                Kết thúc
              </button>
              <button class="btn btn-info btn-lg center-block start-btn" style="margin-top: 100px;">
                Bắt đầu <img src="{{asset('/assets/images/next_button_white.png')}}" alt="">
              </button>
              <button class="btn btn-success btn-lg center-block reset-btn hidden" style="margin-top: 100px;">
                Đội tiếp theo
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="position: absolute; bottom: 5px; right: 5px;">
    <a href="{{route('manager.scene.index')}}"><h5>Quay lại</h5></a>
  </div>
@endsection

@section('extend-plugin')
  <link rel="stylesheet" href="{{asset('/assets/css/flipclock.css')}}">
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
        this.load();
        this.play();
        // to capture IE10
//        vidFade();
      });
    })();

  </script>

  <script src="{{asset('/assets/js/flipclock.js')}}"></script>
  <script>
    var clock;

    $(document).ready(function() {
          (function() {
            var clock;
            var forceStop = false;
            var time = {{$game->intro_time}};

            clock = $('#team_clock').FlipClock({
              clockFace: 'MinuteCounter',
              autoStart: false,
              countdown: true,
              callbacks: {
                stop: function () {
                  if (forceStop) return;

                  $('#team_clock').addClass('stop');
                  clock.setCountdown(false);
                  clock.start();
                }
              }
            });

            clock.setTime(time);
            $('#team_clock').siblings('.btn.start-btn').click(function() {
              clock.start();
              $(this).addClass('hidden');
              $('#team_clock').siblings('.btn.stop-btn').removeClass('hidden');
            });

            $('#team_clock').siblings('.btn.stop-btn').click(function() {
              forceStop = true;
              clock.stop();
              $(this).addClass('hidden');
              $('#team_clock').siblings('.btn.reset-btn').removeClass('hidden');
            });

            $('#team_clock').siblings('.btn.reset-btn').click(function() {
              forceStop = false;
              clock.setTime(time);
              clock.setCountdown(true);
              $('#team_clock').removeClass('stop');
              $(this).addClass('hidden');
              $('#team_clock').siblings('.btn.start-btn').removeClass('hidden');
            });
          })();
    });
  </script>
@stop