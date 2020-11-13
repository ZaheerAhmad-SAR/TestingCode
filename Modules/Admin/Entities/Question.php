<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\QuestionOrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\ValidationRule;

class Question extends Model
{
    use SoftDeletes;
    protected $table = 'question';
    protected $fillable = ['id', 'old_id', 'form_field_type_id', 'section_id', 'option_group_id', 'question_sort', 'question_text', 'c_disk', 'measurement_unit', 'is_dependent', 'dependent_on', 'annotations', 'certification_type', 'deleted_at'];
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
        return $this->hasOne(FormFields::class, 'question_id', 'id')->withDefault();
    }
    public function optionsGroup()
    {
        return $this->hasOne(OptionsGroup::class, 'id', 'option_group_id')->withDefault();
    }

    public function optionGroup()
    {
        return $this->belongsTo(OptionsGroup::class, 'option_group_id', 'id')->withDefault();
    }

    public function getAnswer($getAnswerArray)
    {
        $answer = Answer::getAnswer($getAnswerArray);
        if (null === $answer) {
            $answer = new Answer();
        }
        return $answer;
    }
    public function questionDependency()
    {
        return $this->hasOne(QuestionDependency::class, 'question_id', 'id')->withDefault();
    }

    public function questionAdjudicationStatus()
    {
        return $this->hasOne(QuestionAdjudicationStatus::class, 'question_id', 'id')->withDefault();
    }

    public function validationRules()
    {
        return $this->belongsToMany(ValidationRule::class, 'question_validations', 'question_id', 'validation_rule_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function skiplogic()
    {
        return $this->hasMany(SkipLogic::class, 'question_id', 'id');
    }

    public function questionValidations()
    {
        return $this->hasMany(QuestionValidation::class, 'question_id', 'id');
    }
}
