<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\QuestionOrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $table = 'question';
    protected $fillable = ['id','form_field_type_id','section_id','option_group_id','question_sort','question_text','c_disk','measurement_unit','is_dependent','dependent_on','annotations','deleted_at'];
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new QuestionOrderByScope);
    }

    public function form_field_type()
    {
        return $this->belongsTo(FormFieldType::class);
    }

    public function formFields()
    {
        return $this->hasOne(FormFields::class,'question_id','id');
    }
    public function optionsGroup()
    {
       return $this->hasOne(OptionsGroup::class,'id','option_group_id')->withDefault();
    }

    public function getAnswer($getAnswerArray)
    {
        $answer = Answer::where(function ($q) use ($getAnswerArray) {
            foreach ($getAnswerArray as $key => $value) {
                $q->where($key, '=', $value);
            }
        })->first();
        if(null === $answer)
        {
            $answer = new Answer();
        }
        return $answer;
    }
}
