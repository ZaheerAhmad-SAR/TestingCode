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
                                <td>
                                    <span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('1',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','=','<=',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','!=','<=',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','=','>',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('1','!=','>',$where_study) }}
                                    </span>
                                </td>
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
                                <td><span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('3' , $where_study) }}</span></td>
                                <td><span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','=','<=',$where_study) }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','!=','<=',$where_study) }}</span></td>
                                <td><span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','=','>',$where_study) }}</span></td>
                                <td><span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('3','!=','>',$where_study) }}</span></td>
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
                                <td>
                                    <span class="badge badge-pill badge-light p-2 mb-1">{{ get_all_counts_assigned_work('2',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-success p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','=','<=',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','!=','<=',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-warning p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','=','>',$where_study) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-danger p-2 mb-1">{{ get_all_counts_assigned_work_withoperator('2','!=','>',$where_study) }}
                                    </span>
                                </td>
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