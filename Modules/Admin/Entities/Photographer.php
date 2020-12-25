<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photographer extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'first_name', 'mid_name', 'last_name', 'site_id', 'phone', 'email'];
    public $incrementing = false;
    protected $keyType = 'string';
}
