<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\Modility;

class ModalityPhases extends Model
{
    use softDeletes;
    protected $table = 'modality_phases';
    protected $keyType = 'string';
    protected $fillable = ['id', 'phase_id', 'modility_id', 'form_type_id', 'Transmission_Number'];
    protected $casts = [
        'id' => 'string'
    ];

    public function phase()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'id');
    }

    public function modality()
    {
        return $this->belongsTo(Modility::class, 'modility_id', 'id');
    }

    public function formType()
    {
        return $this->belongsTo(FormType::class, 'form_type_id', 'id');
    }
}
