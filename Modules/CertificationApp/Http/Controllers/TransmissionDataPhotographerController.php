<?php

namespace Modules\CertificationApp\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CertificationApp\Entities\TransmissionDataPhotographer;
use Modules\CertificationApp\Entities\TestPhotographerTransmission;
use Modules\CertificationApp\Entities\CertificationTemplate;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;
use Modules\CertificationApp\Entities\StudySetup;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Site;
use Modules\CertificationApp\Entities\PhotographerTransmissionUpdateDetail;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
use Mail;
use Session;
use Illuminate\Support\Str;

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
        if ($request->Site_ID != "") {

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

        // make array for changings dynamic variable in the text editor
        $variables = [$findTransmission->Photographer_First_Name, $findTransmission->Photographer_Last_Name, $findTransmission->StudyI_ID, $findTransmission->Study_Name, $findTransmission->Site_ID, $findTransmission->Requested_certification, $findTransmission->Transmission_Number, $findTransmission->status];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[modality_name]]', '[[transmission_number]]', '[[status]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $request->photographer_user_email;
        $ccEmail = $request->cc_email;

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $findTransmission)
        {
            $message->subject($findTransmission->Study_Name.' '.$findTransmission->StudyI_ID.' | Photographer Request# '.$findTransmission->Transmission_Number.' | '. $findTransmission->Site_ID.' | '. $findTransmission->Requested_certification);
            $message->to($senderEmail);
            $message->cc($ccEmail);
        });

        // look for sites and photographer and insert in database accordingly
        $transmissionDataStatus = $this->transmissionStatus($findTransmission);

        Session::flash('success', 'Photographer transmission information updated successfully.');

        return redirect(route('certification-photographer.edit',  $id));

    }

    public function transmissionStatus($findTransmission) {

            //get study
            $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();

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
                $getSiteStudy->id = Str::uuid();
                $getSiteStudy->study_id = $getStudy->id;
                $getSiteStudy->site_id = $getSite->id;
                $getSiteStudy->save();

            } // site study check is end

            // get Primary Investigator
            $getPrimaryInvestigator = PrimaryInvestigator::where('site_id', $getSite->id)
                                                          ->where('first_name', $findTransmission->PI_Name)
                                                          ->first();

            if ($getPrimaryInvestigator == null) {
                // insert primary investigator
                $getPrimaryInvestigator = new PrimaryInvestigator;
                $getPrimaryInvestigator->id = Str::uuid();
                $getPrimaryInvestigator->site_id = $getSite->id;
                $getPrimaryInvestigator->first_name = $findTransmission->PI_Name;
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
            $userEmails[] = $getStudyEmail != null ? $getStudyEmail->study_email : [];

            return response()->json(['userEmails' => $userEmails]);

        } // ajax ends
    }
}
