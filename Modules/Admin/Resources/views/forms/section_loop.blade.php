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
                                    <a class="nav-link p-0 {{ $stepClsStr }} {{ $sectionClsStr }} {{ $firstSection ? 'active' : '' }}"
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
                                $studyId = isset($studyId) ? $studyId : 0;
                                $subjectId = isset($subjectId) ? $subjectId : 0;

                                $form_filled_by_user_id = auth()->user()->id;
                                $form_filled_by_user_role_id = auth()->user()->id;

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
                                'studyId'=> $studyId, 'subjectId'=>$subjectId, 'phase'=> $phase,
                                'step'=> $step, 'section'=> $section, 'formStatusObj'=> $formStatusObj,
                                'formStatus'=> $formStatus, 'sectionIdStr'=> $sectionIdStr, 'sectionClsStr'=>
                                $sectionClsStr,
                                'stepClsStr'=> $stepClsStr
                                ];
                                @endphp
                                <div class="tab-pane fade {{ $firstSection ? 'active show' : '' }}"
                                    id="tab{{ $section->id }}">
                                    @include('admin::forms.section_questions', $sharedData )
                                    @include('admin::forms.section_next_previous', ['key'=> $key, 'first'=>0,
                                    'last'=>$last]+$sharedData)
                                </div>
                                @php
                                $firstSection = false;
                                @endphp
                            @endforeach
                            @if ((bool) $subjectId)
                                <div class="row">
                                    <div class="col-md-12">&nbsp;</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input" name="terms_cond_{{ $stepIdStr }}"
                                                id="terms_cond_{{ $stepIdStr }}" value="accepted">
                                            <label class="custom-control-label checkbox-primary" for="primary">I
                                                acknowledge that the information submitted in this form is true and
                                                correct to the best of my knowledge.</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success float-right"
                                            onclick="submitSectionForms{{ $stepIdStr }}('{{ $stepIdStr }}');"
                                            id="submit_{{ $stepIdStr }}">Submit</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            function submitSectionForms{{ $stepIdStr }}(stepIdStr) {
                if ($('#terms_cond_'+stepIdStr).prop('checked')) {
                    var anyFormEditable = false;
                    @foreach($sections as $key => $section)
                    @php
                    $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                    $sectionIdStr = buildSafeStr($section->id, '');
                    @endphp
                    if($('#fieldset_'+stepIdStr).prop('disabled') === false){
                        anyFormEditable = true;
                        submitForm('{{ $sectionIdStr }}', '{{ $sectionClsStr }}', '{{ $stepIdStr }}');
                    }
                    @endforeach
                    if(anyFormEditable === false){
                        alert('Please make form editable first!');
                    }
                } else {
                    alert(
                        'Please acknowledge the truthfulness and correctness of information being submitting in this form!'
                    );
                }
            }
            </script>
            @endpush
