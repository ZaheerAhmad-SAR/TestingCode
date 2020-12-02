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


    public static function buildHtmlForQuerySubmitter($querySubmitedBy, $query)
    {

        $attachment = '';
        if (!empty($query->email_attachment)) {
            $attachment .= '<div class="row">
                        <img  style="width:200px; height:200px;" class="mr-3" src=' . url((string)$query->email_attachment) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->email_attachment) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row text-left">
                    <input type="hidden" value=' . $query->parent_notification_id . ' name="parent_query_id" id="parent_query_id">
                    <div class="col-md-12">

                        <img class="mr-3" style="width: 40px; height: 40px; border-radius: 50%;"
                            src="' . url((string)$querySubmitedBy->profile_image) . '" />

                        <strong>' . ucfirst((string)$querySubmitedBy->name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->email_body . '
                        ' . $attachment . '
                    </div>
                </div><hr>';
    }

    public static function buildHtmlForQueryAnswer($querySubmitedBy, $query)
    {
        $attachment = '';
        if (!empty($query->email_attachment)) {
            $attachment .= '<div class="row">
                        <img style="width:200px; height:200px; margin: 0 auto;" class="mr-3" src=' . url((string)$query->email_attachment) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->email_attachment) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row text-right">
                    <div class="col-md-12">

                    <img class="mr-3" style="width: 40px; height: 40px; border-radius: 50%;" src="'.(asset('public/images/download.png')). '" />
                        <strong>' . ucfirst((string)$query->person_name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->email_body . '
                         ' . $attachment . '
                    </div>
                </div><hr>';
    }
}

