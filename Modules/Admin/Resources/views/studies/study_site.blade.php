@extends('layouts.home')

@section('title')
    <title> Study Sites | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('content')

<style type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    .required:after {
        content:" *";
        color: red;
    }

    .pac-container {
        z-index: 10000 !important;
    }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />


<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Study Sites</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Study Sites</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#assignSites">
                        <i class="fa fa-plus"></i> Assign Sites
                    </button>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Phone</th>
                                <th>Study Site ID</th>
                                <th><a><i class="btn custom-btn fas fa-edit edit-study-sites"></i></a></th>
                                <th>
                                    <div class="showButtonDiv" style="display: none;">
                                        <button name="studyUpdateButton" type="submit" id="studyUpdateButton" class="btn custom-btn blue-color"><i class="fa fa-refresh blue-color"></i> Update</button>
                                    </div>
                                </th>
                            </tr>
                            @if(!empty($sites))
                                @foreach($sites as $site)
                                    <tr>
                                        <td>{{ucfirst($site->site_code)}}</td>
                                        <td>{{ucfirst($site->site_name)}}</td>
                                        <td>{{ucfirst($site->site_address)}}</td>
                                        <td>{{ucfirst($site->site_city)}}</td>
                                        <td>{{ucfirst($site->site_state)}}</td>
                                        <td>{{ucfirst($site->site_country)}}</td>
                                        <td>{{ucfirst($site->site_phone)}}</td>
                                        <td>
                                            <input class="studySiteId" value="{{$site->study_site_id}}" type="text" name="study_site_id[]" id="study_site_id" disabled>
                                            <input type="hidden" name="studySiteIdValue[]" id="studySiteIdValue" value="{{$site->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
</div>


    <div class="modal" tabindex="-1" role="dialog" id="assignSites">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="width: inherit; top: auto!important;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="custom-modal-header gray-background color-black">
                    <p class="modal-title">Assign New Sites</p>
                </div>
                <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST">
                    <div class="custom-modal-body">
                        <div class="tab-content clearfix">
                            <div class="form-group">
                                <div class="{!! ($errors->has('sites')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <select class="searchable" id="select-sites" multiple="multiple" name="sites[]">
                                        @foreach($sites as $site)
                                            {{$site}}
                                            <option selected="selected" value="{{$site->id}}">{{$site->site_name}}</option>
                                        @endforeach
                                        @if(!empty($unassignSites))
                                            @foreach($unassignSites as $unassignSite)
                                            {{$unassignSite}}
                                            <option data-id="{{$unassignSite->id}}"  value="{{$unassignSite->id}}">{{$unassignSite->site_name}}</option>
                                        @endforeach
                                            @endif
                                    </select>
                                </div>
                                @error('sites')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.edit-study-sites').click(function (){
            $('.studySiteId').removeAttr("disabled");
            $('#study_site_id').focus();
            $('.showButtonDiv').show();
            $('.edit-study-sites').hide();
        });

        $("#studyUpdateButton").click(function () {
            var InputValues = $('input[name^=study_site_id]').map(function(idx, elem) {
                return $(elem).val();
            }).get();

            var InputIdValues = $('input[name^=studySiteIdValue]').map(function(idx, elem) {
                return $(elem).val();
            }).get();
            $.ajax({
                url: "{{route('updateStudySite')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'InputValues':InputValues,'InputIdValues':InputIdValues
                },
                success:function(results){
                    console.log(results);
                    $('.studySiteId').prop("disabled",true);
                    $('.showButtonDiv').hide();
                    $('.edit-study-sites').show();
                    //$("#msg").html("Recipe Saved");
                }
            });
        });

        $(document).ready(function() {
            $('#select-sites').multiSelect({
                selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='All Sites'>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Assign Sites'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function(e){
                            if (e.which === 40){
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function(e){
                            if (e.which == 40){
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
        });
    </script>
@endsection

