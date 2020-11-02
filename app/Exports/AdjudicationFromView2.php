<?php

namespace App\Exports;

use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\PhaseSteps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
//use Maatwebsite\Excel\Concerns\FromCollection;

class AdjudicationFromView2 implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return AdjudicationFormStatus::all();
    // }

    public function view(): View {

    	// get subjects
        $subjects = AdjudicationFormStatus::query();
        $subjects = $subjects->select('adjudication_form_status.subject_id as subj_id', 'adjudication_form_status.study_id', 'adjudication_form_status.study_structures_id', 'adjudication_form_status.phase_steps_id', 'adjudication_form_status.adjudication_status', 'adjudication_form_status.modility_id','subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
            ->leftJoin('subjects', 'subjects.id', '=', 'adjudication_form_status.subject_id')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'adjudication_form_status.study_structures_id')
            ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id')
            ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'adjudication_form_status.study_structures_id')
            ->groupBy(['adjudication_form_status.subject_id', 'adjudication_form_status.study_structures_id'])
            ->get();

            if (!$subjects->isEmpty()) {
            
                // get modalities
                $getModilities = AdjudicationFormStatus::query();
                $getModilities = $getModilities->select('adjudication_form_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
                ->leftJoin('modilities', 'modilities.id', '=', 'adjudication_form_status.modility_id')
                ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id')
                ->groupBy('adjudication_form_status.modility_id')
                ->orderBy('modilities.modility_name')
                ->get();

                // get form types for modality
                foreach($getModilities as $modility) {
                    
                    // get modalities as per adjudication
                    $modalitySteps['Adjudication'][] = array(
                        "step_id" => $modility->step_id,
                        "step_name" => $modility->step_name,
                        "modility_id" => $modility->modility_id,
                        "form_type" => $modility->modility_name,
                    );

                } // loop ends modility

            }// subject empty check

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach($formType as $type) {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', 2)
                                                ->first();

                            if ($step != null) {

                                 // for ajudictaion
                                $getAdjudicationFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                ];

                                $formStatus[$key.'_'.$type['form_type']] = \Modules\Admin\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, $wrap = true);

                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key

                    $subject->form_status = $formStatus;
                }// subject loop ends

            } // modality step null check

        return view('userroles::users.adjudication-list-csv', [
            'subjects' 		=> $subjects,
            'modalitySteps' => $modalitySteps
        ]);
    }
}
