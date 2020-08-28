<?php

namespace Modules\Admin\Entities;

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

}
