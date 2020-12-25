<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionAdjudicationRequired extends Model
{
    use softDeletes;
    protected $table = 'question_adjudication_required';
    protected $fillable = ['id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'question_id', 'val_difference', 'is_percentage'];
    protected $keyType = 'string';

    public static function getQuery($array)
    {
        $query = self::where(function ($q) use ($array) {
            foreach ($array as $key => $value) {
                $q->where($key, 'like', (string)$value);
            }
        });
        return $query;
    }

    public static function getAdjudicationRequiredQuestionsArray($array)
    {
        return self::getQuery($array)->pluck('question_id')->toArray();
    }

    public static function deleteAdjudicationRequiredQuestion($array)
    {
        return self::getQuery($array)->delete();
    }
}
