<?php

namespace Modules\CertificationApp\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CertificationApp\Entities\TransmissionDataPhotographer;
use Modules\CertificationApp\Entities\TransmissionDataDevice;
use Modules\CertificationApp\Entities\TestPhotographerTransmission;
use Modules\CertificationApp\Entities\CertificationTemplate;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;
use Modules\CertificationApp\Entities\StudySetup;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Site;
use Modules\CertificationApp\Entities\PhotographerTransmissionUpdateDetail;
use Modules\CertificationApp\Entities\CertificationData;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
use Mail;
use Session;
use Illuminate\Support\Str;
use PDF;

class TransmissionDataPhotographerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $getTransmissions = TransmissionDataPhotographer::query();

        if ($request->trans_id != '') {

           $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }

        if ($request->study != '') {

           $getTransmissions = $getTransmissions->where('Study_Name', 'like', '%' . $request->study . '%');
        }

        if ($request->photographer_name != '') {

           $getTransmissions = $getTransmissions->where('Photographer_First_Name', 'like', '%' . $request->photographer_name . '%');
        }

        if ($request->certification != '') {

           $getTransmissions = $getTransmissions->where('Requested_certification', 'like', '%' . $request->certification . '%');
        }

        if ($request->site != '') {

           $getTransmissions = $getTransmissions->where('Site_Name', 'like', '%' . $request->site . '%');
        }

        if ($request->status != '') {

           $getTransmissions = $getTransmissions->where('status', $request->status);
        }

        $getTransmissions = $getTransmissions->groupBy(['StudyI_ID', 'Photographer_email', 'Requested_certification', 'Site_ID'])
                                            ->orderBy('id', 'desc')
                                            ->paginate(50);

        // loop through the data and get row color and transmission details for each entry
        foreach($getTransmissions as $key => $transmission) {

            // get the no. of accepted transmission accepted for this study and modality
            $acceptedTransmissions = TransmissionDataPhotographer::where('StudyI_ID', $transmission->StudyI_ID)
                    ->where('Photographer_email', $transmission->Photographer_email)
                    ->where('Requested_certification', $transmission->Requested_certification)
                    ->where('Site_ID', $transmission->Site_ID)
                    ->where('status', 'accepted')
                    ->get()
                    ->count();

            // first get the modility ID
            $getModalityID = Modility::where('modility_name', $transmission->Requested_certification)->first();

            $getModalityID = $getModalityID != null ? $getModalityID->id : 0;

            // get study ID
            $getStudyID = Study::where('study_code', $transmission->StudyI_ID)->first();

            $getStudyID = $getStudyID != null ? $getStudyID->id : 0;
            
            // check no. of transmission for study and modility in setup table
            $getTransmissionNo = StudySetup::where('study_id', $getStudyID)->first();

            if ($getTransmissionNo != null) {

                // decode the count column
                $decodedNumberColumn = json_decode($getTransmissionNo->allowed_no_transmission);

                if (isset($decodedNumberColumn->photographer->$getModalityID)) {

                    // compare the counts
                    if($acceptedTransmissions >= $decodedNumberColumn->photographer->$getModalityID) {

                        $transmission->rowColor = 'rgba(76, 175, 80, 0.5)';

                    } else {

                        $transmission->rowColor = '';

                    } // accepted number ends

                } else {

                    $transmission->rowColor = '';

                } // index isset ends

            } else {

                $transmission->rowColor = '';
            } // study setup ends

            // get linked transmissions
            $getLinkedTransmissions = TransmissionDataPhotographer::select('id', 'Transmission_Number', 'status')
                    ->where('StudyI_ID', $transmission->StudyI_ID)
                    ->where('Photographer_email', $transmission->Photographer_email)
                    ->where('Requested_certification', $transmission->Requested_certification)
                    ->where('Site_ID', $transmission->Site_ID)
                    ->get()
                    ->toArray();

            $transmission->linkedTransmission = $getLinkedTransmissions;

        } // loop ends

        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();

        return view('certificationapp::certificate_photographer.index', compact('getTransmissions', 'getTemplates'));
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
    public function edit(Request $request, $id)
    {
        // find transmission
        $findTransmission = TransmissionDataPhotographer::where('id', decrypt($id))->first();

        // get studies
        $systemStudies = Study::get();

        // get all sites
        $getSites =Site::get();

        // get modality
        $getModalities = Modility::get();

        // get all the transmission updates
        $getTransmissionUpdates = PhotographerTransmissionUpdateDetail::where('transmission_id', decrypt($id))->get();

        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();

        return view('certificationapp::certificate_photographer.edit', compact('findTransmission', 'systemStudies', 'getSites', 'getModalities', 'getTransmissionUpdates', 'getTemplates'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        // find the transmission
        $findTransmission = TransmissionDataPhotographer::find(decrypt($id));
        
        // study ID
        if($request->StudyI_ID != "") {

            $findTransmission->StudyI_ID = $request->StudyI_ID;

            // get study Name
            $getStudy = Study::where('study_code', $request->StudyI_ID)->first();
            $findTransmission->Study_Name = $getStudy->study_short_name;

        }
        
        // get site id
        if ($request->Site_ID != "" && $request->Site_ID != "add_new") {

            $siteID = explode('/', $request->Site_ID);
            $findTransmission->transmission_site_id = $siteID[0];
            $findTransmission->Site_ID = $siteID[1];

            // get site name
            $siteName = Site::where('site_code', $siteID[1])->first();
            $findTransmission->Site_Name= $siteName->site_name;

        }

        // get modality name and madality_id
        if ($request->Requested_certification != "") {

            $modilityName = explode('/', $request->Requested_certification);
            $findTransmission->transmission_modility_id = $modilityName[0];
            $findTransmission->Requested_certification = $modilityName[1];
        }

        // status
        $findTransmission->status = $request->status;
        $findTransmission->save();

        // check for status and also store update details in transmission update table
        $transmissionUpdateDetails = new PhotographerTransmissionUpdateDetail;
        $transmissionUpdateDetails->user_id = \Auth::user()->id;
        $transmissionUpdateDetails->user_name = \Auth::user()->name;
        $transmissionUpdateDetails->transmission_id = $findTransmission->id;
        $transmissionUpdateDetails->reason_for_change = $request->reason_for_change;
        $transmissionUpdateDetails->save();

        // look for sites and photographer and insert in database accordingly
        $transmissionDataStatus = $this->transmissionStatus($findTransmission, $request);

        Session::flash('success', 'Photographer transmission information updated successfully.');

        return redirect(route('certification-photographer.edit',  $id));

    }

    public function transmissionStatus($findTransmission, $request) {

            //get study
            $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

            // if user select add new site thing
            if($request->Site_ID == "add_new") {

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

                    // update site transmission ID in Photographer Transmission Table for future Reference
                    $updatePhotographerTransmission = TransmissionDataPhotographer::where('Transmission_Number', $findTransmission->Transmission_Number)
                    ->update(['transmission_site_id' => $getSite->id]);

                } // site check is end

            } elseif ($request->Site_ID != "add_new" && $request->Site_ID != "") {

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
               
            }
            
            // check site study relation
            $getSiteStudy = StudySite::where('study_id', $getStudy->id)
                                        ->where('site_id', $getSite->id)
                                        ->first();

            if ($getSiteStudy == null) {
                // insert study site
                $getSiteStudy = new StudySite;
                $getSiteStudy->id = Str::uuid();
                $getSiteStudy->study_id = $getStudy->id;
                $getSiteStudy->site_id = $getSite->id;
                $getSiteStudy->save();

            } // site study check is end

            // get Primary Investigator
            $getPrimaryInvestigator = PrimaryInvestigator::where('site_id', $getSite->id)
                                                          ->where('first_name', $findTransmission->PI_Name)
                                                          ->where('email', $findTransmission->PI_email)
                                                          ->first();

            if ($getPrimaryInvestigator == null) {
                // insert primary investigator
                $getPrimaryInvestigator = new PrimaryInvestigator;
                $getPrimaryInvestigator->id = Str::uuid();
                $getPrimaryInvestigator->site_id = $getSite->id;
                $getPrimaryInvestigator->first_name = $findTransmission->PI_Name;
                $getPrimaryInvestigator->email = $findTransmission->PI_email;
                $getPrimaryInvestigator->save();
            } // primary investigator check ends

            // get Photographer
            $getPhotographer = Photographer::where('site_id', $getSite->id)
                                            ->where('email', $findTransmission->Photographer_email)
                                            ->first();

            if ($getPhotographer == null) {
                // insert photographer
                $getPhotographer = new Photographer;
                $getPhotographer->id = Str::uuid();
                $getPhotographer->site_id = $getSite->id;
                $getPhotographer->first_name = $findTransmission->Photographer_First_Name;
                $getPhotographer->last_name = $findTransmission->Photographer_Last_Name;
                $getPhotographer->phone = $findTransmission->Photographer_phone;
                $getPhotographer->email = $findTransmission->Photographer_email;
                $getPhotographer->save();

            } // photographer check is end

        // make array for changings dynamic variable in the text editor
        $variables = [$findTransmission->Photographer_First_Name, $findTransmission->Photographer_Last_Name, $findTransmission->StudyI_ID, $findTransmission->Study_Name, $getSite->site_code, $getSite->site_name, $getPrimaryInvestigator->first_name, $findTransmission->Requested_certification, $findTransmission->Transmission_Number, $findTransmission->status, \Auth::user()->name];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[transmission_number]]', '[[status]]', '[[sender_name]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $request->photographer_user_email;
        $ccEmail = $request->cc_email;

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $findTransmission, $getSite)
        {
            $message->subject($findTransmission->Study_Name.' '.$findTransmission->StudyI_ID.' | Photographer Request# '.$findTransmission->Transmission_Number.' | '. $getSite->site_code.' | '. $findTransmission->Requested_certification);
            $message->to($senderEmail);
            $message->cc($ccEmail);
        });
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

    public function updatePhotographerTransmissionStatus(Request $request) {

        $updateStatus = TransmissionDataPhotographer::find($request->hidden_transmission_id);
        $updateStatus->status = $request->status;
        $updateStatus->save();

        Session::flash('success', 'Status updated successfully.');
        // return to page
        return redirect()->back();

    }

    public function transmissionDataPhotographer(Request $request) {

        // remove the upper section
        $explodeGetCFtPTrans = explode('<?xml', $request);

        // concatinate xml with the remaining  xml
        $xml = '<?xml'.$explodeGetCFtPTrans[1];
        // get xml data
        $xml    = simplexml_load_string($xml);

        // check for trimission number
        $checkTransmissionNumber = TransmissionDataPhotographer::where('Transmission_Number', $xml->Transmission_Number)->first();

        if ($checkTransmissionNumber == null) {

            $saveData = new TransmissionDataPhotographer;
            $saveData->data                         = $request;
            $saveData->Transmission_Number          = $xml->Transmission_Number;  
            $saveData->Salute                       = $xml->Salute;
            $saveData->Photographer_First_Name      = $xml->Photographer_First_Name;
            $saveData->Photographer_Last_Name       = $xml->Photographer_Last_Name;
            $saveData->Photographer_email           = $xml->Photographer_email;
            $saveData->Photographer_phone           = $xml->Photographer_phone;
            $saveData->Photographer_OIRRCID         = $xml->Photographer_OIRRCID;
            $saveData->Study_Name                   = $xml->Study_Name;
            $saveData->StudyI_ID                    = $xml->StudyI_ID;
            $saveData->Study_central_email          = $xml->Study_central_email;
            $saveData->sponsor                      = $xml->sponsor;
            $saveData->Site_Name                    = $xml->Site_Name;
            $saveData->Site_ID                      = $xml->Site_ID;
            $saveData->PI_Name                      = $xml->PI_Name;
            $saveData->PI_email                     = $xml->PI_email;
            $saveData->Site_st_address              = $xml->Site_st_address;
            $saveData->Site_city                    = $xml->Site_city;
            $saveData->Site_state                   = $xml->Site_state;
            $saveData->Site_Zip                     = $xml->Site_Zip;
            $saveData->Site_country                 = $xml->Site_country;
            $saveData->Requested_certification      = $xml->Requested_certification;
            $saveData->Certification_Type           = $xml->Certification_Type;
            $saveData->Device_Model                 = $xml->Device_Model;
            $saveData->Comments                     = $xml->Comments;
            $saveData->previous_certification_status = $xml->previous_certification_status;
            $saveData->gfModality                   = $xml->gfModality;
            $saveData->gfCertifying_Study           = $xml->gfCertifying_Study;
            $saveData->gfCertifying_center          = $xml->gfCertifying_center;
            $saveData->gfCertificate_date           = $xml->gfCertificate_date;
            $saveData->Number_files                 = $xml->Number_files;
            $saveData->transmitted_file_name        = $xml->transmitted_file_name;
            $saveData->transmitted_file_size        = $xml->transmitted_file_size;
            $saveData->archive_physical_location    = $xml->archive_physical_location;
            $saveData->transmitted_file_name        = $xml->transmitted_file_name;
            $saveData->received_month               = $xml->received_month;
            $saveData->received_day                 = $xml->received_day;
            $saveData->received_year                = $xml->received_year;
            $saveData->received_hours               = $xml->received_hours;
            $saveData->received_minutes             = $xml->received_minutes;
            $saveData->received_seconds             = $xml->received_seconds;
            $saveData->Study_QCO1                   = $xml->Study_QCO1;
            $saveData->StudyQCO2                    = $xml->StudyQCO2;
            $saveData->Study_cc1                    = $xml->Study_cc1;
            $saveData->Study_cc2                    = $xml->Study_cc2;
            $saveData->QC_folder                    = $xml->QC_folder;
            $saveData->CO_folder                    = $xml->CO_folder;
            $saveData->CO_email                     = json_encode($xml->CO_email);
            $saveData->notification                 = $xml->notification;
            $saveData->notification_list            = $xml->notification_list;
            $saveData->save();

            echo "Records inserted successfully.";

        } else {

            echo 'Transmission Number already exists.';
        }
    }

    // test photographer transmission
    public function testTransmissionDataPhotographer(Request $request) {

        $saveData = new TestPhotographerTransmission;
        $saveData->data = $request;
        $saveData->save();

        echo "Data Saved";
    }

    public function getStudySetupEmail(Request $request) {

        if($request->ajax()) {

            $userEmails = [];

            // get study id
            $getStudy = Study::where('study_code', $request->study_code)->first();

            $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();
            // cc email
            $userEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_cc_email) : [];
            // study email
            $userEmails[] = $getStudyEmail != null ? $getStudyEmail->study_email : '';

            return response()->json(['userEmails' => array_filter($userEmails)]);

        } // ajax ends
    }

    public function getTransmissionData(Request $request) {

        if($request->ajax()) {

            $ccEmails = [];
            $parentModality = [];

            if ($request->type == 'photographer') {
                
                // find transmission
                $findTransmission = TransmissionDataPhotographer::find($request->transmission_id);

                // get study id
                $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

                $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();
                // cc email
                $ccEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_cc_email) : [];
                // study email
                $ccEmails[] = $getStudyEmail != null ? $getStudyEmail->study_email : '';
                // notification email
                $ccEmails[] = $findTransmission->notification == 'Yes' ? $findTransmission->notification_list : '';
                // submitter email
                $submitterEmail = $findTransmission->Photographer_email;

                // select all child modialities for this study transmission
                $getChildModalities = ChildModilities::select('child_modilities.id', 'child_modilities.modility_name')
                                                    ->leftjoin('study_modilities', 'study_modilities.child_modility_id', '=', 'child_modilities.id')
                                                    ->where('study_modilities.study_id', $getStudy->id)
                                                    ->where('study_modilities.parent_modility_id', $findTransmission->transmission_modility_id)
                                                    ->get()->toArray();

                // parent modality
                $parentModality = array(
                    'id' => $findTransmission->transmission_modility_id,
                    'modility_name' => $findTransmission->Requested_certification,
                );

                $getChildModalities[] = $parentModality;


                // get accepted transmission for this study, modality, photographer and site
                $getTransmissions = TransmissionDataPhotographer::select('id', 'Transmission_Number', 'status')
                    ->where('StudyI_ID', $findTransmission->StudyI_ID)
                    ->where('Photographer_email', $findTransmission->Photographer_email)
                    ->where('Requested_certification', $findTransmission->Requested_certification)
                    ->where('Site_ID', $findTransmission->Site_ID)
                    ->where('status', 'accepted')
                    ->get()
                    ->toArray();

            } elseif ($request->type == 'device') {

                // find transmission
                $findTransmission = TransmissionDataDevice::find($request->transmission_id);

                // get study id
                $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

                $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();
                // cc email
                $ccEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_cc_email) : [];
                // study email
                $ccEmails[] = $getStudyEmail != null ? $getStudyEmail->study_email : '';
                // notification email
                $ccEmails[] = $findTransmission->notification == 'Yes' ? $findTransmission->notification_list : '';
                // submitter email
                $submitterEmail = $findTransmission->Request_MadeBy_Email;

                // select all child modialities for this study transmission
                $getChildModalities = ChildModilities::select('child_modilities.id', 'child_modilities.modility_name')
                                ->leftjoin('study_modilities', 'study_modilities.child_modility_id', '=', 'child_modilities.id')
                                ->where('study_modilities.study_id', $getStudy->id)
                                ->where('study_modilities.parent_modility_id', $findTransmission->transmission_modility_id)
                                ->get()->toArray();

                // parent modality
                $parentModality = array(
                    'id' => $findTransmission->transmission_modility_id,
                    'modility_name' => $findTransmission->Requested_certification,
                );

                $getChildModalities[] = $parentModality;

                // get accepted transmission for this study, modality, photographer and site
                $getTransmissions = TransmissionDataDevice::select('id', 'Transmission_Number', 'status')
                    ->where('StudyI_ID', $findTransmission->StudyI_ID)
                    ->where('Request_MadeBy_Email', $findTransmission->Request_MadeBy_Email)
                    ->where('Requested_certification', $findTransmission->Requested_certification)
                    ->where('Site_ID', $findTransmission->Site_ID)
                    ->where('status', 'accepted')
                    ->get()
                    ->toArray();

            } // type check ends
            
            return response()->json(['submitterEmail' => $submitterEmail, 'ccEmails' => array_filter($ccEmails), 'getChildModalities' => $getChildModalities, 'getTransmissions' => $getTransmissions]);

        } // ajax ends
    }

    public function generatePhotographerCertificate(Request $request) {

        // find Transmission
        $findTransmission = TransmissionDataPhotographer::find($request->hidden_transmission_id);

        $newCertificateID = Str::uuid();
        $generateCertificate = new CertificationData;
        $generateCertificate->id = $newCertificateID;
        $generateCertificate->certificate_id = 'OIIRC-01-'.substr(md5(microtime()), 0, 8);
        
        // get photographer ID
        $getPhotographer = Photographer::where('site_id', $findTransmission->transmission_site_id)
                                        ->where('email', $request->user_email)
                                        ->first();

        $generateCertificate->photographer_id = $getPhotographer->id;
        $generateCertificate->photographer_email = $getPhotographer->email;
        $generateCertificate->cc_emails = json_encode($request->cc_user_email);

        // get study information
        $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

        $generateCertificate->study_id = $getStudy->id;
        $generateCertificate->study_name = $getStudy->study_short_name;

        // get site information
        $getSite = Site::where('id', $findTransmission->transmission_site_id)->first();

        $generateCertificate->site_id = $getSite->id;
        $generateCertificate->site_name = $getSite->site_name;

        $generateCertificate->device_model = $findTransmission->Device_Model;

        // get modality information
        $getModality = Modility::where('id', $request->certificate_for)->first();
        //check in child modilities
        if($getModality == null) {

            $getModality = ChildModilities::where('id', $request->certificate_for)->first();
        }

        $generateCertificate->modility_id = $getModality->id;
        $generateCertificate->certificate = $getModality->modility_name;
        $generateCertificate->certificate_for = $getModality->modility_name;

        // certificate status
        $generateCertificate->certificate_status = $request->certification_status;
        $generateCertificate->certificate_type = $request->certificate_type;

        if ($request->certificate_type == 'original') {

            $generateCertificate->transmissions = ($request->transmissions != null) ? json_encode($request->transmissions) : json_encode([]);
        
        } elseif ($request->certificate_type == 'grandfathered') {

            $generateCertificate->grandfather_certificate_id = $request->grandfather_id;
        }
        
        // issue date
        $generateCertificate->issue_date = \Carbon\Carbon::parse($request->issue_date);
        $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addYears(2);

        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        //$generateCertificate->certification_file_name = $filename;

        $generateCertificate->transmission_type = 'photographer_transmission';
        $generateCertificate->validity = 'yes';
        $generateCertificate->save();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        $file_name = uniqid().'_photographer.pdf';
        $path = storage_path('certificates_pdf/photographer');
        // generate pdf
        $pdf = PDF::loadView('certificationapp::certificate_pdf.photographer_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path.'/'.$file_name);
        

        // make array for changings dynamic variable in the text editor
        $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $findTransmission->PI_Name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $generateCertificate->photographer_email;
        $ccEmail = $generateCertificate->cc_emails != '' ? json_decode($generateCertificate->cc_emails) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $generateCertificate, $findTransmission, $getSite, $getStudy, $getModality, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | Photographer Certification# '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            $message->cc($ccEmail);
            $message->attach($path.'/'.$file_name);
        });

        // update the file name in database
        $upateFileName = CertificationData::where('id', $newCertificateID)
                                            ->update(['certificate_file_name' => $file_name]);

        Session::flash('success', 'Certicate generated successfully.');

        // return back
        return redirect()->back();
    }

}
