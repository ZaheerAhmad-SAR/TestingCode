<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSite extends Model
{
    use SoftDeletes;
    protected $fillable = ['id', 'device_id', 'site_id'];
    protected $keyType = 'string';
}
