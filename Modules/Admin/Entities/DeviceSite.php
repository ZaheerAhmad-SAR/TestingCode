<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSite extends Model
{
    //use softDeletes;
    protected $fillable = ['id', 'device_id','device_name','site_id','device_serial','device_software_version'];
    public $incrementing = false;
    protected $keyType = 'string';
}
