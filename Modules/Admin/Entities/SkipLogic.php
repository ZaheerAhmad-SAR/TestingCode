<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class skipLogic extends Model
{
	use SoftDeletes;
    protected $table = 'skip_logics';
    protected $fillable = ['id','question_id','option_title','option_value','activate_forms','activate_sections','activate_questions','deactivate_forms','deactivate_sections','deactivate_questions','deleted_at'];
     protected $keyType = 'string';

    public function skiplogic()
    {
    	return $this->belongsTo(Question::class,'question_id','id');
    }
}
