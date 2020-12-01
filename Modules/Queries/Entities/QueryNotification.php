<?php

namespace Modules\Queries\Entities;

use Illuminate\Database\Eloquent\Model;

class QueryNotification extends Model
{
    protected $table = 'query_notifications';
    protected $fillable = ['id','site_name','cc_email','subject','email_body','email_attachment','notifications_status',
        'parent_notification_id', 'notification_remarked_id', 'study_id','subject_id','transmission_number',
        'vist_name','notifications_token','person_name'
    ];
    protected $keyType = 'string';
}
