@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Skip Logic</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Skip Logic</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 success-alert" style="display: none;">
                <div class="alert alert-primary success-msg" role="alert">
                </div>
            </div>
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">                               
                        <h6 class="card-title">Activate Modalities,Sections,Questions</h6>
                    </div>
                    <div class="card-body">
                        <div class="card-content">
                            <div id="accordion2" class="accordion-alt" role="tablist">
                                @foreach($all_form_data as $key=>$value)
                                <div class="mb-2">
                                    <h6 class="mb-0">
                                        <a class="d-block border collapsed" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                            What is a coronavirus?
                                        </a>
                                    </h6>
                                    <div id="collapse4" class="collapse" role="tabpanel" data-parent="#accordion2" style="">
                                        <div class="card-body">
                                            <p>
                                                Coronaviruses are a large family of viruses which may cause illness in animals or humans.  In humans, several coronaviruses are known to cause respiratory infections ranging from the common cold to more severe diseases such as Middle East Respiratory Syndrome (MERS) and Severe Acute Respiratory Syndrome (SARS). The most recently discovered coronavirus causes coronavirus disease COVID-19. 
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">                               
                        <h6 class="card-title">Deactivate Modalities,Sections,Questions</h6>
                    </div>
                    <div class="card-body">
                        <div class="card-content">
                            <div id="accordion2" class="accordion-alt" role="tablist">
                                @foreach($all_form_data as $key=>$value)
                                <div class="mb-2">
                                    <h6 class="mb-0">
                                        <a class="d-block border collapsed" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                            What is a coronavirus?
                                        </a>
                                    </h6>
                                    <div id="collapse4" class="collapse" role="tabpanel" data-parent="#accordion2" style="">
                                        <div class="card-body">
                                            <p>
                                                Coronaviruses are a large family of viruses which may cause illness in animals or humans.  In humans, several coronaviruses are known to cause respiratory infections ranging from the common cold to more severe diseases such as Middle East Respiratory Syndrome (MERS) and Severe Acute Respiratory Syndrome (SARS). The most recently discovered coronavirus causes coronavirus disease COVID-19. 
                                            </p>
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
        <!-- END: Card DATA-->
    </div>
@endsection
@section('styles')
<style>
    .custom_fields{
        border-bottom: 1px solid #F6F6F7;
        padding: 10px;
    }
    .float-right{
        float: right;
    }
    .display-none{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
@endsection
@section('script')
<script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script>
<script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
<script src="{{ asset('public/js/edit_crf.js') }}"></script>


</script>

@endsection
