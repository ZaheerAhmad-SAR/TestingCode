<?php

namespace App\Exports;

use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\PhaseSteps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromCollection;

class AdjudicationFromView implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Subject::all();
    // }

    public function view(): View {

    	$subjects = Subject::query();

        $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'sites.site_name')
        ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
        ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
        ->leftJoin('sites', 'sites.id', 'subjects.site_id');
        //->leftJoin('form_submit_status', 'form_submit_status.subject_id', 'subjects.id');
        $subjects = $subjects->orderBy('subjects.subject_id')
        ->orderBy('study_structures.position')
        ->get();

            // get modalities
            $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name','modilities.id as modility_id', 'modilities.modility_name')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

            // get form types for modality
            foreach($getModilities as $key => $modility) {

                $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                                        ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                                        ->where('modility_id', $modility->modility_id)
                                        ->where('phase_steps.form_type_id', '!=', 3)
                                        ->orderBy('form_types.sort_order')
                                        ->groupBy('phase_steps.form_type_id')
                                        ->get()->toArray();

                $modalitySteps[$modility->modility_name] = $getSteps;

                // get modalities as per adjudication
                $modalitySteps['Adjudication'][] = array(
                    "step_id" => $modility->step_id,
                    "step_name" => $modility->step_name,
                    "modility_id" => $modility->modility_id,
                    "form_type" => $modility->modility_name,
                );

            }

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach($formType as $type) {

                            $step = PhaseSteps::where('step_id', $type['step_id'])->first();

                            if ($key != 'Adjudication') {

                                $getFormStatusArray = [
                                    'subject_id' => $subject->id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];


                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']] =  1;
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] =  2;
                                }

                            } else {

                                // for ajudictaion
                                $getAdjudicationFormStatusArray = [
                                    'subject_id' => $subject->id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                ];

                                $formStatus[$key.'_'.$type['form_type']] = 3;
                            }

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
