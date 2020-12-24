<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinalAnswer extends Model
{
    use softDeletes;
    protected $table = 'final_answer';
    protected $fillable = ['id', 'study_id', 'subject_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'question_id', 'variable_name', 'field_id', 'answer', 'form_version_num'];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => 'no-id-123',
        'answer' => '',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public static function getFinalAnswerQuery($answerArray)
    {
        return self::where(function ($q) use ($answerArray) {
            foreach ($answerArray as $key => $value) {
                $q->where($key, 'like', $value);
            }
        });
    }

    public static function getFinalAnswer($answerArray)
    {
        return self::getFinalAnswerQuery($answerArray)->firstOrNew();
    }

    public static function deleteFinalAnswer($answerArray)
    {
        return self::getFinalAnswerQuery($answerArray)->delete();
    }
}
