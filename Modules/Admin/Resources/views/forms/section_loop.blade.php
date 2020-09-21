    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="wizard wizard-white mb-4">
                        <ul class="nav nav-tabs d-block d-sm-flex">
                            @php
                            $firstSection = true;
                            @endphp
                            @foreach ($sections as $section)
                            <li class="nav-item mr-auto mb-4">
                                <a class="nav-link p-0 {{ ($firstSection) ? 'active' : '' }}" data-toggle="tab"
                                    href="#tab{{$section->id}}">
                                    <div class="d-flex">
                                        <div class="mr-3 mb-0 h1">{{$section->sort_number}}</div>
                                        <div class="media-body align-self-center">
                                            <h6 class="mb-0 text-uppercase font-weight-bold">{{$section->name}}</h6>
                                            {{$section->description}}
                                        </div>

                                    </div>
                                </a>
                            </li>
                            @php
                            $firstSection = false;
                            @endphp
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @php
                            $firstSection = true;
                            $last = count($sections)-1;
                            @endphp
                            @foreach ($sections as $key=>$section)
                            <div class="tab-pane fade {{ ($firstSection) ? 'active show' : '' }}" id="tab{{$section->id}}">
                                @include('admin::forms.section_questions', ['studyId'=> isset($studyId) ? $studyId:0, 'subjectId'=>isset($subjectId) ? $subjectId:0, 'phase'=> $phase, 'step'=> $step, 'section'=> $section])
                                @include('admin::forms.section_next_previous', ['key'=> $key, 'first'=>0, 'last'=>$last])
                            </div>
                            @php
                            $firstSection = false;
                            @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
