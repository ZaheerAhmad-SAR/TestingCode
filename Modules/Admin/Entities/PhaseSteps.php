<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\PhaseStepOrderByScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\StudyStructure;
class PhaseSteps extends Model
{
    protected $fillable = ['step_id','phase_id','step_position','form_type','step_name','step_description','graders_number','q_c','eligibility'];
    // protected $key = 'string';
    protected $table = 'phase_steps';
    protected $primaryKey = "step_id";
    protected $casts = [
    	'step_id' => 'string'
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PhaseStepOrderByScope);
    }
    
    // public function steps()
    // {
    //     return $this->belongsTo(StudyStructure::class,'step_id','phase_id');
    // }
    public function steps()
	{
    	return $this->belongsTo(StudyStructure::class,'phase_id','step_id');
    }
    
    public function sections()
    {
        return $this->hasMany(Section::class, 'phase_steps_id', 'step_id');
    }

}
