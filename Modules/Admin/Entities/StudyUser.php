<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyUser extends Model
{
    use softDeletes;
    protected  $table = 'study_user';
    protected $fillable = ['id', 'study_id', 'user_id'];
    protected $keyType = 'string';
}
