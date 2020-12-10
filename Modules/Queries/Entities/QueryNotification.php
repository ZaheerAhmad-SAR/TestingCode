<?php

namespace Modules\Queries\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class QueryNotification extends Model
{
    protected $table = 'query_notifications';
    protected $fillable = ['id','site_name','cc_email','subject','email_body','email_attachment','notifications_status',
        'parent_notification_id', 'notification_remarked_id', 'study_id','subject_id','transmission_number',
        'vist_name','notifications_token','person_name','study_short_name'
    ];
    protected $keyType = 'string';


    public static function buildHtmlForQuerySubmitter($querySubmitedBy, $query)
    {

        $profileImage = '';
        $checkUserEmail = $query->notification_remarked_id;
        $checkPersonName = $query->person_name;


        if(!empty($checkUserEmail))
        {
            $result = User::where('email','=',$checkUserEmail)->first();

            if (null=== $result)
            {
                $profileImage = asset('public/images/download.png');
            }
            else
            {
                $profileImage = asset($result->profile_image);
            }
        }

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
                           src="' . url((string)$profileImage) . '" />

                        <strong>' . ucfirst((string)$query->person_name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->email_body . '
                        ' . $attachment . '
                    </div>
                </div><hr>';
    }

    public static function buildHtmlForQueryAnswer($querySubmitedBy, $query)
    {



        $profileImage = '';
        $checkUserEmail = $querySubmitedBy->notification_remarked_id;

        if(!empty($checkUserEmail))
        {
            $result = User::where('email','=',$checkUserEmail)->first();
            if (null=== $result)
            {
                $profileImage = asset('public/images/download.png');
            }
            else
            {
                $profileImage = asset($result->profile_image);
            }
        }

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

                    <img class="mr-3" style="width: 40px; height: 40px; border-radius: 50%;"  src="' . url((string)$profileImage) . '" />
                        <strong>' . ucfirst((string)$query->person_name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->email_body . '
                         ' . $attachment . '
                    </div>
                </div><hr>';
    }
}

