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
                               
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'incomplete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'resumable' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'not_required' ))->where($where_study)->count() }}</span></td>
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
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'incomplete','modility_id' => $value->id))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'resumable','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'not_required','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
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
                                
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'incomplete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'resumable' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'not_required' ))->where($where_study)->count() }}</span></td>
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
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'incomplete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'resumable','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'not_required','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
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
                                
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required' ))->where($where_study)->count() }}</span></td>
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
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'resumable','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'not_required','modility_id'  => $value->id))->where($where_study)->count() }}</span></td>
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
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'incomplete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'resumable' ))->where($where_study)->count() }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'not_required' ))->where($where_study)->count() }}</span></td>
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
                                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'incomplete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete','modility_id' => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'resumable','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
                                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'not_required','modility_id'  => $value->id ))->where($where_study)->count() }}</span></td>
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