<?php

namespace Modules\Admin\Entities;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class AdjudicationFormRevisionHistory extends Model
{
    protected $table = 'adjudication_form_revision_history';
    protected $fillable = ['id', 'adjudication_form_submit_status_id', 'adjudication_form_edit_reason_text'];
    protected $keyType = 'string';

    public function adjudicationFormStatus()
    {
        return $this->belongsTo(AdjudicationFormStatus::class, 'adjudication_form_submit_status_id', 'id');
    }

    public static function putAdjudicationFormRevisionHistory($adjudicationFormRevisionDataArray, $adjudicationFormStatusId = 0)
    {
        $adjudicationFormRevisionHistory = new AdjudicationFormRevisionHistory();
        $adjudicationFormRevisionHistory->id = Str::uuid();
        $adjudicationFormRevisionHistory->adjudication_form_submit_status_id = $adjudicationFormStatusId;
        $adjudicationFormRevisionHistory->adjudication_form_edit_reason_text = $adjudicationFormRevisionDataArray['adjudication_form_edit_reason_text'];
        $adjudicationFormRevisionHistory->adjudication_form_data = json_encode($adjudicationFormRevisionDataArray['adjudication_form_data']);
        $adjudicationFormRevisionHistory->save();

        AdjudicationFormRevisionHistory::deleteAdjudicationFormRevisionHistory();
    }

    public static function deleteAdjudicationFormRevisionHistory()
    {
        AdjudicationFormRevisionHistory::where('created_at', '<', Carbon::now()->subDays(30))->delete();
    }
}
