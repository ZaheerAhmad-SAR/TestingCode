<?php

namespace Modules\BugReporting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\BugReporting\Entities\BugReport;
use App\Traits\UploadTrait;


class BugReportingController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $records = BugReport::where('parent_bug_id','like',0)->where('bug_reporter_by_id','=',\auth()->user()->id)->get();
        return view('bugreporting::pages.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('bugreporting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $shortTitle       = $request->post('shortTitle');
        $yourMessage      = $request->post('yourMessage');
        $query_url        = $request->post('query_url');
        $severity         = $request->post('severity');
        $filePath         = '';
        if ($request->has('attachFile'))
        {
            if (!empty($request->file('attachFile'))) {
                $image = $request->file('attachFile');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/bug_storage/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }

        $id              = Str::uuid();
        $query           = BugReport::create([
            'id'=>$id,
            'bug_reporter_by_id'=>\auth()->user()->id,
            'parent_bug_id'=> 0,
            'bug_message'=>$yourMessage,
            'status'=> 'open',
            'open_status'=>'Unconfirmed',
            'bug_url'=>$query_url,
            'bug_title'=>$shortTitle,
            'bug_attachments'=>$filePath,
            'bug_priority'=>$severity
        ]);
        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('bugreporting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if ($id)
        {
            $record = BugReport::find($id);
            return response()->json([$record]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request,$id)
    {
       if ($request->ajax())
       {
           $delete = BugReport::find($id);
           $delete->delete();
           return response()->json(['success' => 'Bug  is deleted successfully.']);
       }
    }
}
