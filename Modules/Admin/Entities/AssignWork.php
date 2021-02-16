<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\Study;

class AssignWork extends Model
{
    use softDeletes;
    protected $table = 'assign_work';

    public function get_form_status()
    {
        return $this->hasOne(FormStatus::class, 'modility_id', 'modility_id');
    }

    public function study() {
        return $this->belongsTo(Study::class, 'study_id', 'id');

    }

    public static function getAssignWorkStatus($userId, $studyId, $today, $fourteenDaysWork = null, $formTypeId, $modilityId, $type) {

    	if($type == 'missingWork') {

    		return self::where('user_id', $userId)
			            ->where('study_id', $studyId)
			            ->where('form_type_id', $formTypeId)
			            ->where('modility_id', $modilityId)
			            ->whereDate('assign_date', '<' , $today)
			            ->get();

    	} elseif($type == 'fourteenDaysWork') {

    		return self::where('user_id', $userId)
			            ->where('study_id', $studyId)
			            ->where('form_type_id', $formTypeId)
			            ->where('modility_id', $modilityId)
			           	->whereBetween('assign_date', [$today, $fourteenDaysWork])
			            ->get();

    	} elseif($type == 'allDaysWork') {

    		return self::where('user_id', $userId)
			            ->where('study_id', $studyId)
			            ->where('form_type_id', $formTypeId)
			            ->where('modility_id', $modilityId)
			            ->get();
			            
    	}

    }
}
