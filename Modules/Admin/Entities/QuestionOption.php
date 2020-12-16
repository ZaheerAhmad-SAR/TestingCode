<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
	protected $table = 'question_options';
    protected $fillable = ['id','question_id','skip_logic_id','option_question_id','title','value','type','option_depend_on_question_type'];
    protected $key =  'String';
}
