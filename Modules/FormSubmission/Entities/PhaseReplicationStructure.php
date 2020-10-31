<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;

class PhaseReplicationStructure extends Model
{
    protected $table = 'phase_replication_structure';
    protected $fillable = ['id', 'study_structures_id', 'replicated_study_structures_id', 'replication_structure'];
    protected $keyType = 'string';

    public function phase()
    {
        return $this->belongsTo(StudyStructure::class, 'study_structures_id', 'id');
    }
}
