<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SubjectsPhases extends Model
{
    protected $table = 'subjects_phases';
    protected $keyType = 'string';
    protected $fillable = ['id', 'subject_id', 'phase_id', 'visit_date', 'is_out_of_window'];
    protected $casts = [
        'id' => 'string'
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function phases()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'id');
    }
}
