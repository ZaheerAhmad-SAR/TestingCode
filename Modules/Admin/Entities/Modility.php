<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Study;

class Modility extends Model
{
    use softDeletes;
    protected $table = 'modilities';
    protected $fillable = ['id', 'modility_name', 'modility_abbreviation', 'is_parent', 'parent_id'];
    protected $guarded = [];
    protected $keyType = 'string';

    //    public function children()
    //    {
    //        return $this->hasMany(ChildModilities::class,'id','modility_id');
    //    }

    public function children()
    {
        return $this->hasMany(ChildModilities::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'device_modilities');
    }

    public function study()
    {
        return $this->belongsToMany(Study::class, 'study_modilities', 'sitparent_modility_ide_id', 'study_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($modility) {
            foreach ($modility->children()->get() as $children) {
                $children->delete();
            }
        });

        static::restoring(function ($modility) {
            $modility->children()->withTrashed()->restore();
        });
    }
}
