<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuizController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('front.quiz.show');
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

    public function answer(Request $request, $id)
    {
        $ans = $request->ans;
        if (!is_null($ans)) {
            $quest = Question::find($id);
            if ($quest) {
                $a = $quest->teams()->where('user_id', \Auth::user()->id)->first();
                if (!$a) {
                    $quest->teams()->attach([
                      \Auth::user()->id => ['answer' => $ans]
                    ]);
                    \Session::flash('message', 'Bạn đã gửi câu trả lời thành công');
                }
            }
        }

        return redirect()->route('quiz.show');
    }
}
