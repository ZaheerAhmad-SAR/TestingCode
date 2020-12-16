<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    use SoftDeletes;
    protected $table = 'question_options';
    protected $fillable = ['id', 'question_id', 'skip_logic_id', 'option_question_id', 'title', 'value', '	option_depend_on_question_type'];
    protected $key =  'String';
}
