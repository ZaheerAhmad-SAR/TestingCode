<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Preference;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $studyId = session('current_study');
        $preferences = Preference::where('study_id', 'like', $studyId)->get();
        return view('admin::preference.index')
            ->with('studyId', $studyId)
            ->with('preferences', $preferences);
    }

    public function updatePreference(Request $request)
    {
        $studyId = session('current_study');
        $id = $request->id;
        $preference_value = $request->preference_value;

        $preference = Preference::find($id);
        $preference->preference_value = $preference_value;
        $preference->update();
        echo $preference->preference_value;
    }

    public function loadAddPreferenceForm(Request $request)
    {
        $studyId = session('current_study');
        if ($request->has('preferenceId')) {
            $preference = Preference::find($request->preferenceId);
        } else {
            $preference = new Preference();
        }
        echo view('admin::preference.addPreferencePopUpForm')
            ->with('preference', $preference)
            ->with('studyId', $studyId);
    }

    public function submitAddPreferenceForm(Request $request)
    {
        $studyId = session('current_study');
        echo view('admin::preference.addPreferenceMsg');
    }
}
