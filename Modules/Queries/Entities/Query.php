<?php

namespace Modules\Queries\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = [
        'id', 'messages', 'parent_query_id', 'queried_remarked_by_id', 'module_id',
        'module_name', 'query_status', 'query_subject', 'query_url', 'query_type'
    ];
    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany(User::class, 'query_users');
    }
    public static function checkUserhaveQuery($module_id)
    {

        $queryCheck   = false;
        $queryByLogin = self::where('queried_remarked_by_id', 'like', auth()->user()->id)
            ->where('parent_query_id', 'like', 0)
            ->where('module_id', 'like', $module_id)->first();

        if (null !== $queryByLogin) {
            //dd('ddddd');
            $queryCheck = true;
        }
        $queryForUser = QueryUser::where('user_id', auth()->user()->id)->first();

        if (null !== $queryForUser) {

            $queryCheck = true;
        }
        return $queryCheck;
    }

    public static function buildHtmlForQuerySubmitter($querySubmitedBy, $query)
    {
        return '<div class="row text-left">
                    <div class="col-md-1">
                        <img class="mr-3" style="width: 25px; height: 25px; border-radius: 50%;"
                            src="' . url((string)$querySubmitedBy->profile_image) . '" />
                    </div>
                    <div class="col-md-11">
                        <b class="mt-0">' . ucfirst((string)$querySubmitedBy->name) . '<i class="fas fa-circle"
                                style="color: lightgreen; font-size:8px;"></i> <br></b>
                        ' . strip_tags((string)$query->messages) . ' <br>
                        <p style="padding: 10px">' . date_format($query->created_at, 'jS-Y-h:i A') . '</p>
                    </div>
                </div>';
    }

    public static function buildHtmlForQueryAnswer($querySubmitedBy, $query)
    {
        return '<div class="row text-right">
                    <div class="col-md-11">
                        <b class="mt-0">' . ucfirst((string)$querySubmitedBy->name) . '<i class="fas fa-circle"
                                style="color: lightgreen; font-size:8px;"></i> <br></b>
                        ' . strip_tags((string)$query->messages) . ' <br>
                        <p style="padding: 10px">' . date_format($query->created_at, 'jS-Y-h:i A') . '</p>
                    </div>
                    <div class="col-md-1">
                        <img class="mr-3" style="width: 25px; height: 25px; border-radius: 50%;"
                            src="' . url((string)$querySubmitedBy->profile_image) . '" />
                    </div>
                </div>';
    }
}
