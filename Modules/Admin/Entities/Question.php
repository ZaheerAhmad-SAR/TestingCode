<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['id','study_id','section_id','type','basic','data_validation','dependencies','annotations','advanced'];
}
