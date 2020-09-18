<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;

class SubjectFormSubmissionController extends Controller
{
    public function submitForm(Request $request)
    {
        dd($request);
    }
}
