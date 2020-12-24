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
use Modules\FormSubmission\Entities\ExportType;
use Modules\FormSubmission\Entities\ExportTypeUsage;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Preference;

class FormDataExportController extends Controller
{
    public function index()
    {
        $exportTypes = ExportType::where('study_id', 'like', session('current_study'))
            ->orderBy('created_at', 'asc')
            ->paginate(Preference::getPreference('PER_PAGE_PAGINATION'));
        return view('formsubmission::exports.index')
            ->with('exportTypes', $exportTypes);
    }

    public function export(Request $request)
    {

        $exportTypeUsage = new ExportTypeUsage();
        $exportTypeUsage->id = (string)Str::uuid();
        $exportTypeUsage->export_type_id = $request->export_type_id;
        $exportTypeUsage->data_exported_by_id = auth()->user()->id;
        $exportTypeUsage->save();

        $study = Study::find(session('current_study'));
        $formType = FormType::find($request->form_type_id);
        $modility = Modility::find($request->modility_id);
        $fileName = str_replace(' ', '-', $study->study_short_name) . '-' . str_replace(' ', '-', $formType->form_type) . '-' . str_replace(' ', '-', $modility->modility_name) . '-data-export-' . date('Y-m-d-h-i-s') . '.xlsx';
        return Excel::download(new FormDataExport($request), $fileName);
    }
}
