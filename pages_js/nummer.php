<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
<script>
	//Chart of the day
	$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=day&id=<?php echo $_GET['id']; ?>&return=data", function(data) {
		var dataSet = data;
		$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=day&id=<?php echo $_GET['id']; ?>&return=labels", function(labels) {
			chart('day', dataSet, 'Gedraaid', labels);
		});
	});
	//Chart of the week
	$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=week&id=<?php echo $_GET['id']; ?>&return=data", function(data) {
		var dataSet = data;
		$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=week&id=<?php echo $_GET['id']; ?>&return=labels", function(labels) {
			chart('week', dataSet, 'Gedraaid', labels);
		});
	});
	//Chart of the month
	$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=month&id=<?php echo $_GET['id']; ?>&return=data", function(data) {
		var dataSet = data;
		$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=month&id=<?php echo $_GET['id']; ?>&return=labels", function(labels) {
			chart('month', dataSet, 'Gedraaid', labels);
		});
	});
	//Chart of the year
	$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=year&id=<?php echo $_GET['id']; ?>&return=data", function(data) {
		var dataSet = data;
		$.get( "<?php echo BASE_URL; ?>/json.php?data=chartData&type=song&period=year&id=<?php echo $_GET['id']; ?>&return=labels", function(labels) {
			chart('year', dataSet, 'Gedraaid', labels);
		});
	});
	function chart(chartID, data, label, labels) {
		var ctx = document.getElementById(chartID);
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
					label: label,
					data: data,
					backgroundColor: 'rgba(116, 146, 163, 0.2)',
					borderColor: 'rgba(44, 52, 62, 1)',
					borderWidth: 1
				}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true,
							
						}
					}]
				},
				legend: {
					display: false
				},
				tooltips: {
					enabled: true,
					displayColors: false
					
				},
				responsive: true,
				maintainAspectRatio: false,
				showXLabels: 10,
				fullWidth: true
			}
		});
	}
	
</script>