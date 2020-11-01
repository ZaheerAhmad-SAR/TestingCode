<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionValidation extends Model
{
    protected $keyType = 'string';
    protected $fillable = ['id', 'question_id', 'validation_rule_id', 'decision_one', 'opertaor_one', 'dep_on_question_one_id',
        'decision_two', 'opertaor_two', 'error_type', 'error_message', 'deleted_at'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
