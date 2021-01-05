<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class backupCode extends Model
{
    protected $fillable = [
        'user_id', 'expiry_duration', 'backup_code'
    ];
}
