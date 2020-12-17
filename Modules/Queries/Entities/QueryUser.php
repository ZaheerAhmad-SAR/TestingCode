<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueryUser extends Model
{
    use SoftDeletes;
    protected $table = 'query_users';
    protected $fillable = ['id', 'user_id', 'query_id'];
    protected $keyType = 'string';
}
