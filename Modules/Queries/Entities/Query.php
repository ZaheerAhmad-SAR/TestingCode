<?php

namespace Modules\Queries\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = ['id','messages','parent_query_id','queried_remarked_by_id','module_id',
        'module_name','query_status','query_subject','query_url','query_type'];
    protected $keyType = 'string';

    public function users(){
        return $this->belongsToMany(User::class,'query_users');
    }
}
