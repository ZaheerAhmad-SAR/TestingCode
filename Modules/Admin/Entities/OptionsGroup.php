<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class OptionsGroup extends Model
{
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'option_group_name',
        'option_group_description',
        'option_layout',
        'option_name',
        'option_value',
    ];


}
