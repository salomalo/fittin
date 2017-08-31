<?php

function display_chart( $dates, $minutes ) { ?>
	<div class="fittin-chart"><h4></h4>
		<canvas id="fittinChart" width="1200" height="400"></canvas>
		<script>
		var ctx = document.getElementById("fittinChart").getContext('2d');
		var fittinChart = new Chart(ctx, {
		    type: 'line',
		    data: {
				labels : [<?php echo $dates ?>],
				datasets : [
					{
						label 			: "Video views (minutes)" ,
						backgroundColor	: "#663ff2",
						// backgroundColor	: "rgba(255,0,0,0.3)",
						data 			: [<?php echo $minutes ?>]
					},
					// {
					// 	label 			: "Shubbadubba" ,
					// 	backgroundColor	: "rgba(255,255,0,0.3)",
					// 	data 			: [<?php //echo $minutes2 ?>]
					// },
				]
		    },
		    options: {
				lineTension: 1,
				pointBackgroundColor: "f0f",
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fixedStepSize: 1
						}
					}]
			    }
		    }
		});
		</script>

		<div class="chart-nav" data-timestamp='<?php echo time(); ?>' data-week="0" data-month="0">
			<span class="prev hide">Previous</span>
			<span class="divider hide"> || </span>
			<span class="next hide">Next</span>
		</div>
		<div class="hide chart-loading"></div>
	</div><!-- fittin chart-->

	<?php
}
