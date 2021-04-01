<?php

namespace Modules\CertificationApp\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CertificationApp\Entities\TransmissionDataDevice;
use Modules\CertificationApp\Entities\CertificationTemplate;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;
use Modules\CertificationApp\Entities\StudySetup;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\DeviceSite;
use Modules\CertificationApp\Entities\DeviceTransmissionUpdateDetail;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
use Modules\CertificationApp\Entities\CertificationData;
use Modules\CertificationApp\Entities\StudyDevice;
use Modules\Admin\Entities\DeviceModility;
use Modules\UserRoles\Entities\Permission;
use Mail;
use PDF;
use Session;
use Illuminate\Support\Str;
use Carbon\Carbon;


class TransmissionDataDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $getTransmissions = TransmissionDataDevice::query();

        if ($request->trans_id != '') {

            $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }

        if ($request->study != '') {

            $getTransmissions = $getTransmissions->where('Study_Name', 'like', '%' . $request->study . '%');
        }

        if ($request->device_category != '') {

            $getTransmissions = $getTransmissions->where('Device_Category', 'like', '%' . $request->device_category . '%');
        }

        if ($request->device_serial != '') {

            $getTransmissions = $getTransmissions->where('Device_Serial', 'like', '%' . $request->device_serial . '%');
        }

        if ($request->site != '') {

            $getTransmissions = $getTransmissions->where('Site_Name', 'like', '%' . $request->site . '%');
        }

        if ($request->submitter_name != '') {

            $getTransmissions = $getTransmissions->where('Request_MadeBy_FirstName', 'like', "%$request->submitter_name%")
                ->orWhereRaw("concat(Request_MadeBy_FirstName, ' ', Request_MadeBy_LastName) like '$request->submitter_name' ")
                ->orWhere('Request_MadeBy_LastName', 'like', "$request->submitter_name");
        }

        if ($request->created_at != '') {
                $createdAt = explode('-', $request->created_at);
                $from   = Carbon::parse($createdAt[0])
                                    ->startOfDay()        // 2018-09-29 00:00:00.000000
                                    ->toDateTimeString(); // 2018-09-29 00:00:00
                $to     = Carbon::parse($createdAt[1])
                                    ->endOfDay()          // 2018-09-29 23:59:59.000000
                                    ->toDateTimeString(); // 2018-09-29 23:59:59
            $getTransmissions =  $getTransmissions->whereBetween('created_at', [$from, $to]);
        }

        if ($request->status != '') {

            $getTransmissions = $getTransmissions->where('status', $request->status);
        }

        $getTransmissions = $getTransmissions->where('archive_transmission', 'no')
            ->groupBy(['StudyI_ID', 'Request_MadeBy_Email', 'Requested_certification', 'Site_ID', 'Device_Serial'])
            ->orderBy('id', 'desc')->orderBy('id', 'desc')
            ->paginate(50);

        // loop through the data and get row color and transmission details for each entry
        foreach ($getTransmissions as $key => $transmission) {

            // get the no. of accepted transmission accepted for this study and modality
            $acceptedTransmissions = TransmissionDataDevice::where('StudyI_ID', $transmission->StudyI_ID)
                ->where('Request_MadeBy_Email', $transmission->Request_MadeBy_Email)
                ->where('Requested_certification', $transmission->Requested_certification)
                ->where('Site_ID', $transmission->Site_ID)
                ->where('Device_Serial', $transmission->Device_Serial)
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
            $getPhotographerID = Photographer::where('email', $transmission->Request_MadeBy_Email)
                ->where('site_id', $getSiteID)
                ->first();

            $getPhotographerID = $getPhotographerID != null ? $getPhotographerID->id : 0;

            // check no. of transmission for study and modility in setup table
            $getTransmissionNo = StudySetup::where('study_id', $getStudyID)->first();

            if ($getTransmissionNo != null) {

                // decode the count column
                $decodedNumberColumn = json_decode($getTransmissionNo->allowed_no_transmission);

                if (isset($decodedNumberColumn->device->$getModalityID)) {

                    // compare the counts
                    if ($acceptedTransmissions >= $decodedNumberColumn->device->$getModalityID) {

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
                ->where('device_serial_no', $transmission->Device_Serial)
                ->where('transmission_type', 'device_transmission')
                ->whereNULL('pdf_key')
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
            $getLinkedTransmissions = TransmissionDataDevice::select('id', 'Transmission_Number', 'status')
                ->where('StudyI_ID', $transmission->StudyI_ID)
                ->where('Request_MadeBy_Email', $transmission->Request_MadeBy_Email)
                ->where('Requested_certification', $transmission->Requested_certification)
                ->where('Site_ID', $transmission->Site_ID)
                ->where('Device_Serial', $transmission->Device_Serial)
                ->where('archive_transmission', 'no')
                ->get()
                ->toArray();

            $transmission->linkedTransmission = $getLinkedTransmissions;
            // assign status
            $transmission->certificateStatus = $certificateStatus;

        } // loop ends

        // get certification officer users
        $getCertificationOfficers = Permission::getCertificationOfficer();

        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();

        return view('certificationapp::certificate_device.index', compact('getTransmissions', 'getCertificationOfficers', 'getTemplates'));
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
        $findTransmission = TransmissionDataDevice::where('id', decrypt($id))->first();

        // transmission study
        $transmissionStudy = Study::where('study_code', $findTransmission->StudyI_ID)->with(['sites', 'modalities', 'devices'])->first();

        // get studies
        $systemStudies = Study::get();

        // get parent modality Id's
        $getModalityId = ($transmissionStudy != null) ? $transmissionStudy->modalities->pluck('id')->toArray() : [];
        
        // get Modalities
        $getStudyModalities = Modility::whereIn('id', $getModalityId)->get();

        // get modality
        $getModalities = Modility::get();

        // get all the transmission updates
        $getTransmissionUpdates = DeviceTransmissionUpdateDetail::where('transmission_id', decrypt($id))->get();

        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();

        return view('certificationapp::certificate_device.edit', compact('findTransmission', 'systemStudies', 'getModalities', 'getStudyModalities', 'getTransmissionUpdates', 'transmissionStudy', 'getTemplates'));
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
        $findTransmission = TransmissionDataDevice::find(decrypt($id));
        // study ID
        if ($request->StudyI_ID != "") {
            $findTransmission->StudyI_ID = $request->StudyI_ID;
            // get study Name/sponser
            $getStudy = Study::where('study_code', $request->StudyI_ID)->first();
            $findTransmission->Study_Name = $getStudy->study_short_name;
            $findTransmission->sponsor = $getStudy->study_sponsor;
        }
        // get site id
        if ($request->Site_ID != "") {
            $siteID = explode('__/__', $request->Site_ID);
            $findTransmission->transmission_site_id = $siteID[0];
            $findTransmission->Site_ID = $siteID[1];
            // get site name
            $siteName = Site::where('site_code', $siteID[1])->first();
            $findTransmission->Site_Name = $siteName->site_name;
            $findTransmission->Site_st_address = $siteName->site_address;
            $findTransmission->Site_city = $siteName->site_city;
            $findTransmission->Site_state = $siteName->site_state;
            $findTransmission->Site_country = $siteName->site_country;
        }
        // get dvice id
        if ($request->Device_Model != "") {
            $modelID = explode('__/__', $request->Device_Model);
            $findTransmission->transmission_device_id = $modelID[0];
            $findTransmission->Device_Model = $modelID[1];
            // get device name
            $deviceName = Device::where('device_model', $modelID[1])->first();
            $findTransmission->Device_manufacturer = $deviceName->device_manufacturer;
        }
        // update device serial
        $findTransmission->Device_Serial = $request->Device_Serial;

        // get modality name and madality_id
        if ($request->Requested_certification != "") {
            $modilityName = explode('__/__', $request->Requested_certification);
            $findTransmission->transmission_modility_id = $modilityName[0];
            $findTransmission->Requested_certification = $modilityName[1];
        }
        // status
        $findTransmission->status = $request->status;
        $findTransmission->oirrc_comment = $request->oirrc_comment;
        $findTransmission->date_of_capture = $request->date_of_capture;
        $findTransmission->assign_to = \Auth::id();
        $findTransmission->save();
        // check for status and also store update details in transmission update table
        $transmissionUpdateDetails = new DeviceTransmissionUpdateDetail;
        $transmissionUpdateDetails->user_id = \Auth::user()->id;
        $transmissionUpdateDetails->user_name = \Auth::user()->name;
        $transmissionUpdateDetails->transmission_id = $findTransmission->id;
        $transmissionUpdateDetails->reason_for_change = $request->reason_for_change;
        $transmissionUpdateDetails->save();
        // look for sites and photographer and insert in database accordingly
        $transmissionDataStatus = $this->transmissionStatus($findTransmission, $request);
        // success message
        Session::flash('success', 'Device transmission information updated successfully.');
        // return back to route
        return redirect(route('certification-device.edit',  $id));
    }

    public function transmissionStatus($findTransmission, $request)
    {
        //get study
        $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();
        // get site
        $getSite = Site::where('site_code', $findTransmission->Site_ID)->first();

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
        // get Photographer
        $getPhotographer = Photographer::where('site_id', $getSite->id)
            ->where('email', $findTransmission->Request_MadeBy_Email)
            ->first();
        if ($getPhotographer == null) {
            // insert photographer
            $getPhotographer = new Photographer;
            $getPhotographer->id = (string)Str::uuid();
            $getPhotographer->site_id = $getSite->id;
            $getPhotographer->first_name = $findTransmission->Request_MadeBy_FirstName;
            $getPhotographer->last_name = $findTransmission->Request_MadeBy_LastName;
            $getPhotographer->email = $findTransmission->Request_MadeBy_Email;
            $getPhotographer->save();
        } // photographer check is end
        
        // get device
        $getDevice = Device::where('device_model', $findTransmission->Device_Model)->first();

        // get device study
        $getDeviceStudy = StudyDevice::where('study_id', $getStudy->id)
                                        ->where('device_id', $getDevice->id)
                                        ->first();
        if($getDeviceStudy == null) {
            $getDeviceStudy = new StudyDevice;
            $getDeviceStudy->id = (string)Str::uuid();
            $getDeviceStudy->study_id = $getStudy->id;
            $getDeviceStudy->device_id = $getDevice->id;
            $getDeviceStudy->assign_by = \Auth::user()->id;
            $getDeviceStudy->save();
        } // study device ends

        /* check for device and site table */
        $getDeviceSite = DeviceSite::where('device_id', $getDevice->id)
                                    ->where('site_id', $getSite->id)
                                    ->where('device_serial', $findTransmission->Device_Serial)
                                    ->first();
        if($getDeviceSite == null) {
            $getDeviceSite = new DeviceSite;
            $getDeviceSite->id = (string)Str::uuid();
            $getDeviceSite->device_id = $getDevice->id;
            $getDeviceSite->site_id = $getSite->id;
            $getDeviceSite->device_serial = $findTransmission->Device_Serial;
            $getDeviceSite->device_software_version = $findTransmission->Device_Software_version;
            $getDeviceSite->save();
        } // device and site table insertion

        // make array for changings dynamic variable in the text editor
        $variables = [$findTransmission->Request_MadeBy_FirstName, $findTransmission->Request_MadeBy_LastName, $findTransmission->StudyI_ID, $findTransmission->Study_Name, $getSite->site_code, $getSite->site_name, $findTransmission->Requested_certification, $findTransmission->Transmission_Number, $findTransmission->status, $getDevice->device_manufacturer, $getDevice->device_model, \Auth::user()->name];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[modality_name]]', '[[transmission_number]]', '[[status]]', '[[device_manufacturer]]', '[[device_model]]', '[[sender_name]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $request->photographer_user_email;
        $ccEmail = $request->cc_email != '' ? explode(',',$request->cc_email) : '';
        $bccEmail = $request->bcc_email != '' ? explode(',',$request->bcc_email) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $findTransmission, $getSite, $getDevice)
        {
            $message->subject($findTransmission->Study_Name.' '.$findTransmission->StudyI_ID.' | Device Request# '.$findTransmission->Transmission_Number.' | '. $getSite->Site_ID.' | '. $findTransmission->Requested_certification);
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

    public function transmissionDataDevice(Request $request)
    {
        // remove the upper section
        $explodeGetCFtPTrans = explode('<?xml', $request);
        // concatinate xml with the remaining  xml
        $xml = '<?xml' . $explodeGetCFtPTrans[1];
        // get xml data
        $xml    = simplexml_load_string($xml);
        // check for trimission number
        $checkTransmissionNumber = TransmissionDataDevice::where('Transmission_Number', $xml->Transmission_Number)->first();
        if ($checkTransmissionNumber == null) {
            $saveData = new TransmissionDataDevice;
            $saveData->data                         = $request;
            $saveData->Transmission_Number          = $xml->Transmission_Number;
            $saveData->Device_Category              = $xml->Device_Category;
            $saveData->Device_manufacturer          = $xml->Device_manufacturer;
            $saveData->Device_Model                 = $xml->Device_Model;
            $saveData->Device_Serial                = $xml->Device_Serial;
            $saveData->Device_Software_version      = $xml->Device_Software_version;
            $saveData->Device_OIRRCID               = $xml->Device_OIRRCID;
            $saveData->Study_Name                   = $xml->Study_Name;
            $saveData->StudyI_ID                    = $xml->StudyI_ID;
            $saveData->Study_central_email          = $xml->Study_central_email;
            $saveData->sponsor                      = $xml->sponsor;
            $saveData->Site_Name                    = $xml->Site_Name;
            $saveData->Site_ID                      = $xml->Site_ID;
            // $saveData->PI_Name                      = $xml->PI_Name;
            // $saveData->PI_email                     = $xml->PI_email;
            $saveData->Site_st_address              = $xml->Site_st_address;
            $saveData->Site_city                    = $xml->Site_city;
            $saveData->Site_state                   = $xml->Site_state;
            $saveData->Site_Zip                     = $xml->Site_Zip;
            $saveData->Site_country                 = $xml->Site_country;
            $saveData->Requested_certification      = $xml->Requested_certification;
            $saveData->Certification_Type           = $xml->Certification_Type;
            $saveData->Request_MadeBy_FirstName     = $xml->Request_MadeBy_FirstName;
            $saveData->Request_MadeBy_LastName      = $xml->Request_MadeBy_LastName;
            $saveData->Request_MadeBy_Email         = $xml->Request_MadeBy_Email;
            $saveData->Comments                     = $xml->Comments;
            $saveData->previous_certification_status    = $xml->previous_certification_status;
            $saveData->gfModality                   = $xml->gfModality;
            $saveData->gfCertifying_Study           = $xml->gfCertifying_Study;
            $saveData->gfCertifying_center          = $xml->gfCertifying_center;
            $saveData->gfCertificate_date           = $xml->gfCertificate_date;
            $saveData->Number_files                 = $xml->Number_files;
            $saveData->transmitted_file_name        = $xml->transmitted_file_name;
            $saveData->transmitted_file_size        = $xml->transmitted_file_size;
            $saveData->archive_physical_location    = $xml->archive_physical_location;
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

    public function generateDeviceCertificate(Request $request) {

        // find Transmission
        $findTransmission = TransmissionDataDevice::find($request->hidden_transmission_id);
        // certification data
        $newCertificateID = (string)Str::uuid();
        $generateCertificate = new CertificationData;
        $generateCertificate->id = $newCertificateID;

        // get photographer ID
        $getPhotographer = Photographer::where('site_id', $findTransmission->transmission_site_id)
            ->where('email', $request->user_email)
            ->first();
        // save photographer information
        $generateCertificate->photographer_id = $getPhotographer->id;
        $generateCertificate->photographer_email = $getPhotographer->email;

        // cc and bcc emails
        $generateCertificate->cc_emails = $request->cc_user_email != '' ? json_encode(explode(',',$request->cc_user_email)) : json_encode([]);
        $generateCertificate->bcc_emails = $request->bcc_user_email != '' ? json_encode(explode(',',$request->bcc_user_email)) : json_encode([]);

        // get study information
        $getStudy = Study::where('study_code', $findTransmission->StudyI_ID)->first();
        $generateCertificate->study_id = $getStudy->id;
        $generateCertificate->study_name = $getStudy->study_short_name;

        // get site information
        $getSite = Site::where('id', $findTransmission->transmission_site_id)->first();
        $generateCertificate->site_id = $getSite->id;
        $generateCertificate->site_name = $getSite->site_name;

        // get device information
        $getDevice = Device::where('id', $findTransmission->transmission_device_id)->first();
        $generateCertificate->device_id = $getDevice->id;
        $generateCertificate->device_model = $getDevice->device_model;
        $generateCertificate->device_serial_no = $findTransmission->Device_Serial;
        $generateCertificate->device_software_version = $findTransmission->Device_Software_version;

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
            $generateCertificate->expiry_date = \Carbon\Carbon::parse($request->issue_date)->addYears(4);
        }
        $generateCertificate->certificate_type = $request->certificate_type;

        // certificate type
        if ($request->certificate_type == 'original') {
            $generateCertificate->transmissions = ($request->transmissions != null) ? json_encode($request->transmissions) : json_encode([]);
            // random string
            $generateCertificate->certificate_id = 'OIRRC-01-'.substr(md5(microtime()), 0, 8).'-O';        
        } elseif ($request->certificate_type == 'grandfathered') {
            // random string
            $generateCertificate->grandfather_certificate_id = 'Grandfater'.substr(md5(microtime()), 0, 8);
            $generateCertificate->certificate_id = 'OIRRC-01-'.substr(md5(microtime()), 0, 8).'-G';
            $generateCertificate->transmissions = json_encode([$findTransmission->Transmission_Number]);
        }

        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        //$generateCertificate->certification_file_name = $filename;
        $generateCertificate->transmission_type = 'device_transmission';
        $generateCertificate->validity = 'yes';

        // get study email to pass to pdf
        $getStudyEmail = StudySetup::where('study_id', $getStudy->id)->first();

        // check for pdf status
        if($request->pdf_key == 'generate pdf') {

            $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_device.pdf';
            $path = storage_path('certificates_pdf/device');
            // generate pdf
            $pdf = PDF::loadView('certificationapp::certificate_pdf.device_certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail, 'getModality' => $getModality, 'getDevice' => $getDevice])->setPaper('letter')->save($path . '/' . $file_name);

            // update the file name in database
            $generateCertificate->certificate_file_name = $file_name;
            $generateCertificate->save();

        } else {

            $pdf = PDF::loadView('certificationapp::certificate_pdf.device_certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail, 'getModality' => $getModality, 'getDevice' => $getDevice])->setPaper('letter');

            // stream pdf
            return $pdf->stream();

        } // pdf status check ends
       
        // call notification function for sending email
        $sendNotificationForCertificate = $this->notificationForCertificate($request, $generateCertificate);

        // success message
        Session::flash('success', 'Certificate generated successfully.');
        // return back to page
        return redirect()->back();

    } // generate device certificate

    public function certifiedDevice(Request $request)
    {
        $getCertifiedDevice = CertificationData::query();
        $getCertifiedDevice = $getCertifiedDevice->select('certification_data.*', 'photographers.first_name', 'photographers.last_name', 'photographers.email', 'photographers.phone', 'sites.site_name', 'sites.site_code', 'users.name as certification_officer_name')
            ->leftjoin('photographers', 'photographers.id', '=', 'certification_data.photographer_id')
            ->leftjoin('sites', 'sites.id', 'certification_data.site_id')
            ->leftjoin('users', 'users.id', '=', 'certification_data.certification_officer_id')
            ->where('certification_data.transmission_type', 'device_transmission')
            ->whereNULL('pdf_key');

            if ($request->certify_id != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.certificate_id', 'like', '%' . $request->certify_id . '%');
            }
            if ($request->study_name != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.study_name', 'like', '%' . $request->study_name . '%');
            }
            if ($request->site_name != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.site_name', 'like', '%' . $request->site_name . '%');
            }
            if ($request->device_model != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.device_model', 'like', '%' . $request->device_model . '%');
            }
            if ($request->device_serial_no != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.device_serial_no', 'like', '%' . $request->device_serial_no . '%');
            }
            if ($request->modility_id != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.modility_id', 'like', '%' . $request->modility_id . '%');
            }
            // if ($request->certificate_status != '') {
            //    $getCertifiedDevice = $getCertifiedDevice->where('certification_data.certificate_status', 'like', '%' . $request->certificate_status . '%');
            // }
            if ($request->certificate_type != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.certificate_type', 'like', '%' . $request->certificate_type . '%');
            }
            if ($request->validity != '') {
               $getCertifiedDevice = $getCertifiedDevice->where('certification_data.validity', 'like', '%' . $request->validity . '%');
            }
            if ($request->issue_date != '') {
                $issueDate = explode('-', $request->issue_date);
                    $from   = Carbon::parse($issueDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00
                    $to     = Carbon::parse($issueDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59
                $getCertifiedDevice =  $getCertifiedDevice->whereBetween('certification_data.issue_date', [$from, $to]);
            }
            if ($request->expiry_date != '') {
                $expiryDate = explode('-', $request->expiry_date);
                    $from   = Carbon::parse($expiryDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00
                    $to     = Carbon::parse($expiryDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59
                $getCertifiedDevice =  $getCertifiedDevice->whereBetween('certification_data.expiry_date', [$from, $to]);
            }
            $getCertifiedDevice = $getCertifiedDevice->orderBy('certification_data.created_at', 'desc')
                                                    ->paginate(50);
        // get template
        $getStudies = Study::get();
        // get parent modality
        $getParentModality = Modility::select('id', 'modility_name')->get();
        $getChildModality = ChildModilities::select('id', 'modility_name')->get();
        // get templates for email
        $getTemplates = CertificationTemplate::select('id as template_id', 'title as template_title')->get();
        return view('certificationapp::certificate_device.certified_device', compact('getCertifiedDevice', 'getStudies', 'getTemplates', 'getParentModality', 'getChildModality'));
    }

    public function approveGrandFatherDeviceCertificate(Request $request)
    {
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
        // get device information
        $getDevice = Device::where('id', $findCertificate->device_id)->first();
        $generateCertificate->device_id = $findCertificate->device_id;
        $generateCertificate->device_model = $findCertificate->device_model;
        $generateCertificate->device_serial_no = $findCertificate->device_serial_no;
        $generateCertificate->device_software_version = $findCertificate->device_software_version;
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
        $generateCertificate->certificate_id = 'OIRRC-01-'.substr(md5(microtime()), 0, 8).'-G';
        $generateCertificate->transmissions = $findCertificate->transmissions;
        // certification Officer Info
        $generateCertificate->certification_officer_id = \Auth::user()->id;
        //$generateCertificate->certification_file_name = $filename;
        $generateCertificate->transmission_type = 'device_transmission';
        $generateCertificate->validity = 'yes';
        $generateCertificate->pdf_key = $request->gf_pdf_key;
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
        $file_name = $generateCertificate->certificate_id . '_' . $getModality->modility_name . '_device.pdf';
        $path = storage_path('certificates_pdf/device');
        // generate pdf
        $pdf = PDF::loadView('certificationapp::certificate_pdf.device_certification_pdf', ['generateCertificate' => $generateCertificate, 'getStudy' => $getStudy, 'getPhotographer' => $getPhotographer, 'getSite' => $getSite, 'getStudyEmail' => $getStudyEmail, 'getModality' => $getModality, 'getDevice' => $getDevice])->setPaper('letter')->save($path . '/' . $file_name);
        // update the file name in database
        $upateFileName = CertificationData::where('certificate_id', $generateCertificate->certificate_id)
            ->update(['certificate_file_name' => $file_name]);
        // return to pdf function
        return redirect()->route('device-certificate-pdf', $file_name);
    }

    public function generateDeviceGrandfatherCertificate(Request $request) {
        // find the pdf key;
        $generateCertificate = CertificationData::where('pdf_key', $request->gf_pdf_key)->first();
        // call notification function for sending email
        $sendNotificationForCertificate = $this->notificationForCertificate($request, $generateCertificate);
        $generateCertificate->pdf_key = null;
        $generateCertificate->save();
        // session message
        Session::flash('success', 'Certificate generated successfully.');
        // return back to page
        return redirect()->back();
    } // generate device certificate

    public function notificationForCertificate($request, $generateCertificate) {

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
        $path = storage_path('certificates_pdf/device');

        // make array for changings dynamic variable in the text editor
        $variables = [$getPhotographer->first_name, $getPhotographer->last_name, $getStudy->study_code, $getStudy->study_short_name, $getSite->site_code, $getSite->site_name, $getModality->modility_name, $generateCertificate->certificate_id, \Auth::user()->name, $generateCertificate->certificate_status, $generateCertificate->certificate_type, $generateCertificate->issue_date, $generateCertificate->expiry_date, $generateCertificate->grandfather_certificate_id, $generateCertificate->device_model, $generateCertificate->device_serial_no, $generateCertificate->device_software_version];

        $labels    = ['[[first_name]]', '[[last_name]]', '[[study_code]]', '[[study_name]]', '[[site_code]]', '[[site_name]]', '[[modality_name]]', '[[certificate_id]]', '[[sender_name]]', '[[certificate_status]]', '[[certificate_type]]', '[[issue_date]]', '[[expiry_date]]', '[[grandfather_certificate_id]]', '[[device_model]]', '[[device_serial_no]]', '[[device_software_version]]'];

        $data = [];
        $data['email_body'] = str_replace($labels, $variables, $request->comment);
        $senderEmail = $generateCertificate->photographer_email;
        $ccEmail = $generateCertificate->cc_emails != '' ? json_decode($generateCertificate->cc_emails) : '';
        $bccEmail = $generateCertificate->bcc_emails != '' ? json_decode($generateCertificate->bcc_emails) : '';

        // send email to users
        Mail::send('certificationapp::emails.photographer_transmission_email', $data, function($message) use ($senderEmail, $ccEmail, $bccEmail, $generateCertificate, $getSite, $getStudy, $getModality, $path)
        {
            $message->subject($getStudy->study_short_name.' '.$getStudy->study_code.' | Device Certification# '.$generateCertificate->certificate_id.' | '. $getSite->site_code.' | '. $getModality->modility_name);
            $message->to($senderEmail);
            if($ccEmail != null) {
                $message->cc($ccEmail);
            }

            if($bccEmail != null) {
                $message->bcc($bccEmail);
            }
            $message->attach($path.'/'.$generateCertificate->certificate_file_name);

        });
    }

        public function archiveDeviceTransmission(Request $request, $transmissionID, $status) {
        // find transmission data
        $findTransmission = TransmissionDataDevice::find(decrypt($transmissionID));
        $findTransmission->archive_transmission = $status;
        $findTransmission->save();
        // session message
        Session::flash('success', 'Transmission moved to arcive successfully.');
        // redirect back to page
        return redirect()->back();
    }

    public function getArchivedDeviceTransmissionListing(Request $request) {
        
        $getTransmissions = TransmissionDataDevice::query();
        if ($request->trans_id != '') {
            $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }
        if ($request->study != '') {
            $getTransmissions = $getTransmissions->where('Study_Name', 'like', '%' . $request->study . '%');
        }
        if ($request->device_category != '') {
            $getTransmissions = $getTransmissions->where('Device_Category', 'like', '%' . $request->device_category . '%');
        }
        if ($request->device_serial != '') {
            $getTransmissions = $getTransmissions->where('Device_Serial', 'like', '%' . $request->device_serial . '%');
        }
        if ($request->site != '') {
            $getTransmissions = $getTransmissions->where('Site_Name', 'like', '%' . $request->site . '%');
        }
        if ($request->submitter_name != '') {
            $getTransmissions = $getTransmissions->where('Request_MadeBy_FirstName', 'like', "%$request->submitter_name%")
                ->orWhereRaw("concat(Request_MadeBy_FirstName, ' ', Request_MadeBy_LastName) like '$request->submitter_name' ")
                ->orWhere('Request_MadeBy_LastName', 'like', "$request->submitter_name");
        }
        if ($request->status != '') {
            $getTransmissions = $getTransmissions->where('status', $request->status);
        }
        $getTransmissions = $getTransmissions->where('archive_transmission', 'yes')
                                            ->orderBy('id', 'desc')
                                            ->paginate(50);
        return view('certificationapp::certificate_device.archived_device_transmission', compact('getTransmissions'));
    }

    public function assignDeviceTransmission(Request $request) {
        // loop through the transmissions
        $input = $request->all();
        foreach($input['check_transmission'] as $key => $value) {

            // find the transmission
            $transmission = TransmissionDataDevice::find($key);
            // update the certification officer for transmission
            $updateTransmission = TransmissionDataDevice::where('StudyI_ID', $transmission->StudyI_ID)
                                                        ->where('Request_MadeBy_Email', $transmission->Request_MadeBy_Email)
                                                        ->where('Requested_certification', $transmission->Requested_certification)
                                                        ->where('Site_ID', $transmission->Site_ID)
                                                        ->where('Device_Serial', $transmission->Device_Serial)
                                                        ->where('archive_transmission', 'no')
                                                        ->update(['assign_to' => $input['certification_officer_id']]);

        }
        // success message
        \Session::flash('success', 'Transmission assigned successfully.');
        return back();
    }
    
}
