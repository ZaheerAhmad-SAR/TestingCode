<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class QuestionAdjudicationStatus extends Model
{
	use SoftDeletes;
	protected $keyType = 'string';
    protected $fillable = ['id','question_id','adj_status','decision_based_on','opertaor','differnce_status','custom_value','deleted_at'];

     public function questions()
    {
        return $this->belongsTo(Questions::class,'question_id','id');
    }
}
