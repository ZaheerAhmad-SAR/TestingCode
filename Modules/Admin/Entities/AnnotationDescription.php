<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AnnotationDescription extends Model
{
	use SoftDeletes;
    protected $fillable = ['id','question_id','value','description','deleted_at'];
}
