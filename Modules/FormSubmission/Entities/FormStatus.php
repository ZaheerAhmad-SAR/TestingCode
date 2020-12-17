<?php

namespace Modules\FormSubmission\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\FormSubmission\Traits\AdjudicationTrait;
use Modules\FormSubmission\Scopes\FormStatusOrderByScope;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\PhaseSteps;

class FormStatus extends Model
{
    use SoftDeletes;
    use AdjudicationTrait;

    protected $table = 'form_submit_status';
    protected $fillable = [
        'id', 'form_filled_by_user_id', 'form_filled_by_user_role_id', 'subject_id', 'study_id', 'study_structures_id',
        'phase_steps_id', 'section_id', 'form_type_id', 'modility_id', 'form_status', 'form_version_num'
    ];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => 'no-id-123',
        'form_status' => 'no_status',
        'form_type_id' => 0,
        'form_filled_by_user_id' => 'no-user-id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FormStatusOrderByScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'form_filled_by_user_id', 'id');
    }

    public function formType()
    {
        return $this->belongsTo(FormType::class, 'form_type_id', 'id')->withDefault();
    }

    public function getUser($field)
    {
        $user = $this->user;
        $value = '';
        if (null !== $user) {
            $value = $user->{$field};
        }
        return $value;
    }

    public static function getFormStatusObjQuery($getFormStatusArray)
    {
        $formStatusObjectQuery = self::where(function ($q) use ($getFormStatusArray) {
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

    public static function getGradersFormsStatusesSpan($step, $getFormStatusArray, $numGraders = 0, $wrapSeperate = false)
    {
        $retStr = '';
        $numberOfGraders = ($numGraders != 0) ? $numGraders : $step->graders_number;
        $formStatusObjects = self::getFormStatusObjArray($getFormStatusArray);
        $extraNeededObjects = $numberOfGraders - count($formStatusObjects);
        for ($counter = 0; $counter < $extraNeededObjects; $counter++) {
            $formStatusObjects[] = new FormStatus();
        }
        foreach ($formStatusObjects as $formStatusObj) {

            if ($wrapSeperate) {
                $retStr .= self::makeFormStatusSeperateSpan($formStatusObj);
            } else {
                $retStr .= self::makeGraderFormStatusSpan($step, $formStatusObj);
            }
        }
        return $retStr;
    }

    public static function isAllGradersGradedThatForm($step, $getFormStatusArray)
    {
        $ret = false;
        $formStatusObjects = self::getFormStatusObjArray($getFormStatusArray);
        if (count($formStatusObjects) == $step->graders_number) {
            $ret = true;
        }
        return $ret;
    }

    public static function getAllGraderIds($getFormStatusArray)
    {
        $query = self::getFormStatusObjQuery($getFormStatusArray);
        return $query->pluck('form_filled_by_user_id')->toArray();
    }

    public static function getFormStatus($step, $getFormStatusArray, $wrap = false, $wrapSeperate = false)
    {
        $formStatusObj = self::getFormStatusObj($getFormStatusArray);
        if ($wrap) {
            if ($wrapSeperate) {
                return self::makeFormStatusSeperateSpan($formStatusObj);
            } else {
                return self::makeFormStatusSpan($step, $formStatusObj);
            }
        } else {
            return $formStatusObj->form_status;
        }
    }

    public static function makeFormStatusSpan($step, $formStatusObj)
    {
        $info = '';
        $formStatus = $formStatusObj->form_status;
        if ($formStatus != 'no_status') {
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $formStatusObj->user->name . '"';
        }

        $imgSpanStepClsStr = buildSafeStr($step->step_id, 'img_step_status_');
        $imgSpanStepSkipLogicClsStr = buildSafeStr($step->step_id, 'img_step_status_skip_logic_');
        $spanStr = '<span class="' . $imgSpanStepClsStr . ' ' . $imgSpanStepSkipLogicClsStr . '" ' . $info . '>';
        $spanStr .= self::makeFormStatusSpanImage($formStatus) . '</span>';
        return $spanStr;
    }

    public static function makeFormStatusSeperateSpan($formStatusObj)
    {
        $formStatus = $formStatusObj->form_status;
        $formUserName = $formStatusObj->getUser('name');

        $status = '';
        $userName = '';

        switch ($formStatus) {
            case 'no_status':
                $status = 'Not Initiated';
                $userName = 'NoName';
                break;

            case 'no_required':
                $status = 'Not Required';
                $userName = 'NoName';
                break;

            case 'incomplete':
                $status = 'Initiated';
                $userName = $formUserName;
                break;

            case 'complete':
                $status = 'Complete';
                $userName = $formUserName;
                break;

            case 'resumable':
                $status = 'Editing';
                $userName = $formUserName;
                break;

            case 'adjudication':
                $status = 'In Adjudication';
                $userName = $formUserName;
                break;
            default:
                $status = 'Not Initiated';
                $userName = 'NoName';
                break;
        }
        return $userName . '-' . $status . '|';
    }

    public static function makeGraderFormStatusSpan($step, $formStatusObj)
    {
        $info = '';
        $formStatus = $formStatusObj->form_status;
        $imgSpanStepSkipLogicClsStr = '';
        if ($formStatus != 'no_status') {
            $imgSpanClsStr = buildGradingStatusIdClsStr($formStatusObj->id);
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $formStatusObj->user->name . '"';
        } else {
            $imgSpanClsStr = buildSafeStr($step->step_id, 'img_step_status_');
            $imgSpanStepSkipLogicClsStr = buildSafeStr($step->step_id, 'img_step_status_skip_logic_');
        }



        $spanStr = '<span class="' . $imgSpanClsStr . ' ' . $imgSpanStepSkipLogicClsStr . '" ' . $info . '>';

        $spanStr .= self::makeFormStatusSpanImage($formStatus) . '</span>';
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

    public static function putFormStatus($request)
    {
        $current_user_id = auth()->user()->id;
        $getFormStatusArray = [
            'form_filled_by_user_id' => $current_user_id,
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


            if ($formStatusObj->formType->form_type == 'Grading' || $formStatusObj->formType->form_type == 'Eligibility') {
                $step = PhaseSteps::find($request->stepId);
                $getGradingFormStatusArray = [
                    'subject_id' => $request->subjectId,
                    'study_id' => $request->studyId,
                    'study_structures_id' => $request->phaseId,
                    'phase_steps_id' => $request->stepId,
                    'modility_id' => $request->modilityId,
                ];

                if (self::isAllGradersGradedThatForm($step, $getGradingFormStatusArray)) {
                    self::runAdjudicationCheckForThisStep($step, $getGradingFormStatusArray);
                }
            }
        }
        return ['id' => $formStatusObj->id, 'formTypeId' => $formStatusObj->form_type_id, 'formType' => $formStatusObj->formType->form_type, 'formStatus' => $formStatusObj->form_status, 'formStatusIdStr' => buildGradingStatusIdClsStr($formStatusObj->id)];
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

    public static function getStepsIdsArrayByStatusAndFormType($form_type_id, $form_status, $stepsIdsArray)
    {
        return self::whereIn('phase_steps_id', $stepsIdsArray)
            ->where('form_status', 'like', $form_status)
            ->where('form_type_id', 'like', $form_type_id)
            ->pluck('phase_steps_id')
            ->toArray();
    }

    public static function getSubjectIdsArrayByStatusAndFormType($form_type_id, $form_status, $stepsIdsArray)
    {
        return self::whereIn('phase_steps_id', $stepsIdsArray)
            ->where('form_status', 'like', $form_status)
            ->where('form_type_id', 'like', $form_type_id)
            ->pluck('subject_id')
            ->toArray();
    }
}
