<?php

namespace App\Exports;

use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\PhaseSteps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromCollection;

class QCFromView2 implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return FormStatus::all();
    // }

    public function view(): View {

        $modalitySteps = [];

    	// get subjects
        $subjects = FormStatus::query();

        $subjects = $subjects->select('form_submit_status.subject_id as subj_id', 'form_submit_status.study_id', 'form_submit_status.study_structures_id', 'form_submit_status.phase_steps_id', 'form_submit_status.form_type_id', 'form_submit_status.form_status', 'form_submit_status.modility_id','subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
            ->leftJoin('subjects', 'subjects.id', '=', 'form_submit_status.subject_id')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'form_submit_status.study_structures_id')
            ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
            ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'form_submit_status.study_structures_id')
            ->where('form_submit_status.form_type_id', 1)
            ->where('form_submit_status.study_id', \Session::get('current_study'))
            ->groupBy(['form_submit_status.subject_id', 'form_submit_status.study_structures_id'])
            ->get();

            if (!$subjects->isEmpty()) {
            // get modalities
            $getModilities = FormStatus::query();
            $getModilities = $getModilities->select('form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
            ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
            ->groupBy('form_submit_status.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

            // get form types for modality
            foreach($getModilities as $modility) {

                $getSteps = FormStatus::query();

                $getSteps = $getSteps->select('form_submit_status.form_type_id', 'form_submit_status.modility_id','phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name', 'form_types.form_type', 'form_types.sort_order')
                    ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                    ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                    ->leftJoin('form_types', 'form_types.id', '=', 'form_submit_status.form_type_id')
                    ->where('form_submit_status.modility_id', $modility->modility_id)
                    ->where('form_submit_status.form_type_id', 1)
                    ->orderBy('form_types.sort_order')
                    ->groupBy('form_submit_status.form_type_id')
                    ->get()->toArray();

                $modalitySteps[$modility->modility_name] = $getSteps;
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
                                                ->where('form_type_id', $type['form_type_id'])
                                                ->first();

                            if ($step != null) {

                                $getFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];


                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, true);

                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, true);
                                }
                            } else {

                                $formStatus[$key.'_'.$type['form_type']] = 'NoName-Not Initiated|';
                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key

                    $subject->form_status = $formStatus;
                }// subject loop ends

            } // modality step null check

        return view('userroles::users.qc-list-csv', [
            'subjects' 		=> $subjects,
            'modalitySteps' => $modalitySteps
        ]);
    }
}
