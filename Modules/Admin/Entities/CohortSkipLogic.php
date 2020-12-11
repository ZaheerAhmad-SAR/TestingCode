<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class CohortSkipLogic extends Model
{
    // use SoftDeletes;
    protected $table = 'cohort_skiplogic';
    protected $fillable = ['id', 'study_id', 'cohort_name', 'cohort_id', 'deactivate_forms', 'deactivate_sections', 'deactivate_questions', 'deleted_at', 'created_at', 'updated_at'];
    protected $keyType = 'string';
}
