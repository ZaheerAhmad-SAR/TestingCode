<?php

namespace Modules\BugReporting\Entities;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $table = 'bug_reports';
    protected $fillable = [
        'id', 'bug_message', 'parent_bug_id', 'bug_reporter_by_id', 'bug_title', 'status','bug_url',
        'bug_attachments','bug_priority','open_status','closed_status'
    ];
    protected $keyType = 'string';
}
