<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\StudyStructureOrderByScope;
use Illuminate\Database\Eloquent\Model;
class StudyStructure extends Model
{
    protected $fillable = ['id','name','position','duration'];
    // protected $keyType = 'string';
    protected $casts = [
    	'id' => 'string'
	];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StudyStructureOrderByScope);
    }
    
    public function phases()
    {
        return $this->hasMany(PhaseSteps::class,'phase_id','id');
    }
}
