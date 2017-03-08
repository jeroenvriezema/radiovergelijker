<div class='container'>
<?php
	if(isset($_GET['id'])) : ?>
		<?php $song = song::getInfo($_GET['id'], "name");
		if($song) : ?>
			<div class='row'>
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
										<?php foreach(top5::song($song['song_id'], $song['artist_id'], "day") as $row) : ?>
										<tr><td class='np_cover_img'><img src='<?php echo $row['station_image']; ?>'/></td><td><a href='<?php echo $row['station_url']; ?>'><?php echo $row['station_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
										<?php endforeach; ?>
									</table>
								</div>
								<div class='tab-pane fade' id='chartWeek' role='tabpanel'>
									<h4 class="card-title">Afgelopen week</h4>
									<div class='chart'>
										<canvas id="week" width="100%" height="180px"></canvas>
									</div>
									<table class='table'>
										<?php foreach(top5::song($song['song_id'], $song['artist_id'], "week") as $row) : ?>
										<tr><td class='np_cover_img'><img src='<?php echo $row['station_image']; ?>'/></td><td><a href='<?php echo $row['station_url']; ?>'><?php echo $row['station_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
										<?php endforeach; ?>
									</table>
								</div>
								<div class='tab-pane fade' id='chartMonth' role='tabpanel'>
									<h4 class="card-title">Afgelopen maand</h4>
									<div class='chart'>
										<canvas id="month" width="100%" height="180px"></canvas>
									</div>
									<table class='table'>
										<?php foreach(top5::song($song['song_id'], $song['artist_id'], "month") as $row) : ?>
										<tr><td class='np_cover_img'><img src='<?php echo $row['station_image']; ?>'/></td><td><a href='<?php echo $row['station_url']; ?>'><?php echo $row['station_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
										<?php endforeach; ?>
									</table>
								</div>
								<div class='tab-pane fade' id='chartYear' role='tabpanel'>
									<h4 class="card-title">Afgelopen jaar</h4>
									<div class='chart'>
										<canvas id="year" width="100%" height="180px"></canvas>
									</div>
									<table class='table'>
										<?php foreach(top5::song($song['song_id'], $song['artist_id'], "year") as $row) : ?>
										<tr><td class='np_cover_img'><img src='<?php echo $row['station_image']; ?>'/></td><td><a href='<?php echo $row['station_url']; ?>'><?php echo $row['station_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
										<?php endforeach; ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='col-md-4'>
					<div class="card side-card">
						<div class="card-img-top" style='background-image: url("<?php echo song::getInfo($song['song_id'], "image"); ?>")'></div>
						<div class="card-block">
							<h4 class="card-title"><?php echo $song['song']; ?></h4>
							<?php
							if(file_exists(dirname(__FILE__).'/../lib/wikidrain/api.php')) {
								require_once(dirname(__FILE__).'/../lib/wikidrain/api.php');
								$wiki = new wikidrain('wikidrain/1.0 (http://radiovergelijker.nl/)', 'nl');
								$result = $wiki->Search(urlencode($song['artist'] . " - " . $song['song']), 10);
								$data = json_decode($result, true);
								if(array_key_exists(0, $data)) {
									if (strpos($data[0]['description'], 'kan verwijzen naar:') !== false) {
										if(array_key_exists(1, $data)) {
											echo "<p>".$data[1]['description']."</p>";
										}
									}
									else {
										echo "<p>".$data[0]['description']."</p>";
									}
								}
								else {
									echo "<p>Voor deze artiest is geen beschrijving beschikbaar.</p>";
								}
								
							}
							else {
								die("Wikidrain niet beschikbaar!");
							}
							$station = artist::getInfo($_GET['id'], "station");
						?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>