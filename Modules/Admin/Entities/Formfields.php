<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Formfields extends Model
{
    protected $table = 'form_field';
    protected $fillable = ['id','question_id','variable_name','xls_label','is_exportable_to_xls','is_required','lower_limit','upper_limit','field_width','text_info','validation_rules'];
    protected $keyType = 'string';

    public function questions()
    {
        return $this->belongsTo(Questions::class);
    }
}
