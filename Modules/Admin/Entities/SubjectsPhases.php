<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubjectsPhases extends Model
{
    protected $table = 'subjects_phases';
    protected $keyType = 'string';
    protected $fillable = ['id', 'subject_id', 'phase_id', 'visit_date', 'is_out_of_window'];
    protected $casts = [
        'id' => 'string'
    ];
    protected $dates = [
        'visit_date',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function phase()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'id');
    }

    public static function getSubjectPhase($subjectId, $phaseId)
    {
        return self::where('subject_id', $subjectId)->where('phase_id', $phaseId)->first();
    }

    public static function createSubjectPhase($request)
    {
        $data = [
            'id' => Str::uuid(),
            'subject_id' => $request->subject_id,
            'phase_id' => $request->phase_id,
            'visit_date' => $request->visit_date,
            'is_out_of_window' => $request->is_out_of_window,
        ];
        self::create($data);
    }
}
