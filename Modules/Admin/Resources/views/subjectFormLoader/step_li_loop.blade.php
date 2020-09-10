<li class="nav-item  mb-4 col-sm-12">
    <a class="nav-link p-0 {{ ($firstStep) ? 'active' : '' }}" data-toggle="tab" href="#tab{{$step->step_id}}">
        <div class="d-flex text-white">
            <div class="mr-3 mb-0 h1">{{$step->step_position}}
            </div>
            <div class="media-body align-self-center">
                <h6 class="mb-0 text-uppercase font-weight-bold">
                    {{$step->step_name}}
                </h6>
                {{$step->step_description}}
            </div>
        </div>
    </a>
</li>