<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $fillable = ['form_type'];
    protected $table = 'form_types';
}
