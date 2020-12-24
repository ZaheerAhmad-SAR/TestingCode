<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildModilities extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'modility_name', 'modility_id', 'deleted_at'];
    protected $keyType = 'string';
    //protected $dates = ['deleted_at'];
    protected $primaryKeyType = 'string';


    public function modility()
    {
        return $this->belongsTo(Modility::class, 'modility_id', 'id');
    }
}
