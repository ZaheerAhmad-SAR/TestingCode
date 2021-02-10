<?php

namespace App\Console\Commands;

use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\ProgressbarStudy;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Illuminate\Console\Command;

class StudyProgressBar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:StudyProgressBar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the progress bar for study page.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get all studies
        $getAllStudies = Study::all();

        // loop study
        foreach($getAllStudies as $study) {

            // study ID
            $studyId = $study->id;

            $qc_percentage = 0;
            $grading_percentage = 0;
            $adjudication_percentage = 0;

            // get all phases for a study
            $studyPhasesIdsArray = StudyStructure::getStudyPhaseIdsArray($studyId);
            // get only activated unique phases for subject
            $activatedPhasesidsArray = array_unique(SubjectsPhases::getActivatedPhasesidsArray($studyPhasesIdsArray));
            // get activated unique subjects for the above get phase array
            $subjectIdsFromActivatedPhasesIdsArray = array_unique(SubjectsPhases::getSubjectIdsFromActivatedPhasesidsArray($activatedPhasesidsArray));

            /****************** Form Percentage **********************************/

            // total steps
            $totalQcSteps = 0;
            // completed steps
            $completedQcSteps = 0;

            // total steps
            $totalGradingSteps = 0;
            // completed steps
            $completedGradingSteps = 0;

            // total steps
            $totalAdjusictaionSteps = 0;
            // completed steps
            $completedAdjudicationSteps = 0;

            // look for each subject
            foreach($subjectIdsFromActivatedPhasesIdsArray as $subject) {
                // look for each phase
                foreach($activatedPhasesidsArray as $phase) {

                    /******************************** QC Modality ****************************/

                    // get unique modality ids for activated phases where form is QC
                    $qcModalityIdsArray = array_unique(PhaseSteps::getStepsIdsArray(1, $phase));

                    // look for each modality
                    foreach($qcModalityIdsArray as $qcmodality) {

                        $getStepCompleteStatus = PhaseSteps::getStepStatus($studyId, $subject, $phase, $qcmodality, 'complete', 1, 'Qc');

                        if($getStepCompleteStatus == true) {
                            $completedQcSteps++;
                        }
                        
                        $totalQcSteps++;
                    } // qcmodality

                    /******************************** Grading Modality ****************************/

                    // get unique modality ids for activated phases where form is QC
                    $gradingModalityIdsArray = array_unique(PhaseSteps::getStepsIdsArray(2, $phase));

                    // look for each modality
                    foreach($gradingModalityIdsArray as $gradingmodality) {

                        $getStepCompleteStatus = PhaseSteps::getStepStatus($studyId, $subject, $phase, $gradingmodality, 'complete', 2, 'Grading');

                        if($getStepCompleteStatus == true) {
                            $completedGradingSteps++;
                        }
                        
                        $totalGradingSteps++;
                    } // gradingmodality

                    /******************************** Adjudictaion Modality ****************************/

                    // get unique modality ids for activated phases where form is QC
                    $adjudicationModalityIdsArray = array_unique(PhaseSteps::getStepsIdsArray(2, $phase));
                    // look for each modality
                    foreach($adjudicationModalityIdsArray as $adjudicationmodality) {

                        // get grader numbers
                        $getGraderNumber = PhaseSteps::where('phase_id', $phase)
                                                    ->where('modility_id', $adjudicationmodality)
                                                    ->where('form_type_id', 2)
                                                    ->first();
                        // skip the iteration
                        if($getGraderNumber->graders_number < 2) {
                            continue;
                        }

                        $getStepCompleteStatus = PhaseSteps::getStepStatus($studyId, $subject, $phase, $adjudicationmodality, 'complete', 2, 'Adjudication');

                        if($getStepCompleteStatus == true) {
                            $completedAdjudicationSteps++;
                        }
                        
                        $totalAdjusictaionSteps++;
                    } // adjudicationmodality

                } // phase

            } // subject

            if($totalQcSteps > 0 && $completedQcSteps > 0) {
                $qc_percentage = round($completedQcSteps / $totalQcSteps * 100);
            }

            if($totalGradingSteps > 0 && $completedGradingSteps > 0) {
                $grading_percentage = round($completedGradingSteps / $totalGradingSteps * 100);
            }

             if($totalAdjusictaionSteps > 0 && $completedAdjudicationSteps > 0) {
                $adjudication_percentage = round($completedAdjudicationSteps / $totalAdjusictaionSteps * 100);
            }

            /********************* Form Percentage Ends *******************************/

            // save data
            $progressbar = ProgressBarStudy::where('study_id', $studyId)->first();

            if ($progressbar == null) {
               $progressbar = new ProgressBarStudy; 
            }
            $progressbar->study_id = $studyId;
            $progressbar->qc_percentage = $qc_percentage;
            $progressbar->grading_percentage = $grading_percentage;
            $progressbar->adjudication_percentage = $adjudication_percentage;
            $progressbar->save();

        } // study loop ends

        $this->info('Study Progress Bar updated.');
    }
}
