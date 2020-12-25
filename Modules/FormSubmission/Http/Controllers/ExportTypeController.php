<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\ExportType;

class ExportTypeController extends Controller
{
    public function loadExportTypes(Request $request)
    {
        $exportTypes = ExportType::where('study_id', 'like', session('current_study'))->get();

        echo view('formsubmission::exports.exportTypeList')
            ->with('exportTypes', $exportTypes);
    }

    public function loadAddExportTypeForm(Request $request)
    {
        $phase_ids = Answer::where('study_id', 'like', session('current_study'))->pluck('study_structures_id')->toArray();
        $phases = StudyStructure::whereIn('id', $phase_ids)->get();
        $formTypes = FormType::all();
        $modalities = Modility::all();
        echo view('formsubmission::exports.add_export_type_form')
            ->with('phases', $phases)
            ->with('formTypes', $formTypes)
            ->with('modalities', $modalities);
    }

    public function loadEditExportTypeForm(Request $request)
    {
        $phase_ids = Answer::where('study_id', 'like', session('current_study'))->pluck('study_structures_id')->toArray();
        $phases = StudyStructure::whereIn('id', $phase_ids)->get();
        $formTypes = FormType::all();
        $modalities = Modility::all();

        $exportType = ExportType::find($request->exportTypeId);
        $selectedPhaseIds = array_filter(explode(',', $exportType->phase_ids));

        echo view('formsubmission::exports.edit_export_type_form')
            ->with('exportType', $exportType)
            ->with('phases', $phases)
            ->with('selectedPhaseIds', $selectedPhaseIds)
            ->with('formTypes', $formTypes)
            ->with('modalities', $modalities);
    }

    public function submitAddExportTypeForm(Request $request)
    {
        $exportType = new ExportType();
        $exportType->id = (string)Str::uuid();
        $exportType->study_id = session('current_study');
        $exportType->phase_ids = implode(',', $request->phase_ids);
        $exportType->form_type_id = $request->form_type_id;
        $exportType->modility_id = $request->modility_id;
        $exportType->titles_values = $request->titles_values;
        $exportType->export_type_title = $request->export_type_title;
        $exportType->save();
        echo 'export type added';
    }

    public function submitEditExportTypeForm(Request $request)
    {
        $exportType = ExportType::find($request->exportTypeId);
        $exportType->study_id = session('current_study');
        $exportType->phase_ids = implode(',', $request->phase_ids);
        $exportType->form_type_id = $request->form_type_id;
        $exportType->modility_id = $request->modility_id;
        $exportType->titles_values = $request->titles_values;
        $exportType->export_type_title = $request->export_type_title;
        $exportType->update();
        echo 'export type updated';
    }

    public function removeEditExportType(Request $request)
    {
        $exportType = ExportType::find($request->exportTypeId);
        $exportType->delete();
        echo 'export type deleted';
    }
}
