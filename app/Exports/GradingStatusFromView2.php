<?php

namespace App\Exports;

use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\PhaseSteps;
use Modules\FormSubmission\Entities\FormStatus;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
//use Maatwebsite\Excel\Concerns\FromCollection;

class GradingStatusFromView2 implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return AdjudicationFormStatus::all();
    // }

    public function view(): View {

        //$modalitySteps = [];

    	// // get subjects
     //    $subjects = AdjudicationFormStatus::query();
     //    $subjects = $subjects->select('adjudication_form_status.subject_id as subj_id', 'adjudication_form_status.study_id', 'adjudication_form_status.study_structures_id', 'adjudication_form_status.phase_steps_id', 'adjudication_form_status.adjudication_status', 'adjudication_form_status.modility_id','subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
     //        ->leftJoin('subjects', 'subjects.id', '=', 'adjudication_form_status.subject_id')
     //        ->leftJoin('study_structures', 'study_structures.id', '=', 'adjudication_form_status.study_structures_id')
     //        ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
     //        ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id')
     //        ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'adjudication_form_status.study_structures_id')
     //        ->whereNULL('subjects.deleted_at')
     //        ->whereNULL('study_structures.deleted_at')
     //        ->whereNULL('sites.deleted_at')
     //        ->whereNULL('phase_steps.deleted_at')
     //        ->whereNULL('subjects_phases.deleted_at')
     //        ->where('adjudication_form_status.study_id', \Session::get('current_study'))
     //        ->groupBy(['adjudication_form_status.subject_id', 'adjudication_form_status.study_structures_id'])
     //        ->get();

     //        if (!$subjects->isEmpty()) {
            
     //            // get modalities
     //            $getModilities = AdjudicationFormStatus::query();
     //            $getModilities = $getModilities->select('adjudication_form_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
     //            ->leftJoin('modilities', 'modilities.id', '=', 'adjudication_form_status.modility_id')
     //            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id')
     //            ->whereNULL('modilities.deleted_at')
     //            ->whereNULL('phase_steps.deleted_at')
     //            ->groupBy('adjudication_form_status.modility_id')
     //            ->orderBy('modilities.modility_name')
     //            ->get();

     //            // get form types for modality
     //            foreach($getModilities as $modility) {
                    
     //                // get modalities as per adjudication
     //                $modalitySteps['Adjudication'][] = array(
     //                    "step_id" => $modility->step_id,
     //                    "step_name" => $modility->step_name,
     //                    "modility_id" => $modility->modility_id,
     //                    "form_type" => $modility->modility_name,
     //                );

     //            } // loop ends modility

     //        }// subject empty check

     //        //get form status depending upon subject, phase and modality
            // if ($modalitySteps != null) {
            //     foreach($subjects as $subject) {
            //         //get status
            //         $formStatus = [];

            //         // modality loop
            //         foreach($modalitySteps as $key => $formType) {

            //             // form type loop
            //             foreach($formType as $type) {

            //                 $step = PhaseSteps::where('phase_id', $subject->phase_id)
            //                                     ->where('modility_id', $type['modility_id'])
            //                                     ->where('form_type_id', 2)
            //                                     ->first();

            //                 if ($step != null) {

            //                      // for ajudictaion
            //                     $getAdjudicationFormStatusArray = [
            //                         'subject_id' => $subject->subj_id,
            //                         'study_structures_id' => $subject->phase_id,
            //                         'modility_id'=> $type['modility_id'],
            //                     ];

            //                     $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, true, true);

            //                 } else {

            //                     $formStatus[$key.'_'.$type['form_type']] = ' - ';

            //                 }// step check ends

            //             } // step lopp ends

            //         } // modality loop ends
            //         // assign the array to the key

            //         $subject->form_status = $formStatus;
            //     }// subject loop ends

            // } // modality step null check

        $subjects = FormStatus::query();
        $subjects = $subjects->select('form_submit_status.subject_id as subj_id', 'form_submit_status.study_id', 'form_submit_status.study_structures_id', 'form_submit_status.phase_steps_id', 'form_submit_status.form_type_id', 'form_submit_status.form_status', 'form_submit_status.modility_id', 'subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
            ->leftJoin('subjects', 'subjects.id', '=', 'form_submit_status.subject_id')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'form_submit_status.study_structures_id')
            ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
            ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'form_submit_status.study_structures_id')
            ->whereNULL('subjects.deleted_at')
            ->whereNULL('study_structures.deleted_at')
            ->whereNULL('sites.deleted_at')
            ->whereNULL('phase_steps.deleted_at')
            ->whereNULL('subjects_phases.deleted_at')
            ->where('form_submit_status.study_id', \Session::get('current_study'))
            ->groupBy(['form_submit_status.subject_id', 'form_submit_status.study_structures_id'])
            ->get();

            if (!$subjects->isEmpty()) {

                // get modalities
                $getModilities = FormStatus::query();
                $getModilities = $getModilities->select('form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
                    ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                    ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                    ->whereNULL('modilities.deleted_at')
                    ->whereNULL('phase_steps.deleted_at')
                    ->groupBy('form_submit_status.modility_id')
                    ->orderBy('modilities.modility_name')
                    ->get();

                $adjudicationArray = [];
                // get form types for modality
                foreach ($getModilities as $modility) {

                    $getSteps = FormStatus::query();

                    $getSteps = $getSteps->select('form_submit_status.form_type_id', 'form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name', 'form_types.form_type', 'form_types.sort_order')
                        ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                        ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                        ->leftJoin('form_types', 'form_types.id', '=', 'form_submit_status.form_type_id')
                        ->where('form_submit_status.modility_id', $modility->modility_id)
                        ->whereNULL('modilities.deleted_at')
                        ->whereNULL('phase_steps.deleted_at')
                        ->whereNULL('form_types.deleted_at')
                        ->orderBy('form_types.sort_order')
                        ->groupBy('form_submit_status.form_type_id')
                        ->get()->toArray();

                    if($getSteps != null) {

                        $modalitySteps[$modility->modility_name] = $getSteps;

                        //get modalities as per adjudication
                        $adjudicationArray[] = array(
                            "step_id" => $modility->step_id,
                            "step_name" => $modility->step_name,
                            "modility_id" => $modility->modility_id,
                            "form_type" => $modility->modility_name,
                        );
                    }

                } // loop ends modility

                // if array is not null assign it to modalitySteps
                if ($adjudicationArray != null) {
                    $modalitySteps['Adjudication'] = $adjudicationArray;
                }

            } // subject empty check

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach ($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach ($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach ($formType as $type) {

                        if ($key != 'Adjudication') {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                ->where('modility_id', $type['modility_id'])
                                ->where('form_type_id', $type['form_type_id'])
                                ->first();

                            if ($step != null) {

                                $getFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id' => $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];


                                if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, true);
                                } else {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, true);
                                }
                            } else {

                                $formStatus[$key.'_'.$type['form_type']] = ' - ';

                            } // step null check ends

                        } else {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                    ->where('modility_id', $type['modility_id'])
                                    ->where('form_type_id', 2)
                                    ->first();

                                if ($step != null) {

                                    // for ajudictaion
                                    $getAdjudicationFormStatusArray = [
                                        'subject_id' => $subject->id,
                                        'study_structures_id' => $subject->phase_id,
                                        'modility_id' => $type['modility_id'],
                                    ];

                                    $formStatus[$key . '_' . $type['form_type']] = \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, true, true);
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] = ' - '; 
                                }

                        } // adjudication check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key

                    $subject->form_status = $formStatus;
                } // subject loop ends

            } // modality step null check

        return view('userroles::users.adjudication-list-csv', [
            'subjects' 		=> $subjects,
            'modalitySteps' => $modalitySteps
        ]);
    }
}
