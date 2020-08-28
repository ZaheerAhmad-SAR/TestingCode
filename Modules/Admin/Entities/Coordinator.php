<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    protected $fillable = ['id','first_name','mid_name','last_name','site_id','phone','email'];
    protected $keyType = 'string';

    public $incrementing = false;
}
