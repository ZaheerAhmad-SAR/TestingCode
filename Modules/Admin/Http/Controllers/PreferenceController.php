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
        $preferences = Preference::all();
        return view('admin::preference.index')
            ->with('preferences', $preferences);
    }

    public function updatePreference(Request $request)
    {
        $id = $request->id;
        $preference_value = $request->preference_value;

        $preference = Preference::find($id);
        $preference->preference_value = $preference_value;
        $preference->update();
        echo $preference->preference_value;
    }
}
