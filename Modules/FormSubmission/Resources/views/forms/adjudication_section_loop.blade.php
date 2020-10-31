@if (canAdjudication(['index']))
<div class="all_step_sections step_adjudication_sections_{{ $stepIdStr }}"
        style="display: {{ $firstStep ? 'block' : 'none' }};">
        @php
        $form_filled_by_user_id = ($form_filled_by_user_id ?? '');
        $subjectId = ($subjectId ?? '');
        $studyId = ($studyId ?? '');
        $studyClsStr = ($studyClsStr ?? '');

        $getAdjudicationFormStatusArray = [
        'form_adjudicated_by_id' => $form_filled_by_user_id,
        'subject_id' => $subjectId,
        'study_id' => $studyId,
        'study_structures_id' => $phase->id,
        'phase_steps_id' => $step->step_id,
        'modility_id' => $step->modility_id,
        ];
        $adjudicationFormStatusObj =
        \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);
        $adjudicationFormStatus = (null !== $adjudicationFormStatusObj)?
        $adjudicationFormStatusObj->adjudication_status:'no_status';
        @endphp
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form name="adjudication_form_master_{{ $stepIdStr }}"
                            id="adjudication_form_master_{{ $stepIdStr }}">
                            @csrf
                            <input type="hidden" name="studyId" value="{{ $studyId }}" />
                            <input type="hidden" name="subjectId" value="{{ $subjectId }}" />
                            <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
                            <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
                            <input type="hidden" name="formTypeId" value="{{ $step->form_type_id }}" />
                            <input type="hidden" name="modilityId" value="{{ $step->modility_id }}" />
                            <input type="hidden" class="adjudication_form_hid_editing_status_{{ $stepIdStr }}"
                                name="adjudication_form_editing_status_{{ $stepIdStr }}"
                                id="adjudication_form_editing_status_{{ $stepIdStr }}"
                                value="{{ $adjudicationFormStatus == 'resumable' ? 'yes' : 'no' }}" />


                        </form>
                        <form class="" method="POST" name="adjudication_form_{{ $stepIdStr }}"
                            id="adjudication_form_{{ $stepIdStr }}">
                            <div class="wizard wizard-white mb-4">
                                <ul class="nav nav-tabs d-block d-sm-flex">
                                    @php
                                    $stepIdStr = buildSafeStr($step->step_id, '');
                                    $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                    $firstSection = true;
                                    @endphp
                                    @foreach ($sections as $section)
                                        @php
                                        $sectionClsStr = buildSafeStr($section->id, 'section_cls_');
                                        @endphp
                                        <li class="nav-item mr-auto mb-4">
                                            <a class="nav-link p-0
                                    {{ $firstSection ? 'active' : '' }}
                                    {{ $firstSection ? 'first_navlink_' . $stepIdStr : '' }}" data-toggle="tab"
                                                href="#adjudication_tab_{{ $section->id }}">
                                                <div class="d-flex">
                                                    <div class="mr-3 mb-0 h1">{{ $section->sort_number }}</div>
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0 text-uppercase font-weight-bold">
                                                            {{ $section->name }}
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
                                        $sharedData = [
                                        'studyId' => $studyId,
                                        'studyClsStr' => $studyClsStr,
                                        'subjectId' => $subjectId,
                                        'phase' => $phase,
                                        'step' => $step,
                                        'section' => $section,
                                        'adjudicationFormStatusObj' => $adjudicationFormStatusObj,
                                        'adjudicationFormStatus' => $adjudicationFormStatus,
                                        'sectionIdStr' => $sectionIdStr,
                                        'sectionClsStr' => $sectionClsStr,
                                        'stepClsStr'=> $stepClsStr,
                                        'key' => $key,
                                        'first' => 0,
                                        'last' => $last,
                                        'getAdjudicationFormStatusArray'=>$getAdjudicationFormStatusArray
                                        ];
                                        @endphp
                                        <div class="tab-pane fade {{ $firstSection ? 'first_tab_' . $stepIdStr : '' }} {{ $firstSection ? 'active show' : '' }}"
                                            id="adjudication_tab_{{ $section->id }}">
                                            @include('formsubmission::forms.adjudication_section_questions', $sharedData )
                                            @include('formsubmission::forms.adjudication_section_next_previous', $sharedData)
                                        </div>
                                        @php
                                        $firstSection = false;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('queries::queries.query_popup')
    @push('script')
        <script>
            function submitStepAdjudicationForm{{ $stepIdStr }}(stepIdStr, stepClsStr) {
                if (checkAdjudicationFormTermCond(stepIdStr)) {
                    if (isAdjudicationFormInEditMode(stepIdStr)) {
                        if (checkAdjudicationFormReason(stepIdStr) == false) {
                            stopJsHere();
                        }
                    }
                    validateAndSubmitAdjudicationForm(stepIdStr, '{{ $adjudicationFormStatusObj->form_type_id }}',
                        '{{ buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id) }}');
                    reloadPage(3);
                }
            }

        </script>
    @endpush
    @push('script_last')
        <script>
            $(document).ready(function() {
                @php
                if ($adjudicationFormStatusObj->adjudication_status != 'complete') {
                    echo "globalDisableByClass($stepCounter, '$studyClsStr', '$stepClsStr');";
                } else {
                    echo "hideAdjudicationFormReasonField('$stepIdStr', '$stepClsStr', '$adjudicationFormStatusObj->form_type_id', '" . buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id) . "');";
                }
                @endphp
            });

        </script>
    @endpush
@endif
