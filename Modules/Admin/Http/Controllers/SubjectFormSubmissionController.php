<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Answer;

class SubjectFormSubmissionController extends Controller
{
    public function submitForm(Request $request)
    {
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
                $answerArray['answer'] = $request->{'field_'.$question->id};
                $answerObj->update($answerArray);
            } else {
                $answerArray['id'] = Str::uuid();
                $answerArray['answer'] = $request->{'field_'.$question->id};
                $answerObj = Answer::create($answerArray);
            }
            unset($answerArray);
        }
    }
}
