<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormStatus extends Model
{
    protected $table = 'form_submit_status';
    protected $fillable = ['id', 'form_filled_by_user_id', 'form_filled_by_user_role_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'form_type_id', 'form_status'];
    protected $keyType = 'string';

    protected $attributes = [
        'form_status' => 'no_status',
    ];

    public static function getFormStatusObjQuery($getFormStatusArray)
    {
        $formStatusObjectQuery = Self::where(function ($q) use ($getFormStatusArray) {
            foreach ($getFormStatusArray as $key => $value) {
                $q->where($key, '=', $value);
            }
        });
        return $formStatusObjectQuery;
    }

    public static function getFormStatusObj($getFormStatusArray)
    {
        return self::getFormStatusObjQuery($getFormStatusArray)->firstOrNew();
    }

    public static function getFormStatusObjArray($getFormStatusArray)
    {
        return self::getFormStatusObjQuery($getFormStatusArray)->get();
    }


    public function editReasons()
    {
        return $this->hasMany(FormRevisionHistory::class, 'form_submit_status_id', 'id');
    }

    public static function getGradersFormsStatusesSpan($step, $getFormStatusArray)
    {
        $retStr = '';
        $numberOfGraders = $step->graders_number;
        $statusObjects = self::getFormStatusObjArray($getFormStatusArray);
        for ($counter = 0; $counter < $numberOfGraders; $counter++) {
            $formStatusObj = self::getFormStatusObj($getFormStatusArray);
            $retStr .= self::makeFormStatusSpan($step, $formStatusObj->form_status);
        }
        return $retStr;
    }

    public static function getFormStatus($step, $getFormStatusArray, $wrap = false)
    {
        $formStatusObj = self::getFormStatusObj($getFormStatusArray);
        if ($wrap) {
            return self::makeFormStatusSpan($step, $formStatusObj->form_status);
        } else {
            return $formStatusObj->form_status;
        }
    }

    public static function makeFormStatusSpan($step, $form_status)
    {

        $imgSpanStepClsStr = buildSafeStr($step->step_id, 'img_step_status_');
        $spanStr = '<span class="' . $imgSpanStepClsStr . '">';

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

    public static function putFormStatus_bkkkkk($request)
    {
        $sectionIds = $request->input('sectionId', []);
        if (count($sectionIds) != 0) {
            foreach ($sectionIds as $sectionId) {
                $formStatusObj = self::putSingleSectionFormStatus($request, $sectionId);
            }
        } else {
            $question = Question::find($request->questionId);
            $sectionId = $question->section->id;
            $formStatusObj = self::putSingleSectionFormStatus($request, $sectionId);
        }
        return ['id' => $formStatusObj->id, 'formStatus' => $formStatusObj->form_status];
    }

    public static function putFormStatus($request)
    {
        $form_filled_by_user_id = auth()->user()->id;
        $form_filled_by_user_role_id = auth()->user()->id;
        $getFormStatusArray = [
            'form_filled_by_user_id' => $form_filled_by_user_id,
            'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
        ];

        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);

        if ($formStatusObj->form_status == 'no_status') {
            $formStatusObj = self::insertFormStatus($request, $getFormStatusArray);
        } elseif ($request->has(buildSafeStr($request->stepId, 'terms_cond_'))) {
            $formStatusObj->edit_reason_text = $request->edit_reason_text;
            $formStatusObj->form_status = 'complete';
            $formStatusObj->update();
        }
        return ['id' => $formStatusObj->id, 'formStatus' => $formStatusObj->form_status];
    }

    public static function insertFormStatus($request, $formStatusArray)
    {
        $id = Str::uuid();
        $formStatusData = [
            'id' => $id,
            'form_type_id' => $request->formTypeId,
            'edit_reason_text' => $request->edit_reason_text,
            'form_status' => 'incomplete',
        ] + $formStatusArray;
        FormStatus::create($formStatusData);
        return FormStatus::find($id);
    }
}
