<div class='row'>
MOET NOG UITGEWERKT WORDEN!
<div class='col-md-8'>
	<div class="card text-xs-center">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs float-xs-left" role='tablist'>
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#chartDay" role="tab">Dag</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle='tab' href="#chartWeek" role='tab'>Week</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle='tab' href="#chartMonth" role='tab'>Maand</a>
				</li>
				<li class="nav-item">
					<a class='nav-link' data-toggle='tab' href='#chartYear' role='tab'>Jaar</a>
				</li>
			</ul>
		</div>
		<div class="card-block">
			<div class='tab-content'>
				<div class='tab-pane fade active in' id='chartDay' role='tabpanel'>
					<h4 class="card-title">Afgelopen 24 uur</h4>
					<div class='chart'>
						<canvas id="day" width="100%" height="180px"></canvas>
					</div>
					<table class='table'>
						<?php foreach(top5::genres($_GET['id'], "day", "artists") as $row) : ?>
						<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['url']; ?>'><?php echo $row['name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class='tab-pane fade' id='chartWeek' role='tabpanel'>
					<h4 class="card-title">Afgelopen week</h4>
					<div class='chart'>
						<canvas id="week" width="100%" height="180px"></canvas>
					</div>
					<table class='table'>
						<?php foreach(top5::genres($_GET['id'], "week", "artists") as $row) : ?>
						<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['url']; ?>'><?php echo $row['name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class='tab-pane fade' id='chartMonth' role='tabpanel'>
					<h4 class="card-title">Afgelopen maand</h4>
					<div class='chart'>
						<canvas id="month" width="100%" height="180px"></canvas>
					</div>
					<table class='table'>
						<?php foreach(top5::genres($_GET['id'], "month", "artists") as $row) : ?>
						<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['url']; ?>'><?php echo $row['name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class='tab-pane fade' id='chartYear' role='tabpanel'>
					<h4 class="card-title">Afgelopen jaar</h4>
					<div class='chart'>
						<canvas id="year" width="100%" height="180px"></canvas>
					</div>
					<table class='table'>
						<?php foreach(top5::genres($_GET['id'], "year", "artists") as $row) : ?>
						<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['url']; ?>'><?php echo $row['name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>