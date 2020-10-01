<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    protected $table = 'form_submit_status';
    protected $fillable = ['id', 'form_filled_by_user_id', 'form_filled_by_user_role_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'form_type_id', 'form_status'];
    protected $keyType = 'string';

    protected $attributes = [
        'form_status' => 'no_status',
    ];

    public static function getFormStatusObj($getFormStatusArray)
    {
        return FormStatus::where(function ($q) use ($getFormStatusArray) {
            foreach ($getFormStatusArray as $key => $value) {
                $q->where($key, '=', $value);
            }
        })->first();
    }

    public static function getFormStatusStepLevelObj($getFormStatusArray)
    {
        $step_id = $getFormStatusArray['phase_steps_id'];
        $step = PhaseSteps::find($step_id);
        $sectionArray = [];
        foreach ($step->sections as $section) {
            $getFormStatusArray['section_id'] = $section->id;
            $formStatusObject = FormStatus::where(function ($q) use ($getFormStatusArray) {
                foreach ($getFormStatusArray as $key => $value) {
                    $q->where($key, '=', $value);
                }
            })->first();
            if (null === $formStatusObject) {
                $formStatusObject = new FormStatus();
            }
            $sectionArray[] = $formStatusObject;
        }
        return ['step_id' => $step_id, 'sections' => $sectionArray];
    }
}
