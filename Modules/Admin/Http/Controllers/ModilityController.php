<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Other;

class ModilityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

//    public function __construct()
//    {
//
//        $this->middleware();
//
//    }

    public function index()
    {
        $modalities = Modility::all();

        return view('admin::modilities.index',compact('modalities'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $modalities = Modility::paginate(2);
        //dd($modalities);
        foreach ($modalities as $key => $value) {
            // $arr[3] will be updated with each value from $arr...
            //echo "{$key} => {$value} ";
            //print_r($modalities);
        }
        dd($value->id);

        return view('admin::modilities.create',compact('modalities'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
       //dd($request->all());
        $id = Str::uuid();

        if ($request->parent_yes == 1)
        {
           // dd('parent');
            $modility = Modility::create([
                'id'    => $id,
                'modility_name' => $request->modility_name,

            ]);
        }

//


        return response()->json(['Sucess'=>'Save succesfully', $id]);


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
    public function edit(Request $request ,$id)
    {
        if ($request->ajax())
        {
            $parentmodalities = Modility::find($id);
            $output = '';
            $output .= "<input type='text' class='form-control' maxlength='50'
            id='modility_name' name='modility_name' value = '$parentmodalities->modility_name'>";
            $output .= "<input type='hidden' class='form-control' maxlength='50'
            id='parent_id' name='parent_id' value ='$parentmodalities->id'>";
            return Response($output);

        }

    }

    public function showChild(Request $request ,$id)
    {
        if ($request->ajax())
        {
            $childmodalities = ChildModilities::select('*')->where('modility_id', $id)->get();

            $output = "";

            if (!empty($childmodalities)) {
                foreach ($childmodalities as $key => $modality) {
                    $output.= '<li class="nav-item mail-item" style="border-bottom: 1px solid #F6F6F7;">
                                <div class="d-flex align-self-center align-middle">
                                    <div class="mail-content d-md-flex w-100">
                                        <a href="#" data-mailtype="tab_" class="nav-link " data-id='.$modality->id.'>
                                            <span class="mail-user">'.$modality->modility_name.'</span>
                                        </a>
                                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                                            <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item edit_steps" data-id='.$modality->id.'><i class="far fa-edit"></i>&nbsp; Edit</span>
                                                <span class="dropdown-item deleteChild" data-id='.$modality->id.'><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>';
                }

                return Response ($output);
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

        $data = array (
            'modility_name' => $request->modility_name
        );
        Modility::where('id', $request->parent_id)->update($data);

        return response()->json(['success'=>'Modility  is Updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */


    public function destroy(Request $request,$id)
    {
        if ($request->ajax())
        {
            $delete = Modility::find($id);

            $delete->delete();
            return response()->json(['success'=>'Parent is deleted successfully.']);
        }
    }

    public function restoreParent(Request $request,$id)
    {

        if ($request->ajax())
        {
            $child = Modility::withTrashed()->find($id)->restore();
            return response()->json(['success'=>'Parent and there prospective Childs are restore successfully.']);
        }
    }


    public function replicateParent(Request $request,$id)
    {

        if ($request->ajax()) {

            $modalities = Modility::find($id);

            $clone = $modalities->replicate();

            $id = Str::uuid();

                $modility = Modility::create([
                    'id'    => $id,
                    'modility_name' => $clone->modility_name,

                ]);

              $latest_modility = Modility::select('id')->latest()->first();

            foreach ($modalities->children as $child)
            {
                    $child_id = Str::uuid();
                    $child = ChildModilities::create([
                        'id'    => Str::uuid(),
                        'modility_name'=>$child->modility_name,
                        'modility_id' =>$latest_modility->id
                    ]);
            }

        }

    }




}
