<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class FormFieldType extends Model
{
    protected $fillable = [];
    protected $table = 'form_field_type';

    public function questions_type()
    {
        return $this->hasMany(Question::class);
    }
}
