<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteStudyCoordinator extends Model
{
    use SoftDeletes;
    protected $fillable = ['id', 'site_study_id', 'coordinator_id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function studySite()
    {
        return $this->belongsTo(StudySite::class);
    }
}
