<?php

namespace App\Http\Controllers\Manager;

use App\Models\Game;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class SceneController extends Controller
{

  /**
   * @var Game
   */
  protected $game;

  public function __construct()
  {
    $gameId = Session::get(ManagerController::CURRENT_GAME, null);
    $game = null;
    if (!is_null($gameId)) {
      $game = Game::find($gameId);
    }

    if (!$game) {
      $game = Game::query()->orderBy('created_at', 'desc')->first();
      Session::put(ManagerController::CURRENT_GAME, $game->id);
    }
    $this->game = $game;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
//    Session::put(ManagerController::CURRENT_TURN, 'turn-1');
    Session::put(ManagerController::CURRENT_QUIZ, -1);
    $teams = $this->getTeams();
    return view('manager.scene.index')->with([
      'teams' => $teams,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id, Request $request)
  {
    $teams = $this->getTeams();

    switch ($id) {
      case 'intro':
        return view('manager.scene.show_intro')->with([
          'teams' => $teams,
          'game' => $this->game,
        ]);
      case 'quiz':
        $currentTurn = Session::get(ManagerController::CURRENT_TURN, 'turn-1');
        $ts = $this->getTeamsByTurn($currentTurn, $teams);

        $currentQuiz = Session::get(ManagerController::CURRENT_QUIZ, -1);
        $quizzes = $this->getQuizes($currentTurn);
        $question = $currentQuiz == -1 ? null : $quizzes->get($currentQuiz);
        $nextTurn = $currentQuiz == $quizzes->count()-1;

        if (!is_null($question)) {
          $this->notifyQuestion($question);
        }

        return view('manager.scene.show_quiz')->with([
          'teams' => $ts,
          'question' => $question,
          'quizes' => $quizzes,
          'nextTurn' => $nextTurn,
          'currentQuiz' => $currentQuiz,
          'game' => $this->game,
        ]);
      case 'discuss':
        return view('manager.scene.show_discuss')->with([
          'teams' => $teams,
          'game' => $this->game,
        ]);
      case 'video-quiz':
        $currentTurn = Session::get(ManagerController::CURRENT_TURN);
        $start = 0;
        if ($currentTurn == 'turn-1') {
          $start = 0;
        } else if ($currentTurn == 'turn-2') {
          $start = 3;
        } else {
          $start = 6;
        }

        return view('manager.scene.show_video_quiz')->with([
          'teams' => $teams,
          'game' => $this->game,
          'clip' => 'team'.($start + $request->query('team', 0)).'.mp4',
        ]);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }

  protected function getQuizes($turn)
  {
    $quizes = $this->game->questions()->with('teams')->where('turn', $turn)->get();

    return $quizes;
  }

  protected function getTeams()
  {
    $teams = $this->game->teams;

    return $teams;
  }

  protected function setQuizTurn($turn)
  {
    Session::put(ManagerController::CURRENT_QUIZ, -1);
    Session::put(ManagerController::CURRENT_TURN, $turn);
  }

  protected function notifyQuestion($question)
  {
    event(new \App\Events\NextSceneEvent($question, $this->game->quiz_time));
  }

  protected function getTeamsByTurn($currentTurn, $teams)
  {
    $ts = [];
    if ($currentTurn == 'turn-1') {
      $team = 0;
    } elseif ($currentTurn == "turn-2") {
      $team = 3;
    } else {
      $team = 6;
    }
    for ($i=0; $i < 3; $i++) {
      $ts[] = $teams->get($i+$team);
    }

    return $ts;
  }
}
