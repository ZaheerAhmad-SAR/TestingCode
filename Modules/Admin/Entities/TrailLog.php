<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TrailLog extends Model
{
    //

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
