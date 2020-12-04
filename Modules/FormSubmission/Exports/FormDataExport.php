<?php

namespace Modules\FormSubmission\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Entities\Answer;

class FormDataExport implements FromView
{
    public function __construct($request)
    {
        $this->study_id = session('current_study');
        $this->visit_ids = $request->input('visit_id', '');
        $this->modility_id = $request->input('modility_id', '');
        $this->form_type_id = $request->input('form_type_id', '');
    }

    public function view(): View
    {

        $study = Study::find($this->study_id);
        $stepIds = PhaseSteps::whereIn('phase_id', $this->visit_ids)
            ->where('form_type_id', $this->form_type_id)
            ->where('modility_id', $this->modility_id)
            ->pluck('step_id')
            ->toArray();

        $sectionIds = Section::whereIn('phase_steps_id', $stepIds)
            ->pluck('id')
            ->toArray();

        $questionIds = Question::whereIn('section_id', $sectionIds)
            ->pluck('id')
            ->toArray();
        $questionIds = FormFields::whereIn('question_id', $questionIds)
            ->where('is_exportable_to_xls', 'yes')
            ->pluck('question_id')
            ->toArray();

        $body = '';
        $questionHeader = '';

        $subjectIds = Answer::where('study_id', 'like', $this->study_id)
            ->whereIn('study_structures_id', $this->visit_ids)
            ->whereIn('phase_steps_id', $stepIds)
            ->whereIn('question_id', $questionIds)
            ->pluck('subject_id')
            ->toArray();
        $subjectIds = array_unique($subjectIds);

        foreach ($subjectIds as $subject_id) {
            $subject = Subject::find($subject_id);
            foreach ($this->visit_ids as $visit_id) {
                $visit = StudyStructure::find($visit_id);
                $steps = PhaseSteps::where('phase_id', 'like', $visit_id)
                    ->where('form_type_id', $this->form_type_id)
                    ->where('modility_id', $this->modility_id)
                    ->get();

                foreach ($steps as $step) {
                    $step = PhaseSteps::find($step->step_id);
                    $sections = Section::where('phase_steps_id', 'like', $step->step_id)->get();
                    $permanentTds = '<tr><td>' . $study->study_short_name . '</td><td>' . $subject->subject_id . '</td><td>' . $visit->name . '</td><td>' . $step->step_name . ' (' . $step->formType->form_type . ' - ' . $step->modility->modility_name . ')</td>';
                    $answerTds = '';
                    foreach ($sections as $section) {
                        $questions = Question::where('section_id', 'like', $section->id)
                            ->whereIn('id', $questionIds)
                            ->get();
                        foreach ($questions as $question) {
                            $form_filled_by_user_ids = Answer::where('study_id', 'like', $this->study_id)
                                ->where('subject_id', 'like', $subject_id)
                                ->where('study_structures_id', $visit->id)
                                ->where('phase_steps_id', $step->step_id)
                                ->where('question_id', $question->id)
                                ->pluck('form_filled_by_user_id')
                                ->toArray();

                            $form_filled_by_user_ids = array_unique($form_filled_by_user_ids);
                            $form_filled_by_user_ids = array_values($form_filled_by_user_ids);

                            foreach ($form_filled_by_user_ids as $form_filled_by_user_id) {
                                $questionHeader .= '<th>' . $question->formFields->variable_name . '</th>';
                                $tdAnswer = '';
                                $answer = Answer::where('study_id', 'like', $this->study_id)
                                    ->where('subject_id', 'like', $subject_id)
                                    ->where('study_structures_id', 'like', $visit->id)
                                    ->where('phase_steps_id', 'like', $step->step_id)
                                    ->where('question_id', 'like', $question->id)
                                    ->where('form_filled_by_user_id', 'like', $form_filled_by_user_id)
                                    ->first();
                                $answerArray[] = $answer;
                                if (null !== $answer) {
                                    $tdAnswer = $answer->answer;
                                }
                                $answerTds .= '<td>' . $tdAnswer . '</td>';
                            }
                        }
                    }
                }
            }
            $body .= $permanentTds . $answerTds;
        }

        $header = '<tr><th>Study</th><th>Subject</th><th>Visit</th><th>Step</th>' . $questionHeader . '</tr>';

        return view('formsubmission::exports.export_view', [
            'header' => $header,
            'body' => $body
        ]);
    }

    public function view123(): View
    {
        $study = Study::find($this->study_id);
        $visits = StudyStructure::find($this->visit_ids);
        $step = PhaseSteps::find($this->step_id);

        $sectionIds = Section::where('phase_steps_id', 'like', $this->step_id)->pluck('id')->toArray();
        $questionIds = Question::whereIn('section_id', $sectionIds)->pluck('id')->toArray();
        $questionIds = FormFields::whereIn('question_id', $questionIds)->where('is_exportable_to_xls', 'yes')->pluck('question_id')->toArray();
        $questions = Question::whereIn('id', $questionIds)->get();

        $questionHeader = '';
        foreach ($questions as $question) {
            $questionHeader .= '<th>' . $question->question_text . '</th>';
        }
        $header = '<tr>
                    <th>Study</th>
                    <th>Subject</th>
                    <th>Visit</th>
                    <th>Step</th>
                    ' . $questionHeader . '
                </tr>';


        $body = '';
        $subjectIds = Answer::where('study_id', 'like', $this->study_id)
            ->where('study_structures_id', 'like', $this->visit_ids)
            ->where('phase_steps_id', 'like', $this->step_id)
            ->pluck('subject_id')
            ->toArray();
        $subjectIds = array_unique($subjectIds);

        foreach ($subjectIds as $subject_id) {
            $subject = Subject::find($subject_id);
            $permanentTds = '<tr><td>' . $study->study_short_name . '</td><td>' . $subject->subject_id . '</td><td>' . $visits->name . '</td><td>' . $step->step_name . ' (' . $step->formType->form_type . ' - ' . $step->modility->modility_name . ')</td>';
            $answerTds = '';
            foreach ($questions as $question) {
                $answer = Answer::where('study_id', 'like', $this->study_id)
                    ->where('subject_id', 'like', $subject_id)
                    ->where('study_structures_id', 'like', $this->visit_ids)
                    ->where('phase_steps_id', 'like', $this->step_id)
                    ->where('question_id', 'like', $question->id)
                    ->first();
                if (null !== $answer) {
                    $tdAnswer = $answer->answer;
                } else {
                    $tdAnswer = '';
                }
                $answerTds .= '<td>' . $tdAnswer . '</td>';
            }
            $body .= $permanentTds . $answerTds;
        }

        return view('formsubmission::exports.export_view', [
            'header' => $header,
            'body' => $body
        ]);
    }
}
