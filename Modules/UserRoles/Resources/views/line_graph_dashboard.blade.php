<div class="row">
	<div class="col-12 col-lg-12  mt-3">
	    <div class="card">                           
	        <div class="card-content">
	            <div class="card-body">
	                <canvas id="chartjs_corona"></canvas>
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
		// $record = App\user::all();
		
		// dd($record);
	@endphp
    var config = {
        type: 'line',
        data: {
            labels: ['12:00', '1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM', '6:00 AM', '7:00', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM', '6:00 PM', '7:00 PM', '8:00 PM', '9:00 PM', '10:00 PM', '11:00 PM'],
            datasets: [{
                    label: 'Online',
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    data: [27, 69, 22, 55, 31, 50,27, 69, 22, 55, 31, 50,27, 69, 22, 55, 31, 50,27, 69, 22, 55, 31, 50],
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