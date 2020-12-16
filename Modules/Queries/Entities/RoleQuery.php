<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleQuery extends Model
{
    use SoftDeletes;
    protected $table = 'role_queries';
    protected $fillable = ['id', 'roles_id', 'query_id'];
    protected $keyType = 'string';
}
