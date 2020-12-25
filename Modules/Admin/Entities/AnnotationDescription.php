<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnotationDescription extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'annotation_id', 'question_id', 'value', 'description', 'deleted_at'];
}
