<?php

namespace Modules\BugReporting\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $table = 'bug_reports';
    protected $fillable = [
        'id', 'bug_message', 'parent_bug_id', 'bug_reporter_by_id', 'bug_title', 'status','bug_url',
        'bug_attachments','bug_priority','open_status','closed_status'
    ];
    protected $keyType = 'string';

    public static function buildHtmlForQuerySubmitter($querySubmitedBy, $query)
    {

            $profileImage = '';
            if (null ===  $querySubmitedBy->profile_image) {
                $profileImage = asset('public/images/download.png');
            } else {
                $profileImage = asset($querySubmitedBy->profile_image);
            }

        $attachment = '';
        if (!empty($query->query_attachments)) {
            $attachment .= '<div class="row">
                        <img  style="width:200px; height:200px;" class="mr-3" src=' . url((string)$query->bug_attachments) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->bug_attachments) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row">
                    <input type="hidden" value=' . $query->parent_bug_id . ' name="parent_query_id" id="parent_query_id">
                    <div class="col-md-12">

                        <img class="mr-3" style="width: 30px; height: 30px; border-radius: 50%;"
                            src="' . url((string)$profileImage) . '" />

                        <strong>' . ucfirst((string)$querySubmitedBy->name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="text-justify"><strong>Bug Title </strong> <br> '.$query->bug_title.' </div>
                            <div class="text-left"><strong>Response </strong> <br>'.$query->bug_message.' </div><br>
                            <div class="text-left"><strong> Status &nbsp; </strong> '.$query->status.'</div>
                             <div class="text-left"><strong> Priority &nbsp; </strong> '.$query->bug_priority.'</div>
                        ' . $attachment . '
                    </div>
                </div><hr>';
    }

    public static function buildHtmlForQueryAnswer($querySubmitedBy, $query)
    {
        $profileImage = '';
        if (null ===  $querySubmitedBy->profile_image) {
            $profileImage = asset('public/images/download.png');
        } else {
            $profileImage = asset($querySubmitedBy->profile_image);
        }


        $attachment = '';
        if (!empty($query->query_attachments)) {
            $attachment .= '<div class="row">
                        <img style="width:200px; height:200px; margin: 0 auto;" class="mr-3" src=' . url((string)$query->bug_attachments) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->bug_attachments) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row">
                    <div class="col-md-12">

                    <img class="mr-3" style="width: 30px; height: 30px; border-radius: 50%;" src="' . url((string)$profileImage) . '" />
                        <strong>' . ucfirst((string)$querySubmitedBy->name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->bug_message . '
                         ' . $attachment . '
                    </div>
                </div><hr>';
    }

}
