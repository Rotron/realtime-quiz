<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamAnswer extends Model
{
    protected $fillable = ['answer'];

  public function question()
  {
    return $this->belongsTo(Question::class, 'question_id', 'id');
  }

  public function team()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
