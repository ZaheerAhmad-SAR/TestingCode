<?php

namespace Modules\FormSubmission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;

class FormVersion extends Model
{
    protected $table = 'form_version';
    protected $fillable = ['id', 'step_id', 'form_questions', 'form_version_num', 'created_at', 'updated_at'];
    protected $keyType = 'string';

    public function step()
    {
        return $this->belongsTo(PhaseSteps::class, 'step_id', 'id');
    }

    public static function createFormVersion($step, $newVersion)
    {
        $formQuestionArray = [];
        foreach ($step->sections as $section) {
            $formQuestionArray['section'] = [$section->id => $section->getAttributes()];
            foreach ($section->questions as $question) {
                $formQuestionArray[$section->id][$question->id] = [$question->id => $question->getAttributes()];
            }
        }
        /***************************************/
        $formVersion = new FormVersion();
        $formVersion->id = Str::uuid();
        $formVersion->step_Id = $step->step_id;
        $formVersion->form_questions = json_encode($formQuestionArray);
        $formVersion->form_version_num = $newVersion;
        $formVersion->save();
    }
}
