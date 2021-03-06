<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use softDeletes;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'site_name',
        'site_code',
        'site_address',
        'site_city',
        'site_state',
        'site_phone',
        'site_country',
    ];
    public $incrementing = false;
    public function studies()
    {
        return $this->belongsToMany(Study::class)->withPivot('study_id', 'site_id');
    }

    public function study()
    {
        return $this->belongsToMany(Study::class, 'site_study', 'site_id', 'study_id');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'device_site');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'site_id', 'id');
    }

    public function primaryInvestigator()
    {
        return $this->hasMany(PrimaryInvestigator::class, 'site_id', 'id');
    }

    public function studySite()
    {
        return $this->hasOne(StudySite::class);
    }
}
