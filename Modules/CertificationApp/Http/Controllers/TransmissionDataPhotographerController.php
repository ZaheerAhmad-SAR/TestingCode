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
use Carbon\Carbon;

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

            $getTransmissions = $getTransmissions->where('Photographer_First_Name', 'like', "%$request->photographer_name%")
                ->orWhereRaw("concat(Photographer_First_Name, ' ', Photographer_Last_Name) like '$request->photographer_name' ")
                ->orWhere('Photographer_Last_Name', 'like', "$request->photographer_name");
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

        $getTransmissions = $getTransmissions->where('archive_transmission', 'no')
            ->groupBy(['StudyI_ID', 'Photographer_email', 'Requested_certification', 'Site_ID'])
            ->orderBy('id', 'desc')
            ->paginate(50);

        // loop through the data and get row color and transmission details for each entry
        foreach ($getTransmissions as $key => $transmission) {

            // get the no. of accepted transmission accepted for this study and modality
            $acceptedTransmissions = TransmissionDataPhotographer::where('StudyI_ID', $transmission->StudyI_ID)
                ->where('Photographer_email', $transmission->Photographer_email)
                ->where('Requested_certification', $transmission->Requested_certification)
                ->where('Site_ID', $transmission->Site_ID)
                ->where('status', 'accepted')
                ->where('archive_transmission', 'no')
                ->get()
                ->count();

            // first get the modility ID
            $getModalityID = Modility::where('modility_name', $transmission->Requested_certification)->first();
            $getModalityID = $getModalityID != null ? $getModalityID->id : 0;

            // get study ID
            $getStudyID = Study::where('study_code', $transmission->StudyI_ID)->first();
            $getStudyID = $getStudyID != null ? $getStudyID->id : 0;

            // get Site ID
            $getSiteID = Site::where('site_code', $transmission->Site_ID)->first();
            $getSiteID = $getSiteID != null ? $getSiteID->id : 0;

            // get photographer ID
            $getPhotographerID = Photographer::where('email', $transmission->Photographer_email)
                ->where('site_id', $getSiteID)
                ->first();
            $getPhotographerID = $getPhotographerID != null ? $getPhotographerID->id : 0;

            // check no. of transmission for study and modility in setup table
            $getTransmissionNo = StudySetup::where('study_id', $getStudyID)->first();



            if ($getTransmissionNo != null) {

                // decode the count column
                $decodedNumberColumn = json_decode($getTransmissionNo->allowed_no_transmission);


                if (isset($decodedNumberColumn->photographer->$getModalityID)) {
                    
                    // compare the counts
                    if ($acceptedTransmissions >= $decodedNumberColumn->photographer->$getModalityID) {

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

            // get child modalities as well
            $getChildModalities = [];
            $getChildModalities = ChildModilities::where('modility_id', $getModalityID)->pluck('id')->toArray();
            // assignparent ID as well
            $getChildModalities[] = $getModalityID;

            $certificateStatus = [];

            // check if this transmission has already been certified for this modality and study
            $certifiedTransmission = CertificationData::select('id as certificate_id', 'certificate_status')
                ->where('study_id', $getStudyID)
                ->whereIn('modility_id', $getChildModalities)
                ->where('photographer_id', $getPhotographerID)
                ->where('site_id', $getSiteID)
                ->where('transmission_type', 'photographer_transmission')
                ->first();

            if ($certifiedTransmission != null) {

                // check for provisional or full certificate
                $certificateStatus['status'] = $certifiedTransmission->certificate_status == 'provisional' ? 'provisional' : 'full';
                $certificateStatus['certificate_id'] = $certifiedTransmission->certificate_id;
            } else {

                // look for number of counts
                $certificateStatus['status'] = $acceptedTransmissions > 0 ? 'allowed' : 'not_allowed';
                $certificateStatus['certificate_id'] = 0;
            }

            // get linked transmissions

            $getLinkedTransmissions = TransmissionDataPhotographer::select('id', 'Transmission_Number', 'status', 'pathology')
                    ->where('StudyI_ID', $transmission->StudyI_ID)
                    ->where('Photographer_email', $transmission->Photographer_email)
                    ->where('Requested_certification', $transmission->Requested_certification)
                    ->where('Site_ID', $transmission->Site_ID)
                    ->where('archive_transmission', 'no')
                    ->get()
                    ->toArray();

            $transmission->linkedTransmission = $getLinkedTransmissions;
            // assign status
            $transmission->certificateStatus = $certificateStatus;
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
        $getSites = Site::get();

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
        if ($request->StudyI_ID != "") {

            $findTransmission->StudyI_ID = $request->StudyI_ID;

            // get study Name
            $getStudy = Study::where('study_code', $request->StudyI_ID)->first();
            $findTransmission->Study_Name = $getStudy->study_short_name;
        }

        // get site id
        if ($request->Site_ID != "" && $request->Site_ID != "add_new") {

            $siteID = explode('__/__', $request->Site_ID);
            $findTransmission->transmission_site_id = $siteID[0];
            $findTransmission->Site_ID = $siteID[1];

            // get site name
            $siteName = Site::where('site_code', $siteID[1])->first();
            $findTransmission->Site_Name = $siteName->site_name;
        }

        // get modality name and madality_id
        if ($request->Requested_certification != "") {

            $modilityName = explode('__/__', $request->Requested_certification);
            $findTransmission->transmission_modility_id = $modilityName[0];
            $findTransmission->Requested_certification = $modilityName[1];
        }

        // status
        $findTransmission->status = $request->status;
        $findTransmission->pathology = $request->pathology;
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

    public function transmissionStatus($findTransmission, $request)
    {

        //get study
        $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

        // if user select add new site thing
        if ($request->Site_ID == "add_new") {

            $getSite = Site::where('site_code', $findTransmission->Site_ID)->first();

            if ($getSite == null) {
                // insert site
                $getSite = new Site;
                $getSite->id = (string)Str::uuid();
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
                $getSite->id = (string)Str::uuid();
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
            $getSiteStudy->id = (string)Str::uuid();
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
            $getPrimaryInvestigator->id = (string)Str::uuid();
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
            $getPhotographer->id = (string)Str::uuid();
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
        $ccEmail = $request->cc_email != null ? $request->cc_email : '';
        $bccEmail = $request->bcc_email != null ? $request->bcc_email : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $findTransmission, $getSite)
        {
            $message->subject($findTransmission->Study_Name.' '.$findTransmission->StudyI_ID.' | Photographer Request# '.$findTransmission->Transmission_Number.' | '. $getSite->site_code.' | '. $findTransmission->Requested_certification);

            $message->to($senderEmail);
            
            if($ccEmail != '') {
                $message->cc($ccEmail);
            }
            if($bccEmail != '') {
                $message->bcc($bccEmail);
            }
            
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

    // public function updatePhotographerTransmissionStatus(Request $request)
    // {

    //     $updateStatus = TransmissionDataPhotographer::find($request->hidden_transmission_id);
    //     $updateStatus->status = $request->status;
    //     $updateStatus->save();

    //     Session::flash('success', 'Status updated successfully.');
    //     // return to page
    //     return redirect()->back();
    // }

    public function transmissionDataPhotographer(Request $request)
    {

        // remove the upper section
        $explodeGetCFtPTrans = explode('<?xml', $request);

        // concatinate xml with the remaining  xml
        $xml = '<?xml' . $explodeGetCFtPTrans[1];
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
    public function testTransmissionDataPhotographer(Request $request)
    {

        $saveData = new TestPhotographerTransmission;
        $saveData->data = $request;
        $saveData->save();

        echo "Data Saved";
    }

    public function getStudySetupEmail(Request $request)
    {

        if ($request->ajax()) {

            $userEmails = [];
            $userBCCEmails = [];

            // get study id
            $getStudy = Study::where('study_code', $request->study_code)->first();

            $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();
            // cc email
            $userEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_cc_email) : [];
            // study email
            $userEmails[] = $getStudyEmail != null ? $getStudyEmail->study_email : '';

            // get BCC email
            $userBCCEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_bcc_email) : [];

            return response()->json(['userEmails' => array_filter($userEmails), 'userBCCEmails' => array_filter($userBCCEmails)]);

        } // ajax ends
    }

    public function getTransmissionData(Request $request)
    {

        if ($request->ajax()) {

            $ccEmails = [];
            $bccEmails = [];
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

                // get bcc email
                $bccEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_bcc_email) : [];

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
                    ->where('archive_transmission', 'no')
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

                // get bcc email
                $bccEmails = $getStudyEmail != null ? explode(',', $getStudyEmail->study_bcc_email) : [];

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
                    ->where('archive_transmission', 'no')
                    ->get()
                    ->toArray();
            } // type check ends
   
            return response()->json(['submitterEmail' => $submitterEmail, 'ccEmails' => array_filter($ccEmails), 'bccEmails' => array_filter($bccEmails), 'getChildModalities' => $getChildModalities, 'getTransmissions' => $getTransmissions]);

        } // ajax ends
    }

    public function generatePhotographerCertificate(Request $request)
    {

        // find Transmission
        $findTransmission = TransmissionDataPhotographer::find($request->hidden_transmission_id);

        $newCertificateID = (string)Str::uuid();
        $generateCertificate = new CertificationData;
        $generateCertificate->id = $newCertificateID;

        // get photographer ID
        $getPhotographer = Photographer::where('site_id', $findTransmission->transmission_site_id)
            ->where('email', $request->user_email)
            ->first();

        $generateCertificate->photographer_id = $getPhotographer->id;
        $generateCertificate->photographer_email = $getPhotographer->email;
        $generateCertificate->cc_emails = json_encode($request->cc_user_email);
        $generateCertificate->bcc_emails = json_encode($request->bcc_user_email);

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
        if ($getModality == null) {

            $getModality = ChildModilities::where('id', $request->certificate_for)->first();
        }

        $generateCertificate->modility_id = $getModality->id;
        $generateCertificate->certificate = $getModality->modility_name;
        $generateCertificate->certificate_for = $getModality->modility_name;

        // certificate status
        $generateCertificate->certificate_status = $request->certification_status;

        // check if it is full or provisional
        if ($request->certification_status == 'provisional') {

            // issue date
            $generateCertificate->issue_date = \Carbon\Carbon::parse($request->issue_date);
            $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addMonths(3);
        } else {

            // issue date
            $generateCertificate->issue_date = \Carbon\Carbon::parse($request->issue_date);
            $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addYears(2);
        }

        $generateCertificate->certificate_type = $request->certificate_type;

        if ($request->certificate_type == 'original') {

            $generateCertificate->transmissions = ($request->transmissions != null) ? json_encode($request->transmissions) : json_encode([]);

            $generateCertificate->certificate_id = 'OIRRC-02-'.substr(md5(microtime()), 0, 8).'-O';

        
        } elseif ($request->certificate_type == 'grandfathered') {

            $generateCertificate->grandfather_certificate_id = 'Grandfater'.substr(md5(microtime()), 0, 8);

            $generateCertificate->certificate_id = 'OIRRC-02-'.substr(md5(microtime()), 0, 8).'-G';
        
        }

        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        //$generateCertificate->certification_file_name = $filename;

        $generateCertificate->transmission_type = 'photographer_transmission';
        $generateCertificate->validity = 'yes';
        $generateCertificate->save();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_photographer.pdf';
        $path = storage_path('certificates_pdf/photographer');
        // generate pdf
        $pdf = PDF::loadView('certificationapp::certificate_pdf.certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path . '/' . $file_name);


        // make array for changings dynamic variable in the text editor
        $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $findTransmission->PI_Name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $generateCertificate->photographer_email;
        $ccEmail = $generateCertificate->cc_emails != '' ? json_decode($generateCertificate->cc_emails) : '';
        $bccEmail = $generateCertificate->bcc_emails != '' ? json_decode($generateCertificate->bcc_emails) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $findTransmission, $getSite, $getStudy, $getModality, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | Photographer Certification# '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            if($ccEmail != null) {
                $message->cc($ccEmail);
            }

            if($bccEmail != null) {
                $message->bcc($bccEmail);
            }
            
            $message->attach($path.'/'.$file_name);
        });

        // update the file name in database
        $upateFileName = CertificationData::where('id', $newCertificateID)
            ->update(['certificate_file_name' => $file_name]);

        Session::flash('success', 'Certicate generated successfully.');

        // return back
        return redirect()->back();
    }

    public function updatePhotographerProvisonalCertificate(Request $request)
    {

        // find transmission
        $findTransmission = TransmissionDataPhotographer::find($request->hidden_transmission_id);

        // generate new certificate
        $generateCertificate = CertificationData::find($request->hidden_photographer_certification_id);
        // update cc emails
        $generateCertificate->cc_emails = json_encode($request->cc_user_email);
        $generateCertificate->bcc_emails = json_encode($request->bcc_user_email);

        // remove previous pdf certificate for this record
        @unlink(storage_path('/certificates_pdf/photographer/' . $generateCertificate->certificate_file_name));

        // get modality information
        $getModality = Modility::where('id', $request->certificate_for)->first();
        //check in child modilities
        if ($getModality == null) {

            $getModality = ChildModilities::where('id', $request->certificate_for)->first();
        }

        $generateCertificate->modility_id = $getModality->id;
        $generateCertificate->certificate = $getModality->modility_name;
        $generateCertificate->certificate_for = $getModality->modility_name;

        // certificate status
        $generateCertificate->certificate_status = $request->certification_status;

        // check if it is full or provisional
        if ($request->certification_status == 'provisional') {

            // issue date
            $generateCertificate->issue_date = \Carbon\Carbon::parse($request->issue_date);
            $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addMonths(3);
        } else {

            // issue date
            $generateCertificate->issue_date = \Carbon\Carbon::parse($request->issue_date);
            $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addYears(2);
        }

        $generateCertificate->certificate_type = $request->certificate_type;

        if ($request->certificate_type == 'original') {

            $generateCertificate->transmissions = ($request->transmissions != null) ? json_encode($request->transmissions) : json_encode([]);

            $generateCertificate->certificate_id = str_replace('-G', '-O', $generateCertificate->certificate_id);

            $generateCertificate->grandfather_certificate_id = '';
        } elseif ($request->certificate_type == 'grandfathered') {

            $generateCertificate->grandfather_certificate_id = 'Grandfater' . substr(md5(microtime()), 0, 8);

            $generateCertificate->certificate_id = str_replace('-O', '-G', $generateCertificate->certificate_id);
        }

        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        $generateCertificate->save();


        /** ---------------------------- Email Section ---------------------------------- **/

        // get photographer ID
        $getPhotographer = Photographer::find($generateCertificate->photographer_id);

        // get study information
        $getStudy = Study::find($generateCertificate->study_id);

        // get site information
        $getSite = Site::where('id', $generateCertificate->site_id)->first();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_photographer.pdf';
        $path = storage_path('certificates_pdf/photographer');
        // generate pdf
        $pdf = PDF::loadView('certificationapp::certificate_pdf.certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path . '/' . $file_name);


        // make array for changings dynamic variable in the text editor
        $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $findTransmission->PI_Name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $generateCertificate->photographer_email;
        $ccEmail = $generateCertificate->cc_emails != '' ? json_decode($generateCertificate->cc_emails) : '';
        $bccEmail = $generateCertificate->bcc_emails != '' ? json_decode($generateCertificate->bcc_emails) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $findTransmission, $getSite, $getStudy, $getModality, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | Photographer Certification# '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            if($ccEmail != null) {
                $message->cc($ccEmail);
            }
             if($bccEmail != null) {
                $message->bcc($bccEmail);
            }
            
            $message->attach($path.'/'.$file_name);
        });

        // update the file name in database
        $upateFileName = CertificationData::where('id', $generateCertificate->id)
            ->update(['certificate_file_name' => $file_name]);

        Session::flash('success', 'Certicate generated successfully.');

        // return back
        return redirect()->back();
    }

    public function certifiedPhotographer(Request $request)
    {

        $getCertifiedPhotographer = CertificationData::query();
        $getCertifiedPhotographer = $getCertifiedPhotographer->select('certification_data.*', 'photographers.first_name', 'photographers.last_name', 'photographers.email', 'photographers.phone', 'sites.site_name', 'sites.site_code', 'users.name as certification_officer_name')
            ->leftjoin('photographers', 'photographers.id', '=', 'certification_data.photographer_id')
            ->leftjoin('sites', 'sites.id', 'certification_data.site_id')
            ->leftjoin('users', 'users.id', '=', 'certification_data.certification_officer_id')
            ->where('certification_data.transmission_type', 'photographer_transmission');
            // ->whereNULL('photographers.deleted_at')
            // ->whereNULL('sites.deleted_at')
            // ->whereNULL('users.deleted_at');

            if ($request->certify_id != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.certificate_id', 'like', '%' . $request->certify_id . '%');
            }

            if ($request->study_name != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.study_name', 'like', '%' . $request->study_name . '%');
            }

            if ($request->photographer_name != '') {

                $getCertifiedPhotographer = $getCertifiedPhotographer->where('photographers.first_name', 'like', "%$request->photographer_name%")
                    ->orWhereRaw("concat(photographers.first_name, ' ', photographers.last_name) like '$request->photographer_name' ")
                    ->orWhere('photographers.last_name', 'like', "$request->photographer_name");
            }

            if ($request->site_name != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.site_name', 'like', '%' . $request->site_name . '%');
            }

            if ($request->modility_id != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.modility_id', 'like', '%' . $request->modility_id . '%');
            }

            if ($request->certificate_status != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.certificate_status', 'like', '%' . $request->certificate_status . '%');
            }

            if ($request->certificate_type != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.certificate_type', 'like', '%' . $request->certificate_type . '%');
            }

            if ($request->validity != '') {

               $getCertifiedPhotographer = $getCertifiedPhotographer->where('certification_data.validity', 'like', '%' . $request->validity . '%');
            }

            if ($request->issue_date != '') {

                $issueDate = explode('-', $request->issue_date);
                    $from   = Carbon::parse($issueDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00

                    $to     = Carbon::parse($issueDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59

                $getCertifiedPhotographer =  $getCertifiedPhotographer->whereBetween('certification_data.issue_date', [$from, $to]);
            }

            if ($request->expiry_date != '') {

                $expiryDate = explode('-', $request->expiry_date);
                    $from   = Carbon::parse($expiryDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00

                    $to     = Carbon::parse($expiryDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59

                $getCertifiedPhotographer =  $getCertifiedPhotographer->whereBetween('certification_data.expiry_date', [$from, $to]);
            }


            $getCertifiedPhotographer = $getCertifiedPhotographer->orderBy('certification_data.created_at', 'desc')
                                                                ->paginate(50);

        // get template
        $getStudies = Study::get();

        // get parent modality
        $getParentModality = Modility::select('id', 'modility_name')->get();
        $getChildModality = ChildModilities::select('id', 'modility_name')->get();

        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();

        return view('certificationapp::certificate_photographer.certified_photographer', compact('getCertifiedPhotographer', 'getStudies', 'getTemplates', 'getParentModality', 'getChildModality'));

    }

    public function checkGrandfatherCertificate(Request $request)
    {

        if ($request->ajax()) {

            // find certificate
            $findCertificate = CertificationData::where('certificate_id', $request->certificate_id)->first();

            if ($request->type == 'photographer') {

                // check grandfather certificate on the basis of study, modality, site, photographer
                $checkGrandfather = CertificationData::where('study_id', $request->study_id)
                                    ->where('modility_id', $findCertificate->modility_id)
                                    ->where('site_id', $findCertificate->site_id)
                                    ->where('photographer_id', $findCertificate->photographer_id)
                                    ->where('transmission_type', 'photographer_transmission')
                                    //->where('certificate_type', '!=', 'grandfathered')
                                    ->where('validity', 'yes')
                                    ->first();

            } else {

                // check grandfather certificate on the basis of study, modality, site, photographer
                $checkGrandfather = CertificationData::where('study_id', $request->study_id)
                                    ->where('modility_id', $findCertificate->modility_id)
                                    ->where('site_id', $findCertificate->site_id)
                                    ->where('photographer_id', $findCertificate->photographer_id)
                                    ->where('device_serial_no', $findCertificate->device_serial_no)
                                    ->where('transmission_type', 'device_transmission')
                                    //->where('certificate_type', '!=', 'grandfathered')
                                    ->where('validity', 'yes')
                                    ->first();
            }

            if ($checkGrandfather != null) {

                return response()->json(['success' => 'false']);
            } else {

                return response()->json(['success' => 'true']);
            } // null check

        } // ajax request
    }

    public function generatePhotographerGrandfatherCertificate(Request $request)
    {

        // find crtificate
        $findCertificate = CertificationData::where('certificate_id', $request->certificate_id)->first();

        $newCertificateID = Str::uuid();
        $generateCertificate = new CertificationData;
        $generateCertificate->id = $newCertificateID;

        $generateCertificate->photographer_id = $findCertificate->photographer_id;
        $generateCertificate->photographer_email = $findCertificate->photographer_email;
        $generateCertificate->cc_emails = json_encode($request->cc_user_email);
        $generateCertificate->bcc_emails = json_encode($request->bcc_user_email);

        // get study information
        $getStudy = Study::where('id', $request->study)->first();
        $generateCertificate->study_id = $getStudy->id;
        $generateCertificate->study_name = $getStudy->study_short_name;

        $generateCertificate->site_id = $findCertificate->site_id;
        $generateCertificate->site_name = $findCertificate->site_name;

        $generateCertificate->device_model = $findCertificate->device_model;

        $generateCertificate->modility_id = $findCertificate->modility_id;
        $generateCertificate->certificate = $findCertificate->certificate;
        $generateCertificate->certificate_for = $findCertificate->certificate_for;

        // certificate status
        $generateCertificate->certificate_status = $findCertificate->certificate_status;

        // issue date
        $generateCertificate->issue_date = $findCertificate->issue_date;
        $generateCertificate->expiry_date = $findCertificate->expiry_date;

        $generateCertificate->certificate_type = 'grandfathered';

        $generateCertificate->grandfather_certificate_id = 'Grandfater'.substr(md5(microtime()), 0, 8);
        $generateCertificate->certificate_id = 'OIRRC-02-'.substr(md5(microtime()), 0, 8).'-G';

        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        //$generateCertificate->certification_file_name = $filename;

        $generateCertificate->transmission_type = 'photographer_transmission';
        $generateCertificate->validity = 'yes';
        $generateCertificate->save();

        /** ---------------------------- Email Section ---------------------------------- **/

        $getModality = Modility::where('id', $generateCertificate->modility_id)->first();
        //check in child modilities
        if ($getModality == null) {

            $getModality = ChildModilities::where('id', $generateCertificate->modility_id)->first();
        }

        // get photographer ID
        $getPhotographer = Photographer::find($generateCertificate->photographer_id);

        // get site information
        $getSite = Site::where('id', $generateCertificate->site_id)->first();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_grandfathered_photographer.pdf';
        $path = storage_path('certificates_pdf/photographer');
        // generate pdf
        $pdf = PDF::loadView('certificationapp::certificate_pdf.certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path . '/' . $file_name);


        // make array for changings dynamic variable in the text editor
        $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, '', $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $generateCertificate->photographer_email;
        $ccEmail = $generateCertificate->cc_emails != '' ? json_decode($generateCertificate->cc_emails) : '';
        $bccEmail = $generateCertificate->bcc_emails != '' ? json_decode($generateCertificate->bcc_emails) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $getSite, $getStudy, $getModality, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | Grandfather Photographer Certification# '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            if($ccEmail != '') {
                $message->cc($ccEmail);
            }
            if($bccEmail != '') {
                $message->bcc($bccEmail);
            }
            
            $message->attach($path.'/'.$file_name);

        });

        // update the file name in database
        $upateFileName = CertificationData::where('id', $newCertificateID)
            ->update(['certificate_file_name' => $file_name]);

        Session::flash('success', 'Certicate generated successfully.');

        // return back
        return redirect()->back();
    }

     public function archivePhotographerTransmission(Request $request, $transmissionID, $status) {

        $findTransmission = TransmissionDataPhotographer::find(decrypt($transmissionID));
        $findTransmission->archive_transmission = $status;
        $findTransmission->save();

        Session::flash('success', 'Transmission moved to arcive successfully.');

        return redirect()->back();
    }

    public function getArchivedPhotographerTransmissionListing(Request $request) {
        
        //dd($request);

        $getTransmissions = TransmissionDataPhotographer::query();

        if ($request->trans_id != '') {

            $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }

        if ($request->study != '') {

            $getTransmissions = $getTransmissions->where('Study_Name', 'like', '%' . $request->study . '%');
        }

        if ($request->photographer_name != '') {

            $getTransmissions = $getTransmissions->where('Photographer_First_Name', 'like', "%$request->photographer_name%")
                ->orWhereRaw("concat(Photographer_First_Name, ' ', Photographer_Last_Name) like '$request->photographer_name' ")
                ->orWhere('Photographer_Last_Name', 'like', "$request->photographer_name");
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

        $getTransmissions = $getTransmissions->where('archive_transmission', 'yes')
                                            ->orderBy('id', 'desc')
                                            ->paginate(50);

        return view('certificationapp::certificate_photographer.archived_photographer_transmission', compact('getTransmissions'));                              
    }

    public function changeCertificateStatus(Request $request) {
        
        $generateCertificate = CertificationData::where('certificate_id', $request->status_certificate_id)->first();
        $generateCertificate->certificate_status = $request->certification_status;
        $generateCertificate->save();

        $getModality = Modility::where('id', $generateCertificate->modility_id)->first();
        //check in child modilities
        if ($getModality == null) {

            $getModality = ChildModilities::where('id', $generateCertificate->modility_id)->first();
        }
        // get photographer ID
        $getPhotographer = Photographer::find($generateCertificate->photographer_id);

        // get study information
        $getStudy = Study::find($generateCertificate->study_id);

        // get site information
        $getSite = Site::where('id', $generateCertificate->site_id)->first();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        // check transmission type
        if ($generateCertificate->transmission_type == 'device_transmission') {

            $file_name = $generateCertificate->certificate_file_name;
            $path = storage_path('certificates_pdf/device');

            $certificateType = 'Device Certificate#';

            // make array for changings dynamic variable in the text editor
            $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id, $generateCertificate->device_model, $generateCertificate->device_serial_no, $generateCertificate->user_input_device_id];

            $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]', '[[device_model]]', '[[device_serial_no]]', '[[device_id]]'];

        } else {

            $file_name = $generateCertificate->certificate_file_name;
            $path = storage_path('certificates_pdf/photographer');

            $certificateType = 'Photographer Certificate#';

            // make array for changings dynamic variable in the text editor
            $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, '', $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id];

            $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[pi_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]'];

        }

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->status_comment);
        $senderEmail = $request->status_user_email;
        $ccEmail = $request->status_cc_user_email;
        $bccEmail = $request->status_bcc_user_email;

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $getSite, $getStudy, $getModality, $certificateType, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | '.$certificateType.' '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            if($ccEmail != '') {
                $message->cc($ccEmail);
            }
            if($bccEmail != '') {
                $message->bcc($bccEmail);
            }
            if ($generateCertificate->certificate_status == 'full' || $generateCertificate->certificate_status == 'provisional') {
                $message->attach($path.'/'.$file_name);
            }

        });

        return redirect()->back();
    
    }

    public function changeCertificateDate(Request $request) {

        $generateCertificate = CertificationData::where('certificate_id', $request->date_certificate_id)->first();
        $generateCertificate->expiry_date = $request->certificate_expiry_date;
        $generateCertificate->save();

        $getModality = Modility::where('id', $generateCertificate->modility_id)->first();
        //check in child modilities
        if ($getModality == null) {

            $getModality = ChildModilities::where('id', $generateCertificate->modility_id)->first();
        }
        // get photographer ID
        $getPhotographer = Photographer::find($generateCertificate->photographer_id);

        // get study information
        $getStudy = Study::find($generateCertificate->study_id);

        // get site information
        $getSite = Site::where('id', $generateCertificate->site_id)->first();

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        // for photographer date update
        if ($generateCertificate->transmission_type == 'photographer_transmission') {

            $certificateType = 'Photographer Certificate#';

            $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_photographer.pdf';
            $path = storage_path('certificates_pdf/photographer');
            // generate pdf
            $pdf = PDF::loadView('certificationapp::certificate_pdf.certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path . '/' . $file_name);

            // make array for changings dynamic variable in the text editor
            $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id];

            $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]'];

        } else {

            $certificateType = 'Device Certificate#';

            $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_device.pdf';
            $path = storage_path('certificates_pdf/device');
            // generate pdf
            $pdf = PDF::loadView('certificationapp::certificate_pdf.certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail])->setPaper('a4')->save($path . '/' . $file_name);

            // make array for changings dynamic variable in the text editor
            $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id, $generateCertificate->device_model, $generateCertificate->device_serial_no, $generateCertificate->user_input_device_id];

            $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]', '[[device_model]]', '[[device_serial_no]]', '[[device_id]]'];
        }

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->date_comment);
        $senderEmail = $request->date_user_email;
        $ccEmail = $request->date_cc_user_email;
        $bccEmail = $request->date_bcc_user_email;

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $getSite, $getStudy, $getModality, $certificateType, $path, $file_name)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | '.$certificateType.' '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            
            $message->to($senderEmail);

            if($ccEmail != '') {
                $message->cc($ccEmail);
            }
            if($bccEmail != '') {
                $message->bcc($bccEmail);
            }
            
            $message->attach($path.'/'.$file_name);

        });

        \Session::flash('success', 'Certificate expiry date updated successfully.');

        return redirect()->back();

    } // certificate date change function ends

}
