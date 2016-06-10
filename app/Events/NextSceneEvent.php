<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Question;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NextSceneEvent implements ShouldBroadcast
{
    public $question;
    public $answers;
    public $question_id;
    public $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Question $question, $time)
    {
        $this->question_id = $question->id;
        $this->question = $question->question;
        $this->time = $time;
        $answers = json_decode($question->answers, true);
        $this->answers = [];
        foreach($answers as $id=>$ans) {
            $this->answers[$id] = $ans;
        }
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['quiz_channel'];
    }
}
