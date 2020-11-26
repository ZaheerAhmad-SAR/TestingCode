<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class QueryNotificationUser extends Model
{
    protected $table = 'query_notification_users';
    protected $fillable = ['id','query_notification_user_id','query_notification_id'];
    protected $keyType = 'string';
}
