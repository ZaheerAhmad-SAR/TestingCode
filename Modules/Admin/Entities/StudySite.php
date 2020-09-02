<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class StudySite extends Model
{
    protected $table = 'site_study';
    protected $fillable = ['id','study_id','site_id','study_site_id'];
    protected $keyType = 'string';
}
