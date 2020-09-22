<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class StudySite extends Model
{
    protected $table = 'site_study';
    protected $fillable = ['id','study_id','site_id','study_site_id'];
    protected $keyType = 'string';

    public function primaryInvestigator(){
        return $this->hasMany(PrimaryInvestigator::class,'site_id','id');
    }

    public function siteStudyCoordinator()
    {
       return $this->hasMany(SiteStudyCoordinator::class,'site_study_id','id');
    }


   }




