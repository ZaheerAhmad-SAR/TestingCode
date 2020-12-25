<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueryNotificationUser extends Model
{
    use softDeletes;
    protected $table = 'query_notification_users';
    protected $fillable = ['id', 'query_notification_user_id', 'query_notification_id'];
    protected $keyType = 'string';
}
