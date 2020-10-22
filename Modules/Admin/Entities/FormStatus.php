<?php

namespace Modules\Admin\Entities;

use App\User;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'form_filled_by_user_id', 'id');
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

    public static function getGradersFormsStatusesSpan($step, $getFormStatusArray, $numGraders = 0)
    {
        $retStr = '';
        $numberOfGraders = ($numGraders != 0) ? $numGraders : $step->graders_number;
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

    public static function getFormStatus($step, $getFormStatusArray, $wrap = false)
    {
        $formStatusObj = self::getFormStatusObj($getFormStatusArray);
        if ($wrap) {
            return self::makeFormStatusSpan($step, $formStatusObj);
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
        $spanStr = '<span class="' . $imgSpanStepClsStr . '" ' . $info . '>';
        $spanStr .= self::makeFormStatusSpanImage($formStatus) . '</span>';
        return $spanStr;
    }

    public static function makeGraderFormStatusSpan($step, $formStatusObj)
    {
        $info = '';
        $formStatus = $formStatusObj->form_status;
        if ($formStatus != 'no_status') {
            $imgSpanClsStr = buildGradingStatusIdClsStr($formStatusObj->id);
            $info = 'data-toggle="popover" data-trigger="hover" title="" data-content="' . $formStatusObj->user->name . '"';
        } else {
            $imgSpanClsStr = buildSafeStr($step->step_id, 'img_step_status_');
        }


        $spanStr = '<span class="' . $imgSpanClsStr . '" ' . $info . '>';
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

            if ($formStatusObj->form_type_id == 2) {
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
        return ['id' => $formStatusObj->id, 'formTypeId' => $formStatusObj->form_type_id, 'formStatus' => $formStatusObj->form_status, 'formStatusIdStr' => buildGradingStatusIdClsStr($formStatusObj->id)];
    }

    public static function runAdjudicationCheckForThisStep($step, $getGradingFormStatusArray)
    {
        $sections = $step->sections;
        foreach ($sections as $section) {
            $questions = $section->questions;
            foreach ($questions as $question) {
                $fieldType = $question->form_field_type->field_type;
                if (
                    $fieldType === 'Upload' ||
                    $fieldType === 'Date & Time'
                ) {
                    continue;
                }
                /********************************** */
                $answersArray = [];
                $getAnswerArray = [
                    'study_id' => $getGradingFormStatusArray['study_id'],
                    'subject_id' => $getGradingFormStatusArray['subject_id'],
                    'study_structures_id' => $getGradingFormStatusArray['study_structures_id'],
                    'phase_steps_id' => $getGradingFormStatusArray['phase_steps_id'],
                    'section_id' => $section->id,
                    'question_id' => $question->id,
                    'field_id' => $question->formfields->id
                ];
                $answersArray = Answer::getAnswersArray($getAnswerArray);
                $numberOfAnswers = count($answersArray);
                $finalAnswer = '';
                $isAdjudicationRequired = false;
                $returnData = [];
                $questionAdjudicationStatusObj = $question->AdjStatus;
                if ($questionAdjudicationStatusObj->adj_status == 'yes') {
                    if ($fieldType === 'Radio') {
                        $returnData = self::checkAdjudicationForRadio($numberOfAnswers, $answersArray);
                    } elseif ($fieldType === 'Checkbox') {
                        $returnData = self::checkAdjudicationForRadio($numberOfAnswers, $answersArray);
                    } elseif ($fieldType === 'Dropdown') {
                        $returnData = self::checkAdjudicationForRadio($numberOfAnswers, $answersArray);
                    } elseif ($fieldType === 'Number') {
                        $returnData = self::checkAdjudicationForNumber($numberOfAnswers, $answersArray);
                    } elseif ($fieldType === 'Text') {
                        $returnData = self::checkAdjudicationForText($numberOfAnswers, $answersArray);
                    } elseif ($fieldType === 'Textarea') {
                        $returnData = self::checkAdjudicationForText($numberOfAnswers, $answersArray);
                    }
                }

                $isAdjudicationRequired = $returnData['isAdjudicationRequired'];
                $finalAnswer = $returnData['finalAnswer'];
            }
        }
    }

    public static function checkAdjudicationForNumber($numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        if ($numberOfAnswers == 2) {
            if ($answersArray[0] != $answersArray[1]) {
                $isAdjudicationRequired = true;
            }
        } elseif ($numberOfAnswers == 3) {
            if (
                ($answersArray[0] != $answersArray[1]) ||
                ($answersArray[0] != $answersArray[2]) ||
                ($answersArray[1] != $answersArray[2])
            ) {
                $isAdjudicationRequired = true;
            }
        } elseif ($numberOfAnswers == 4) {
            if (
                ($answersArray[0] != $answersArray[1]) ||
                ($answersArray[0] != $answersArray[2]) ||
                ($answersArray[0] != $answersArray[3]) ||
                ($answersArray[1] != $answersArray[2]) ||
                ($answersArray[1] != $answersArray[3]) ||
                ($answersArray[2] != $answersArray[3])
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = $answersArray[0];
        }
        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForText($numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        if ($numberOfAnswers == 2) {
            if ($answersArray[0] != $answersArray[1]) {
                $isAdjudicationRequired = true;
            }
        } elseif ($numberOfAnswers == 3) {
            if (
                ($answersArray[0] != $answersArray[1]) ||
                ($answersArray[0] != $answersArray[2]) ||
                ($answersArray[1] != $answersArray[2])
            ) {
                $isAdjudicationRequired = true;
            }
        } elseif ($numberOfAnswers == 4) {
            if (
                ($answersArray[0] != $answersArray[1]) ||
                ($answersArray[0] != $answersArray[2]) ||
                ($answersArray[0] != $answersArray[3]) ||
                ($answersArray[1] != $answersArray[2]) ||
                ($answersArray[1] != $answersArray[3]) ||
                ($answersArray[2] != $answersArray[3])
            ) {
                $isAdjudicationRequired = true;
            }
        }
        if ($isAdjudicationRequired === false) {
            $finalAnswer = $answersArray[0];
        }
        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForRadio($numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        if ($numberOfAnswers == 2) {
            if ($answersArray[0] != $answersArray[1]) {
                $isAdjudicationRequired = true;
            }
        } elseif (
            ($numberOfAnswers == 3) ||
            ($numberOfAnswers == 4)
        ) {
            $countedArray = array_count_values($answersArray);
            if (count($countedArray) == count($answersArray)) {
                $isAdjudicationRequired = true;
            } else {
                arsort($countedArray);
                $finalAnswer = array_keys($countedArray)[0];
            }
        }

        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
    }

    public static function checkAdjudicationForCheckBox($numberOfAnswers, $answersArray)
    {
        $isAdjudicationRequired = false;
        $finalAnswer = 0;
        if ($numberOfAnswers == 2) {
            if ($answersArray[0] != $answersArray[1]) {
                $isAdjudicationRequired = true;
            }
        } elseif (
            ($numberOfAnswers == 3) ||
            ($numberOfAnswers == 4)
        ) {
            $countedArray = array_count_values($answersArray);
            arsort($countedArray);
            $finalAnswer = array_keys($countedArray)[0];
        }

        return ['isAdjudicationRequired' => $isAdjudicationRequired, 'finalAnswer' => $finalAnswer];
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
