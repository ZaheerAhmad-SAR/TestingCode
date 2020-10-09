<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = ['id','messages','parent_query_id','queried_remarked_by_id','module_id','module_name','query_status'];
    protected $keyType = 'string';
}
