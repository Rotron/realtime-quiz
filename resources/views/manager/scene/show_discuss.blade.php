@extends('layout.app')

@section('content')
  <video id="bgvid" autoplay>
    <source src="{{url('/assets/videos/background.mp4')}}" type="video/mp4">
  </video>

  <div class="row">
    <?php
    $t = Request::route('team', null);
    $t = $t ?: str_slug($teams->first()['name']);
    ?>
    <div class="col-md-12" style="min-height: 64px;">
    </div>
    <div class="col-md-12">
      <div class="card-item card-lg">
        <div class="tab-content">
          @foreach($teams as $index => $team)
            <div role="tabpanel" class="tab-pane {{$t == str_slug($team['name']) ? 'active' : ''}}" id="{{str_slug($team['name'])}}">
              <div class="center-block" style="width: 400px; margin-top: 100px;">
                <div class="clock" id="{{str_slug($team['name'])}}_clock"></div>
                <button class="btn btn-danger center-block stop-btn hidden" style="margin-top: 100px;">
                  STOP
                </button>
                <button class="btn btn-info center-block start-btn" style="margin-top: 100px;">
                  START
                </button>
                <button class="btn btn-success center-block reset-btn hidden" style="margin-top: 100px;">
                  RESET
                </button>
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
  <script>
    (function() {
      var vid = document.getElementById("bgvid");

      function vidFade() {
        vid.classList.add("stopfade");
      }

      vid.addEventListener('ended', function () {
        // only functional if "loop" is removed
        console.log('123');
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
      @foreach($teams as $index => $team)
      (function() {
        var clock;
        var forceStop = false;
        var time = {{$game->discuss_time}};

        clock = $('#{{str_slug($team['name'])}}_clock').FlipClock({
          clockFace: 'MinuteCounter',
          autoStart: false,
          countdown: true,
          callbacks: {
            stop: function () {
              playSound('/assets/sounds/start_question.mp3');

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
          $('#{{str_slug($team['name'])}}_clock').siblings('.btn.stop-btn').removeClass('hidden');
        });

        $('#{{str_slug($team['name'])}}_clock').siblings('.btn.stop-btn').click(function() {
          forceStop = true;
          clock.stop();
          $(this).addClass('hidden');
          $('#{{str_slug($team['name'])}}_clock').siblings('.btn.reset-btn').removeClass('hidden');
        });

        $('#{{str_slug($team['name'])}}_clock').siblings('.btn.reset-btn').click(function() {
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