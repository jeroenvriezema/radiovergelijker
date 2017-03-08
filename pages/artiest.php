<div class='container'>
<?php if(isset($_GET['id'])) : ?>
	<?php if(artist::getInfo($_GET['id'], "exists")) : ?>
		<div class='row'>
			<div class='col-md-8'>
				<div class="card text-xs-center">
					<div class="card-header">
						<div class='tab-pane fade active in' id='chartDay' role='tabpanel'>
							<ul class="nav nav-tabs card-header-tabs float-xs-left" role='tablist'>
								<li class="nav-item">
									<a class="nav-link active" href="#daytab" data-toggle='tab' role='tab'>Vandaag</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#weektab" role="tab">Week</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#monthtab" role="tab">Maand</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#yeartab" role="tab">Jaar</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="card-block">
						<div class="tab-content">
							<div class="tab-pane fade active in" id="daytab" role="tabpanel">
								<h3 class='card-title'>Vandaag</h3>
								<div class='chart'>
									<canvas id="day" width="100%" height="180px"></canvas>
								</div>
								<table class='table'>
									<?php foreach(top5::artist($_GET['id'], "day") as $row) : ?>
									<tr><td><img class='np_cover_img' src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?> X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class="tab-pane fade" id="weektab" role="tabpanel">
								<h3 class='card-title'>Deze week</h3>
								<div class='chart'>
									<canvas id="week" width="100%" height="180px"></canvas>
								</div>
								<table class='table'>
									<?php foreach(top5::artist($_GET['id'], "week") as $row) : ?>
									<tr><td><img class='np_cover_img' src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?> X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class="tab-pane fade" id="monthtab" role="tabpanel">
								<h3 class='card-title'>Deze maand</h3>
								<div class='chart'>
									<canvas id="month" width="100%" height="180px"></canvas>
								</div>
								<table class='table'>
									<?php foreach(top5::artist($_GET['id'], "month") as $row) : ?>
									<tr><td><img class='np_cover_img' src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?> X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class="tab-pane fade" id="yeartab" role="tabpanel">
								<h3 class='card-title'>Dit jaar</h3>
								<div class='chart'>
									<canvas id="year" width="100%" height="180px"></canvas>
								</div>
								<table class='table'>
									<?php foreach(top5::artist($_GET['id'], "year") as $row) : ?>
									<tr><td><img class='np_cover_img' src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?> X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='col-md-4'>
				<div class="card side-card">
					<div class="card-img-top" style='background-image: url("<?php echo artist::getInfo($_GET['id'], "image"); ?>")'></div>
						<div class='card-block'>
							<h4 class='card-title'><?php echo artist::getInfo($_GET['id'], "name"); ?></h4>
							<?php
							if(file_exists(dirname(__FILE__).'/../lib/wikidrain/api.php')) {
								require_once(dirname(__FILE__).'/../lib/wikidrain/api.php');
								$wiki = new wikidrain('wikidrain/1.0 (http://radiovergelijker.nl/)', 'nl');
								$result = $wiki->Search(urlencode(artist::getInfo($_GET['id'], "name")), 10);
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
						<h5 class='card-title'>Deze artiest is populair op <br /><a href='<?php echo $station['url']; ?>'><?php echo $station['name']; ?></a></h5>
						</div>
					</div>
				</div>
		</div>
	<?php else : ?>
		<div class='row'>
			<div class='col-md-12'>
				<H1>Fout</H1>
				<p>Deze artiest komt niet voor in onze database!</p>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
</div>