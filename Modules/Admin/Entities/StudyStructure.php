<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
class StudyStructure extends Model
{
    protected $fillable = ['id','name','position','duration'];
    // protected $keyType = 'string';
    protected $casts = [
    	'id' => 'string'
	];

    public function phases()
    {
        return $this->hasMany(PhaseSteps::class,'phase_id','id');
    }
}
