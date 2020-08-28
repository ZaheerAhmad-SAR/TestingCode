<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $sites = Site::paginate(20);
        $site = Site::latest('created_at')->first();
        $photographers = Photographer::all();
        $coordinators = Coordinator::all();
        $pinvestigators = PrimaryInvestigator::all();
            return view('admin::sites.index',compact('sites','photographers','pinvestigators','coordinators','site'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::sites.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
       $validate = $request->validate([
           'site_code:required',
           'site_name:required',
            'site_country:required',
            'site_address:required',
            'site_city:required',
            'site_state:required',
            'site_phone:required|numeric|max:15',
            'site_email:required|email',
        ]);
       $id = Str::uuid();
            $site = Site::create([
                'id'    => $id,
                'site_code'=> empty($request->site_code) ? Null : $request->site_code,
                'site_name' => empty($request->site_name) ? Null : $request->site_name,
                'site_country'=> empty($request->site_country) ? Null : $request->site_country,
                'site_address'=>empty($request->site_address)? Null : $request->site_address,
                'site_city'=> empty($request->site_city) ? Null : $request->site_city,
                'site_state'=> empty($request->site_state)? Null : $request->site_state,
                'site_phone'=> empty($request->site_phone) ? Null : $request->site_phone,
                'site_email'=>empty($request->site_email)? Null : $request->site_email
            ]);
        $new_site = Site::select('id')->latest()->first();


        return response()->json(['success'=>'Site Info is added successfully']);

        //return redirect()->route('sites.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Site $site)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Site $site, $id)
    {
        $pi = PrimaryInvestigator::find($id);
        return view('admin::sites.edit',compact('site','pi'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Site $site)
    {
        $pi = PrimaryInvestigator::find($request->id);

        $site->update($request->all());

        return redirect()->route('sites.index')->with('success','Study updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
