@extends('layout.app')

@section('content')
  <style>
    .form-group {
      padding-bottom: 15px;
      padding-top: 10px;
      border-bottom: 1px dotted #cccccc;
    }
    .true-answer {
      color: #2ca02c;
      font-weight: 800;
      text-decoration: underline;
    }
  </style>
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('success-message'))
        <div class="alert alert-success">{{Session::get('success-message')}}</div>
      @endif
    </div>
    <div class="col-md-6">
      <div class="card-item">
        <div class="row">
          <div class="col-md-12">
            <form action="{{route('manager.game.set')}}" class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <p>Thiết lập game:</p>
                </div>
                <div class="col-md-4">
                  <input type="number" class="form-control" placeholder="Game ID" name="game_id">
                </div>
                <div class="col-md-4">
                  <button class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <a href="{{route('manager.game.create')}}" class="btn btn-info">Create game</a>
            </div>
          </div>
          <div class="col-md-12">
            <h4 class="page-header">List of game</h4>
            <table class="table table-border">
              <thead>
              <tr>
                <td>ID</td>
                <td>Time Round 1</td>
                <td>Time Round 2</td>
                <td>Time Round 3</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              </thead>
              <tbody>
              @foreach($games as $game)
                <tr>
                  <td>{{$game->id}}</td>
                  <td>{{$game->intro_time}}</td>
                  <td>{{$game->quiz_time}}</td>
                  <td>{{$game->discuss_time}}</td>
                  <td>
                    <form action="{{route('manager.game.destroy', ['id' => $game->id])}}" method="post">
                      {!! csrf_field() !!}
                      {!! method_field('DELETE') !!}
                      <button class="btn btn-danger">
                        Delete
                      </button>
                    </form>
                  </td>
                  <td>
                    <form action="{{route('manager.game.reset', ['id' => $game->id])}}" method="post">
                      {!! csrf_field() !!}
                      {!! method_field('PUT') !!}
                      <button class="btn btn-warning">
                        Reset
                      </button>
                    </form>
                  </td>
                  <td>
                    <a class="btn btn-info" href="{{route('manager.game.show', ['id' => $game->id])}}">
                      View
                    </a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card-item">
        <div class="row">
          <div class="col-md-12">
            @if($currentGame)
              <h4 class="page-header">Game hiện tại: #{{$currentGame->id}}</h4>
              <h5>Team</h5>
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
                @foreach($currentGame->teams as $index => $team)
                  <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$team->name}}</td>
                    <td>{{$team->intro}}</td>
                    <td>{{$team->email}}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>

              <h5>Questions</h5>
              @foreach($currentGame->questions->groupBy('turn') as $turn => $questions)
                <p><strong>{{trans('game.turns.'.$turn)}}</strong></p>
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
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('extend-js')
@stop