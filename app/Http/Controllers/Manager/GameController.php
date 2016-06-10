<?php

namespace App\Http\Controllers\Manager;

use App\Models\Game;
use App\Models\Question;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class GameController extends Controller
{
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
        return view('manager.game.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Manager\GameRequest $request)
    {
        $game = DB::transaction(function() use ($request) {
            $game = Game::create([
                'intro' => 'Intro',
                'discuss' => 'Discuss',
                'intro_time' => $request->time_intro,
                'discuss_time' => $request->time_discuss,
                'quiz_time' => $request->time_quiz,
                'video_quiz_time' => $request->time_video_quiz,
            ]);

            // dd($game);

            $turns = $request->team;
            $teamIDs = [];
            foreach($turns as $teams) {
               foreach($teams as $team) {
                   $user = User::firstOrNew(['email' => $team['mail']]);

                   $user = $user->fill([
                       'name' => $team['name'],
                       'intro' => $team['intro'],
                       'email' => $team['mail'],
                       'password' => bcrypt($team['password']),
                   ]);
                   $user->save();

                   $teamIDs[] = $user->id;
               }
            }

            $game->teams()->sync($teamIDs);

            $turns = $request->question;
            foreach($turns as $turn => $questions) {
                foreach($questions as $q) {
                    if ($q['content']) {
                        $answers = $q['answer'];
                        $trueAns = $q['true_answer'];
                        $refacTrueAns = 0;
                        $count = 0;
                        $refacAns = [];
                        foreach($answers as $id=>$ans) {
                            if ($id == $trueAns) {
                                $refacTrueAns = $count;
                            }
                            $refacAns[] = $ans;
                            $count++;
                        }
                        $question = new Question([
                          'question' => $q['content'],
                          'true_answer' => $refacTrueAns,
                          'answers' => json_encode($refacAns),
                          'turn' => $turn,
                        ]);
                        $question->game()->associate($game);

                        $question->save();
                    }
                }
            }

            return $game;
        });

        // dd(1);

        return redirect()->route('manager.game.show', ['id' => $game->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::findOrFail($id);

        return view('manager.game.show')->with([
            'game' => $game,
        ]);
    }

    public function reset($id)
    {
        $game = Game::findOrFail($id);
        $qs = $game->questions()->with('teams')->get();
        foreach($qs as $q) {
            $q->teams()->sync([]);
        }

        Session::put(ManagerController::CURRENT_TURN, 'turn-1');
        Session::put(ManagerController::CURRENT_QUIZ, -1);

        return redirect()->route('manager.dashboard')->with([
          'success-message' => 'Reset players\' data successfully',
        ]);
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
        $game = Game::findOrFail($id);

        $game->forceDelete();

        return redirect()->route('manager.dashboard')->with([
            'success-message' => 'Game #'.$id.' has been removed',
        ]);
    }

    public function set(Request $request)
    {
        $gameId = $request->game_id;
        $game = Game::findOrFail($gameId);

        \Session::put(ManagerController::CURRENT_GAME, $gameId);

        return redirect()->route('manager.dashboard')->with([
            'success-message' => 'Set current game to game #'.$gameId,
        ]);
    }
}
