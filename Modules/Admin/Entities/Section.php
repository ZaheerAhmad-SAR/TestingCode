<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\SectionOrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use softDeletes;
    protected $fillable = [
        'id',
        'phase_steps_id',
        'name',
        'description',
        'sort_number',
        'parent_id', 'replicating_or_cloning',
        'deleted_at'
    ];

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SectionOrderByScope);
    }

    public function step()
    {
        return $this->belongsTo(PhaseSteps::class, 'phase_steps_id', 'step_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'section_id', 'id');
    }
}
