<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\SubjectsPhases;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
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

        if ($request->status != '') {

           $getTransmissions = $getTransmissions->where('status', $request->status);
        }

        $getTransmissions = $getTransmissions->orderBy('id', 'desc')->paginate(50);

        return view('admin::transmission_details', compact('getTransmissions'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function transmissionData(Request $request)
    {
        
        // $cFtpTrans = CrushFtpTransmission::create([
        //     'data' => $request,
        // ]);

        $getCFtPTrans = DB::table('transmissions')->where('id', 9446)->first();
        
        if ($getCFtPTrans != null) {
        // remove the upper section
        $explodeGetCFtPTrans = explode('<?xml', $getCFtPTrans->Data);
        //dd($explodeGetCFtPTrans[1]);
        // concatinate xml with the remaining  xml
        $xml = '<?xml'.$explodeGetCFtPTrans[1];
        //dd($xml);
        $xml    = simplexml_load_string($xml);

        // check for trimission number
        $checkTransmissionNumber = DB::table('crush_ftp_transmissions')->where('Transmission_Number', $xml->Transmission_Number)->first();

        if ($checkTransmissionNumber == null) {

            $saveData = DB::table('crush_ftp_transmissions')->insert([
                'data'                      => $getCFtPTrans->Data,
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
                'visit_date'                => date('Y-m-d', strtotime($xml->visit_date)),
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

        } else {
            echo "Nothing to Insert.";
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
        $findTransmission->save();
        // get all subjects
        $getSubjects = Subject::get();
        // get all phases
        $getPhases = StudyStructure::get();
        // get modality
        $getModalities = Modility::get();

        return view('admin::view_transmission_details', compact('findTransmission', 'getSubjects', 'getPhases', 'getModalities'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // find record
        $findTransmission = CrushFtpTransmission::where('id', decrypt($id))->first();
        $findTransmission->Submitter_email = $request->d_submitter_email;
        $findTransmission->Submitter_phone = $request->d_submitter_phone;
        $findTransmission->Site_Name = $request->d_site_name;
        $findTransmission->PI_FirstName = $request->d_pi_first_name;
        $findTransmission->PI_LastName = $request->d_pi_last_name;
        $findTransmission->PI_email = $request->d_pi_email;
        $findTransmission->Site_state = $request->d_site_state;
        $findTransmission->Site_Zip = $request->d_site_zip;
        $findTransmission->Site_country = $request->d_site_country;
        // get subject Id
        $subjectID = explode('/', $request->d_subject_Id);
        $findTransmission->subj_id = $subjectID[0];
        $findTransmission->Subject_ID = $subjectID[1];
        $findTransmission->StudyEye = $request->d_study_eye;
        // get visit name and visit_id
        $visitName = explode('/', $request->d_visit_name);
        $findTransmission->phase_id = $visitName[0];
        $findTransmission->visit_name = $visitName[1];

        $findTransmission->visit_date = $request->d_visit_date;
        // get modality name and madality_id
        $modilityName = explode('/', $request->d_image_modality);
        $findTransmission->modility_id = $modilityName[0];
        $findTransmission->ImageModality = $modilityName[1];

        $findTransmission->device_model = $request->d_device_model;
        $findTransmission->device_oirrcID = $request->d_device_oirrc_id;
        $findTransmission->Submitted_By = $request->d_submitted_by;
        $findTransmission->photographer_full_name = $request->d_photographer_full_name;
        $findTransmission->photographer_email = $request->d_photographer_email;
        $findTransmission->save();

        \Session::flash('success', 'Transmission information updated successfully.');

        return redirect(route('transmissions.edit',  $id));
    }

    public function transmissionStatus(Request $request) {

        $findTransmission = CrushFtpTransmission::where('id', decrypt($request->hidden_transmission_id))->first();

        $findTransmission->status = $request->status;
        $findTransmission->comment = $request->comment;
        $findTransmission->qc_officerId = \Auth::user()->id;
        $findTransmission->qc_officerName = \Auth::user()->name;
        $findTransmission->save();

        // if status is accepted, then insert
        if ($findTransmission->status == 'accepted') {

            ////////////////////////////////// Save Study ///////////////////////////////////
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

            ////////////////////// Save site ////////////////////////////////////////////

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

            //////////////////////// Primary Investigator /////////////////////////////

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

            ////////////////// Photographer ///////////////////////////////////////////

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

            //////////////////// Modality /////////////////////////////////////////////

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

            ///////////////////// Subject ////////////////////////////////////////////
            
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
                $getSubject->save();

            } // subject check is end

            /////////////////// Phase /////////////////////////////////////////////////

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

            } // phase check is end

            // check for visit date
            $getSubjectPhase = SubjectsPhases::where('subject_id', $subjectID)
                                              ->where('phase_id', $phaseID)
                                              ->first();


            if ($getSubjectPhase == null) {
                // insert into subject phases
                $getSubjectPhase = new SubjectsPhases;
                $getSubjectPhase->id = Str::uuid();
                $getSubjectPhase->subject_id = $subjectID;
                $getSubjectPhase->phase_id = $phaseID;
                $getSubjectPhase->visit_date = $findTransmission->visit_date;
                $getSubjectPhase->save();
            } // subject phases check is end


            dd('StudyID:  '.$getStudy->id.'???????  SiteID:   '.$getSite->id.'???????  Primary INV:    '.$getPrimaryInvestigator->id.'???????   Photographer:     '.$getPhotographer->id.'???????   Modality:    '.$getModality->id.'???????   Subject ID:    '.$getSubject->id.'???????   PhaseID:    '.$getPhase->id.'???????   SubjectPhaseID:      '.$getSubjectPhase->id);

        } // status check ends

        \Session::flash('success', 'Transmission information updated successfully.');

        return redirect(route('transmissions.index'));

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
}
