<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Section;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class SectionController extends Controller
{
    use ReplicatePhaseStructure;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::index');
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
    public function store(Request $request)
    {
        $id    = Str::uuid();
        Section::create([
            'id'    => $id,
            'phase_steps_id'    => $request->step_id,
            'name'  =>  $request->sec_name,
            'description' =>  $request->sec_description,
            'sort_number' =>  $request->sort_num
        ]);

        /************************* */
        $section = Section::find($id);
        $this->addSectionToReplicatedVisits($section, true);

        return $last_id = Section::select('id')->latest()->first();
        // return redirect()->route('study.index');
    }
    public function getSectionby_id(Request $request)
    {
        $id = $request->id;
        $section = Section::where('phase_steps_id', '=', $id)->orderBy('sort_number', 'asc')->get();
        $sectionData['data'] = $section;
        echo json_encode($sectionData);
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        dd($id);
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $section = Section::find($id);
        return view('admin::edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $section = Section::find($request->section_id);
        $section->name  =  $request->post('sec_name');
        $section->description  =  $request->post('sec_description');
        $section->sort_number  =  $request->post('sort_num');
        $section->save();
        $this->updateSectionToReplicatedVisits($section);
        $data = [
            'success' => true,
            'message' => 'Recode updated successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $section = Section::find($id);
        $this->deleteSection($section);
    }
}
