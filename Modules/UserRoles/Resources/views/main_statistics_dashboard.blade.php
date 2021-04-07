<div class="row">
        <div class="col-12 col-lg-12  mt-3">
            <div class="card">                           
                <div class="card-content" style="padding: 2px;">
                    <div class="card-body" style="text-align: right;">
                       <div class="form-row"> 
                            <select class="form-control filter-by-study" style="width: 300px;">
                               <option value="">All Studies</option>
                               @foreach($studies as $key => $study_title)
                               <option value="{{ $study_title->id }}" @if($study_title->id == $studyid) selected @endif>{{ $study_title->study_short_name }}</option>
                               @endforeach
                            </select>
                        </div> 
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="row" style="margin-top: 5px;">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-file-medical-alt fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ Modules\Admin\Entities\Study::count() }}</h2> <strong>Total Studies</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-user-tag fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ Modules\Admin\Entities\Subject::where($where_study)->count() }}</h2> <strong>Total Subjects</strong></span> 
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'> 
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-file-signature fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;">
                             @php 
                                $getAllSubjects = Modules\Admin\Entities\Subject::where($where_study)->pluck('id')->toArray();
                             @endphp   
                             <h2>{{ Modules\FormSubmission\Entities\SubjectsPhases::whereIn('subject_id',$getAllSubjects)->count() }}</h2> 
                             <strong>Total Visits</strong>
                        </span> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-users fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ App\User::count() }}</h2> <strong>Total Users</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-users fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ App\User::where('working_status','online')->count() }}</h2> <strong>Online Users</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <span class="col-xl-4" style="display: contents;"><i class="fas fa-users fa-4x"></i></span>
                        <span class="col-xl-8" style="display: inline-block;"> <h2>{{ App\User::where('working_status','offline')->count() }}</h2> <strong>Offline Users</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>