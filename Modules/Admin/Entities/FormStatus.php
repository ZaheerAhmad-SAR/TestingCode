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
        $formStatusObject = FormStatus::where(function ($q) use ($getFormStatusArray) {
            foreach ($getFormStatusArray as $key => $value) {
                $q->where($key, '=', $value);
            }
        })->firstOrNew();
        return $formStatusObject;
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
            })->firstOrNew();
            $sectionArray[] = $formStatusObject;
        }
        return ['step_id' => $step_id, 'sections' => $sectionArray];
    }

    public function editReasons()
    {
        return $this->hasMany(FormRevisionHistory::class, 'form_submit_status_id', 'id');
    }

    public static function getStepFormStatus($step, $getFormStatusArray, $wrap = false)
    {
        $formStatusArray = [];
        foreach ($step->sections as $section) {
            $getFormStatusArray = [
                'phase_steps_id' => $step->step_id,
                'section_id' => $section->id,
            ];
            $formStatusObj = self::getFormStatusObj($getFormStatusArray);

            $formStatusArray[] = $formStatusObj->form_status;
        }
        $totalStatuses = count($formStatusArray);
        $statusCount = array_count_values($formStatusArray);
        if ($statusCount['complete'] == $totalStatuses) {
            $formStatus = 'complete';
        } else {
            unset($statusCount['complete']);
            $formStatusArray = array_flip($statusCount);
            arsort($formStatusArray);
            $formStatus = array_pop($formStatusArray);
        }
        if ($wrap) {
            return self::makeFormStatusSpan($step, $section, $formStatus);
        } else {
            return $formStatus;
        }
    }

    public static function getStepFormsStatusSpans($step, $getFormStatusArray)
    {
        $spanStr = '';
        foreach ($step->sections as $section) {
            $spanStr .= self::getSectionFormStatusSpan($step, $section, $getFormStatusArray);
        }
        return $spanStr;
    }

    public static function getSectionFormStatusSpan($step, $section, $getFormStatusArray)
    {
        $getFormStatusArray = [
            'phase_steps_id' => $step->step_id,
            'section_id' => $section->id,
        ];
        $formStatusObj = self::getFormStatusObj($getFormStatusArray);
        return self::makeFormStatusSpan($step, $section, $formStatusObj->form_status, 'section');
    }

    public static function makeFormStatusSpan($step, $section, $form_status, $cssClass = 'both')
    {

        $imgSpanSectionClsStr = buildSafeStr($section->id, 'img_section_status_');

        $imgSpanStepClsStr = buildSafeStr($step->step_id, 'img_section_status_');

        $class = $imgSpanStepClsStr . ' ' . $imgSpanSectionClsStr;
        if ($cssClass === 'step') {
            $class = $imgSpanStepClsStr;
        } elseif ($cssClass === 'section') {
            $class = $imgSpanSectionClsStr;
        }

        $spanStr = '<span class="' . $class . '">';

        if ($form_status == 'complete') {
            $spanStr .= '<img src="' . url('images/complete.png') . '"/>';
        } elseif ($form_status == 'incomplete') {
            $spanStr .= '<img src="' . url('images/incomplete.png') . '"/>';
        } elseif ($form_status == 'resumable') {
            $spanStr .= '<img src="' . url('images/resumable.png') . '"/>';
        } elseif ($form_status == 'no_status') {
            $spanStr .= '<img src="' . url('images/no_status.png') . '"/>';
        } elseif ($form_status == 'adjudication') {
            $spanStr .= '<img src="' . url('images/adjudication.png') . '"/>';
        } elseif ($form_status == 'notrequired') {
            $spanStr .= '<img src="' . url('images/not_required.png') . '"/>';
        }
        $spanStr .= '</span>';
        return $spanStr;
    }
}
