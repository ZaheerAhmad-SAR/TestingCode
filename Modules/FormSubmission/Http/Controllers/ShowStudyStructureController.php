<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;

class ShowStudyStructureController extends Controller
{
    public function showStructure($type, $id)
    {
        if ($type == 'question') {
            $question = Question::find($id);
            $section = Section::find($question->section_id);
            $step = PhaseSteps::find($section->phase_steps_id);
            $phase = StudyStructure::find($step->phase_id);

            dd($question, $section, $step, $phase);
        }

        if ($type == 'section') {
            $section = Section::find($id);
            $step = PhaseSteps::find($section->phase_steps_id);
            $phase = StudyStructure::find($step->phase_id);

            dd($section, $step, $phase);
        }

        if ($type == 'step') {
            $step = PhaseSteps::find($id);
            $phase = StudyStructure::find($step->phase_id);

            dd($step, $phase);
        }

        if ($type == 'phase') {
            $phase = StudyStructure::find($id);

            dd($phase);
        }
    }
}
