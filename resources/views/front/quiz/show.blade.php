@extends('layout.app')

@section('content')
  <style>
    label {
      width: 200px;
      border-radius: 3px;
      border: 1px solid #D1D3D4;
    }

    /* hide input */
    input.ans-radio:empty {
      display: none;
    }

    input.ans-radio:empty {
      margin-left: -999px;
    }

    /* style label */
    input.ans-radio:empty ~ label {
      position: relative;
      float: left;
      width: 100%;
      line-height: 2.5em;
      padding-left: 4.5em;
      padding-right: 3.5em;
      /*text-indent: 4.5em;*/
      margin-top: 2em;
      cursor: pointer;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    input.ans-radio:empty ~ label:before {
      position: absolute;
      display: block;
      top: 0;
      bottom: 0;
      left: 0;
      content: '';
      width: 50px;
      background: transparent;
      border-radius: 3px 0 0 3px;
    }

    /* toggle hover */
    input.ans-radio:hover:not(:checked) ~ label:before {
      content:'';
      text-indent: .9em;
      font-size: 20px;
      color: #C2C2C2;
    }

    input.ans-radio:hover:not(:checked) ~ label {
      color: #888;
    }

    /* toggle on */
    input.ans-radio:checked ~ label:before {
      content:'\2714';
      text-indent: .9em;
      font-size: 20px;
      color: #9CE2AE;
      background-color: #4DCB6D;
    }

    input.ans-radio:checked ~ label {
      color: #fff;
      background: #4DCB6D;
    }

    /* radio focus */
  </style>
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('message'))
        <div class="alert alert-info">
          {{Session::get('message')}}
        </div>
      @endif
      <h1>{!! \Auth::user()->name !!}</h1>
      <form class="card-item card-lg" method="post">
        <div class="col-md-12">
          <div id="time_count" class="center-block" data-seconds-left="{{env('TIME_QUIZ')}}"></div>
        </div>
        {!! csrf_field()!!}
        <h3 class="text-center" id="question">
        </h3>
        <div class="answer-section" id="answers">
        </div>
        <!-- <div>
          <button type="submit" class="btn btn-success center-block btn-lg hidden">Submit</button>
        </div> -->
      </form>
    </div>
  </div>

  <div style="position: absolute; bottom: 5px; right: 5px;">
    <a href="/auth/logout">Logout</a>
  </div>

  <script type="text/html" id="ans-template">
    <div class="col-md-12">
      <input type="radio" name="ans" id="radio1" class="ans-radio"/>
      <label for="radio1" class="ans-label" name="content">First Option</label>
    </div>
  </script>
@endsection

@section('extend-plugin')
@endsection

@section('extend-js')
  <script src="{{ asset('assets/js/socket.io.js') }}"></script>
  <script>
    var socket = io('http://{{env('REDIS_SERVER')}}:3000');
    var isStart = false;
    var firstF11 = false;

    socket.on("quiz_channel:App\\Events\\StopQuestionEvent", function() {
      $('#time_count').addClass('hidden');
      $('button').addClass('hidden');
    });
    socket.on("quiz_channel:App\\Events\\StartQuestionEvent", function() {
      console.log('start question');
      isStart = true;
      // $('button').removeClass('hidden');

      $('#time_count').removeClass('hidden');
      $('#time_count').startTimer({
        onComplete: function(element){
          element.addClass('hidden');
          // $('button').addClass('hidden');
        }
      });
    });
    socket.on("quiz_channel:App\\Events\\NextSceneEvent", function(quest){
      console.log(quest);
      var questionID = quest.question_id;
      var question = quest.question;
      var answers = quest.answers;

      $('#question').text(question);
      $('#answers').empty();
      $('.alert').remove();
      
      // $('button').addClass('hidden');
      $.each(answers, function(index, elem) {
        var template = $($('#ans-template').html());
        template.find('[name="ans"]').attr('id', 'radio_'+index).val(index);
        template.find('[name="content"]').attr('for', 'radio_'+index).text(elem);

        $('#answers').append(template);
      });

      var path = '{{route('quiz.answer', ['id' => 'xxx'])}}';
      path = path.replace('xxx', questionID);
      $('form').attr('action', path);

      $('#time_count').empty();
      $('#time_count').data('seconds-left', quest.time);
    });

    $('body').keydown(function(event) {

      if (!firstF11 && event.which == 122) {
        firstF11 = true;
        return true;
      }

      event.preventDefault();

      if (!isStart) return;
      var selected = -1;
      if (event.which == 49) {
        selected = 0;
      } else if (event.which == 50) {
        selected = 1;
      } else if (event.which == 51) {
        selected = 2;
      }

      if (selected < 0) return;

      $("input[name=ans][value=" + selected + "]").prop('checked', 'checked');
      console.log($('input[name=ans]:checked', '#myForm').val());

      $('form').submit();
    });
  </script>
@stop