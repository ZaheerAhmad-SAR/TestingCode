<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionDependency extends Model
{
    use softDeletes;
    protected $keyType = 'string';
    protected $fillable = ['id', 'question_id', 'q_d_status', 'dep_on_question_id', 'opertaor', 'custom_value', 'deleted_at'];

    protected $attributes = [
        'id' => 'no-id-123',
        'dep_on_question_id' => 'not_any',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
