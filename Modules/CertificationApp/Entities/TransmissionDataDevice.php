<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class TransmissionDataDevice extends Model
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
		$getCaptureTransmissions = self::where('StudyI_ID', $transmission->StudyI_ID)
				                        ->where('Request_MadeBy_Email', $transmission->Request_MadeBy_Email)
				                        ->where('Requested_certification', $transmission->Requested_certification)
				                        ->where('Site_ID', $transmission->Site_ID)
				                        ->where('Device_Serial', $transmission->Device_Serial)
				                        ->where('archive_transmission', 'no')
				                        ->whereDate('date_of_capture', '=', $transmission->date_of_capture)
				                        ->get()->count();
		if($getCaptureTransmissions > 1) {
			return ' &nbsp; | &nbsp;<span data-toggle="tooltip" title="Capture date is same!"><i class="fas fa-exclamation-circle"></i></span>';
		}

		return null;
    }
}
