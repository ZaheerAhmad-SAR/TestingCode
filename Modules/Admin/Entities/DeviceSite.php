<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSite extends Model
{
    //use softDeletes;
    protected $fillable = ['id', 'device_id','device_name','device_serial_no','site_id'];
    public $incrementing = false;
    protected $keyType = 'string';
}
