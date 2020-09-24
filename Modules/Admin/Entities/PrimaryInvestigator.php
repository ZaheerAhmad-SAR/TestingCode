<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class PrimaryInvestigator extends Model
{
    protected $fillable = ['id','first_name','mid_name','last_name','site_id','phone','email'];

    public $incrementing = false;
    protected $keyType = 'string';

    public function site() {
        return $this->belongsToMany(Site::class,'sites');
    }
    public function studySite(){
        return $this->belongsToMany(StudySite::class,'site_study');
    }

}
