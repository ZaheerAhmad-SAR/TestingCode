<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class RoleQuery extends Model
{
    protected $table = 'role_queries';
    protected $fillable = ['id','roles_id','query_id'];
    protected $keyType = 'string';
}
