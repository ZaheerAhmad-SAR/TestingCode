<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class FormRevisionHistory extends Model
{
    protected $table = 'form_revision_history';
    protected $fillable = ['id', 'form_submit_status_id', 'edit_reason_text'];
    protected $keyType = 'string';

    public function formStatus()
    {
        return $this->belongsTo(FormStatus::class, 'form_submit_status_id', 'id');
    }
}
