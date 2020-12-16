<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionsGroup extends Model
{
    use SoftDeletes;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'option_group_name',
        'option_group_description',
        'option_layout',
        'option_name',
        'option_value',
    ];

    protected $attributes = [
        'option_layout' => 'horizontal',
        'option_value' => '',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'option_group_id', 'id');
    }
}
