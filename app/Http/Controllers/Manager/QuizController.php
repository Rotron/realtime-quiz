<?php

namespace App\Http\Controllers\Manager;

use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Queue\Capsule\Manager;
use Session;

class QuizController extends Controller
{
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
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function startQuiz()
    {
        $currentQuiz = \Session::get(ManagerController::CURRENT_QUIZ, -1);
        $currentQuiz++;
        \Session::put(ManagerController::CURRENT_QUIZ, $currentQuiz);

        return redirect()->route('manager.scene.show', ['id' => 'quiz']);
    }

    public function startQuizTimer()
    {
        $this->notifyStartQuestion();
    }

    public function stopQuiz()
    {
        $this->notifyStopQuestion();

        Session::put(ManagerController::STOP_TIME, Carbon::now());
        $currentQuiz = \Session::get(ManagerController::CURRENT_QUIZ);
        $currentTurn = \Session::get(ManagerController::CURRENT_TURN);

// dump($this->game->questions()->where('turn', $currentTurn)->get()->count()->get(0));
//         dd($currentQuiz);
        $quest = $this->game->questions()->where('turn', $currentTurn)->get()->get($currentQuiz);
        $teams = $this->getTeamsByTurn($currentTurn);
        $teamIDs = $teams->map(function($t) {
           return $t->id;
        })->toArray();
        $teamAns = $quest->teams()->whereIn('user_id', $teamIDs)->get()->map(function($t) use ($quest) {
            return [
                'team_id' => $t->id,
                'answer' => $t->pivot->answer,
                'question_id' => $quest->id,
            ];
        })->toArray();

        return $teamAns;
    }

    public function showQuizAnswer()
    {

    }

    public function nextTurn()
    {
        $currentTurn = Session::get(ManagerController::CURRENT_TURN, 'turn-1');
        if ($currentTurn == 'turn-1') {
            $nextTurn = 'turn-2';
        } else if ($currentTurn == 'turn-2') {
            $nextTurn = 'turn-3';
        } else {
            $nextTurn = 'turn-1';
        }

        Session::put(ManagerController::CURRENT_TURN, $nextTurn);
        Session::put(ManagerController::CURRENT_QUIZ, -1);

        return redirect()->route('manager.scene.show', ['id' => 'quiz']);
    }

    protected function getTeamsByTurn($currentTurn)
    {
        $teams = $this->game->teams;
        $ts = collect();
        if ($currentTurn == 'turn-1') {
            $team = 0;
        } elseif ($currentTurn == "turn-2") {
            $team = 3;
        } else {
            $team = 6;
        }

        for ($i=0; $i < 3; $i++) {
            $ts->push($teams->get($i+$team));
        }

        return $ts;
    }

    protected function notifyStartQuestion()
    {
        event(new \App\Events\StartQuestionEvent());
    }

    protected function notifyStopQuestion()
    {
        event(new \App\Events\StopQuestionEvent());
    }
}
