<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Device;
use Modules\CertificationApp\Entities\StudyDevice;
use Modules\Admin\Entities\Other;

class ModilityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function index()
    {
        $modalities = Modility::all();
        return view('admin::modilities.index', compact('modalities'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $modalities = Modility::paginate(\Auth::user()->user_prefrences->default_pagination);
        //dd($modalities);
        foreach ($modalities as $key => $value) {
            // $arr[3] will be updated with each value from $arr...
            //echo "{$key} => {$value} ";
            //print_r($modalities);
        }
        dd($value->id);

        return view('admin::modilities.create', compact('modalities'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = (string)Str::uuid();

        if ($request->parent_yes == 1) {
            // dd('parent');
            $modility = Modility::create([
                'id'    => $id,
                'modility_name' => $request->modility_name,
                'modility_abbreviation' => $request->modility_abbreviation,
            ]);
        }

        $oldModality = [];

        // log event details
        $logEventDetails = eventDetails($id, 'Modality', 'Add', $request->ip(), $oldModality);

        return response()->json(['Sucess' => 'Save succesfully', $id]);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //return view('modilities::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $parentmodalities = Modility::find($id);
            $output = '';

            $output .= '
            <input type="hidden" name="parent_yes" value="1">
            <input type="hidden" name="parent_id" value="' . $parentmodalities->id . '">
            <div class="form-group row">
                <label for="Name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="modility_name" name="modility_name" placeholder="Enter Modility name" maxlength="50"  value="' . $parentmodalities->modility_name . '" required dusk="update-parent-modality-name"/>
                </div>
            </div>
            <div class="form-group row">
                <label for="modility_abbreviation" class="col-sm-3 col-form-label">Abbreviation</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="modility_abbreviation" name="modility_abbreviation" placeholder="Enter Modility abbreviation" maxlength="50"  value="' . $parentmodalities->modility_abbreviation . '" required dusk="update-parent-modality-abbreviation"/>
                </div>
            </div>';
            return Response($output);
        }
    }

    public function showChild(Request $request, $id)
    {
        if ($request->ajax()) {
            $childmodalities = ChildModilities::select('*')->where('modility_id', $id)->get();

            $output = "";

            if (!empty($childmodalities)) {
                foreach ($childmodalities as $key => $modality) {
                    $output .= '<li class="nav-item mail-item" style="border-bottom: 1px solid #F6F6F7;">
                                <div class="d-flex align-self-center align-middle">
                                    <div class="mail-content d-md-flex w-100">
                                        <a href="#" data-mailtype="tab_" class="nav-link " data-id=' . $modality->id . '>
                                            <span class="mail-user">' . $modality->modility_name . '</span>
                                        </a>
                                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                                            <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;" dusk="child-modality-navtab"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item edit_steps" data-id=' . $modality->id . ' dusk="child-modality-edit"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                                <span class="dropdown-item deleteChild" data-id=' . $modality->id . '><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>';
                }

                return Response($output);
            }
        }
    }




    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $oldModality = Modility::find($request->parent_id);

        $data = array(
            'modility_name' => $request->modility_name,
            'modility_abbreviation' => $request->modility_abbreviation,
        );
        Modility::where('id', $request->parent_id)->update($data);

        // log event details

        $logEventDetails = eventDetails($request->parent_id, 'Modality', 'Update', $request->ip(), $oldModality);

        return response()->json(['success' => 'Modility  is Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */


    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $delete = Modility::find($id);

            $delete->delete();
            return response()->json(['success' => 'Parent is deleted successfully.']);
        }
    }

    public function restoreParent(Request $request, $id)
    {

        if ($request->ajax()) {
            $child = Modility::withTrashed()->find($id)->restore();
            return response()->json(['success' => 'Parent and there prospective Childs are restore successfully.']);
        }
    }


    public function replicateParent(Request $request, $id)
    {
        if ($request->ajax()) {

            $modalities = Modility::find($id);

            $clone = $modalities->replicate();

            $id = (string)Str::uuid();

            $modility = Modility::create([
                'id'    => $id,
                'modility_name' => $clone->modility_name,
                'modility_abbreviation' => $clone->modility_abbreviation,

            ]);

            $latest_modility = Modility::select('id')->latest()->first();

            foreach ($modalities->children as $child) {
                $child_id = (string)Str::uuid();
                $child = ChildModilities::create([
                    'id'    => (string)Str::uuid(),
                    'modility_name' => $child->modility_name,
                    'modility_abbreviation' => $child->modility_abbreviation,
                    'modility_id' => $latest_modility->id
                ]);
            }
        }
    }

    public function getModalityDevices(Request $request) {
        if($request->ajax()) {
            // explode data
            $getModalityId = explode('__/__', $request->modality_id);
            // find Modality and get device ids
            $getModalityDeviceIds = Modility::find($getModalityId[0])->devices->pluck('id')->toArray();
            // get study
            $getStudy = Study::where('study_code', $request->study_code)->first();
            // get devices from study_devices table based on device id and study id
            $getStudyDeviceIds = StudyDevice::where('study_id', $getStudy->id)
                                            ->whereIn('device_id', $getModalityDeviceIds)
                                            ->pluck('device_id')
                                            ->toArray();
            // get study Devices
            $getStudyDevices = Device::whereIn('id', $getStudyDeviceIds)->get()->toArray();

            return response()->json(['study_devices' => $getStudyDevices]);
        } // ajax ends
    }
}
