<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class DeviceModility extends Model
{
    protected $table = 'device_modilities';
    protected $fillable = ['id','device_id','modility_id'];
    protected $keyType = 'string';
}
