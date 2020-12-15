<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionComments extends Model
{
    use SoftDeletes;

    protected $table = 'question_comments';
    protected $fillable = ['id', 'comment_by_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'question_id', 'question_comment', 'created_at', 'updated_at', 'deleted_at'];
    protected $keyType = 'string';

    public static function getQuestionCommentQuery($questionCommentArray)
    {
        $query = self::where(function ($q) use ($questionCommentArray) {
            foreach ($questionCommentArray as $key => $value) {
                $q->where($key, 'like', $value);
            }
        });
        return $query;
    }

    public static function getQuestionComment($questionCommentArray)
    {
        return self::getQuestionCommentQuery($questionCommentArray)->first();
    }

    public static function getQuestionCommentsArray($questionCommentArray)
    {
        return self::getQuestionCommentQuery($questionCommentArray)->pluck('question_comment')->toArray();
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public static function getQuestionComments($getQuestionCommentArray)
    {
        $questionCommentQuery = self::getQuestionCommentQuery($getQuestionCommentArray);
        return $questionCommentQuery->get();
    }
}
