<?php

namespace Modules\Admin\Entities;

use Modules\FormSubmission\Entities\SubjectsPhases;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['id', 'old_id', 'study_id', 'subject_id', 'enrollment_date', 'study_eye', 'site_id', 'disease_cohort_id'];
    protected $keyType = 'string';

    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    public function sites()
    {
        // dd($this->belongsTo(Site::class,'site_id','id'));
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    public function disease_cohort()
    {
        return $this->belongsTo(DiseaseCohort::class);
    }

    public function phases()
    {
        return $this->belongsToMany(StudyStructure::class, 'subjects_phases', 'subject_id', 'phase_id');
    }

    public function subjectPhases()
    {
        return $this->hasMany(SubjectsPhases::class, 'subject_id', 'id');
    }

    public function subjectPhasesArray()
    {
        return $this->subjectPhases()->distinct()->pluck('phase_id')->toArray();
    }
}
