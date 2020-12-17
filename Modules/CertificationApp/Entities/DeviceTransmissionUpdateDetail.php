<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class DeviceTransmissionUpdateDetail extends Model
{
    protected $fillable = [];

    public $timestamps = true;

    public function users()
    {
        //return $this->hasOne(User::class, 'user_id', 'id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}