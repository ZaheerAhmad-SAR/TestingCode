<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TransmissionUpdateDetail extends Model
{
    //
    protected $table = 'transmission_update_details';

    public $timestamps = true;

    public function users()
    {
        //return $this->hasOne(User::class, 'user_id', 'id');
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
