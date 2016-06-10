<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
  use SoftDeletes;

  protected $fillable = ['intro', 'discuss', 'intro_time', 'discuss_time', 'quiz_time', 'video_quiz_time'];

  public function questions() {
    return $this->hasMany(Question::class);
  }

  public function teams()
  {
    return $this->belongsToMany(User::class, 'game_participants', 'game_id', 'user_id');
  }
}
