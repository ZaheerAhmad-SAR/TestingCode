<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CohortSkipLogicOption extends Model
{
    use softDeletes;
    protected $table = 'cohort_skiplogic_options';
    protected $fillable = ['id', 'cohort_skiplogic_id', 'study_id', 'option_question_id', 'title', 'value'];
    protected $key =  'String';
}
