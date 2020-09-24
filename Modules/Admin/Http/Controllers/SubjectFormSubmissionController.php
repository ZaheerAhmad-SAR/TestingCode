<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Answer;
use Modules\Admin\Entities\FormStatus;

class SubjectFormSubmissionController extends Controller
{
    public function submitForm(Request $request)
    {
        $form_filled_by_user_id = auth()->user()->id;
        $form_filled_by_user_role_id = auth()->user()->id;

        $sectionId = $request->sectionId;
        $section = Section::find($sectionId);
        $questions = $section->questions;

        $answerFixedArray = [];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $request->sectionId;

        foreach ($questions as $question) {
            $form_field_name = 'field_' . $question->id;
            if ($request->has($form_field_name)) {

                $answerArray = [];
                $answerArray = $answerFixedArray;

                $answerArray['question_id'] = $question->id;
                $answerArray['field_id'] = $question->formFields->id;
                /************************** */
                $answerObj = Answer::where(function ($q) use ($answerArray) {
                    foreach ($answerArray as $key => $value) {
                        $q->where($key, 'like', $value);
                    }
                })->first();
                /************************** */
                if ($answerObj) {
                    $answerArray['answer'] = $request->{$form_field_name};
                    $answerObj->update($answerArray);
                } else {
                    $answerArray['id'] = Str::uuid();
                    $answerArray['answer'] = $request->{$form_field_name};
                    $answerObj = Answer::create($answerArray);
                }
                unset($answerArray);
            }
        }

        $getFormStatusArray = [
            'form_filled_by_user_id' => $form_filled_by_user_id,
            'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'section_id' => $request->sectionId,
        ];
        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
        if (null === $formStatusObj) {
            $formStatusObj = $this->insertFormStatus($request, $getFormStatusArray);
        } elseif ($request->has(buildSafeStr($request->sectionId, 'terms_cond_'))) {
            $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
            $formStatusObj->form_status = 'complete';
            $formStatusObj->update();
        }
        echo 'ok';
    }

    private function insertFormStatus($request, $getFormStatusArray)
    {
        $formStatusData = [
            'id' => Str::uuid(),
            'form_type_id' => $request->formTypeId,
            'form_status' => 'resumable'
        ] + $getFormStatusArray;
        return FormStatus::create($formStatusData);
    }
}
