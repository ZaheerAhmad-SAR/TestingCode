<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignWork extends Model
{
    use softDeletes;
    protected $table = 'assign_work';
}
