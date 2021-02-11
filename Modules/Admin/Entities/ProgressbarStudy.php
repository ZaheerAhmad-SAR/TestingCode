<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\Study;


class ProgressbarStudy extends Model
{
    protected $fillable = [];

    public function study() {
   		return $this->belongsTo(Study::class, 'id', 'study_id');
    }
}
