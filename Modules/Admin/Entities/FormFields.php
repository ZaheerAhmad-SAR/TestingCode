<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormFields extends Model
{
    use SoftDeletes;
    protected $table = 'form_field';
    protected $fillable = ['id', 'question_id','old_question_id', 'variable_name', 'xls_label', 'is_exportable_to_xls', 'is_required', 'lower_limit', 'upper_limit', 'decimal_point', 'field_width', 'text_info', 'validation_rules', 'deleted_at'];
    protected $keyType = 'string';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
