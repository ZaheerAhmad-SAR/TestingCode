<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class TrailLog extends Model
{
    use SoftDeletes;
    //

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
