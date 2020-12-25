<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AdjudicationFormStatus extends Model
{
    use softDeletes;
    protected $table = 'adjudication_form_status';
    protected $fillable = ['id', 'form_adjudicated_by_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'modility_id', 'adjudication_status'];
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

    public static function getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, $wrap = false)
    {
        $adjudicationFormStatusObj = self::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);
        if ($wrap) {
            return self::makeAdjudicationFormStatusSpan($step, $adjudicationFormStatusObj);
        } else {
            return $adjudicationFormStatusObj->adjudication_status;
        }
    }

    public static function makeAdjudicationFormStatusSpan($step, $adjudicationFormStatusObj)
    {
        $info = '';
        $adjudicationFormStatus = $adjudicationFormStatusObj->adjudication_status;
        if ($adjudicationFormStatus != 'no_status') {
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $adjudicationFormStatusObj->user->name . '"';
        }

        $imgSpanStepClsStr = buildSafeStr($step->step_id, 'img_adjudication_status_');
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

        if ($adjudicationFormStatusObj->adjudication_status == 'no_status') {
            $adjudicationFormStatusObj = self::insertAdjudicationFormStatus($request, $getAdjudicationFormStatusArray);
        } elseif ($request->has(buildSafeStr($request->stepId, 'adjudication_form_terms_cond_'))) {
            $adjudicationFormStatusObj->adjudication_status = 'complete';
            $adjudicationFormStatusObj->update();
        }
        return ['id' => $adjudicationFormStatusObj->id, 'adjudicationFormStatus' => $adjudicationFormStatusObj->adjudication_status, 'adjudicationFormStatusIdStr' => buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id)];
    }

    public static function insertAdjudicationFormStatus($request, $adjudicationFormStatusArray)
    {
        $id = (string)Str::uuid();
        $adjudicationFormStatusData = [
            'id' => $id,
            'adjudication_status' => 'incomplete',
        ] + $adjudicationFormStatusArray;
        AdjudicationFormStatus::create($adjudicationFormStatusData);
        return AdjudicationFormStatus::find($id);
    }
}
