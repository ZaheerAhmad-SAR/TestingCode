<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['id','study_id','subject_id','enrollment_date','study_eye','site_id','disease_cohort_id'];
    protected $keyType = 'string';

    public function study(){
        return $this->hasOne(Study::class);
    }

    public function sites(){
       // dd($this->belongsTo(Site::class,'site_id','id'));
        return $this->belongsTo(Site::class);
    }
}
