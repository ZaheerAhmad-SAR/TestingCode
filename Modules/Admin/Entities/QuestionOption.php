<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['id','question_id','option_question_id','title','value'];
    protected $key =  'String';
}
