<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Annotation;

class AnnotationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $annotation = Annotation::select('*')->where('study_id',session('current_study'))->get();
        return view('admin::annotation.index',compact('annotation'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id    = Str::uuid();
        $annotation = Annotation::create([
            'id' => $id, 
            'study_id' => session('current_study'),
            'label' => $request->annotation_name
        ]);

        $oldAnnotation = [];

        // log event details
        $logEventDetails = eventDetails($id, 'Annotation', 'Add', $request->ip(), $oldAnnotation);

        return redirect()->route('annotation.index');
    }

    public function update_annotation(Request $request, $id='') {
        // to get old record for logs
        $oldAnnotation = Annotation::find($request->annotation_id);
        $newAnnotation = Annotation::find($request->annotation_id);
        $newAnnotation->label  =  $request->annotation_name;
        $newAnnotation->save();

        // log event details
        $logEventDetails = eventDetails($request->annotation_id, 'Annotation', 'Update', $request->ip(), $oldAnnotation);

        return redirect()->route('annotation.index');
    }
    public function destroy($id)
    {
        
    }
    public function deleteAnnotation($id){
       $annotation = Annotation::where('id',$id)->delete();
       $Response['data'] = 'success';
       echo json_encode($Response);  
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
   
}
