<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    use SoftDeletes;
    protected $table = 'answer';
    protected $fillable = [
        'id', 'form_filled_by_user_id', 'grader_id', 'adjudicator_id', 'subject_id', 'study_id', 'study_structures_id',
        'phase_steps_id', 'section_id', 'question_id', 'variable_name', 'field_id', 'answer', 'is_answer_accepted', 'form_version_num'
    ];
    protected $keyType = 'string';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public static function getAnswerQuery($answerArray)
    {
        $query = self::where(function ($q) use ($answerArray) {
            foreach ($answerArray as $key => $value) {
                $q->where($key, 'like', $value);
            }
        });
        return $query;
    }

    public static function getAnswer($answerArray)
    {
        return self::getAnswerQuery($answerArray)->first();
    }

    public static function getAnswersArray($answerArray)
    {
        return self::getAnswerQuery($answerArray)->pluck('answer')->toArray();
    }

    public static function getAnswersAgainstStepId($answerArray)
    {
        return DB::select('SELECT `form_filled_by_user_id`, `subject_id` FROM `answer` WHERE `phase_steps_id` LIKE :phase_steps_id ORDER BY `form_filled_by_user_id`, `subject_id`', ['phase_steps_id' => $answerArray['phase_steps_id']]);
    }
}
