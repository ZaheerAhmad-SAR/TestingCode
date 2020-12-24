<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\QuestionComments;

class QuestionCommentController extends Controller
{
    public function loadQuestionCommentPopup(Request $request)
    {
        $getQuestionCommentsArray = [
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'section_id' => $request->sectionId,
            'question_id' => $request->questionId,
        ];
        $questionComments = QuestionComments::getQuestionComments($getQuestionCommentsArray);

        echo view('formsubmission::question_comments.questionCommentsListing')
            ->with('questionComments', $questionComments)
            ->with('subjectId', $request->subjectId)
            ->with('studyId', $request->studyId)
            ->with('phaseId', $request->phaseId)
            ->with('stepId', $request->stepId)
            ->with('sectionId', $request->sectionId)
            ->with('questionId', $request->questionId);
    }

    public function loadAddQuestionCommentForm(Request $request)
    {
        echo view('formsubmission::question_comments.addQuestionCommentForm')
            ->with('subjectId', $request->subjectId)
            ->with('studyId', $request->studyId)
            ->with('phaseId', $request->phaseId)
            ->with('stepId', $request->stepId)
            ->with('sectionId', $request->sectionId)
            ->with('questionId', $request->questionId);
    }

    public function submitAddQuestionCommentForm(Request $request)
    {
        $questionComment = new QuestionComments();
        $questionComment->id = (string)Str::uuid();
        $questionComment->comment_by_id = $request->commentById;
        $questionComment->study_id = $request->studyId;
        $questionComment->subject_id = $request->subjectId;
        $questionComment->study_structures_id = $request->phaseId;
        $questionComment->phase_steps_id = $request->stepId;
        $questionComment->section_id = $request->sectionId;
        $questionComment->question_id = $request->questionId;
        $questionComment->question_comment = $request->question_comment;
        $questionComment->save();
        echo 'comment saved';
    }
}
