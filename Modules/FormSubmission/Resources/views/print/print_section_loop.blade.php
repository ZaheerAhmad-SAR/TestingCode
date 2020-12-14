@php
/**************************************/
/**************************************/
/**************************************/
$transmissionNumber = \Modules\FormSubmission\Entities\SubjectsPhases::getTransmissionNumber($subjectId, $phase->id);
/**************************************/
/**************************************/
/**************************************/
@endphp
    <div class="all_step_sections step_sections_{{ $stepIdStr }}">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="wizard wizard-white mb-4">
                            @php
                            $current_user_id = ($current_user_id ?? '');
                            $subjectId = ($subjectId ?? '');
                            $studyId = ($studyId ?? '');
                            $studyClsStr = ($studyClsStr ?? '');
                            @endphp
                            @foreach ($sections as $key => $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'section_cls_');
                                @endphp
                                <div class="">
                                    <div class="d-flex">
                                        <div class="mr-3 mb-0 h1">{{ $section->sort_number }}</div>
                                        <div class="media-body align-self-center">
                                            <h6 class="mb-0 text-uppercase font-weight-bold">
                                                {{ $section->name }}
                                            </h6>
                                            {{ $section->description }}
                                            @if (!empty($transmissionNumber))
                                                <br>
                                                <span class="text text-danger">Transmission Number :
                                                    {{ $transmissionNumber }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        @php
                                        $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                        $skipLogicSectionIdStr = buildSafeStr($section->id, 'skip_logic_');
                                        $sectionIdStr = buildSafeStr($section->id, '');
                                        $sharedData = [
                                        'studyId' => $studyId,
                                        'studyClsStr' => $studyClsStr,
                                        'subjectId' => $subjectId,
                                        'phase' => $phase,
                                        'step' => $step,
                                        'section' => $section,
                                        'formStatusObj' => $formStatusObj,
                                        'sectionIdStr' => $sectionIdStr,
                                        'sectionClsStr' => $sectionClsStr,
                                        'skipLogicSectionIdStr' => $skipLogicSectionIdStr,
                                        'stepClsStr'=> $stepClsStr,
                                        'skipLogicStepIdStr'=> $skipLogicStepIdStr,
                                        ];

                                        @endphp
                                        <div>
                                            @include('formsubmission::print.print_section_questions', $sharedData )
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
