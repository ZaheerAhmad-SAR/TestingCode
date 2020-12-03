<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;

class SubjectsPhases extends Model
{

    protected $table = 'subjects_phases';
    protected $keyType = 'string';
    protected $fillable = ['id', 'subject_id', 'old_subject_id', 'phase_id', 'visit_date', 'is_out_of_window', 'modility_id', 'form_type_id'];
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

    public static function createSubjectPhase($request, $modalityIdsArray)
    {
        foreach ($modalityIdsArray as $modalityId) {
            $data = [
                'id' => Str::uuid(),
                'subject_id' => $request->subject_id,
                'phase_id' => $request->phase_id,
                'visit_date' => $request->visit_date,
                'is_out_of_window' => $request->is_out_of_window,
                'modility_id' => $modalityId,
                'form_type_id' => 1,
            ];
            self::create($data);
        }

        $oldPhase = [];

        // log event details
        $logEventDetails = eventDetails($request, 'Phase', 'Activate', 'N/A', $oldPhase);
    }

    public static function getActivatedPhasesidsArray($studyPhasesIdsArray)
    {
        return self::whereIn('phase_id', $studyPhasesIdsArray)
            ->pluck('phase_id')
            ->toArray();
    }

    public static function getSubjectIdsFromActivatedPhasesidsArray($activatedPhasesidsArray)
    {
        return self::whereIn('phase_id', $activatedPhasesidsArray)
            ->pluck('subject_id')
            ->toArray();
    }

    public static function getModilityIdsFromActivatedPhasesidsArray($activatedPhasesidsArray)
    {
        return self::whereIn('phase_id', $activatedPhasesidsArray)
            ->pluck('modility_id')
            ->toArray();
    }

    public static function getTransmissionNumber($subjectId, $phaseId)
    {
        if (!empty((string)$subjectId) && !empty((string)$phaseId)) {
            $subjectPhaseDetail = self::where('subject_id', 'like', $subjectId)->where('phase_id', 'like', $phaseId)->first();
            return $subjectPhaseDetail->Transmission_Number;
        } else {
            return '';
        }
    }
}
