<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiseaseCohort extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'study_id', 'name'];
    protected $keyType = 'string';

    public function study()
    {
        return $this->belongsTo(Study::class);
    }
}
