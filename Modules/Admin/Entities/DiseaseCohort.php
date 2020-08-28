<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class DiseaseCohort extends Model
{
    protected $fillable = ['id','study_id','name'];
    protected $keyType ='string';

    public function study(){
        return $this->belongsTo(Study::class);
    }
}
