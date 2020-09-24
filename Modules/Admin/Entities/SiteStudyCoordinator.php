<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SiteStudyCoordinator extends Model
{
    protected $fillable = ['id','site_study_id','coordinator_id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function studySite() {
        return $this->belongsTo(StudySite::class);
    }
}
