<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Scopes\FormFieldTypeOrderByScope;

class FormFieldType extends Model
{
    use SoftDeletes;
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
