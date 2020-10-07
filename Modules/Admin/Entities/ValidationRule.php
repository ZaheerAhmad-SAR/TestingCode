<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\RuleActiveScope;
use Illuminate\Database\Eloquent\Model;

class ValidationRule extends Model
{
    protected $table = 'validation_rules';
    protected $fillable = ['rule', 'title', 'description', 'rule_group', 'is_active', 'is_related_to_other_field'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new RuleActiveScope);
    }

    public function scopeWithRelatedToOtherFields($query)
    {
        return $query->where('is_related_to_other_field', 1);
    }

    public function scopeWithOutRelatedToOtherFields($query)
    {
        return $query->where('is_related_to_other_field', 0);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_validations', 'validation_rule_id', 'question_id');
    }
}
