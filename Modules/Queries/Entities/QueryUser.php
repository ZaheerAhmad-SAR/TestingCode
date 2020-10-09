<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class QueryUser extends Model
{
    protected $table = 'query_users';
    protected $fillable = ['id','user_id','query_id'];
    protected $keyType = 'string';
}
