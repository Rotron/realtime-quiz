  <div class="col-md-12">
    <div class="card-item" style="min-height: 190px;">
      <div class="center-block">
      @if (Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ) == -1)
      @else
      <div class="question-section text-justify" data-question-id="{{$question->id}}">
          <h4></h4>
          <div class="question-wrapper">
            <h5 style="font-size: {{strlen($question->question) > 300 ? 26 : 36}}px;">
              {!! $question->question !!}
            </h5>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-8">
  <div class="card-item card-lg">
  <!-- Tab panes -->
  <div class="tab-content">
    @if (Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ) == -1)
    <div role="tabpanel" class="tab-pane active" id="intro_quiz">
      <a href="{{route('manager.quiz.start', ['id' => Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ)])}}"
         class="btn btn-success btn-lg center-block start-slider">
        Bắt đầu
      </a>
    </div>
    @else
    <div role="tabpanel" class="tab-pane active">
      <div class="center-block">
        <div class="answer-section">
          <div>
            @foreach(json_decode($question->answers) as $i => $a)
              <div class="well text-center" data-answer="{{$i == $question->true_answer}}" style="font-size: {{strlen($a) < 40 ? 36 : 22}}px">
                {!! $a !!}
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    @endif
    </div>
  </div>

  @if (Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ) != -1)
  <div class="pull-right" style="position: fixed; right: 80px; bottom: 10px;">
    @if(!$nextTurn)
    <a href="{{route('manager.quiz.start', ['id' => Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ)])}}"
       class="next-slider" style="display: ">Câu tiếp</a>
    @endif
      <a href="#" class="stop-slider">Stop</a>
      <a href="#" class="start-time-slider">Start</a>

    <a href="#" class="show-answer">Đáp án</a>
  </div>
  @endif

  <!-- Nav tabs -->
  <ul class="nav nav-tabs hidden" role="tablist">

  </ul>
</div>

@section('extend-js')
  @parent

  <script>
    (function() {
      var stopped = false;
      var slider = $('#quiz-slider');
      var numTab = slider.find('.nav-tabs > li').length;

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

      @if (Session::get(\App\Http\Controllers\Manager\ManagerController::CURRENT_QUIZ) != -1)
      $(document).ready(function() {
        setTimeout(function() {
          playSound('/assets/sounds/start_question.mp3');
        }, 500);
      });
      @endif

      slider.find('.show-answer').click(function(event) {
        event.preventDefault();

        playSound('/assets/sounds/true_answer.mp3');
        var ans = slider.find('.tab-pane.active .answer-section [data-answer="1"]');
        ans.addClass('success');
        var ansPos = ans.index();
        var quest = $('.question-section').data('question-id');
        $('.table [data-question="'+quest+'"] [data-target="score"]').addClass('fail').addClass('animate');
        $('.table [data-question="'+quest+'"] [data-target="score"][data-answer="'+ansPos+'"]').removeClass('fail').addClass('success').addClass('animate');

        var path = '{{route('manager.quiz.answer.show',['id' => 'xxx'])}}';
        path.replace('xxx', quest);
        $.get(path, function() {
        });
      });

      slider.find('.stop-slider').click(function(event) {
        event.preventDefault();

        if (stopped) return;
        stopped = true;

        $('audio').each(function(index, sound) {
          sound.pause();
          sound.currentTime = 0;
        });

        $('#time_count').remove();
        var path = '{{route('manager.quiz.stop')}}';
        $.get(path, function(datas) {
          console.log(datas);
          $.each(datas, function(index, data) {
            $('.table [data-question="'+data.question_id+'"] [data-target="score"][data-team="'+data.team_id+'"]')
                .attr('data-answer', data.answer)
                .data('answer', ''+data.answer)
                .find('h3')
                .text(1+Math.round(data.answer));
          });
        });
      });

      slider.find('.start-time-slider').click(function(event) {
        playSound('/assets/sounds/10s.mp3');
        playSound('/assets/sounds/clock_bg.mp3');

        $('#time_count').removeClass('hidden');

        $('#time_count').startTimer({
          onComplete: function(element) {
            if (stopped) return;
            stopped = true;
            var path = '{{route('manager.quiz.stop')}}';
            $.get(path, function(datas) {
              console.log(datas);
              $.each(datas, function(index, data) {
                $('.table [data-question="'+data.question_id+'"] [data-target="score"][data-team="'+data.team_id+'"]')
                    .attr('data-answer', data.answer)
                    .data('answer', ''+data.answer)
                    .find('h3')
                    .text(1+Math.round(data.answer));
              });
            });
          }
        });

        $('#quiz-slider').find('.stop-slider').show();
        $(this).hide();

        var path = '{{route('manager.quiz.starttimer')}}';
        $.get(path, function(datas) {});

        event.preventDefault();
      });

      $(document).ready(function() {
        $('#quiz-slider').find('.stop-slider').hide();
      });
    })();
  </script>
@stop