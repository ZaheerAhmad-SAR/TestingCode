@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">Phase: {{$phase->name}} / {{$step->step_name}}</h4>
                </div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Forms</li>
                    <li class="breadcrumb-item active"><a href="#">Form Type Here</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-12">
            @php
            $firstStep = true;
            @endphp
            @php
            $sections = $step->sections;
            @endphp
            <div class="tab-pane fade {{ ($firstStep) ? 'active show' : '' }}" id="tab{{$step->step_id}}">
                @include('admin::forms.section_loop', ['step'=>$step, 'sections'=> $sections])
            </div>
            @php
            $firstStep = false;
            @endphp
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
@stop

@section('styles')
<style>
.wizard-dark .wizard .nav-tabs .nav-link.active {
    border-bottom: 1px solid #1e3d73 !important;
}

/* Form Control */
.custom-control-input {
    z-index: 1 !important;
}

.form-control-ocap {
    display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}


.form-control-ocap,
.form-control-ocap:focus,
.form-control-ocap:disabled,
.form-control-ocap[readonly] {
    background: transparent;
    border-color: var(--bordercolor);
    font-size: 12px;
}

.form-group .form-control-ocap+.form-control-placeholder {
    position: absolute;
    top: 0;
    padding: 7px 0 0 13px;
    transition: all 200ms;
    opacity: 0.5;
}

.form-group .form-control-ocap:focus+.form-control-placeholder {
    transform: translate3d(0, -100%, 0);
    opacity: 1;
}

.form-group .form-control-ocap.form-control-lg+.form-control-placeholder {
    padding: 14px 0 0 13px;
}

.form-group .form-control-ocap.form-control-lg.float-input:focus {
    padding: 1.2rem 1rem .3rem 1rem;
}

.form-group .form-control-ocap.form-control-lg.float-input:focus+.form-control-placeholder {
    font-size: 70%;
    transform: translate3d(0, -40%, 0);
}

.form-group .input-group .input-group-prepend+.form-control-ocap+.form-control-placeholder {
    left: 40px;
}

.input-primary .form-control-ocap:focus {
    border-color: var(--primarycolor);
}

.input-primary .form-control-ocap:focus+.form-control-placeholder {
    color: var(--primarycolor);
}

.input-secondary .form-control-ocap:focus {
    border-color: var(--secondary);
}

.input-secondary .form-control-ocap:focus+.form-control-placeholder {
    color: var(--secondary);
}

.input-warning .form-control-ocap:focus {
    border-color: var(--warning);
}

.input-warning .form-control-ocap:focus+.form-control-placeholder {
    color: var(--warning);
}

.input-danger .form-control-ocap:focus {
    border-color: var(--danger);
}

.input-danger .form-control-ocap:focus+.form-control-placeholder {
    color: var(--danger);
}

.input-info .form-control-ocap:focus {
    border-color: var(--info);
}

.input-info .form-control-ocap:focus+.form-control-placeholder {
    color: var(--info);
}

.input-dark .form-control-ocap:focus {
    border-color: var(--dark);
}

.input-dark .form-control-ocap:focus+.form-control-placeholder {
    color: var(--dark);
}

.input-success .form-control-ocap:focus {
    border-color: var(--success);
}

.input-success .form-control-ocap:focus+.form-control-placeholder {
    color: var(--success);
}

.form-control-ocap,
.form-control-ocap:focus,
.btn-primary:not(:disabled):not(.disabled).active:focus,
.btn-primary:not(:disabled):not(.disabled):active:focus,
.show>.btn-primary.dropdown-toggle:focus,
.btn-secondary:not(:disabled):not(.disabled).active:focus,
.btn-secondary:not(:disabled):not(.disabled):active:focus,
.show>.btn-secondary.dropdown-toggle:focus,
.btn-primary.focus,
.btn-primary:focus {
    outline: none;
    box-shadow: none;
}
</style>
@stop