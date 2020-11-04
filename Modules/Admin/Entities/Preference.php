<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $table = 'preferences';
    protected $fillable = ['id', 'preference_title', 'preference_value', 'is_selectable', 'preference_options', 'created_at', 'updated_at','study_id'];
}
