@extends('layouts.home')
@section('title')
    <title> Dashboard | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">System Dashboard</h4>
                </div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->
    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-file-medical-alt fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ Modules\Admin\Entities\Study::count() }}</h2> <strong>Total Studies</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-user fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ App\User::count() }}</h2> <strong>Total Users</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-user-tag fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ Modules\Admin\Entities\Subject::count() }}</h2> <strong>Total Subjects</strong></span> 
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'> 
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-file-signature fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ Modules\FormSubmission\Entities\SubjectsPhases::count() }}</h2> <strong>Total Visits</strong></span> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12  col-lg-12 mt-3">
            <div class="card">
                <div class="card-header  justify-content-between align-items-center">
                    <h6 class="card-title"> Visits Progress </h6>
                </div>
                <div class="card-body table-responsive p-0">

                    <table class="table font-w-600 mb-0">
                        <thead>
                            <tr>
                                <th style="text-align: left;width:10%">Expand</th>
                                <th>Type</th>
                                <th>Initiated</th>
                                <th>Complete</th>
                                <th>Editing</th>
                                <th>Not Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-QC" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>QC</td>                            
                               
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'incomplete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'resumable' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'not_required' ))->count() }}</span></td>
                            </tr>
                            <tr class="collapse row-QC">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Total Initiated</th>
                                            <th>Total Complete</th>
                                            <th>Total Editing</th>
                                            <th>Total Not Required</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'incomplete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'resumable','modility_id'  => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'not_required','modility_id'  => $value->id ))->count() }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-eligibility" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Eligibility</td>
                                
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'incomplete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'resumable' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'not_required' ))->count() }}</span></td>
                            </tr>
                            <tr class="collapse row-eligibility">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Total Initiated</th>
                                            <th>Total Complete</th>
                                            <th>Total Editing</th>
                                            <th>Total Not Required</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'incomplete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'resumable','modility_id'  => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'not_required','modility_id'  => $value->id ))->count() }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-G1" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Grader 1</td>
                                
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required' ))->count() }}</span></td>
                            </tr>
                            <tr class="collapse row-G1">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Total Initiated</th>
                                            <th>Total Complete</th>
                                            <th>Total Editing</th>
                                            <th>Total Not Required</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable','modility_id'  => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required','modility_id'  => $value->id ))->count() }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-G2" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Grader 2</td>
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required' ))->count() }}</span></td>
                            </tr>
                            <tr class="collapse row-G2">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Total Initiated</th>
                                            <th>Total Complete</th>
                                            <th>Total Editing</th>
                                            <th>Total Not Required</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable','modility_id'  => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required','modility_id'  => $value->id ))->count() }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-Adj" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Adjudication</td>
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'incomplete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'resumable' ))->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'not_required' ))->count() }}</span></td>
                            </tr>
                            <tr class="collapse row-Adj">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Total Initiated</th>
                                            <th>Total Complete</th>
                                            <th>Total Editing</th>
                                            <th>Total Not Required</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'incomplete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete','modility_id' => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'resumable','modility_id'  => $value->id ))->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'not_required','modility_id'  => $value->id ))->count() }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--  --}}
    <div class="row">
        <div class="col-12  col-lg-12 mt-3">
            <div class="card">
                <div class="card-header  justify-content-between align-items-center">
                    <h6 class="card-title"> Assigned Statistics </h6>
                </div>
                <div class="card-body table-responsive p-0">

                    <table class="table font-w-600 mb-0">
                        <thead>
                            <tr>
                                <th style="text-align: left;width:10%">Expand</th>
                                <th>Type</th>
                                <th>Total Assigned</th>
                                <th>Complete Within Due Date </th>
                                <th>Not Complete and Due </th>
                                <th>Complete After Due Date</th>
                                <th>Not Complete After Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-QC2" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>QC</td>                            
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('1') }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','!=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','=','>') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','!=','>') }}</span></td>
                            </tr>
                            <tr class="collapse row-QC2">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Complete Within Due Date </th>
                                            <th>Not Complete and Due </th>
                                            <th>Complete After Due Date</th>
                                            <th>Not Complete After Due Date</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('1','=','<=',$value->id) }}</span></td>
                                                {{-- <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoutoperator_modality('1',$value->id) }}</span></td> --}}
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_not_complete_and_due('1',$value->id) }}</span></td>
                                                
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('1','=','>',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('1','!=','>',$value->id) }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-eligibility2" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Eligibility</td>
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('3') }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','!=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','=','>') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','!=','>') }}</span></td>
                            </tr>
                            <tr class="collapse row-eligibility2">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Complete Within Due Date </th>
                                            <th>Not Complete and Due </th>
                                            <th>Complete After Due Date</th>
                                            <th>Not Complete After Due Date</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('3','=','<=',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoutoperator_modality('3',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('3','=','>',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('3','!=','>',$value->id) }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;width:10%">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-grading_assign" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td>Grading</td>
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('2') }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','!=','<=') }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','=','>') }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','!=','>') }}</span></td>
                            </tr>
                            <tr class="collapse row-grading_assign">
                                <td colspan="7">
                                   <table class="table table-hover" style="width: 100%">
                                        <thead class="table-info">
                                            <th>Modality Name</th>
                                            <th>Complete Within Due Date </th>
                                            <th>Not Complete and Due </th>
                                            <th>Complete After Due Date</th>
                                            <th>Not Complete After Due Date</th>
                                        </thead>
                                        <tbody>
                                            @foreach($modalities as $key => $value)
                                            <tr>
                                                <td>{{ $value->modility_abbreviation }}</td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('2','=','<=',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_not_complete_and_due('2',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('2','=','>',$value->id) }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator_modality('2','!=','>',$value->id) }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- END: Card DATA-->
</div>
@stop
@section('styles')
<style type="text/css">
    .badge{
        line-height: 0.4 !important;
    }
    .detail-icon{
        cursor: pointer;
    }
    td {
        text-align: center;
    }
    th {
        text-align: center;
    }
    
</style>
<link rel="stylesheet"  href="{{ asset('public/dist/vendors/chartjs/Chart.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/morris/morris.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/chartjs/Chart.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/starrr/starrr.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/ionicons/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css') }}">
@stop
@section('script')
<script type="text/javascript">
    $('.detail-icon').click(function(e){
        $(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
    });
</script>
<script src="{{ asset('public/dist/vendors/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/morris/morris.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/starrr/starrr.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.canvaswrapper.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.colorhelpers.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.saturated.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.browser.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.drawSeries.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.uiConstants.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.legend.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('public/dist/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js') }}"></script>
<script src="{{ asset('public/dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
<script src="{{ asset('public/dist/vendors/apexcharts/apexcharts.js') }}"></script>
<script  src="{{ asset('public/dist/vendors/lineprogressbar/jquery.lineProgressbar.js') }}"></script>
<script  src="{{ asset('public/dist/vendors/lineprogressbar/jquery.barfiller.js') }}"></script>

<script src="{{ asset('public/dist/js/home.script.js') }}"></script>
@stop
