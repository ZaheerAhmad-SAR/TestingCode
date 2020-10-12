<?php

namespace Modules\Admin\Entities;

use Carbon\Carbon;
use Illuminate\Support\Str;
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

    public static function putFormRevisionHistory($formRevisionDataArray, $formStatusId = 0)
    {
        $formRevisionHistory = new FormRevisionHistory();
        $formRevisionHistory->id = Str::uuid();
        $formRevisionHistory->form_submit_status_id = $formStatusId;
        $formRevisionHistory->edit_reason_text = $formRevisionDataArray['edit_reason_text'];
        $formRevisionHistory->form_data = json_encode($formRevisionDataArray['form_data']);
        $formRevisionHistory->save();

        FormRevisionHistory::deleteFormRevisionHistory();
    }

    public static function deleteFormRevisionHistory()
    {
        FormRevisionHistory::where('created_at', '<', Carbon::now()->subDays(30))->delete();
    }
}
