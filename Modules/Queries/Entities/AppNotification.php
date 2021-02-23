<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';
    protected $fillable = ['id', 'user_id', 'notifications_type','role_id','is_read','notification_create_by_user_id','queryorbugid'];
    protected $keyType = 'string';


    public static function countUserUnReadNotification()
    {
      $count = '';
      $count = self::where('user_id','=', auth()->user()->id)->where('is_read','no')->count();
      if ($count > 0)
      {
          return '<span class="badge badge-pill badge-danger" style="height: 20px;top: 12px;">'.$count.'</span>';
      }
    }
    public static function showMarkAllReadDiv()
    {
      $count = '';
      $count = self::where('user_id','=', auth()->user()->id)->where('is_read','no')->count();
      if ($count > 0)
      {
          return '&nbsp; &nbsp;<td class="align-baseline"><a class="markAllRead" href="javascript:void(0);"><span><i class="fas fa-check"></i></span> &nbsp;Mark All Read</a></td> &nbsp; &nbsp; &nbsp;';
      }
    }


}
