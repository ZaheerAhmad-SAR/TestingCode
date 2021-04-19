<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class TransmissionDataPhotographer extends Model
{
    use softDeletes;
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }

    public static function getAssignUser($transmissionId) {
    	$transmission = self::find($transmissionId);

    	if($transmission->assign_to != null) {
    		return ' &nbsp; | &nbsp;<span class="badge badge-primary" data-toggle="tooltip" title="Assign to '.$transmission->user->name.'">'.self::getInitials($transmission->user->name).'</span>';
    	}

    	return null;

    }

    public static function getInitials($name) {
    	$names = explode(' ', trim($name));
    	$initials = '';
		foreach($names as $name) {
			// put same letter
		    $initials .= mb_substr($name,0,1);
		      
		}
		// return values
		return $initials;
    }

    public static function getCaptureDateStatus($transmissionId) {
    	// find the transmission
    	$transmission = self::find($transmissionId);
    	// check for same capture date
		$getCaptureTransmissions = self::where(function($query) use ($transmission) {
                                            $query->where('StudyI_ID', $transmission->StudyI_ID);
                                            $query->where('Photographer_email', $transmission->Photographer_email);
                                            $query->where('Requested_certification', $transmission->Requested_certification);
                                            $query->where('Site_ID', $transmission->Site_ID);
                                            $query->where('archive_transmission', 'no');
                                            $query->whereDate('date_of_capture', '=', $transmission->date_of_capture);
                                            $query->orWhere('transmitted_file_list', $transmission->transmitted_file_list);
                                            $query->orWhere('Received_Zip_Size', $transmission->Received_Zip_Size);
                                            $query->orWhere('Received_Zip_MD5', $transmission->Received_Zip_MD5);
                                        })->where(function($query) use ($transmission){
                                            $query->whereNotNull('date_of_capture');
                                            $query->whereNotNull('transmitted_file_list');
                                            $query->whereNotNull('Received_Zip_Size');
                                            $query->whereNotNull('Received_Zip_MD5');
                                        })->get()->count();

		if($getCaptureTransmissions > 1) {
			return ' &nbsp; | &nbsp;<span data-toggle="tooltip" title="Same capture date, file list, zip size or md5!"><i class="fas fa-exclamation-circle"></i></span>';
		}

		return null;
    }
}
