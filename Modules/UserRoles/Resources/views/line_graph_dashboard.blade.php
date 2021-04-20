<div class="row">
	<div class="col-12 col-lg-12  mt-3">
	    <div class="card">                           
	        <div class="card-content" style="padding: 2px;">
	            <div class="card-body">
	                <canvas id="chartjs_corona" style="height: 300px !important;"></canvas>
                    <canvas id="chartjs-account-chart" style="display: none;"></canvas>
	            </div>
                <button type="button" class="btn btn-outline-primary" id="line">
                    <i class="fas fa-chart-line"></i> Daily Statistics
                </button>
                <button type="button" class="btn btn-outline-primary" id="bar_chart">
                    <i class="fas fa-chart-bar"></i> Monthly Statistics
                </button>
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
////////////// Account Page Chart //////////////////////
    var ctx = '';
    var chartjs_multiaxis_bar = document.getElementById("chartjs-account-chart");
    if (chartjs_multiaxis_bar) {
        var barmultiaxisChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                    label: 'Investment',
                    backgroundColor: [
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor,
                        primarycolor
                    ],
                    yAxisID: 'y-axis-1',
                    data: [35, 55, 75, 95, 115, 135, 155, 175, 195, 215, 235, 250]
                }]

        };
        ctx = document.getElementById('chartjs-account-chart').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barmultiaxisChartData,
            options: {
                responsive: true,
                legend: {
                    display: false,
                    position: 'top',
                    labels: {
                        fontColor: bodycolor
                    }
                },
                scales: {
                    xAxes: [{
                            display: true,
                            maxBarThickness: 10,
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
                            type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                            display: false,
                            position: 'left',
                            id: 'y-axis-2',
                            gridLines: {
                                drawOnChartArea: false,
                                color: bordercolor,
                                zeroLineColor: bordercolor
                            },
                            ticks: {
                                fontColor: bodycolor,
                            }
                        }, {
                            display: true,
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
        });
    }
/////////////////////// Map ///////////////////////
})(jQuery);
</script>
<script type="text/javascript">
    $('body').on('click','#bar_chart',function(){
        $('#chartjs_corona').css('display','none');
        $('#chartjs-account-chart').css('display','block');
    })
    $('body').on('click','#line',function(){
        $('#chartjs_corona').css('display','block');
        $('#chartjs-account-chart').css('display','none');
    })
</script>
@endpush