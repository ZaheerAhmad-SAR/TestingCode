<?php

namespace App\Exports;

use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\PhaseSteps;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
//use Maatwebsite\Excel\Concerns\FromCollection;

class GradingStatusFromView implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return Subject::all();
    // }

    public function view(): View
    {

        $modalitySteps = [];

        $subjects = Subject::query();

        $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'sites.site_name')
            ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
            ->leftJoin('sites', 'sites.id', 'subjects.site_id')
            ->where('subjects.study_id', \Session::get('current_study'))
            ->orderBy('subjects.subject_id')
            ->orderBy('study_structures.position')
            ->get();

        // get modalities
        $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'modilities.id as modility_id', 'modilities.modility_name')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

        // adjudication array
        $adjudicationArray = [];

        // get form types for modality
        foreach ($getModilities as $key => $modility) {

            $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                ->where('modility_id', $modility->modility_id)
                ->where('phase_steps.form_type_id', '!=', 3)
                ->orderBy('form_types.sort_order')
                ->groupBy('phase_steps.form_type_id')
                ->get()->toArray();

            $modalitySteps[$modility->modility_name] = $getSteps;

            // get modalities as per adjudication
            $adjudicationArray[] = array(
                "step_id" => $modility->step_id,
                "step_name" => $modility->step_name,
                "modility_id" => $modility->modility_id,
                "form_type" => $modility->modility_name,
            );
        }

        $modalitySteps['Adjudication'] = $adjudicationArray;

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
                                    'subject_id' => $subject->id,
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

                                $formStatus[$key . '_' . $type['form_type']] = 'NoName-Not Initiated|';
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

                                $formStatus[$key . '_' . $type['form_type']] = 'NoName-Not Initiated|';
                            }
                        } // ADJUDICATION CHECK ENDS

                    } // step lopp ends

                } // modality loop ends
                // assign the array to the key
                $subject->form_status = $formStatus;
            } // subject loop ends
        } // modality step null check

        return view('userroles::users.adjudication-list-csv', [
            'subjects'         => $subjects,
            'modalitySteps' => $modalitySteps
        ]);
    }
}
