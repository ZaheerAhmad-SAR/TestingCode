<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    protected $fillable = [
        'id',
        'device_name',
        'device_manufacturer',
        'device_model'
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    use SoftDeletes;

    public function sites()
    {
        return $this->belongsToMany(Site::class,'device_site');
    }

    public function modalities(){
        return $this->belongsToMany(Modility::class,'device_modilities');
    }
}
