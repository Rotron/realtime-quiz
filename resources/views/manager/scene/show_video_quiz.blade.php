@extends('layout.app')

@section('content')
  <style>
    video.quiz {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100%;
      height: 100%;
    }
  </style>

  <div class="row">
    <?php
    $t = Request::route('team', null);
    $t = $t ?: str_slug($teams->first()['name']);
    ?>
    <div class="col-md-12" style="min-height: 64px;">
    </div>
    <div class="col-md-12">
      <div class="card-item card-lg trans">
        <div class="tab-content">
          @foreach($teams as $index => $team)
            <div role="tabpanel" class="tab-pane {{$t == str_slug($team['name']) ? 'active' : ''}}" id="{{str_slug($team['name'])}}">
              <div class="center-block" style="    width: 400px; margin-top: 100px;">
                <video class="quiz">
                  <source src="{{asset('games/'.Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_GAME).'/'.$clip)}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
                <div class="clock hidden" id="{{str_slug($team['name'])}}_clock"></div>
                <a class="center-block stop-btn hidden"
                   style="position: absolute; bottom: -30px; left: 0;">
                  Kết thúc
                </a>
                <button class="btn btn-info btn-lg center-block start-btn" style="margin-top: 100px;">
                  Bắt đầu <img src="{{asset('/assets/images/next_button_white.png')}}" alt="">
                </button>
                <a href="{{route('manager.scene.show', ['id'=>'video-quiz', 'team' => Request::query('team', 0)+1])}}" class="reset-btn hidden"
                   style="position: absolute; bottom: -30px; left: 0;">
                  Đội tiếp theo
                </a>
                <a href="{{route('manager.quiz.nextturn')}}" class="reset-btn hidden"
                   style="position: absolute; bottom: -30px; right: 0;">
                  Lượt tiếp theo
                </a>
              </div>
            </div>
          @endforeach
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
  <script src="{{asset('/assets/js/flipclock.js')}}"></script>
  <script>
    var clock;
    var playSound = function(sound, loop) {
      var snd = new Audio(sound);
      if (typeof loop != 'undefined' && loop) {
        snd.addEventListener('ended', function() {
          console.log('sound '+sound +' ended');
          snd.currentTime = 0;
          snd.play();
        });
      }
      snd.play();
    };

    $(document).ready(function() {
      $('video.quiz').click(function(event) {
        $(this)[0].play();
      });
      $('video.quiz').on('ended', function() {
        $('.clock').removeClass('hidden');
        $(this).remove();
        $('.card-item').removeClass('trans');
      });

      @foreach($teams as $index => $team)
      (function() {
        var clock;
        var forceStop = false;
        var time = {{$game->video_quiz_time}};

        clock = $('#{{str_slug($team['name'])}}_clock').FlipClock({
          clockFace: 'MinuteCounter',
          autoStart: false,
          countdown: true,
          callbacks: {
            stop: function () {
              playSound('/assets/sounds/finished.mp3');
              if (forceStop) return;

              $('#{{str_slug($team['name'])}}_clock').addClass('stop');
              clock.setCountdown(false);
              clock.start();
            }
          }
        });

        clock.setTime(time);
        $('#{{str_slug($team['name'])}}_clock').siblings('.btn.start-btn').click(function() {
          clock.start();
          $(this).addClass('hidden');
          $('#{{str_slug($team['name'])}}_clock').siblings('.stop-btn').removeClass('hidden');
        });

        $('#{{str_slug($team['name'])}}_clock').siblings('.stop-btn').click(function() {
          forceStop = true;
          clock.stop();
          $(this).addClass('hidden');
          $('#{{str_slug($team['name'])}}_clock').siblings('.reset-btn').removeClass('hidden');
        });

        $('#{{str_slug($team['name'])}}_clock').siblings('.reset-btn').click(function() {
          forceStop = false;
          clock.setTime(time);
          clock.setCountdown(true);
          $(this).addClass('hidden');
          $('#{{str_slug($team['name'])}}_clock').siblings('.btn.start-btn').removeClass('hidden');
        });
      })();
      @endforeach

    });
  </script>
@stop