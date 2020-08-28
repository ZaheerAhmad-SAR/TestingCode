<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\StudyStructure;
class PhaseSteps extends Model
{
    protected $fillable = ['step_id','phase_id','step_position','step_name','step_description','graders_number','q_c','eligibility'];
    // protected $key = 'string';
    protected $table = 'phase_steps';
    protected $primaryKey = "step_id";
    protected $casts = [
    	'step_id' => 'string'
	];
    // public function steps()
    // {
    //     return $this->belongsTo(StudyStructure::class,'step_id','phase_id');
    // }
    public function steps()
	{
    	return $this->belongsTo(StudyStructure::class,'phase_id','step_id');
	}

}
