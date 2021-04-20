<?php

namespace Modules\FormSubmission\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;



class FormSiteDataExport implements FromView
{
    public function __construct($request)
    {

    }

    public function view(): View
    {
        $old_cert_sites_array = DB::connection('mysql2')->table('site')->pluck('OIIRC_id')->where('status', '!=' , -1)->toArray();
        $old_cert_sites_count = DB::connection('mysql2')->table('site')->pluck('OIIRC_id')->where('status', '!=' , -1)->count();


      
        $new_cert_sites_array = DB::connection('mysql')->table('sites')->pluck('site_code')->where('deleted_at', '=' , NULL)->toArray();
        $new_cert_sites_count = DB::connection('mysql')->table('sites')->pluck('site_code')->where('deleted_at', '=' , NULL)->count();

        $not_in_new_app_array = DB::connection('mysql2')->table('site')->whereNotIn('OIIRC_id', $new_cert_sites_array)->get();

        $not_in_new_app_count = DB::connection('mysql2')->table('site')->whereNotIn('OIIRC_id', $new_cert_sites_array)->pluck('OIIRC_id')->count();

       
        return view('formsubmission::exports.export_site_view', compact('old_cert_sites_array', 'old_cert_sites_count','new_cert_sites_count','not_in_new_app_array','not_in_new_app_count'));
      
    }
}
