<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Scopes\QuestionValidationRulesOrderByScope;


class QuestionValidation extends Model
{
    protected $keyType = 'string';
    protected $fillable = [
        'id', 'question_id', 'validation_rule_id', 'parameter_1', 'parameter_2', 'message_type', 'message', 'sort_order',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new QuestionValidationRulesOrderByScope);
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
