<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class StudyUser extends Model
{
    protected  $table = 'study_user';
    protected $fillable = ['id','study_id','user_id'];
    protected $keyType = 'string';

}
