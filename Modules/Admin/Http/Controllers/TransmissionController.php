<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\CrushFtpTransmission;
use DB;
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

        $getCFtPTrans = DB::table('transmissions')->where('id', 9447)->first();
        
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
        dd('edit');
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        dd('update');
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
