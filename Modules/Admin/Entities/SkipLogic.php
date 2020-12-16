<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkipLogic extends Model
{
    use SoftDeletes;
    protected $table = 'skip_logics';
    protected $fillable = ['id', 'question_id', 'option_title', 'option_value', 'textbox_value', 'number_value', 'operator', 'activate_forms', 'activate_sections', 'activate_questions', 'deactivate_forms', 'deactivate_sections', 'deactivate_questions', 'deleted_at', 'created_at', 'updated_at'];
    protected $keyType = 'string';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
