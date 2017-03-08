<div class='container-fluid top5-container hidden-xs-down color-container'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12 top5-now'>
				<h1 class='section-title'>Top 5 deze week</h1>
				<?php $cards = top5::allStations("week", "songs"); ?>
				<div class='card-group'>
					<?php foreach($cards as $row) : ?>
					<div class='card'>
						<a href='<?php echo $row['song_url']; ?>'><img class='card-img-top' src='<?php echo $row['image']; ?>' /></a>
						<div class='card-block'>
							<a href='<?php echo $row['song_url']; ?>'><h4 class='card-title'><?php echo $row['song_name']; ?></h4></a>
							<a href='<?php echo $row['artist_url']; ?>'><p class='card-text'><?php echo $row['artist_name']; ?></p></a>
						</div>
						<footer class='card-footer'>
							<p class='card-text'><small class='text-muted'><?php echo $row['count']; ?>x gedraaid</small></p>
						</footer>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class='container-fluid now-radio-container color-container'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12'>
				<h1 class='section-title'>Nu op de radio</h1>
				<table class='table' id='nowPlaying'>
					<?php
					foreach(station::allNow() as $row) {
						if(isset($row['event'])) {
							echo "<tr class='special-event'>";
								if($row['status'] == "Paused") {
								echo "<td class='np_cover_img'><img src='".$row['image']."' /></td><td>Er wordt nu geen muziek gedraaid.</td><td><a href='" . $row['station_url'] . "'>" . $row['station'] . "</a><span class='event'><a class='event-title' href='". $row['event_url'] . "'><span class='event-title'>".$row['event']."</span></a></span></td></tr>";
							}
							else {
								echo "<td class='np_cover_img'><img src='".$row['image']."' /></td><td><a href='" . $row['artist_url'] . "'>" . $row['artist'] . "</a> - <a href='" . $row['song_url'] . "'>" . $row['song'] . "</a></td><td><a href='" . $row['station_url'] . "'>" . $row['station'] . "</a><span class='event'><a class='event-title' href='". $row['event_url'] . "'>".$row['event']."</a></span></td></tr>";
							}
						}
						else {
							echo "<tr>";
							if($row['status'] == "Paused") {
								echo "<td class='np_cover_img'><img src='".$row['image']."' /></td><td>Er wordt nu geen muziek gedraaid.</td><td><a href='" . $row['station_url'] . "'>" . $row['station'] . "</a></td></tr>";
							}
							else {
								echo "<td class='np_cover_img'><img src='".$row['image']."' /></td><td><a href='" . $row['artist_url'] . "'>" . $row['artist'] . "</a> - <a href='" . $row['song_url'] . "'>" . $row['song'] . "</a></td><td><a href='" . $row['station_url'] . "'>" . $row['station'] . "</a></td></tr>";
							}
						}
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<div class='container-fluid top5-radio-container color-container'>
	<div class='container'>
		<div class='row'>
			<div class='col-md-6'>
				<h1 class='section-title'>Top 5 Nummers</h1>
				<div class="card text-xs-center">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs float-xs-left" role='tablist'>
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#day" role="tab">Dag</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle='tab' href="#week" role='tab'>Week</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle='tab' href="#month" role='tab'>Maand</a>
							</li>
							<li class="nav-item">
								<a class='nav-link' data-toggle='tab' href='#year' role='tab'>Jaar</a>
							</li>
							<li class="nav-item">
								<a class='nav-link' data-toggle='tab' href='#all' role='tab'>Alltertijden</a>
							</li>
						</ul>
					</div>
					<div class="card-block">
						<div class='tab-content'>
							<div class='tab-pane fade active in' id='day' role='tabpanel'>
								<h4 class="card-title">Afgelopen 24 uur</h4>
								<table class='table'>
									<?php foreach(top5::allStations("day") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='week' role='tabpanel'>
								<h4 class="card-title">Afgelopen week</h4>
								<table class='table'>
									<?php foreach(top5::allStations("week") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='month' role='tabpanel'>
								<h4 class="card-title">Afgelopen maand</h4>
								<table class='table'>
									<?php foreach(top5::allStations("month") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='year' role='tabpanel'>
								<h4 class="card-title">Afgelopen jaar</h4>
								<table class='table'>
									<?php foreach(top5::allStations("year") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='all' role='tabpanel'>
								<h4 class="card-title">Allertijden</h4>
								<table class='table'>
									<?php foreach(top5::allStations("all") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='col-md-6'>
				<h1 class='section-title'>Top 5 Artiesten</h1>
				<div class="card text-xs-center">
					<div class="card-header">
						<ul class="nav nav-tabs card-header-tabs float-xs-left" role='tablist'>
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#dayArt" role="tab">Dag</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle='tab' href="#weekArt" role='tab'>Week</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle='tab' href="#monthArt" role='tab'>Maand</a>
							</li>
							<li class="nav-item">
								<a class='nav-link' data-toggle='tab' href='#yearArt' role='tab'>Jaar</a>
							</li>
							<li class="nav-item">
								<a class='nav-link' data-toggle='tab' href='#allArt' role='tab'>Alltertijden</a>
							</li>
						</ul>
					</div>
					<div class="card-block">
						<div class='tab-content'>
							<div class='tab-pane fade active in' id='dayArt' role='tabpanel'>
								<h4 class="card-title">Afgelopen 24 uur</h4>
								<table class='table'>
									<?php foreach(top5::allStations("day", "artists") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='weekArt' role='tabpanel'>
								<h4 class="card-title">Afgelopen week</h4>
								<table class='table'>
									<?php foreach(top5::allStations("week", "artists") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='monthArt' role='tabpanel'>
								<h4 class="card-title">Afgelopen maand</h4>
								<table class='table'>
									<?php foreach(top5::allStations("month", "artists") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='yearArt' role='tabpanel'>
								<h4 class="card-title">Afgelopen jaar</h4>
								<table class='table'>
									<?php foreach(top5::allStations("year", "artists") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
							<div class='tab-pane fade' id='allArt' role='tabpanel'>
								<h4 class="card-title">Allertijden</h4>
								<table class='table'>
									<?php foreach(top5::allStations("all", "artists") as $row) : ?>
									<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
									<?php endforeach; ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>