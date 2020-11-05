<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Scopes\PreferencesByStudy;

class Preference extends Model
{
    protected $table = 'preferences';
    protected $fillable = ['id', 'preference_title', 'preference_value', 'is_selectable', 'preference_options', 'created_at', 'updated_at'];
    protected $attributes = [
        'id' => 0,
        'preference_title' => '',
        'preference_value' => '',
        'is_selectable' => 'no',
        'preference_options' => '',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PreferencesByStudy);
    }

    public static function getPreference($preference_title)
    {
        $preference = self::where('preference_title', 'like', $preference_title)->first();
        return $preference->preference_value;
    }
}
