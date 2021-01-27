<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';
    protected $fillable = ['id', 'user_id', 'query_id','role_id','is_read','notification_create_by_user_id'];
    protected $keyType = 'string';
}
