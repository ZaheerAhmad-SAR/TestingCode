<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class backupCode extends Model
{
    protected $fillable = [
        'user_id','expiry_duration','backup_code'
    ];
}
