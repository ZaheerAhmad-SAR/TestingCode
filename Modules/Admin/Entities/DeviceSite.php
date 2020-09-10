<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class DeviceSite extends Model
{
    protected $fillable = ['id','device_id','site_id'];
    protected $keyType = 'string';
}
