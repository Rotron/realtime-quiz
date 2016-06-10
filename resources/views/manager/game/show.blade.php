@extends('layout.app')

@section('content')
  <style>
    .true-answer {
      color: #2ca02c;
      font-weight: 800;
      text-decoration: underline;
    }
  </style>
  <div class="row">
    <div class="col-md-12">
      <h2 class="pull-left">Game #{{$game->id}}</h2>
      <a href="{{route('manager.game.set')}}?game_id={{$game->id}}" class="pull-right">
        <h4>Thiết lập làm game hiện tại</h4>
      </a>
    </div>

    <div class="card-item">
      <h3 class="page-header">Team</h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Intro</th>
            <th>Username</th>
          </tr>
        </thead>
        <tbody>
        @foreach($game->teams as $index => $team)
          <tr>
            <td>{{$index+1}}</td>
            <td>{{$team->name}}</td>
            <td>{{$team->intro}}</td>
            <td>{{$team->email}}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="card-item">
      <h3 class="pager-header">Questions</h3>
      @foreach($game->questions->groupBy('turn') as $turn => $questions)
        <h4>{{trans('game.turns.'.$turn)}}</h4>
        <div class="">
          @foreach($questions as $index => $q)
            <div>
              <h5>Question #{{$index+1}}: {{$q->question}}</h5>
              <ul>
              @foreach(json_decode($q->answers, true) as $id => $ans)
                <li class="{{$id == $q->true_answer ? 'true-answer': ''}}">{{$ans}}</li>
              @endforeach
              </ul>
            </div>
          @endforeach
        </div>
        <hr>
      @endforeach
    </div>
  </div>
@endsection

@section('extend-js')
@stop