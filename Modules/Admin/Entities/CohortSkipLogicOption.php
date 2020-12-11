<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class CohortSkipLogicOption extends Model
{
    protected $table = 'cohort_skiplogic_options';
    protected $fillable = ['id','cohort_skiplogic_id','study_id','option_question_id','title','value'];
    protected $key =  'String';
}
