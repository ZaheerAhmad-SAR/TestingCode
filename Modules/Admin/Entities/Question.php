<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $fillable = ['id','form_field_type_id','section_id','option_group_id','question_text','c_disk','measurement_unit','is_dependent','dependent_on','annotations'];
    protected $keyType = 'string';

    public function form_field_type()
    {
        return $this->belongsTo(Formfieldtype::class);
    }

    public function formFields()
    {
        return $this->hasOne(Formfields::class,'question_id','id');
    }
    public function optionsGroup()
    {
       return $this->hasOne(OptionsGroup::class,'option_group_id','id')->withDefault();
    }
}
