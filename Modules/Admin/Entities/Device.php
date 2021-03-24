<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Study;

class Device extends Model
{
    use softDeletes;
    protected $fillable = [
        'id',
        'device_name',
        'device_manufacturer',
        'device_model'
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'device_site');
    }

    public function modalities()
    {
        return $this->belongsToMany(Modility::class, 'device_modilities');
    }

    public function study()
    {
        return $this->belongsToMany(Study::class, 'study_devices', 'device_id', 'study_id');
    }
}
