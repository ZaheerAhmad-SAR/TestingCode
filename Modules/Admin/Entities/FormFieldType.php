<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Scopes\FormFieldTypeOrderByScope;

class FormFieldType extends Model
{
    protected $fillable = [];
    protected $table = 'form_field_type';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FormFieldTypeOrderByScope);
    }

    public function questions_type()
    {
        return $this->hasMany(Question::class);
    }
}
