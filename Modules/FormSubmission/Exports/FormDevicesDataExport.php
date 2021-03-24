<?php

namespace Modules\FormSubmission\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;



class FormDevicesDataExport implements FromView
{
    public function __construct($request)
    {

    }

    public function view(): View
    {


              $devices  = DB::connection('mysql2')->table('certify_device')
               ->select('certify_device.site_id','certify_device.device_categ','certify_device.device_manf','certify_device.device_model','certify_device.device_sn')
               ->join('site', 'certify_device.site_id', '=', 'site.OIIRC_id')
               ->get();

        return view('formsubmission::exports.export_devices_view', compact('devices'));
           
      
    }
}
