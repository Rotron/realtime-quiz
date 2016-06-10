@extends('layout.app')

@section('content')
  <div class="row">
    <div class="col-md-12">
      <h2 class="page-header">Create game</h2>
    </div>

    @if ($errors->has())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          {{ $error }}<br>
        @endforeach
      </div>
    @endif

    <form action="{{route('manager.game.store')}}" method="post" enctype="multipart/form-data">
      <?php
      $turns =
          [
              'Turn 1' => [
                  'Team 1',
                  'Team 2',
                  'Team 3',
              ],
              'Turn 2' => [
                  'Team 4',
                  'Team 5',
                  'Team 6',
              ],
              'Turn 3' => [
                  'Team 7',
                  'Team 8',
                  'Team 9',
              ],
          ];

        $count = 0;
        $countQuestion = 0;
      ?>
      {!! csrf_field() !!}
        <div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="">Thời gian Phần tự giới thiệu</label>
              <input name="time_intro" type="number" value="{{env('TIME_INTRO', 60)}}" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="">Thời gian Phần lý thuyết</label>
              <input name="time_quiz" type="number" value="{{env('TIME_QUIZ', 60)}}" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="">Thời gian Phần lý thuyết: câu hỏi video</label>
              <input name="time_video_quiz" type="number" value="{{env('TIME_VIDEO_QUIZ', 60)}}" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="">Thời gian Phần thực hành</label>
              <input name="time_discuss" type="number" value="{{env('TIME_DISCUSS', 60)}}" class="form-control">
            </div>
          </div>
        </div>
      <ul class="nav nav-tabs" role="tablist">
        @foreach($turns as $turn => $teams)
          <li class="col-md-4" role="presentation">
            <a class="btn btn-primary" role="tab" data-toggle="tab" href="#team-section_{{str_slug($turn)}}">
              {{$turn}}
            </a>
          </li>
        @endforeach
      </ul>

      <div class="tab-content">
        @foreach($turns as $turn => $teams)
          <div id="team-section_{{str_slug($turn)}}" class="tab-pane">
            <div class="row">
              @foreach($teams as $index=>$t)
                <div class="col-md-4">
                  <div class="card-item">
                    <h4>{{$t}}</h4>
                    <div class="form-group">
                      <label for="">Tên đội</label>
                      <input type="text" class="form-control" name="team[{{str_slug($turn)}}][{{$index}}][name]">
                    </div>
                    <div class="form-group">
                      <label for="">Mô tả về đội</label>
                      <textarea type="text" class="form-control" name="team[{{str_slug($turn)}}][{{$index}}][intro]"></textarea>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="">Username</label>
                        <input type="text" class="form-control" name="team[{{str_slug($turn)}}][{{$index}}][mail]">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="">Password</label>
                        <input type="text" class="form-control" name="team[{{str_slug($turn)}}][{{$index}}][password]">
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              <hr>
              <div class="col-md-12">
                <h4>Questions</h4>
                <div class="card-item-list row" data-turn="{{str_slug($turn)}}">
                  <div class="card-item">
                    <a href="#" class="btn btn-danger delete-question pull-right">Delete Question</a>
                    <div class="form-group">
                      <label for="">Nội dung câu hỏi:</label>
                      <input type="text" class="form-control" name="question[{{str_slug($turn)}}][0][content]">
                    </div>
                    <h5>Các câu trả lời</h5>
                    <div class="answer-list" data-question="0">
                      @foreach([0,1,2] as $index)
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon">
                            <input type="radio" name="question[{{str_slug($turn)}}][0][true_answer]" value="{{$index}}">
                          </span>
                          <input type="text" class="form-control" name="question[{{str_slug($turn)}}][0][answer][{{$index}}]">
                        </div>
                      </div>
                      @endforeach
                    </div>
                    <a href="#" class="btn btn-info btn-sm add-answer">Add answer</a>
                  </div>
                </div>
                <a href="#" class="btn btn-info add-question">Add Question</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="col-md-12" style="margin-top: 20px;">
        <button class="btn btn-success btn-lg" type="submit">Submit</button>
      </div>
    </form>
    </div>

    <script type="text/html" id="answer_template">
      <div class="form-group">
        <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio" name="question[true_answer][]">
                  </span>
          <input type="text" class="form-control" name="question[answer][]">
        </div>
      </div>
    </script>

    <script type="text/html" id="question_template">
      <div class="card-item">
        <a href="#" class="btn btn-danger delete-question pull-right">Delete Question</a>
        <div class="form-group">
          <label for="">Nội dung câu hỏi:</label>
          <input type="text" class="form-control" name="question[content][0]">
        </div>
        <div class="answer-list" data-question="0">
          <div class="form-group">
            <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio" name="question[true_answer][0]" value="0">
                  </span>
              <input type="text" class="form-control" name="question[answer][0][0]">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio" name="question[true_answer][0]" value="1">
                  </span>
              <input type="text" class="form-control" name="question[answer][0][1]">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio" name="question[true_answer][0]" value="2">
                  </span>
              <input type="text" class="form-control" name="question[answer][0][2]">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
                  <span class="input-group-addon">
                    <input type="radio" name="question[true_answer][0]" value="3">
                  </span>
              <input type="text" class="form-control" name="question[answer][0][3]">
            </div>
          </div>
        </div>
        <a href="#" class="btn btn-info btn-sm add-answer">Add answer</a>
      </div>
    </script>
    @endsection

    @section('extend-js')
      <script>
        var count = 3;
        var countQuestion = 1;
        var removeQuestionInit = function (container) {
          $('a.delete-question').click(function (event) {
            event.preventDefault();
            $(this).closest('.card-item').remove();
          });
        };

        var addQuestionInit = function (container) {
          $('a.add-answer').click(function (event) {
            event.preventDefault();

            var question = $(this).siblings('.answer-list').data('question');
            var turn = $(this).closest('.card-item-list').data('turn');
            var template = $('#answer_template').html();
            template = $(template);
            $(template).find('[name="question[answer][]"]').attr('name', 'question['+turn+'][' + question + '][answer][' + count + ']');
            $(template).find('[name="question[true_answer][]"]').attr('name', 'question['+turn+'][' + question + '][true_answer]').val(count);
            count++;
            $(this).siblings('.answer-list').append(template);
          });
        }

        $('a.add-question').click(function (event) {
          event.preventDefault();

          var turn = $(this).siblings('.card-item-list').data('turn');
          var template = $($('#question_template').html());
          template.find('[name="question[content][0]"]').attr('name', 'question['+turn+'][' + countQuestion + '][content]');
          template.find('[name="question[answer][0][0]"]').attr('name', 'question['+turn+'][' + countQuestion + '][answer][0]');
          template.find('[name="question[answer][0][1]"]').attr('name', 'question['+turn+'][' + countQuestion + '][answer][1]');
          template.find('[name="question[answer][0][2]"]').attr('name', 'question['+turn+'][' + countQuestion + '][answer][2]');
          template.find('[name="question[answer][0][3]"]').attr('name', 'question['+turn+'][' + countQuestion + '][answer][3]');
          template.find('[name="question[true_answer][0]"]').attr('name', 'question['+turn+'][' + countQuestion + '][true_answer]');
          template.find('.answer-list').data('question', countQuestion);
          countQuestion++;
          $(this).siblings('.card-item-list').append(template);

          removeQuestionInit(template);
          addQuestionInit(template);
        });

        removeQuestionInit($);
        addQuestionInit($);
      </script>
@stop