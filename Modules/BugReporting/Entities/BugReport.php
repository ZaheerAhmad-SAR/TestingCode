<?php

namespace Modules\BugReporting\Entities;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $table = 'bug_reports';
    protected $fillable = [
        'id', 'bug_message', 'parent_bug_id', 'bug_reporter_by_id', 'bug_title', 'bug_status','bug_url','bug_attachments'
    ];
    protected $keyType = 'string';
}
