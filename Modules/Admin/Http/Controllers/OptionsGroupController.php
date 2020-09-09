<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\OptionsGroup;

class OptionsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $optionsGroups = OptionsGroup::paginate(20);
        $optionsGroup = OptionsGroup::latest('created_at')->get();
        return view('admin::optionsgroup.index',compact('optionsGroups','optionsGroup'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function getall_options()
    {
        $options_dropdown = OptionsGroup::all();
        $optionsData['data'] = $options_dropdown;
        echo json_encode($optionsData);
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $input['option_name']   = $request->option_name;
        $input['option_value']  = $request->option_value;
        $name   = implode(',',(array)$input['option_name']);
        $value  = implode(',',(array)$input['option_value']);
        $others = OptionsGroup::create([
                'id'    => Str::uuid(),
                'option_group_name' => $request->option_group_name,
                'option_group_description' => empty($request->option_group_description) ? Null : $request->option_group_description,
                'option_layout' => empty($request->option_layout) ? Null : $request->option_layout,
                'option_name'=>empty($name) ? Null :$name,
                'option_value'=>empty($value) ? Null: $value
            ]);
       return response()->json([$others,'success'=>'others data is added successfully']);
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
    public function edit($id)
    {
        if ($id)
        {
            $record = OptionsGroup::find($id);
            return response()->json([$record]);
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
        $input = $request->all();
        $input['option_name']   = $request->option_name_edit;
        $input['option_value']  = $request->option_value_edit;
        $name   = implode(',',(array)$input['option_name']);
        $value  = implode(',',(array)$input['option_value']);
        $data   = array
        (
            'option_group_name' => $request->option_group_name_edit,
            'option_group_description' => $request->option_group_description_edit,
            'option_layout' => $request->option_layout_edit,
            'option_name'=>empty($name) ? Null :$name,
            'option_value'=>empty($value) ? Null: $value
        );
        OptionsGroup::where('id', $request->options_groups_id)->update($data);

        return response()->json(['success'=>'Option Group  is Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */


    public function destroy(Request $request,$id)
    {

        if ($request->ajax())
        {
            $delete = OptionsGroup::find($id);
            $delete->delete();
            return response()->json(['success'=>'Option is deleted successfully.']);
        }
    }

}
