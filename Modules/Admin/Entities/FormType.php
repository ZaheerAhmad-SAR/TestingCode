<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\FormTypeOrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormType extends Model
{
    use softDeletes;
    protected $fillable = ['form_type'];
    protected $table = 'form_types';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FormTypeOrderByScope);
    }

    public function steps()
    {
        return $this->hasMany(PhaseSteps::class, 'form_type_id', 'id');
    }
}
