<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\SectionOrderByScope;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'id',
        'phase_steps_id',
        'name',
        'description',
        'sort_number'
    ];

    protected $keyType ='string';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SectionOrderByScope);
    }

    public function step()
	{
    	return $this->belongsTo(PhaseSteps::class,'phase_steps_id','id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'section_id', 'id');
    }

}
