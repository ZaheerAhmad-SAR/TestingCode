<div class="row">
	<div class="col-12 col-lg-12  mt-3">
	    <div class="card">                           
	        <div class="card-content">
	            <div class="card-body">
	                <canvas id="chartjs_corona" style="height: 300px !important;"></canvas>
	            </div>
	        </div>
	    </div>
	</div> 
</div> 
@push('script')
<script type="text/javascript">
	(function ($) {
    "use strict";
    var primarycolor = getComputedStyle(document.body).getPropertyValue('--primarycolor');
    var bordercolor = getComputedStyle(document.body).getPropertyValue('--bordercolor');
    var bodycolor = getComputedStyle(document.body).getPropertyValue('--bodycolor');
    var theme = 'light';
    if ($('body').hasClass('dark')) {
        theme = 'dark';
    }
    if ($('body').hasClass('dark-alt')) {
        theme = 'dark';
    }

/////////////////////////////////// Corona Chart /////////////////////

if ($("#chartjs_corona").length > 0)
{
	@php 
		$total_online = '';
        $time_in_24_hour_format = '';
        $i = 0;
        foreach($records as $key => $value){
            $key = $key.':00';
            $time  = date("h:i a", strtotime($key));
            $time_in_24_hour_format  .= "'$time'".',';
            $i++;
        }
        foreach($records as $key => $value){
            $total_online .= count($value).',';
            $i++;
        }
        foreach($records as $key => $value){
            $total_online .= count($value).',';
            
        }
	@endphp
    var config = {
        type: 'line',
        data: {
            labels: [@php echo $time_in_24_hour_format @endphp],
            datasets: [{
                    label: 'Online',
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    data: [@php echo $total_online @endphp],
                    borderWidth: 1
                }]
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'index',
            },
            hover: {
                mode: 'index'
            },
            legend: {
                position: 'top',
                display: false,
                labels: {
                    fontColor: bodycolor
                }
            },
            scales: {
                xAxes: [{
                        gridLines: {
                            display: true,
                            color: bordercolor,
                            zeroLineColor: bordercolor
                        },
                        ticks: {
                            fontColor: bodycolor,
                        },
                    }],
                yAxes: [{

                        gridLines: {
                            display: true,
                            color: bordercolor,
                            zeroLineColor: bordercolor
                        },
                        ticks: {
                            fontColor: bodycolor,
                        }
                    }]
            }
        }
    };
    var chartjs_area_stacked = document.getElementById("chartjs_corona");
    if (chartjs_area_stacked) {
        var ctx = document.getElementById('chartjs_corona').getContext('2d');
        window.myLine = new Chart(ctx, config);
    }

}
/////////////////////// Map ///////////////////////
})(jQuery);

</script>
@endpush