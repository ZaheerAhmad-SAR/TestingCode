<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annotation extends Model
{
    use SoftDeletes;
    protected $keyType = 'string';
    protected $fillable = ['id', 'study_id', 'label', 'deleted_at'];
}
