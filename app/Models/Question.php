<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  protected $fillable = ['question', 'answers', 'true_answer', 'turn'];

  public function game()
  {
    return $this->belongsTo(Game::class, 'game_id', 'id');
  }

  public function teams()
  {
    return $this->belongsToMany(User::class, 'team_answers', 'question_id', 'user_id')->withPivot('answer');
  }
}
