<?php

namespace App\Http\Controllers\Manager;

use App\Models\Game;
use File;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class ManagerController extends Controller
{
    const CURRENT_GAME = 'current_game';
    const CURRENT_TURN = 'current_turn';
    const CURRENT_QUIZ = 'current_quiz';
    const STOP_TIME = 'stop_time';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

    public function dashboard()
    {
        $games = Game::all();
        $gameId = Session::get(ManagerController::CURRENT_GAME, null);
        $game = null;
        if (!is_null($gameId)) {
            $game = Game::find($gameId);
        }

        if (!$game) {
            $game = Game::query()->orderBy('created_at', 'desc')->first();
            if ($game)
                Session::put(ManagerController::CURRENT_GAME, $game->id);
        }

        return view('manager.dashboard')->with([
            'games' => $games,
            'currentGame' => $game,
        ]);
    }

    public function slide()
    {
        $files = File::allFiles(public_path('/images'));
        $filePaths = [];
        foreach($files as $f) {
            $filePaths[] = '/images/'.$f->getRelativePathname();
        }
        return view('manager.slide')->with([
            'files' => $filePaths,
        ]);
    }
}
