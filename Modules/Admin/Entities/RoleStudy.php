<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleStudy extends Model
{
    use softDeletes;
    protected $fillable = [];
}
