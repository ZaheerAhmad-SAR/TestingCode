<?php

namespace Modules\CertificationApp\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;

use Modules\CertificationApp\Entities\StudyModility;
use Modules\CertificationApp\Entities\StudySetup;
use Modules\CertificationApp\Entities\CertificationTemplate;

use Illuminate\Support\Str;
use Session;

class CertificationPreferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // get all studies
        $getStudies = Study::query();

        if ($request->study_code != '') {

           $getStudies = $getStudies->where('study_code', 'like', '%' . $request->study_code . '%');
        }

        if ($request->short_name != '') {

           $getStudies = $getStudies->where('study_short_name', 'like', '%' . $request->short_name . '%');
        }

        if ($request->study_title != '') {

           $getStudies = $getStudies->where('study_title', 'like', '%' . $request->study_title . '%');
        }

        if ($request->study_sponsor != '') {

           $getStudies = $getStudies->where('study_sponsor', 'like', '%' . $request->study_sponsor . '%');
        }

        $getStudies = $getStudies->paginate(50);

        return view('certificationapp::certificate_preferences.index', compact('getStudies'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('certificationapp::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('certificationapp::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('certificationapp::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function assignModality(Request $request) {

        // get all parent and child modalities
        $getModalities = ChildModilities::query();
        $getModalities = $getModalities->select('modilities.id as parent_modility_id', 'modilities.modility_name as parent_modility_name', 'child_modilities.id as child_modility_id', 'child_modilities.modility_name as child_modility_name')
        ->leftjoin('modilities', 'modilities.id', '=', 'child_modilities.modility_id');

        if ($request->parent_modility != '') {

            $getModalities = $getModalities->where('modilities.id', $request->parent_modility);
        }

        if ($request->child_modility != '') {

            $getModalities = $getModalities->where('child_modilities.id', $request->child_modility);
        }

        $getModalities = $getModalities->orderBy('modilities.modility_name')
        ->paginate(50);

        // get parent modalities
        $getParentModalities = Modility::all();

        // get child modalities
        $getChildModalities = ChildModilities::all();


        return view('certificationapp::certificate_preferences.assign_modalities', compact('getModalities', 'getParentModalities', 'getChildModalities'));
    }

    public function saveAssignModality(Request $request) {

        // get input
        $input = $request->all();

        foreach($input['parent_modility_id'] as $key => $parentModality) {

            // check if checkbox is checked
            if(isset($input['check_modality'][$parentModality.'_'.$input['child_modility_id'][$key]])) {

                $checkStudyModality = StudyModility::where('parent_modility_id', $parentModality)
                                                    ->where('child_modility_id', $input['child_modility_id'][$key])
                                                    ->where('study_id', decrypt($request->study_id))
                                                    ->first();

                // check if this modality is already assigned to study
                if ($checkStudyModality == null) {

                    $saveAssignModality = new StudyModility;
                    $saveAssignModality->id = Str::uuid();
                    $saveAssignModality->parent_modility_id = $parentModality;
                    $saveAssignModality->child_modility_id  = $input['child_modility_id'][$key];
                    $saveAssignModality->study_id  = decrypt($request->study_id);
                    $saveAssignModality->assign_by  = \Auth::user()->id;
                    $saveAssignModality->save();

                } // null check ends

            } // checkbox checked condition ends

        } // parent modality loop ends

        Session::flash('success', 'Modalities assigned successfully.');

        return redirect(route ('preferences.assign-modality', $request->study_id));

    } // function end

    public function removeAssignModality(Request $request) {

        // get input
        $input = $request->all();

        foreach($input['parent_modility_id'] as $key => $parentModality) {

            // check if checkbox is checked
            if(isset($input['check_modality'][$parentModality.'_'.$input['child_modility_id'][$key]])) {

                $checkStudyModality = StudyModility::where('parent_modility_id', $parentModality)
                                                    ->where('child_modility_id', $input['child_modility_id'][$key])
                                                    ->where('study_id', decrypt($request->study_id))
                                                    ->delete();

            } // checkbox checked condition ends

        } // parent modality loop ends

        Session::flash('success', 'Modalities assigned successfully.');

        return redirect(route ('preferences.assign-modality', $request->study_id));

    } // function end

    public function studySetup(Request $request) {

        // get study setups
        $checkStudy = StudySetup::where('study_id', decrypt($request->study_id))->first();

        // get parent modalities
        $getParentModalities = Modility::all();

        return view('certificationapp::certificate_preferences.study_setup', compact('checkStudy', 'getParentModalities'));

    } // study setup function ends

    public function saveStudySetup(Request $request) {

        $checkStudy = StudySetup::where('study_id', decrypt($request->study_id))->first();

        if ($checkStudy === null) {

            $checkStudy = new StudySetup;
            $checkStudy->id = Str::uuid();
            $checkStudy->study_email = $request->study_email;
            $checkStudy->study_cc_email = $request->study_cc_email;
            $checkStudy->allowed_no_transmission = json_encode($request->allowed_no_transmission);
            $checkStudy->study_id = decrypt($request->study_id);
            $checkStudy->save();

        } else {

            $checkStudy->study_email = $request->study_email;
            $checkStudy->study_cc_email = $request->study_cc_email;
            $checkStudy->allowed_no_transmission = json_encode($request->allowed_no_transmission);
            $checkStudy->study_id = decrypt($request->study_id);
            $checkStudy->save();
        }

        Session::flash('success', 'Study setup successfully.');

        return redirect(route ('preferences.study-setup', $request->study_id));
    }

    public function getTemplate(Request $request) {

        // get Template
        $getTemplates = CertificationTemplate::select('certification_templates.id as template_id', 'certification_templates.title', 'certification_templates.body', 'users.name')
        ->leftjoin('users', 'users.id', '=', 'certification_templates.created_by')
        ->orderBy('certification_templates.id', 'desc')
        ->paginate(50);

        return view('certificationapp::certificate_preferences.template', compact('getTemplates'));

    }

    public function saveTemplate(Request $request) {

        $saveTemplate = new CertificationTemplate;
        $saveTemplate->id = Str::uuid();
        $saveTemplate->title = $request->title;
        $saveTemplate->body = $request->body;
        $saveTemplate->created_by = \Auth::user()->id;
        $saveTemplate->save();

        Session::flash('success', 'Template added successfully.');

        return redirect(route ('certification-template'));
    }

    public function updateTemplate(Request $request) {

        $saveTemplate = CertificationTemplate::find($request->template_id);
        $saveTemplate->title = $request->edit_title;
        $saveTemplate->body = $request->edit_body;
        $saveTemplate->save();

        Session::flash('success', 'Template updated successfully.');

        return redirect(route ('certification-template'));
    }
}
