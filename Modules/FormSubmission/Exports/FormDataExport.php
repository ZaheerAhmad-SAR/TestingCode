<?php

namespace Modules\FormSubmission\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\SubjectsPhases;

class FormDataExport implements FromView
{
    public function __construct($request)
    {
        $this->study_id = session('current_study');
        $this->visit_ids = arrayFilter(explode(',', $request->input('visit_ids', [])));
        $this->modility_id = $request->input('modility_id', '');
        $this->form_type_id = $request->input('form_type_id', '');
        $this->print_options_values = $request->input('print_options_values', 'option_values');
    }

    public function view(): View
    {
        $header = [
            'study' => 'Study',
            'cohort' => 'Cohort',
            'site_id' => 'Site ID',
            'site_name' => 'Site Name',
            'site_code' => 'Site Code',
            'subject_id' => 'Subject',
            'study_eye' => 'Study EYE',
            'visit' => 'Visit',
            'visit_date' => 'Visit Date',
            'step' => 'Step',
        ];

        if($this->form_type_id == 1) {

            $study = Study::find($this->study_id);

            $stepIds = PhaseSteps::whereIn('phase_id', $this->visit_ids)
                ->where('form_type_id', $this->form_type_id)
                ->where('modility_id', $this->modility_id)
                ->pluck('step_id')
                ->toArray();

            $maxNumberOfGraders = PhaseSteps::whereIn('phase_id', $this->visit_ids)
                ->where('form_type_id', $this->form_type_id)
                ->where('modility_id', $this->modility_id)
                ->pluck('graders_number')
                ->toArray();

            $maxNumberOfGraders = $maxNumberOfGraders != null ? max($maxNumberOfGraders) : 1;

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

            $subjectIds = Answer::where('study_id', 'like', $this->study_id)
                ->whereIn('study_structures_id', $this->visit_ids)
                ->whereIn('phase_steps_id', $stepIds)
                ->whereIn('question_id', $questionIds)
                ->pluck('subject_id')
                ->toArray();
            $subjectIds = array_unique($subjectIds);

            $body = [];
            foreach ($subjectIds as $subject_id) {
                $subject = Subject::find($subject_id);
                if($subject!=null){
                $site = Site::find($subject->site_id);
                $studySite = StudySite::where('study_id', $study->id)->where('site_id', $site->id)->firstOrNew();

                foreach ($this->visit_ids as $visit_id) {
                    $visit = StudyStructure::find($visit_id);
                    $subjectVisit = SubjectsPhases::where('phase_id', 'like', $visit_id)->where('subject_id', 'like', $subject_id)->first();
                    $steps = PhaseSteps::where('phase_id', 'like', $visit_id)
                        ->where('form_type_id', $this->form_type_id)
                        ->where('modility_id', $this->modility_id)
                        ->get();
                    if($subjectVisit!=null){
                    foreach ($steps as $step) {
                        $step = PhaseSteps::find($step->step_id);
                        $sections = Section::where('phase_steps_id', 'like', $step->step_id)->get();
                        
                        $permanentTds = [
                            'study' => $study->study_short_name,
                            'cohort' => Subject::getDiseaseCohort($subject),
                            'site_id' => $studySite->study_site_id,
                            'site_name' => $site->site_name,
                            'site_code' => $site->site_code,
                            'subject_id' => $subject->subject_id,
                            'study_eye' => $subject->study_eye,
                            'visit' => $visit->name,
                            'visit_date' => $subjectVisit->visit_date->format('m-d-Y'),
                            'step' => $step->step_name . ' (' . $step->formType->form_type . ' - ' . $step->modility->modility_name . ')',
                        ];

                        $answerTds = [];

                        $answerabc = [];
                        
                        foreach ($sections as $section) {
                            $questions = Question::where('section_id', 'like', $section->id)
                                ->whereIn('id', $questionIds)
                                ->get();

                            foreach ($questions as $question) {
                                // print_r($question);
                                // echo "<br>";
                                $variableName = $question->formFields->variable_name;
                                $form_filled_by_user_ids = Answer::where('study_id', 'like', $this->study_id)
                                    ->where('subject_id', 'like', $subject_id)
                                    ->where('study_structures_id', $visit->id)
                                    ->where('phase_steps_id', $step->step_id)
                                    ->where('question_id', $question->id)
                                    ->pluck('form_filled_by_user_id')
                                    ->toArray();
    // print_r("study_id".$this->study_id);
    // print_r("subject_id".$subject_id);
    // print_r("visit_id".$visit->id);
    // print_r("stepy_id".$step->step_id);
    // print_r("question_id".$question->id);
    // echo "<br>";
                                if($form_filled_by_user_ids!=null)
                                {
                                $form_filled_by_user_ids = array_unique($form_filled_by_user_ids);
                                $form_filled_by_user_ids = array_values($form_filled_by_user_ids);
    //print_r($form_filled_by_user_ids); 
                                for ($counter = 0; $counter < $maxNumberOfGraders; ++$counter) {
                                    $headerName = ($step->formType->form_type == 'QC') ? $variableName : $variableName . '_G' . ($counter + 1);
                                    if (!in_array($headerName, $header)) {
                                        $header[$headerName] = $headerName;
                                    }
                                    if(isset($form_filled_by_user_ids[$counter]))
                                    {
                                    $answerVal = '';
                                    $answer = Answer::where('study_id', 'like', $this->study_id)
                                        ->where('subject_id', 'like', $subject_id)
                                        ->where('study_structures_id', 'like', $visit->id)
                                        ->where('phase_steps_id', 'like', $step->step_id)
                                        ->where('variable_name', 'like', $variableName)
                                        ->where('form_filled_by_user_id', 'like', $form_filled_by_user_ids[$counter])
                                        ->first();

                                    if (null !== $answer) {
                                        $answerVal = $answer->answer;
                                        $fieldType = $question->form_field_type->field_type;

                                        if (
                                            ($this->print_options_values == 'Option Titles') &&
                                            (
                                                ($fieldType == 'Radio') ||
                                                ($fieldType == 'Checkbox') ||
                                                ($fieldType == 'Dropdown'))
                                        ) {
                                            $option_names = [];
                                            $option_values = [];
                                            $optionGroup = $question->optionGroup;
                                           
                                            if (!empty($optionGroup->option_value)) {
                                                $option_values = arrayFilter(explode(',', $optionGroup->option_value));
                                                $option_names = arrayFilter(explode(',', $optionGroup->option_name));
                                                $options = array_combine($option_values, $option_names);
                                               // print_r($options);
                                                $searchForValue = ',';
                                              
                                               if( strpos($answer->answer, $searchForValue) !== false ) {
                                            
                                                $answerVals_exploded =explode(",",$answer->answer);
                                              
                                                foreach($answerVals_exploded as $answerVal_exploded)
                                                {
                                                    $answerVal =$options[$answerVal_exploded];
                                                }
                                               }
                                               else{
                                                 $answerVal = $options[$answer->answer];
                                               }
                                                //  $options = 'abc';
                                                // $answerVal = 'xyz';
                                            }
                                        }
                                    }
                                    $answerTds[$headerName] = htmlentities($answerVal);
                                }
                            }
                        }
                        }
                    }
                    }
                    }
                    $body[] = $permanentTds + $answerTds;
                }
            }
            } // foreach loop ends
    } else {

        $study = Study::find($this->study_id);
            $stepIds = PhaseSteps::whereIn('phase_id', $this->visit_ids)
                ->where('form_type_id', $this->form_type_id)
                ->where('modility_id', $this->modility_id)
                ->pluck('step_id')
                ->toArray();

            $maxNumberOfGraders = max(PhaseSteps::whereIn('phase_id', $this->visit_ids)
                ->where('form_type_id', $this->form_type_id)
                ->where('modility_id', $this->modility_id)
                ->pluck('graders_number')
                ->toArray());

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

            $subjectIds = FinalAnswer::where('study_id', 'like', $this->study_id)
                ->whereIn('study_structures_id', $this->visit_ids)
                ->whereIn('phase_steps_id', $stepIds)
                ->whereIn('question_id', $questionIds)
                ->pluck('subject_id')
                ->toArray();
            $subjectIds = array_unique($subjectIds);

            $body = [];
            foreach ($subjectIds as $subject_id) {
                $subject = Subject::find($subject_id);
                if($subject!=null){
                $site = Site::find($subject->site_id);
                $studySite = StudySite::where('study_id', $study->id)->where('site_id', $site->id)->firstOrNew();

                foreach ($this->visit_ids as $visit_id) {
                    $visit = StudyStructure::find($visit_id);
                    $subjectVisit = SubjectsPhases::where('phase_id', 'like', $visit_id)->where('subject_id', 'like', $subject_id)->first();
                    $steps = PhaseSteps::where('phase_id', 'like', $visit_id)
                        ->where('form_type_id', $this->form_type_id)
                        ->where('modility_id', $this->modility_id)
                        ->get();
                    if($subjectVisit!=null){
                    foreach ($steps as $step) {
                        $step = PhaseSteps::find($step->step_id);
                        $sections = Section::where('phase_steps_id', 'like', $step->step_id)->get();
                        
                        $permanentTds = [
                            'study' => $study->study_short_name,
                            'cohort' => Subject::getDiseaseCohort($subject),
                            'site_id' => $studySite->study_site_id,
                            'site_name' => $site->site_name,
                            'site_code' => $site->site_code,
                            'subject_id' => $subject->subject_id,
                            'study_eye' => $subject->study_eye,
                            'visit' => $visit->name,
                            'visit_date' => $subjectVisit->visit_date->format('m-d-Y'),
                            'step' => $step->step_name . ' (' . $step->formType->form_type . ' - ' . $step->modility->modility_name . ')',
                        ];

                        $answerTds = [];

                        $answerabc = [];
                        
                        foreach ($sections as $section) {
                            $questions = Question::where('section_id', 'like', $section->id)
                                ->whereIn('id', $questionIds)
                                ->get();

                            foreach ($questions as $question) {
                                // print_r($question);
                                // echo "<br>";
                                $variableName = $question->formFields->variable_name;
                                // $form_filled_by_user_ids = Answer::where('study_id', 'like', $this->study_id)
                                //     ->where('subject_id', 'like', $subject_id)
                                //     ->where('study_structures_id', $visit->id)
                                //     ->where('phase_steps_id', $step->step_id)
                                //     ->where('question_id', $question->id)
                                //     ->pluck('form_filled_by_user_id')
                                //     ->toArray();
    // print_r("study_id".$this->study_id);
    // print_r("subject_id".$subject_id);
    // print_r("visit_id".$visit->id);
    // print_r("stepy_id".$step->step_id);
    // print_r("question_id".$question->id);
    // echo "<br>";
                                // if($form_filled_by_user_ids!=null)
                                // {
                                // $form_filled_by_user_ids = array_unique($form_filled_by_user_ids);
                                // $form_filled_by_user_ids = array_values($form_filled_by_user_ids);
    //print_r($form_filled_by_user_ids); 
                                for ($counter = 0; $counter < $maxNumberOfGraders; ++$counter) {
                                    $headerName = ($step->formType->form_type == 'QC') ? $variableName : $variableName . '_G' . ($counter + 1);
                                    if (!in_array($headerName, $header)) {
                                        $header[$headerName] = $headerName;
                                    }
                                    // if(isset($form_filled_by_user_ids[$counter]))
                                    // {
                                    $answerVal = '';
                                    $answer = FinalAnswer::where('study_id', 'like', $this->study_id)
                                        ->where('subject_id', 'like', $subject_id)
                                        ->where('study_structures_id', 'like', $visit->id)
                                        ->where('phase_steps_id', 'like', $step->step_id)
                                        ->where('variable_name', 'like', $variableName)
                                        ->first();

                                    if (null !== $answer) {
                                        $answerVal = $answer->answer;
                                        $fieldType = $question->form_field_type->field_type;

                                        if (
                                            ($this->print_options_values == 'Option Titles') &&
                                            (
                                                ($fieldType == 'Radio') ||
                                                ($fieldType == 'Checkbox') ||
                                                ($fieldType == 'Dropdown'))
                                        ) {
                                            $option_names = [];
                                            $option_values = [];
                                            $optionGroup = $question->optionGroup;
                                           
                                            if (!empty($optionGroup->option_value)) {
                                                $option_values = arrayFilter(explode(',', $optionGroup->option_value));
                                                $option_names = arrayFilter(explode(',', $optionGroup->option_name));
                                                $options = array_combine($option_values, $option_names);
                                               // print_r($options);
                                                $searchForValue = ',';
                                              
                                               if( strpos($answer->answer, $searchForValue) !== false ) {
                                            
                                                $answerVals_exploded =explode(",",$answer->answer);
                                              
                                                foreach($answerVals_exploded as $answerVal_exploded)
                                                {
                                                    $answerVal =$options[$answerVal_exploded];
                                                }
                                               }
                                               else{
                                                 $answerVal = isset($options[$answer->answer]) ? $options[$answer->answer] : '';
                                               }
                                                //  $options = 'abc';
                                                // $answerVal = 'xyz';
                                            }
                                        }
                                    }
                                    $answerTds[$headerName] = htmlentities($answerVal);
                                }
                            }
                        // }
                        // }
                    }
                    }
                    }
                    $body[] = $permanentTds + $answerTds;
                }
            }
            } // foreach loop ends

    } // form type check ends

        return view('formsubmission::exports.export_view', [
            'header' => $header,
            'body' => $body,
        ]);
    }
}
