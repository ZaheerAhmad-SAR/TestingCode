<?php

namespace Modules\Admin\Http\Controllers;

use App\Mail\TransmissonQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\SubjectsPhases;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\TransmissionUpdateDetail;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\DeviceModility;
use Modules\Admin\Entities\ModalityPhase;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $getTransmissions = CrushFtpTransmission::query();

        if ($request->trans_id != '') {

           $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }

        if ($request->subject_id != '') {

           $getTransmissions = $getTransmissions->where('Subject_ID', 'like', '%' . $request->subject_id . '%');
        }

        if ($request->visit_name != '') {

           $getTransmissions = $getTransmissions->where('visit_name', 'like', '%' . $request->visit_name . '%');
        }

        if ($request->visit_date != '') {

            $visitDate = explode('-', $request->visit_date);
                    $from   = Carbon::parse($visitDate[0]); // 2018-09-29 00:00:00

                    $to     = Carbon::parse($visitDate[1]); // 2018-09-29 23:59:59

                $getTransmissions =  $getTransmissions->whereDate('visit_date', '>=', $from)
                    ->whereDate('visit_date', '<=', $to);
        }

        if ($request->imagine_modality != '') {

           $getTransmissions = $getTransmissions->where('ImageModality', $request->imagine_modality);
        }

        if ($request->modility_id != '') {

           $getTransmissions = $getTransmissions->where('modility_id', $request->modility_id);
        }

        if ($request->is_read != '') {

           $getTransmissions = $getTransmissions->where('is_read', $request->is_read);
        }

        if ($request->status != '') {

           $getTransmissions = $getTransmissions->where('status', $request->status);
        }

        // check for system user Admin
        if(hasPermission(auth()->user(),'systemtools.index') && hasPermission(auth()->user(),'qualitycontrol.index')) {

            $getTransmissions = $getTransmissions->orderBy('id', 'desc')->paginate(50);

        } else if (hasPermission(auth()->user(),'qualitycontrol.index')) {

            // get session id
            $studyID = Study::where('id', \Session::get('current_study'))
                            ->pluck('study_code')
                            ->toArray();

            $getTransmissions = $getTransmissions->where('StudyI_ID', $studyID[0])
                                                 ->orderBy('id', 'desc')
                                                 ->paginate(50);

        }

        // get modality
        $getModalities = Modility::get();

        return view('admin::transmission_details', compact('getTransmissions', 'getModalities'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function transmissionData(Request $request)
    {

        // remove the upper section

        $explodeGetCFtPTrans = explode('<?xml', $request);
      
        // concatinate xml with the remaining  xml
        $xml = '<?xml'.$explodeGetCFtPTrans[1];
        //dd($xml);
        $xml    = simplexml_load_string($xml);

        // check for trimission number
        $checkTransmissionNumber = DB::table('crush_ftp_transmissions')->where('Transmission_Number', $xml->Transmission_Number)->first();

        if ($checkTransmissionNumber == null) {

            $saveData = DB::table('crush_ftp_transmissions')->insert([
                'data'                      => $request,
                'Transmission_Number'       => $xml->Transmission_Number,
                'Study_Name'                => $xml->Study_Name,
                'StudyI_ID'                 => $xml->StudyI_ID,
                'sponsor'                   => $xml->sponsor,
                'Study_central_email'       => $xml->Study_central_email,
                'Salute'                    => $xml->Salute,
                'Submitter_First_Name'      => $xml->Submitter_First_Name,
                'Submitter_Last_Name'       => $xml->Submitter_Last_Name,
                'Submitter_email'           => $xml->Submitter_email,
                'Submitter_phone'           => $xml->Submitter_phone,
                'Submitter_Role'            => $xml->Submitter_Role,
                'Site_Initials'             => $xml->Site_Initials,
                'Site_Name'                 => $xml->Site_Name,
                'Site_ID'                   => $xml->Site_ID,
                'PI_Name'                   => $xml->PI_Name,
                'PI_FirstName'              => $xml->PI_FirstName,
                'PI_LastName'               => $xml->PI_LastName,
                'PI_email'                  => $xml->PI_email,
                'Site_st_address'           => $xml->Site_st_address,
                'Site_city'                 => $xml->Site_city,
                'Site_state'                => $xml->Site_state,
                'Site_Zip'                  => $xml->Site_Zip,
                'Site_country'              => $xml->Site_country,
                'Subject_ID'                => $xml->Subject_ID,
                'StudyEye'                  => $xml->StudyEye,
                'visit_name'                => $xml->visit_name,
                'visit_date'                => $xml->visit_date,
                'ImageModality'             => $xml->ImageModality,
                'device_model'              => $xml->device_model,
                'device_oirrcID'            => $xml->device_oirrcID,
                'Compliance'                => $xml->Compliance,
                //'Compliance_comments'              => $xml->Compliance_comments,
                'Submitted_By'              => $xml->Submitted_By,
                'Number_files'              => $xml->Number_files,
                'transmitted_file_name'     => $xml->transmitted_file_name,
                'transmitted_file_size'     => $xml->transmitted_file_size,
                'archive_physical_location' => $xml->archive_physical_location,
                'received_month'            => $xml->received_month,
                'received_day'              => $xml->received_day,
                'received_year'             => $xml->received_year,
                'received_hours'            => $xml->received_hours,
                'received_minutes'          => $xml->received_minutes,
                'received_seconds'          => $xml->received_seconds,
                //'received-mesc'              => $xml->received-mesc,
                'Study_QCO1'                => $xml->Study_QCO1,
                'StudyQCO2'                 => $xml->StudyQCO2,
                'Study_cc1'                 => $xml->Study_cc1,
                'Study_cc2'                 => $xml->Study_cc2,
                'QC_folder'                 => $xml->QC_folder,
                'Graders_folder'            => $xml->Graders_folder,
                'QClink'                    => $xml->QClink,
                'Glink'                     => $xml->Glink,
            ]);

            echo "Records inserted successfully.";

        } else {

            echo 'Transmission Number already exists.';
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data = CrushFtpTransmission::create([
            'data' => ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        dd('store');
        return view('admin::index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id = '')
    {
        // find the transmission
        $findTransmission = CrushFtpTransmission::where('id', decrypt($id))->get();

        return view('admin::view_transmission_details', compact('findTransmission'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // find the transmission
        $findTransmission = CrushFtpTransmission::where('id', decrypt($id))->first();
        $findTransmission->is_read = 'yes';
        $findTransmission->qc_officerId = \Auth::user()->id;
        $findTransmission->qc_officerName = \Auth::user()->name;
        $findTransmission->save();

        // get all sites
        $getSites =Site::get();
        // get all subjects
        $getSubjects = Subject::select('subjects.id', 'subjects.subject_id')
                                ->leftjoin('studies', 'studies.id', '=', 'subjects.study_id')
                                ->where('studies.study_code', $findTransmission->StudyI_ID)
                                ->get();
        // get all phases
        $getPhases = StudyStructure::select('study_structures.id', 'study_structures.name')
                                    ->leftjoin('studies', 'studies.id', '=', 'study_structures.study_id')
                                    ->where('studies.study_code', $findTransmission->StudyI_ID)
                                    ->get();
        // get modality
        $getModalities = Modility::get();

        // get all the transmission updates
        $getTransmissionUpdates = TransmissionUpdateDetail::where('transmission_id', decrypt($id))->get();

        return view('admin::view_transmission_details', compact('findTransmission', 'getSites', 'getSubjects', 'getPhases', 'getModalities', 'getTransmissionUpdates'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // get old transmission data
        $oldTransmission = CrushFtpTransmission::where('id', decrypt($id))->first();
        // find record
        $findTransmission = CrushFtpTransmission::where('id', decrypt($id))->first();
        $findTransmission->Submitter_email = $request->d_submitter_email;
        $findTransmission->Submitter_phone = $request->d_submitter_phone;

        // get site id
        if ($request->d_site_id != "") {

            $siteID = explode('/', $request->d_site_id);
            $findTransmission->sit_id = $siteID[0];
            $findTransmission->Site_ID = $siteID[1];
        }

        $findTransmission->Site_Name = $request->d_site_name;
        $findTransmission->Site_state = $request->d_site_state;
        $findTransmission->Site_Zip = $request->d_site_zip;
        $findTransmission->Site_country = $request->d_site_country;

        // PI
        $findTransmission->PI_FirstName = $request->d_pi_first_name;
        $findTransmission->PI_LastName = $request->d_pi_last_name;
        $findTransmission->PI_email = $request->d_pi_email;

        // get subject Id
        if ($request->d_subject_Id == "1") {

            $findTransmission->new_subject = "1";

        } else if ($request->d_subject_Id != "") {

            $subjectID = explode('/', $request->d_subject_Id);
            $findTransmission->subj_id = $subjectID[0];
            $findTransmission->Subject_ID = $subjectID[1];
        }

        $findTransmission->StudyEye = $request->d_study_eye;

        // get visit name and visit_id
        if ($request->d_visit_name != "") {

            $visitName = explode('/', $request->d_visit_name);
            $findTransmission->phase_id = $visitName[0];
            $findTransmission->visit_name = $visitName[1];
        }

        $findTransmission->visit_date = $request->d_visit_date;

        // get modality name and madality_id
        if ($request->d_image_modality != "") {

            $modilityName = explode('/', $request->d_image_modality);
            $findTransmission->modility_id = $modilityName[0];
            $findTransmission->ImageModality = $modilityName[1];
        }

        $findTransmission->device_model = $request->d_device_model;
        $findTransmission->device_oirrcID = $request->d_device_oirrc_id;
        $findTransmission->Submitted_By = $request->d_submitted_by;
        $findTransmission->photographer_full_name = $request->d_photographer_full_name;
        $findTransmission->photographer_email = $request->d_photographer_email;
        // status
        $findTransmission->status = $request->status;
        $findTransmission->save();

        // check for status and also store update details in transmission update table
        $transmissionUpdateDetails = new TransmissionUpdateDetail;
        $transmissionUpdateDetails->user_id = \Auth::user()->id;
        $transmissionUpdateDetails->user_name = \Auth::user()->name;
        $transmissionUpdateDetails->transmission_id = $findTransmission->id;
        $transmissionUpdateDetails->comment = $request->comment;
        $transmissionUpdateDetails->save();

        // log event details
        $logEventDetails = eventDetails($findTransmission->id, 'Transmission Data', 'Update', $request->ip(), $oldTransmission);

        // if status is accepted, then insert
        if ($findTransmission->status == 'accepted') {

            $transmissionDataStatus = $this->transmissionStatus($findTransmission);
        }

        \Session::flash('success', 'Transmission information updated successfully.');

        return redirect(route('transmissions.edit',  $id));
    }

    public function transmissionStatus($findTransmission) {

    ///////////////////////////// Save Study ///////////////////////////////////
            //get study
            $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

            if ($getStudy == null) {
                // insert study
                $getStudy = new Study;
                $getStudy->id = Str::uuid();
                $getStudy->study_code = $findTransmission->StudyI_ID;
                $getStudy->study_title = $findTransmission->Study_Name;
                $getStudy->study_sponsor = $findTransmission->sponsor;
                $getStudy->save();
            } // study check is end

        ////////////////////// Save site ///////////////////////////////////////

            // get site
            $getSite = Site::where('site_code', $findTransmission->Site_ID)->first();

            if ($getSite == null) {
                // insert site
                $getSite = new Site;
                $getSite->id = Str::uuid();
                $getSite->site_code = $findTransmission->Site_ID;
                $getSite->site_name = $findTransmission->Site_Name;
                $getSite->site_address = $findTransmission->Site_st_address;
                $getSite->site_city = $findTransmission->Site_city;
                $getSite->site_state = $findTransmission->Site_state;
                $getSite->site_country = $findTransmission->Site_country;
                $getSite->save();

            } // site check is end

            // check site study relation
            $getSiteStudy = StudySite::where('study_id', $getStudy->id)
                                        ->where('site_id', $getSite->id)
                                        ->first();

            if ($getSiteStudy == null) {
                // insert study site
                $getSiteStudy = new StudySite;
                $getSiteStudy->study_id = $getStudy->id;
                $getSiteStudy->site_id = $getSite->id;
                $getSiteStudy->save();

            } // site study check is end

        //////////////////// Primary Investigator ///////////////////////////

            // get Primary Investigator
            $getPrimaryInvestigator = PrimaryInvestigator::where('site_id', $getSite->id)
                                                          ->where('email', $findTransmission->PI_email)
                                                          ->first();

            if ($getPrimaryInvestigator == null) {
                // insert primary investigator
                $getPrimaryInvestigator = new PrimaryInvestigator;
                $getPrimaryInvestigator->id = Str::uuid();
                $getPrimaryInvestigator->site_id = $getSite->id;
                $getPrimaryInvestigator->first_name = $findTransmission->PI_FirstName;
                $getPrimaryInvestigator->last_name = $findTransmission->PI_LastName;
                $getPrimaryInvestigator->email = $findTransmission->PI_email;
                $getPrimaryInvestigator->save();
            } // primary investigator check ends

        ////////////////// Photographer ///////////////////////////////////////

            // get Photographer
            $getPhotographer = Photographer::where('site_id', $getSite->id)
                                            ->where('email', $findTransmission->photographer_email)
                                            ->first();

            if ($getPhotographer == null) {
                // insert photographer
                $getPhotographer = new Photographer;
                $getPhotographer->id = Str::uuid();
                $getPhotographer->site_id = $getSite->id;
                $getPhotographer->first_name = $findTransmission->photographer_full_name;
                $getPhotographer->email = $findTransmission->photographer_email;
                $getPhotographer->save();
            } // photographer check is end

        //////////////////// Modality //////////////////////////////////////////

            // get Modality
            $getModality = Modility::where('modility_name', $findTransmission->ImageModality)
            ->first();

            if ($getModality == null) {
                // insert modility
                $getModality = new Modility;
                $getModality->id = Str::uuid();
                $getModality->modility_name = $findTransmission->ImageModality;
                $getModality->save();
            } // modility check is end

        /////////////////////// Devices ///////////////////////////////////////

            //get devices
            // $getDevices = Device::where('device_model', $findTransmission->device_model)->first();

            // if ($getDevices == null) {
            //     // insert modility
            //     $getDevices = new Device;
            //     $getDevices->id = Str::uuid();
            //     $getDevices->device_model = $findTransmission->device_model;
            //     $getDevices->save();
            // } // devices check is end

            // // make relation for devices and modality
            // $getDeviceModality = DeviceModility::where('device_id', $getDevices->id)
            //                                      ->where('modility_id', $getModality->id)
            //                                      ->first();

            // if ($getDeviceModality == null) {

            //     $getDeviceModality = new DeviceModility;
            //     $getDeviceModality->id = Str::uuid();
            //     $getDeviceModality->device_id = $getDevices->id;
            //     $getDeviceModality->modility_id = $getModality->id;
            //     $getDeviceModality->save();
            // }

        ///////////////////// Subject /////////////////////////////////////////

            // if  new_subject status is 1 insert new subject and change ne_subject status to 0
            if ($findTransmission->new_subject == "1") {

                // get subject
                $getSubject = Subject::where('study_id', $getStudy->id)
                                  ->where('subject_id', $findTransmission->Subject_ID)
                                  ->first();

                if ($getSubject == null) {
                    // insert subject
                    $getSubject = new Subject;
                    $subjectID = Str::uuid();
                    $getSubject->id = $subjectID;
                    $getSubject->study_id = $getStudy->id;
                    $getSubject->subject_id = $findTransmission->Subject_ID;
                    $getSubject->site_id = $getSite->id;
                    $getSubject->study_eye = $findTransmission->StudyEye;
                    $getSubject->transmission_status = "1";
                    $getSubject->save();


                // change new_subject status to 0
                $updateSubjectStatus = CrushFtpTransmission::where('id', $findTransmission->id)
                ->update(['new_subject' => "0"]);

                    // assign ID to pointer
                    $getSubject->id = $subjectID;

                    // change new_subject status to 0
                    $updateSubjectStatus = CrushFtpTransmission::where('id', $findTransmission->id)
                    ->update(['new_subject' => 0]);

                } // subject check is end


            } else {

                // get subject
                $getSubject = Subject::where('study_id', $getStudy->id)
                                  ->where('subject_id', $findTransmission->Subject_ID)
                                  ->first();

                if ($getSubject == null) {
                    // insert subject
                    $getSubject = new Subject;
                    $subjectID = Str::uuid();
                    $getSubject->id = $subjectID;
                    $getSubject->study_id = $getStudy->id;
                    $getSubject->subject_id = $findTransmission->Subject_ID;
                    $getSubject->site_id = $getSite->id;
                    $getSubject->study_eye = $findTransmission->StudyEye;
                    $getSubject->transmission_status = "1";
                    $getSubject->save();

                    // assign ID to pointer
                    $getSubject->id = $subjectID;

                    // change new_subject status to 0
                    $updateSubjectStatus = CrushFtpTransmission::where('id', $findTransmission->id)
                    ->update(['new_subject' => 0]);

                } // subject check is end

            } // else ends

        /////////////////// Phase /////////////////////////////////////////////

            // get phase
            $getPhase = StudyStructure::where('study_id', $getStudy->id)
                                        ->where('name', $findTransmission->visit_name)
                                        ->first();

            if ($getPhase == null) {
                // insert phase
                $getPhase = new StudyStructure;
                $phaseID = Str::uuid();
                $getPhase->id = $phaseID;
                $getPhase->study_id = $getStudy->id;
                $getPhase->name = $findTransmission->visit_name;
                $getPhase->save();

                // assign ID to pointer
                $getPhase->id = $phaseID;

            } // phase check is end

            // check for visit date
            $getSubjectPhase = SubjectsPhases::where('subject_id', $getSubject->id)
                                              ->where('phase_id', $getPhase->id)
                                              ->first();

            if ($getSubjectPhase == null) {
                // insert into subject phases
                $getSubjectPhase = new SubjectsPhases;
                $getSubjectPhase->id = Str::uuid();
                $getSubjectPhase->subject_id = $getSubject->id;
                $getSubjectPhase->phase_id = $getPhase->id;
                $getSubjectPhase->visit_date = $findTransmission->visit_date;
                $getSubjectPhase->save();
            } // subject phases check is end

            // check modality and phase id
            $getModalityPhase = ModalityPhase::where('modility_id', $getModality->id)
                                               ->where('phase_id', $getPhase->id)
                                               ->first();

            if ($getModalityPhase == null) {
                // insert into modality phase table
                $getModalityPhase = new ModalityPhase;
                $getModalityPhase->modility_id = $getModality->id;
                $getModalityPhase->phase_id = $getPhase->id;
                $getModalityPhase->form_type_id = 1;
                $getModalityPhase->save();
            }

        return true;

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

    public function getAllPIBySiteId(Request $request)
    {
        $transmissionNumber = $request->transmissionNumber;
        $records = CrushFtpTransmission::where('Transmission_Number',$transmissionNumber)->get();
        echo  view('admin::transmissions.users_dropdown',compact('records'));
    }

    public function queryTransmissionMail()
    {
        dd(\request()->all());
        request()->validate(['cc_email'=>'required|email']);
        Mail::to(\request('cc_email'))
            ->send(new TransmissonQuery('shirts'));
//        //$ccEmail = $request->post('cc_email');
//        $remarks = $request->post('remarks');

        /// Mail Raw using plain text
//        Mail::raw(request('remarks'),function ($message){
//                $message->to(request('cc_email'))
//                ->subject(request('query_subject'));
//        });
        //// Mail Raw end function


        return redirect('/transmissions')->with('message','Query has been send');
        //$users = $request->post('users');
//        $data = array
//        (
//            'query_subject'=>$request['query_subject'],
//            'site_name'=> $request['site_name'],
//            'users'=>$users,
//            'cc_email'=>$request['cc_email'],
//            'remarks'=>$request['remarks']
//        );
    }
}

