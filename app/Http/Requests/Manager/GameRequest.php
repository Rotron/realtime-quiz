<?php

namespace App\Http\Requests\Manager;

use App\Http\Requests\Request;

class GameRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time_discuss' => 'required',
            'time_intro' => 'required',
            'time_quiz' => 'required',
            'time_video_quiz' => 'required',
        ];
    }
}
