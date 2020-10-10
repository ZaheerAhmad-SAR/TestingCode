<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    protected $table = 'form_submit_status';
    protected $fillable = ['id', 'form_filled_by_user_id', 'form_filled_by_user_role_id', 'subject_id', 'study_id', 'study_structures_id', 'phase_steps_id', 'form_type_id', 'form_status'];
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

    public function editReasons()
    {
        return $this->hasMany(FormRevisionHistory::class, 'form_submit_status_id', 'id');
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
}
