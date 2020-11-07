<?php

namespace Modules\Queries\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class Query extends Model
{
    protected $table = 'queries';
    protected $fillable = [
        'id', 'messages', 'parent_query_id', 'queried_remarked_by_id', 'module_id',
        'module_name', 'query_status', 'query_subject', 'query_url', 'query_type', 'query_attachments',
        'study_id', 'subject_id', 'study_structures_id', 'phase_steps_id', 'section_id', 'question_id',
        'field_id', 'form_type_id', 'modility_id'
    ];
    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany(User::class, 'query_users');
    }
    public static function checkUserhaveQuery($module_id)
    {

        $queryCheck   = false;
        $queryByLogin = self::where('queried_remarked_by_id', 'like', auth()->user()->id)
            ->where('parent_query_id', 'like', 0)
            ->where('query_status', '!=', 'close')
            ->where('module_id', 'like', $module_id)->first();

        if (null !== $queryByLogin) {
            //dd('ddddd');
            $queryCheck = true;
        }
        $queryForUser = QueryUser::where('user_id', auth()->user()->id)->first();

        if (null !== $queryForUser) {

            $queryCheck = true;
        }
        return $queryCheck;
    }

    public static function buildHtmlForQuerySubmitter($querySubmitedBy, $query)
    {
        $attachment = '';
        if (!empty($query->query_attachments)) {
            $attachment .= '<div class="row">
                        <img  style="width:200px; height:200px;" class="mr-3" src=' . url((string)$query->query_attachments) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->query_attachments) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row text-left">
                    <input type="hidden" value=' . $query->parent_query_id . ' name="parent_query_id" id="parent_query_id">
                    <div class="col-md-12">
                        <i class="fas fa-circle" style="color: lightgreen; font-size:8px;position: absolute;float: right;top: 15px;left: 43px;"></i>
                        <img class="mr-3" style="width: 30px; height: 30px; border-radius: 50%;"
                            src="' . url((string)$querySubmitedBy->profile_image) . '" />

                        <strong>' . ucfirst((string)$querySubmitedBy->name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->messages . '
                        ' . $attachment . '
                    </div>
                </div><hr>';
    }

    public static function buildHtmlForQueryAnswer($querySubmitedBy, $query)
    {
        $attachment = '';
        if (!empty($query->query_attachments)) {
            $attachment .= '<div class="row">
                        <img style="width:200px; height:200px; margin: 0 auto;" class="mr-3" src=' . url((string)$query->query_attachments) . ' alt="">
                        </div>
                        <div class="row">
                        <a target="_blank" data-fancybox-group="gallery" href=' . url((string)$query->query_attachments) . ' class="fancybox">View Large</a></div>';
        }
        return '<div class="row text-right">
                    <div class="col-md-12">
                    <i class="fas fa-circle" style="color: lightgreen; font-size:8px;position: absolute;float: right;top: 11px;  right: 220px !important;"></i>
                    <img class="mr-3" style="width: 30px; height: 30px; border-radius: 50%;" src="' . url((string)$querySubmitedBy->profile_image) . '" />
                        <strong>' . ucfirst((string)$querySubmitedBy->name) . ':</strong>
                        ' . date_format($query->created_at, 'M-d-Y H:i A') . '<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        ' . $query->messages . '
                         ' . $attachment . '
                    </div>
                </div><hr>';
    }

    public function getProfileImage()
    {
        if (!empty($this->query_file)) {
            return '<img src="' . url('query_attachments/' . $this->query_file) . '"/>';
        }
    }

    public static function getFormQueryObjQuery($getFormQueryArray)
    {
        $formQueryObjectQuery = self::where(function ($q) use ($getFormQueryArray) {
            foreach ($getFormQueryArray as $key => $value) {
                $q->where($key, 'like', (string)$value);
            }
        });
        return $formQueryObjectQuery;
    }

    public static function getFormQueryObj($getFormQueryArray)
    {
        return self::getFormQueryObjQuery($getFormQueryArray)->firstOrNew();
    }

    public static function getFormQueryObjArray($getFormQueryArray)
    {
        return self::getFormQueryObjQuery($getFormQueryArray)->orderBy('created_at')->get();
    }

    public static function questionHasQuery($questionQueryArray)
    {
        $query = new Query();

        $questionQueryArray = array_intersect_key($questionQueryArray, array_flip($query->getFillable()));
        $sqlQuery = self::getFormQueryObjQuery($questionQueryArray);
        //printSqlQuery($sqlQuery, false);
        $queryCheck   = false;
        $queryByLogin = $sqlQuery->where('queried_remarked_by_id', 'like', auth()->user()->id)
            ->where('parent_query_id', 'like', 0)
            ->where('query_status', '!=', 'close')
            ->first();

        if (null !== $queryByLogin) {
            //dd('ddddd');
            $queryCheck = true;
        }
        $queryForUser = QueryUser::where('user_id', auth()->user()->id)->first();

        if (null !== $queryForUser) {

            $queryCheck = true;
        }
        return $queryCheck;
    }
}
