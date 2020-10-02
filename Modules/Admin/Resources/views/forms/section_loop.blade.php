    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="wizard wizard-white mb-4">
                        <ul class="nav nav-tabs d-block d-sm-flex">
                            @php
                            $stepIdStr = buildSafeStr($step->step_id, '');
                            $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                            $firstSection = true;
                            @endphp
                            @foreach ($sections as $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                @endphp
                                <li class="nav-item mr-auto mb-4">
                                    <a class="nav-link p-0
                                    {{ $studyClsStr }}
                                    {{ $stepClsStr }}
                                    {{ $sectionClsStr }}
                                    {{ $firstSection ? 'active' : '' }}
                                    {{ $firstSection ? 'first_navlink_'.$stepIdStr : '' }}"

                                        data-toggle="tab" href="#tab{{ $section->id }}">
                                        <div class="d-flex">
                                            <div class="mr-3 mb-0 h1">{{ $section->sort_number }}</div>
                                            <div class="media-body align-self-center">
                                                <h6 class="mb-0 text-uppercase font-weight-bold">{{ $section->name }}
                                                </h6>
                                                {{ $section->description }}
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
                            @foreach ($sections as $key => $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                $sectionIdStr = buildSafeStr($section->id, '');

                                $getFormStatusArray = [
                                'form_filled_by_user_id' => $form_filled_by_user_id,
                                'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
                                'subject_id' => $subjectId,
                                'study_id' => $studyId,
                                'study_structures_id' => $phase->id,
                                'phase_steps_id' => $step->step_id,
                                'section_id' => $section->id,
                                ];
                                $formStatusObj =
                                \Modules\Admin\Entities\FormStatus::getFormStatusObj($getFormStatusArray);
                                $formStatus = (null !== $formStatusObj)? $formStatusObj->form_status:'NoStatus';

                                $sharedData = [
                                'studyId' => $studyId, 'studyClsStr' => $studyClsStr, 'subjectId' => $subjectId, 'phase' => $phase,
                                'step' => $step, 'section' => $section, 'formStatusObj' => $formStatusObj,
                                'formStatus' => $formStatus, 'sectionIdStr' => $sectionIdStr,
                                'sectionClsStr' => $sectionClsStr, 'stepClsStr'=> $stepClsStr,
                                'key' => $key, 'first' => 0, 'last' => $last
                                ];

                                @endphp
                                <div class="tab-pane tab-pane_{{ $stepIdStr }} fade {{ $firstSection ? 'first_tab_'.$stepIdStr : '' }} {{ $firstSection ? 'active show' : '' }}"
                                    id="tab{{ $section->id }}">
                                    @include('admin::forms.section_questions', $sharedData )
                                    @include('admin::forms.section_next_previous', $sharedData)
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
    @push('script')
        <script>
            function submitSectionForms{{ $stepIdStr }}(stepIdStr, stepClsStr) {
                if (checkTermCond(stepIdStr)) {
                    @foreach($sections as $key => $section)
                        @php
                        $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                        $sectionIdStr = buildSafeStr($section->id, '');
                        @endphp
                        validateForm{{ $sectionIdStr }}();
                        var submitFormFlag = true;
                        if (isFormInEditMode('{{ $sectionIdStr }}')) {
                            if (checkReason(stepIdStr) === false) {
                                submitFormFlag = false;
                            }
                        }
                        if (submitFormFlag) {
                            submitForm('{{ $sectionIdStr }}', '{{ $sectionClsStr }}', '{{ $stepIdStr }}');
                        }
                    @endforeach
                        if(submitFormFlag){
                            hideReasonField(stepIdStr, stepClsStr)
                        }
                }
            }
        </script>
    @endpush
