<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\StudyStructure;

class ExportType extends Model
{
    use softDeletes;
    protected $table = 'export_type';
    protected $fillable = [
        'id', 'study_id', 'phase_ids', 'form_type_id', 'modility_id', 'titles_values', 'export_type_title', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $keyType = 'string';

    public function formType()
    {
        return $this->belongsTo(FormType::class, 'form_type_id', 'id')->withDefault();
    }

    public function modality()
    {
        return $this->belongsTo(Modility::class, 'modility_id', 'id')->withDefault();
    }

    public function exportTypeUsage()
    {
        return $this->hasMany(ExportTypeUsage::class, 'export_type_id', 'id');
    }

    public static function getPhaseNames($phaseIdsString)
    {
        $phaseIdsArray = array_filter(explode(',', $phaseIdsString));
        $phasesNamesArray = StudyStructure::whereIn('id', $phaseIdsArray)->withOutRepeated()->pluck('name')->toArray();
        return implode(', ', $phasesNamesArray);
    }
}
