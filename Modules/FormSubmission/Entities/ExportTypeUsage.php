<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\StudyStructure;

class ExportTypeUsage extends Model
{
    use SoftDeletes;
    protected $table = 'export_type_usage';
    protected $fillable = [
        'id', 'export_type_id', 'data_exported_by_id', 'created_at', 'updated_at', 'deleted_at'
    ];
    protected $keyType = 'string';

    protected $attributes = [
        'id' => '',
        'data_exported_by_id' => '',
        'created_at' => '',
    ];

    public function exportType()
    {
        return $this->belongsTo(ExportType::class, 'export_type_id', 'id')->withDefault();
    }
}
