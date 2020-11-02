<?php

namespace App\Exports;

use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\PhaseSteps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromCollection;

//class GradingFromView implements FromCollection
class GradingFromView implements FromView
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
                                        ->orderBy('form_types.sort_order')
                                        ->groupBy('phase_steps.form_type_id')
                                        ->get();

                $modalitySteps[$modility->modility_name] = $getSteps;
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

                                $getFormStatusArray = [
                                    'subject_id' => $subject['id'],
                                    'study_structures_id' => $subject['phase_id'],
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];


                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, false, false);
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, false, false);
                                }


                        } // step lopp ends
                      
                    } // modality loop ends
                    // assign the array to the key
                    $subject->form_status = $formStatus;
                }// subject loop ends
            } // modality step null check

            //dd($subject);

        return view('userroles::users.grading-list-csv', [
            'subjects' 		=> $subjects,
            'modalitySteps' => $modalitySteps
        ]);
    }
}
