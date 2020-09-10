<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modility extends Model
{
    protected $table = 'modilities';
    protected $fillable = ['id','modility_name','is_parent','parent_id'];
    protected $guarded = [];
    protected $keyType = 'string';

    use SoftDeletes;

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
        return $this->belongsToMany(Device::class,'device_modility');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($modility) {
            foreach ($modility->children()->get() as $children) {
                $children->delete();
            }
        });

        static::restoring(function($modility) {
            $modility->children()->withTrashed()->restore();
        });

    }

}
