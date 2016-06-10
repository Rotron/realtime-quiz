<?php

use Illuminate\Support\Facades\App;

get('/broadcast', function() {


    return view('welcome');
});

Route::get('/', function () {
    if (Auth::check() && Auth::user()->type == \App\Models\User::T_ADMIN) {
        return redirect()->route('manager.dashboard');
    }

    return view('welcome');
});

Route::get('/add-team', function() {
    \App\Models\User::create(['name' => 'CD Cong nghe va Ky thuat Oto', 
        'email'=>'2@1.1', 
        'password'=>bcrypt('2016'), 
        'intro'=>'', 'type'=>\App\Models\User::T_USER,
    ]);
});

Route::controllers([
  'auth' => 'Auth\AuthController',
//  'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function() {
    Route::get('/quiz', 'QuizController@show')->name('quiz.show');
    Route::post('/quiz/{id}', 'QuizController@answer')->name('quiz.answer');

    Route::group(['middleware' => 'manager', 'prefix' => '/manager', 'namespace' => 'Manager'], function() {
        Route::get('/dashboard', 'ManagerController@dashboard')->name('manager.dashboard');

        Route::get('/game/set', 'GameController@set')->name('manager.game.set');
        Route::get('/game/create', 'GameController@create')->name('manager.game.create');
        Route::post('/game/store', 'GameController@store')->name('manager.game.store');
        Route::get('/game/{id}', 'GameController@show')->name('manager.game.show');
        Route::delete('/game/{id}', 'GameController@destroy')->name('manager.game.destroy');
        Route::put('/game/{id}', 'GameController@reset')->name('manager.game.reset');

        Route::get('/slide', 'ManagerController@slide')->name('manager.slide');
        Route::get('/scene', 'SceneController@index')->name('manager.scene.index');
        Route::get('/scene/{id}', 'SceneController@show')->name('manager.scene.show');

        Route::get('/quiz/next-turn', 'QuizController@nextTurn')->name('manager.quiz.nextturn');
        Route::get('/quiz/start', 'QuizController@startQuiz')->name('manager.quiz.start');
        Route::get('/quiz/stop', 'QuizController@stopQuiz')->name('manager.quiz.stop');
        Route::get('/quiz/start-timer', 'QuizController@startQuizTimer')->name('manager.quiz.starttimer');
        Route::get('/quiz/answer/show', 'QuizController@showQuizAnswer')->name('manager.quiz.answer.show');
    });
});