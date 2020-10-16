<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormStatus extends Model
{
    protected $table = 'form_submit_status';
    protected $fillable = ['id', 'form_filled_by_user_id', 'form_filled_by_user_role_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'form_type_id', 'modility_id', 'form_status'];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => 'no-id-123',
        'form_status' => 'no_status',
        'form_type_id' => 0,
        'form_filled_by_user_id' => 'no-user-id',
    ];

    public static function getFormStatusObjQuery($getFormStatusArray)
    {
        $formStatusObjectQuery = Self::where(function ($q) use ($getFormStatusArray) {
            foreach ($getFormStatusArray as $key => $value) {
                $q->where($key, 'like', (string)$value);
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
        return self::getFormStatusObjQuery($getFormStatusArray)->orderBy('created_at')->get();
    }


    public function editReasons()
    {
        return $this->hasMany(FormRevisionHistory::class, 'form_submit_status_id', 'id');
    }

    public static function getGradersFormsStatusesSpan($step, $getFormStatusArray)
    {
        $retStr = '';
        $numberOfGraders = $step->graders_number;
        $formStatusObjects = self::getFormStatusObjArray($getFormStatusArray);
        $extraNeededObjects = $numberOfGraders - count($formStatusObjects);
        for ($counter = 0; $counter < $extraNeededObjects; $counter++) {
            $formStatusObjects[] = new FormStatus();
        }

        foreach ($formStatusObjects as $formStatusObj) {

            $retStr .= self::makeGraderFormStatusSpan($step, $formStatusObj);
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
        $spanStr .= self::makeFormStatusSpanImage($form_status) . '</span>';
        return $spanStr;
    }

    public static function makeGraderFormStatusSpan($step, $formStatusObj)
    {
        $formStatus = $formStatusObj->form_status;
        if ($formStatus != 'no_status') {
            $imgSpanClsStr = buildGradingStatusIdClsStr($formStatusObj->id);
        } else {
            $imgSpanClsStr = buildSafeStr($step->step_id, 'img_step_status_');
        }


        $spanStr = '<span class="' . $imgSpanClsStr . '">';
        $spanStr .= self::makeFormStatusSpanImage($formStatusObj->form_status) . '</span>';
        return $spanStr;
    }

    public static function makeFormStatusSpanImage($form_status)
    {

        $imageStr = '';

        if ($form_status == 'complete') {
            $imageStr .= '<img src="' . url('images/complete.png') . '"/>';
        } elseif ($form_status == 'incomplete') {
            $imageStr .= '<img src="' . url('images/incomplete.png') . '"/>';
        } elseif ($form_status == 'resumable') {
            $imageStr .= '<img src="' . url('images/resumable.png') . '"/>';
        } elseif ($form_status == 'no_status') {
            $imageStr .= '<img src="' . url('images/no_status.png') . '"/>';
        } elseif ($form_status == 'adjudication') {
            $imageStr .= '<img src="' . url('images/adjudication.png') . '"/>';
        } elseif ($form_status == 'notrequired') {
            $imageStr .= '<img src="' . url('images/not_required.png') . '"/>';
        }
        return $imageStr;
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
        return ['id' => $formStatusObj->id, 'formTypeId' => $formStatusObj->form_type_id, 'formStatus' => $formStatusObj->form_status, 'formStatusIdStr' => buildGradingStatusIdClsStr($formStatusObj->id)];
    }

    public static function insertFormStatus($request, $formStatusArray)
    {
        $id = Str::uuid();
        $formStatusData = [
            'id' => $id,
            'form_type_id' => $request->formTypeId,
            'modility_id' => $request->modilityId,
            'edit_reason_text' => $request->edit_reason_text,
            'form_status' => 'incomplete',
        ] + $formStatusArray;
        FormStatus::create($formStatusData);
        return FormStatus::find($id);
    }
}
