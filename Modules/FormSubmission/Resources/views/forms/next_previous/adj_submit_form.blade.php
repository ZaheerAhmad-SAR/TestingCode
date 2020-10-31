@if ((bool) $subjectId)
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-md-11">
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="adjudication_form_terms_cond_{{ $stepIdStr }}"
                        id="adjudication_form_terms_cond_{{ $stepIdStr }}" value="accepted">
                    <label class="custom-control-label checkbox-primary" for="primary">I
                        acknowledge that the information submitted in this form is true and
                        correct to the best of my knowledge.</label>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-success float-right"
                    onclick="submitStepAdjudicationForm{{ $stepIdStr }}('{{ $stepIdStr }}', '{{ $stepClsStr }}');"
                    id="adjudication_form_submit_{{ $stepIdStr }}">Submit</button>
            </div>
        </div>
    @endif
