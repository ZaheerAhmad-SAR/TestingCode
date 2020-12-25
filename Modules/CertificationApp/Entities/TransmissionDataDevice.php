<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransmissionDataDevice extends Model
{
    use softDeletes;
    protected $fillable = [];
}
