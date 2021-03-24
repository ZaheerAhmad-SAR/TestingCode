<?php

namespace Modules\FormSubmission\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;



class FormPhotograperDataExport implements FromView
{
    public function __construct($request)
    {

    }

    public function view(): View
    {

              $sites_array = DB::connection('mysql')->table('sites')->where('deleted_at', '=' , NULL)->get();

              $result_old  = DB::connection('mysql2')->table('site')
               ->select('photographer_data.first_name','photographer_data.last_name','photographer_data.middle_name','photographer_data.site_id','photographer_data.email_address','photographer_data.phone_number')
               ->join('photographer_data', 'photographer_data.site_id', '=', 'site.OIIRC_id')
               ->groupBy('photographer_data.email_address')
               //->groupBy('photographer_data.email_address')
               ->get();
              //->toSql();
              //dd($result_old);
                
         
               $result_new = DB::connection('mysql')->table('sites')
               ->select('photographers.email','sites.site_code')
               ->join('photographers', 'sites.id', '=', 'photographers.site_id')
               ->groupBy('photographers.email')
               ->get(); 

                $phdata=[];
                foreach($result_old as $rold)
                { 

               $result_new_count = DB::connection('mysql')->table('sites')
               ->select('photographers.email','sites.site_code')
               ->join('photographers', 'sites.id', '=', 'photographers.site_id')
               ->where('photographers.email', '=' , $rold->email_address)
               ->where('sites.site_code', '=' , $rold->site_id)
               ->count(); 

               if($result_new_count==0){

           
                  $phdata[]=array_merge($phdata,['first_name' => $rold->first_name,'last_name' => $rold->last_name,'middle_name' => $rold->middle_name,'email' => $rold->email_address, 'sitecode' => $rold->site_id, 'phone_number' => $rold->phone_number]);



               }
              
             

                }
   //dd($phdata);

        return view('formsubmission::exports.export_photograpers_view', compact('sites_array', 'result_old','result_new','phdata'));
           
      
    }
}
