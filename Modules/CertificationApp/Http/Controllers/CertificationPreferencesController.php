<?php

namespace Modules\CertificationApp\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Device;

use Modules\CertificationApp\Entities\StudyModility;
use Modules\CertificationApp\Entities\StudyDevice;
use Modules\CertificationApp\Entities\StudySetup;
use Modules\CertificationApp\Entities\CertificationTemplate;
use Illuminate\Support\Facades\DB;

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
        ->leftjoin('modilities', 'modilities.id', '=', 'child_modilities.modility_id')
        ->whereNULL('modilities.deleted_at')
        ->whereNULL('child_modilities.deleted_at');

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
                    $saveAssignModality->id = (string)Str::uuid();
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

    public function assignDevice(Request $request) {

        // get all devices
        $getDevices = Device::query();

        if ($request->device_name != '') {

           $getDevices = $getDevices->where('device_name', 'like', '%' . $request->device_name . '%');
        }

        if ($request->device_manufacturer != '') {

           $getDevices = $getDevices->where('device_manufacturer', 'like', '%' . $request->device_manufacturer . '%');
        }

        $getDevices = $getDevices->paginate(50);

        return view('certificationapp::certificate_preferences.assign_devices', compact('getDevices'));
    }

    public function saveAssignDevice(Request $request) {

        // get input
        $input = $request->all();

        foreach($input['device_name'] as $key => $device) {

            // check if checkbox is checked
            if(isset($input['check_device'][$device])) {

                $checkStudyDevice = StudyDevice::where('device_id', $device)
                                                    ->where('study_id', decrypt($request->study_id))
                                                    ->first();

                // check if this device is already assigned to study
                if ($checkStudyDevice == null) {

                    $saveDevice = new StudyDevice;
                    $saveDevice->id = (string)Str::uuid();
                    $saveDevice->device_id = $device;
                    $saveDevice->study_id  = decrypt($request->study_id);
                    $saveDevice->assign_by  = \Auth::user()->id;
                    $saveDevice->save();

                } // device check ends

            } // checkbox checked condition ends

        } // device loop ends

        Session::flash('success', 'Devices assigned successfully.');

        return redirect(route ('preferences.assign-device', $request->study_id));

    } // function end

    public function removeAssignDevice(Request $request) {

        // get input
        $input = $request->all();

        foreach($input['device_name'] as $key => $device) {

            // check if checkbox is checked
            if(isset($input['check_device'][$device])) {

                $checkStudyDevice = StudyDevice::where('device_id', $device)
                                                ->where('study_id', decrypt($request->study_id))
                                                ->delete();
            } // check ends

        } // loop ends

        Session::flash('success', 'Devices removed successfully.');

        return redirect(route ('preferences.assign-device', $request->study_id));

    } // function end

    public function studySetup(Request $request) {

        // get study setups
        $checkStudy = StudySetup::where('study_id', decrypt($request->study_id))->first();

        // get parent modalities that are assigned to this study
        $getParentModalities = Modility::select('modilities.id', 'modilities.modility_name')
        ->leftjoin('study_modilities', 'study_modilities.parent_modility_id', '=', 'modilities.id')
        ->where('study_modilities.study_id', decrypt($request->study_id))
        ->groupBy('study_modilities.parent_modility_id')
        ->get();

        return view('certificationapp::certificate_preferences.study_setup', compact('checkStudy', 'getParentModalities'));

    } // study setup function ends

    public function saveStudySetup(Request $request) {

        $checkStudy = StudySetup::where('study_id', decrypt($request->study_id))->first();

        if ($checkStudy === null) {

            $checkStudy = new StudySetup;

            $checkStudy->id = Str::uuid();
            $checkStudy->study_email = $request->study_email != '' ?  $request->study_email : '';
            $checkStudy->study_cc_email = $request->study_cc_email != '' ? preg_replace("/\s+/", "", $request->study_cc_email) : '';
            $checkStudy->study_bcc_email = $request->study_bcc_email != '' ? preg_replace("/\s+/", "", $request->study_bcc_email) : '';

            $checkStudy->allowed_no_transmission = json_encode($request->allowed_no_transmission);
            $checkStudy->study_id = decrypt($request->study_id);
            $checkStudy->save();

        } else {

            $checkStudy->study_email = $request->study_email != '' ?  $request->study_email : '';
            $checkStudy->study_cc_email = $request->study_cc_email != '' ? preg_replace("/\s+/", "", $request->study_cc_email) : '';
            $checkStudy->study_bcc_email = $request->study_bcc_email != '' ? preg_replace("/\s+/", "", $request->study_bcc_email) : '';
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
        $saveTemplate->id = (string)Str::uuid();
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

    public function getTemplateData(Request $request) {

        if($request->ajax()) {

            $getTemplate = CertificationTemplate::select('id as template_id', 'title as template_title', 'body as template_body')
            ->where('id', $request->template_id)
            ->first();

            // return response
            return response()->json(['getTemplate' => $getTemplate]);

        } // ajax ends
    }

    public function showCertificationReport(Request $request) {

        $old_cert_sites_array = DB::connection('mysql2')->table('site')->pluck('OIIRC_id')->where('status', '!=' , -1)->toArray();
        $old_cert_sites_count = DB::connection('mysql2')->table('site')->pluck('OIIRC_id')->where('status', '!=' , -1)->count();

      
        $new_cert_sites_array = DB::connection('mysql')->table('sites')->pluck('site_code')->where('deleted_at', '=' , NULL)->toArray();
        $new_cert_sites_count = DB::connection('mysql')->table('sites')->pluck('site_code')->where('deleted_at', '=' , NULL)->count();



        $not_in_new_app_array = DB::connection('mysql2')->table('site')->whereNotIn('OIIRC_id', $new_cert_sites_array)->get();

        $not_in_new_app_count = DB::connection('mysql2')->table('site')->whereNotIn('OIIRC_id', $new_cert_sites_array)->pluck('OIIRC_id')->count();

       
       //dd($not_in_new_app_array);
       // dd("old_cert_sites_count".$old_cert_sites_count.'new_cert_sites_count'.$new_cert_sites_count.'not_in_new_app_count'.$not_in_new_app_count);


        return view('certificationapp::certificate_preferences.certification_report', compact('old_cert_sites_array', 'old_cert_sites_count','new_cert_sites_count','not_in_new_app_array','not_in_new_app_count'));
      
       

    }


        public function showCertificationReportPhotograper(Request $request) {


              $sites_array = DB::connection('mysql')->table('sites')->where('deleted_at', '=' , NULL)->get();

              $result_old  = DB::connection('mysql2')->table('site')
               ->select('photographer_data.site_id','photographer_data.email_address')
               ->join('photographer_data', 'photographer_data.site_id', '=', 'site.OIIRC_id')
               ->groupBy('photographer_data.email_address')
               //->groupBy('photographer_data.email_address')
               ->get();
              //->toSql();
             // dd($result_old);
                
         
              $result_new = DB::connection('mysql')->table('sites')
               ->select('photographers.email','sites.site_code')
               ->join('photographers', 'sites.id', '=', 'photographers.site_id')
               
               ->get(); 

                   foreach($result_old as $site_array)
                   {
                                


               $result_arry= $this->multidimensional_search($result_new, array('email'=>$site_array->email_address, 'site_code'=>$site_array->site_id));
               dd($result_arry);
        }




       // return view('certificationapp::certificate_preferences.certification_report_photograper', compact('sites_array', 'result_old','result_new'));
      
       

    }


       /**
 * @param array multidimensional 
 * @param string $search_value The value to search for, ie a specific 'Taylor'
 * @param string $key_to_search The associative key to find it in, ie first_name
 * @param string $other_matching_key The associative key to find in the matches for employed
 * @param string $other_matching_value The value to find in that matching associative key, ie true
 * 
 * @return array keys, ie all the people with the first name 'Taylor' that are employed.
 */
 function search_revisions($dataArray, $search_value, $key_to_search, $other_matching_value = null, $other_matching_key = null) {
    // This function will search the revisions for a certain value
    // related to the associative key you are looking for.
    $keys = array();
    foreach ($dataArray as $key => $cur_value) {
        if ($cur_value[$key_to_search] == $search_value) {
            if (isset($other_matching_key) && isset($other_matching_value)) {
                if ($cur_value[$other_matching_key] == $other_matching_value) {
                    $keys[] = $key;
                }
            } else {
                // I must keep in mind that some searches may have multiple
                // matches and others would not, so leave it open with no continues.
                $keys[] = $key;
            }
        }
    }
    return $keys;
}


        public function showCertificationReportDevices(Request $request) {


              $sites_array = DB::connection('mysql')->table('sites')->where('deleted_at', '=' , NULL)->get();

              $result_old  = DB::connection('mysql2')->table('site')
               ->select('site.site_id','site.site_title','site.OIIRC_id', DB::raw('COUNT(photographer_data.site_id_db) AS photograpers_count'))
               ->join('photographer_data', 'site.site_id', '=', 'photographer_data.site_id_db')
               ->groupBy('site.site_id')
               ->get();

              $result_new = DB::connection('mysql')->table('sites')
               ->select('sites.id','sites.site_name','sites.site_code', DB::raw('COUNT(photographers.site_id) AS photograpers_count'))
               ->join('photographers', 'sites.id', '=', 'photographers.site_id')
               ->groupBy('sites.id')
               ->get();



        return view('certificationapp::certificate_preferences.certification_report_photograper', compact('sites_array', 'result_old','result_new'));
      
       

    }


}
