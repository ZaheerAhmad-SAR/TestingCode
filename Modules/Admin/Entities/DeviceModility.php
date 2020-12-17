<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceModility extends Model
{
    use SoftDeletes;
    protected $table = 'device_modilities';
    protected $fillable = ['id', 'device_id', 'modility_id'];
    protected $keyType = 'string';
}
