<?php

namespace Modules\Queries\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = ['id','messages','parent_query_id','queried_remarked_by_id','module_id',
        'module_name','query_status','query_subject','query_url','query_type'];
    protected $keyType = 'string';

    public function users(){
        return $this->belongsToMany(User::class,'query_users');
    }
    public static function checkUserhaveQuery($module_id)
    {

        $queryCheck   = false;
        $queryByLogin = self::where('queried_remarked_by_id','like',auth()->user()->id)
            ->where('parent_query_id','like',0)
            ->where('module_id','like',$module_id)
            ->get();
        if (null != $queryByLogin)
        {
            dd(auth()->user()->id);
            $queryCheck = true;
        }
        $queryForUser = QueryUser::where('user_id',auth()->user()->id)->get();

        if (null != $queryForUser)
        {
           $queryCheck = true;
        }
        return $queryCheck;
    }
}
