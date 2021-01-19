<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FormSubmission\Entities\FormStatus;

class AssignWork extends Model
{
    use softDeletes;
    protected $table = 'assign_work';

    public function get_form_status()
    {
        return $this->hasOne(FormStatus::class, 'modility_id', 'modility_id');
    }
}
