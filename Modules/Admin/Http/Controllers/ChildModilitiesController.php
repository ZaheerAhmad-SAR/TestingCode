<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Support\Str;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Modility;

class ChildModilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()

    {

        $parentmodalities = ChildModilities::all();
        dd($parentmodalities);
        //return view('admin::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $parent = Modility::all();
        return view('admin::create',compact('parent'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = Str::uuid();
        /*dd('chuld con'. ' ' ,$request->all());*/
        $child = ChildModilities::create([
            'id'    => $id,
            'modility_name'=>$request->modility_name,
            'modility_id' =>$request->parent_id
        ]);

        $oldModality = [];

        // log event details
        $logEventDetails = eventDetails($id, 'Child Modality', 'Add', $request->ip(), $oldModality);

        return response()->json(['Sucess'=>'Save succesfully']);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request ,$id)
    {
        if ($request->ajax())
        {

            $childmodalities = ChildModilities::find($id);

            $output = '';

            $output .= "<input type='text' class='form-control' maxlength='50'
            id='modility_name' name='modility_name' value ='$childmodalities->modility_name'>";
            $output .= "<input type='hidden' class='form-control' maxlength='50'
            id='child_id' name='child_id' value ='$childmodalities->id'>";

            return Response($output);

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
        $oldModality = ChildModilities::find($request->child_id);

        $data = array (
            'modility_name' => $request->modility_name
        );
        ChildModilities::where('id', $request->child_id)->update($data);

        // log event details
        $logEventDetails = eventDetails($request->child_id, 'Child Modality', 'Update', $request->ip(), $oldModality);

        return response()->json(['success'=>'Child Modility is Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax())
        {
            $delete = ChildModilities::find($id);

            $delete->delete();

            return response()->json(['success'=>'Child is deleted successfully.']);
        }
    }

    public function restoreChild(Request $request,$id)
    {

        if ($request->ajax())
        {
            $child = ChildModilities::withTrashed()->find($id)->restore();

            return response()->json(['success'=>'Child is restore successfully.']);
        }
    }

}
