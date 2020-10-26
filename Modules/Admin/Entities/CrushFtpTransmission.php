<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class CrushFtpTransmission extends Model
{
	protected $primaryKey = 'id'; // or null

    protected $fillable = ['data'];
    
    public $table = 'crush_ftp_transmissions';
    
    public $timestamps = true;
}
