<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormAdjudicationStatus extends Model
{
    protected $table = 'form_adjudication_status';
    protected $fillable = ['id', 'form_adjudicated_by', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'modility_id', 'adjudication_status'];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => 'no-id-123',
        'adjudication_status' => 'no_status',
        'modility_id' => 0,
        'form_adjudicated_by' => 'no-user-id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'form_adjudicated_by', 'id');
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

    public static function getFormAdjudicationStatusObjQuery($getFormAdjudicationStatusArray)
    {
        $formAdjudicationStatusObjectQuery = Self::where(function ($q) use ($getFormAdjudicationStatusArray) {
            foreach ($getFormAdjudicationStatusArray as $key => $value) {
                $q->where($key, 'like', (string)$value);
            }
        });
        return $formAdjudicationStatusObjectQuery;
    }

    public static function getFormAdjudicationStatusObj($getFormAdjudicationStatusArray)
    {
        return self::getFormAdjudicationStatusObjQuery($getFormAdjudicationStatusArray)->firstOrNew();
    }

    public static function getFormAdjudicationStatus($step, $getFormAdjudicationStatusArray, $wrap = false)
    {
        $formAdjudicationStatusObj = self::getFormAdjudicationStatusObj($getFormAdjudicationStatusArray);
        if ($wrap) {
            return self::makeFormAdjudicationStatusSpan($step, $formAdjudicationStatusObj);
        } else {
            return $formAdjudicationStatusObj->adjudication_status;
        }
    }

    public static function makeFormAdjudicationStatusSpan($step, $formAdjudicationStatusObj)
    {
        $info = '';
        $formAdjudicationStatus = $formAdjudicationStatusObj->adjudication_status;
        if ($formAdjudicationStatus != 'no_status') {
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $formAdjudicationStatusObj->user->name . '"';
        }

        $imgSpanStepClsStr = buildSafeStr($step->step_id, 'img_step_status_');
        $spanStr = '<span class="' . $imgSpanStepClsStr . '" ' . $info . '>';
        $spanStr .= self::makeFormAdjudicationStatusSpanImage($formAdjudicationStatus) . '</span>';
        return $spanStr;
    }

    public static function makeFormAdjudicationStatusSpanImage($adjudication_status)
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
        }
        return $imageStr;
    }

    public static function putFormAdjudicationStatus($request)
    {
        $form_adjudicated_by_id = auth()->user()->id;
        $getFormAdjudicationStatusArray = [
            'form_adjudicated_by_id' => $form_adjudicated_by_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'modility_id' => $request->modilityId,
        ];

        $formAdjudicationStatusObj = FormAdjudicationStatus::getFormAdjudicationStatusObj($getFormAdjudicationStatusArray);

        if ($formAdjudicationStatusObj->adjudication_status == 'no_status') {
            $formAdjudicationStatusObj = self::insertFormAdjudicationStatus($request, $getFormAdjudicationStatusArray);
        } else {
            $formAdjudicationStatusObj->adjudication_status = 'complete';
            $formAdjudicationStatusObj->update();
        }
        return ['id' => $formAdjudicationStatusObj->id, 'formAdjudicationStatus' => $formAdjudicationStatusObj->adjudication_status, 'formAdjudicationStatusIdStr' => buildGradingStatusIdClsStr($formAdjudicationStatusObj->id)];
    }

    public static function insertFormAdjudicationStatus($request, $formAdjudicationStatusArray)
    {
        $id = Str::uuid();
        $formAdjudicationStatusData = [
            'id' => $id,
            'adjudication_status' => 'incomplete',
        ] + $formAdjudicationStatusArray;
        FormAdjudicationStatus::create($formAdjudicationStatusData);
        return FormAdjudicationStatus::find($id);
    }
}
