<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;


class CertificationData extends Model
{
    //use softDeletes;
    protected $fillable = [];

    protected $table = 'certification_data';
    public $timestamps = true;

    public function users()
    {
        //return $this->hasOne(User::class, 'user_id', 'id');
        return $this->belongsTo(User::class, 'certification_officer_id', 'id');
    }
}
