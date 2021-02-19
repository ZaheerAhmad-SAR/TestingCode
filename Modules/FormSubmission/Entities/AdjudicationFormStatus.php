<?php

namespace Modules\FormSubmission\Entities;

use App\User;
use Modules\Admin\Entities\FormType;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AdjudicationFormStatus extends Model
{
    use softDeletes;

    protected $table = 'adjudication_form_status';
    protected $fillable = ['id', 'form_adjudicated_by_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'modility_id', 'adjudication_status', 'form_version_num'];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => 'no-id-123',
        'adjudication_status' => 'no_status',
        'modility_id' => 0,
        'form_adjudicated_by_id' => 'no-user-id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'form_adjudicated_by_id', 'id');
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

    public static function getAdjudicationFormStatusObjQuery($getAdjudicationFormStatusArray)
    {
        $adjudicationFormStatusObjectQuery = self::where(function ($q) use ($getAdjudicationFormStatusArray) {
            foreach ($getAdjudicationFormStatusArray as $key => $value) {
                $q->where($key, 'like', (string)$value);
            }
        });
        return $adjudicationFormStatusObjectQuery;
    }

    public static function getAdjudicationFormStatusObj($getAdjudicationFormStatusArray)
    {
        return self::getAdjudicationFormStatusObjQuery($getAdjudicationFormStatusArray)->firstOrNew();
    }

    public static function getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, $wrap = false, $wrapSeperate = false)
    {
        $adjudicationFormStatusObj = self::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);
        if ($wrap) {
            if ($wrapSeperate) {
                return self::makeAdjudicationFormStatusSeperateSpan($adjudicationFormStatusObj);
            } else {
                return self::makeAdjudicationFormStatusSpan($step, $adjudicationFormStatusObj, $getAdjudicationFormStatusArray);
            }
        } else {
            return $adjudicationFormStatusObj->adjudication_status;
        }
    }

    public static function makeAdjudicationFormStatusSeperateSpan($adjudicationFormStatusObj)
    {
        $adjudicationFormStatus = $adjudicationFormStatusObj->adjudication_status;
        $adjudicatorUserName = $adjudicationFormStatusObj->getUser('name');

        $status = '';
        $userName = '';

        switch ($adjudicationFormStatus) {
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
                $userName = $adjudicatorUserName;
                break;

            case 'complete':
                $status = 'Complete';
                $userName = $adjudicatorUserName;
                break;

            case 'resumable':
                $status = 'Editing';
                $userName = $adjudicatorUserName;
                break;

            case 'adjudication':
                $status = 'In Adjudication';
                $userName = $adjudicatorUserName;
                break;
            default:
                $status = 'Not Initiated';
                $userName = 'NoName';
                break;
        }
        return $userName . '-' . $status . '|';
    }

    public static function makeAdjudicationFormStatusSpan($step, $adjudicationFormStatusObj,$getAdjudicationFormStatusArray)
    {
        $info = '';
        $adjudicationFormStatus = $adjudicationFormStatusObj->adjudication_status;

        if ($adjudicationFormStatus != 'no_status') {
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $adjudicationFormStatusObj->user->name . '"';
        }else{
            $checkIfAnyQuestionNeedAdj = QuestionAdjudicationRequired::checkEnteryInAdjudicationRequired($getAdjudicationFormStatusArray);
            if($checkIfAnyQuestionNeedAdj > 0){
                $adjudicationFormStatus = 'required';
            }
        }
        $imgSpanStepClsStr = buildAdjudicationStatusIdClsStr($step->step_id);
        $spanStr = '<span class="' . $imgSpanStepClsStr . '" ' . $info . '>';
        $spanStr .= self::makeAdjudicationFormStatusSpanImage($adjudicationFormStatus) . '</span>';
        return $spanStr;
    }

    public static function makeAdjudicationFormStatusSpanImage($adjudication_status)
    {

        $imageStr = '';

        if ($adjudication_status == 'complete') {
            $imageStr .= '<img src="' . url('images/complete.png') . '"/>';
        } elseif ($adjudication_status == 'incomplete') {
            $imageStr .= '<img src="' . url('images/incomplete.png') . '"/>';
        } elseif ($adjudication_status == 'resumable') {
            $imageStr .= '<img src="' . url('images/resumable.png') . '"/>';
        } elseif ($adjudication_status == 'no_status') {
            $imageStr .= '<img src="' . url('images/no_status.png') . '"/>';
        } elseif ($adjudication_status == 'adjudication') {
            $imageStr .= '<img src="' . url('images/adjudication.png') . '"/>';
        } elseif ($adjudication_status == 'notrequired') {
            $imageStr .= '<img src="' . url('images/not_required.png') . '"/>';
        } elseif ($adjudication_status == 'required') {
            // $imageStr .= '<img src="' . url('images/required.png') . '"/>';
            $imageStr .= '<i class="fas fa-exclamation-circle" style="font-size:15px;"></i>';
        }
        return $imageStr;
    }

    public static function putAdjudicationFormStatus($request)
    {
        $form_adjudicated_by_id = auth()->user()->id;
        $getAdjudicationFormStatusArray = [
            'form_adjudicated_by_id' => $form_adjudicated_by_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'modility_id' => $request->modilityId,
        ];

        $adjudicationFormStatusObj = AdjudicationFormStatus::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);

        if (
            ($adjudicationFormStatusObj->adjudication_status == 'no_status') &&
            $request->has(buildSafeStr($request->stepId, 'adjudication_form_terms_cond_'))
        ) {
            $adjudicationFormStatusObj = self::insertAdjudicationFormStatus('complete', $getAdjudicationFormStatusArray);
        } elseif (
            ($adjudicationFormStatusObj->adjudication_status != 'no_status') &&
            $request->has(buildSafeStr($request->stepId, 'adjudication_form_terms_cond_'))
        ) {
            $adjudicationFormStatusObj->adjudication_status = 'complete';
            $adjudicationFormStatusObj->update();
        } elseif ($adjudicationFormStatusObj->adjudication_status == 'no_status') {
            $adjudicationFormStatusObj = self::insertAdjudicationFormStatus('incomplete', $getAdjudicationFormStatusArray);
        }

        return ['id' => $adjudicationFormStatusObj->id, 'adjudicationFormStatus' => $adjudicationFormStatusObj->adjudication_status, 'adjudicationFormStatusIdStr' => buildAdjudicationStatusIdClsStr($request->stepId)];
    }

    public static function insertAdjudicationFormStatus($status = 'incomplete', $adjudicationFormStatusArray)
    {
        $id = (string)Str::uuid();
        $adjudicationFormStatusData = [
            'id' => $id,
            'adjudication_status' => $status,
        ] + $adjudicationFormStatusArray;
        AdjudicationFormStatus::create($adjudicationFormStatusData);
        return AdjudicationFormStatus::find($id);
    }

    public static function getStepsIdsArrayByStatus($form_status, $stepsIdsArray)
    {
        return self::whereIn('phase_steps_id', $stepsIdsArray)
            ->where('adjudication_status', 'like', $form_status)
            ->pluck('phase_steps_id')
            ->toArray();
    }

    public static function getTotalAdjudicationStatus($studyId, $status = null)
    {
        $getTotalStatus = self::query();
        $getTotalStatus = $getTotalStatus->where('study_id', $studyId);
                    if($status != null) {
                        $getTotalStatus->where('adjudication_status', $status);
                    }
        $getTotalStatus = $getTotalStatus->get()->count();

        return $getTotalStatus;
       
    }
}
