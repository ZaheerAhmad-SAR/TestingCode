<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FormSubmission\Exports\FormDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\Answer;

class FormDataExportController extends Controller
{
    public function index()
    {
        return view('formsubmission::exports.index');
    }

    public function filterForm(Request $request)
    {
        $visit_ids = Answer::where('study_id', 'like', session('current_study'))->pluck('study_structures_id')->toArray();
        $visits = StudyStructure::whereIn('id', $visit_ids)->get();
        $formTypes = FormType::all();
        $modalities = Modility::all();
        echo view('formsubmission::exports.export_filter_form')
            ->with('visits', $visits)
            ->with('formTypes', $formTypes)
            ->with('modalities', $modalities);
    }

    public function export(Request $request)
    {
        $study = Study::find(session('current_study'));
        $formType = FormType::find($request->form_type_id);
        $modility = Modility::find($request->modility_id);
        $fileName = str_replace(' ', '-', $study->study_short_name) . '-' . str_replace(' ', '-', $formType->form_type) . '-' . str_replace(' ', '-', $modility->modility_name) . '-data-export-' . date('Y-m-d-h-i-s') . '.xlsx';
        return Excel::download(new FormDataExport($request), $fileName);
    }
}
