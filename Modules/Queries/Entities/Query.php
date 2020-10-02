<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = ['id','sender_id','receiver_id','messages','status'];
    protected $keyType = 'string';
}
