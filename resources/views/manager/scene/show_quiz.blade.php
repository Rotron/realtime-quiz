@extends('layout.app')

@section('content')
  <style>
    .container {
      width: 1280px !important;
    }

    .card-item.card-lg {
      min-height: 490px;
    }
  </style>
  <video id="bgvid" autoplay>
    <source src="{{url('/assets/videos/background.mp4')}}" type="video/mp4">
  </video>

  <div class="row">
    <div class="col-md-6">
    </div>
    <div class="col-md-6 " style="min-height: 14px;">
      @if($nextTurn)
      <div style="">
        <a class="pull-right" href="{{route('manager.scene.show', ['id' => 'video-quiz'])}}"><h5>Phần tranh luận</h5></a>
      </div>
      @endif
    </div>
  </div>
  <div class="row" id="quiz-slider">
    @include('partials.quiz._slider')
    <div class="col-md-4">
      <div class="card-item" style="min-height: 70px !important;">
      <div class="col-md-12">
        <div id="time_count" class="center-block hidden" data-seconds-left="{{$game->quiz_time}}" style="margin-bottom: 0;"></div>
      </div>
      </div>
      <div class="card-item" style="min-height: 60px;">
        <table class="table table-hover" style="margin-bottom: 0;">
          <thead>
          <tr>
            @foreach($teams as $team)
            <th style="width: 33%; vertical-align: middle; border-bottom: none;">{!!$team->name!!}</th>
            @endforeach
          </tr>
          </thead>
          <tbody>
            @foreach($quizes as $index => $quiz)
              <?php
                  $ansTeams = collect();
                  if ($currentQuiz > $index) {
                    $ansTeams = $quiz->teams;
                    $trueAnsTeams = collect();
                    $ansTeams->each(function($t) use ($trueAnsTeams, $quiz) {
                      if ($t->pivot->answer == $quiz->true_answer) {
                        $trueAnsTeams->push($t);
                      }
                    });
                  }
              ?>
              <tr data-question="{{$quiz->id}}">
                @foreach($teams as $team)
                  <td data-target="score" data-team="{{$team->id}}" class="{{$currentQuiz <= $index ? '' : ($trueAnsTeams->where('id', $team->id)->first() ? 'success' : 'fail')}} team-result">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 92 46" enable-background="new 0 0 92 92" xml:space="preserve">
                      <circle cx="45" cy="23" r="21" stroke-width="4" fill="none" class="svg-path"/>
                    </svg>
                    <h3>
                      {{$currentQuiz <= $index ? '' : ($ansTeams->where('id', $team->id)->first() ? $ansTeams->where('id', $team->id)->first()->pivot->answer+1 : '')}}
                    </h3>
                  </td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div style="position: fixed; bottom: 10px; right: 5px;">
    <a href="{{route('manager.scene.index')}}">Quay lại</a>
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
        this.load();
        this.play();
        // to capture IE10
//        vidFade();
      });
    })();
  </script>
@stop
