<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $fillable = ['id','form_field_type_id','section_id','option_group_id','question_text','c_disk','measurement_unit','is_dependent','dependent_on','annotations'];
    protected $keyType = 'string';
}
