<div class='container'>
	<div class='row'>
		<div class='col-md-8'>
			<div class="card text-xs-center">
				<div class="card-header">
					<ul class="nav nav-tabs card-header-tabs float-xs-left" role='tablist'>
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#statsday" role="tab">Dag</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle='tab' href="#statsweek" role='tab'>Week</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle='tab' href="#statsmonth" role='tab'>Maand</a>
						</li>
						<li class="nav-item">
							<a class='nav-link' data-toggle='tab' href='#statsyear' role='tab'>Jaar</a>
						</li>
					</ul>
				</div>
				<div class="card-block">
					<div class='tab-content'>
						<div class='tab-pane fade active in' id='statsday' role='tabpanel'>
							<h2 class="card-title title-important">Afgelopen 24 uur</h2>
							<div class='chart'>
								<canvas id="day" width="100%" height="180px"></canvas>
							</div>
							<?php $stats = station::repeatPerc($_GET['id'], "day"); ?>
							<div class='card-group card-radio-station'>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['total'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal nummers</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['repeated'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal herhalingen</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['percentage'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Herhaalpercentage</small></p>
									</div>
								</div>
							</div>
							<h3 class='card-title title-important'>Populaire nummers</h3>
							<table class='table'>
								<?php foreach(top5::station($_GET['id'], "day") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>
								<?php endforeach; ?>
							</table>
							<h3 class='card-title title-important'>Populaire artiesten</h3>
							<table class='table'>
								<?php foreach(top5::stationArtists($_GET['id'], "day") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						
								<?php endforeach; ?>
							</table>
						</div>
						<div class='tab-pane fade' id='statsweek' role='tabpanel'>
							<h2 class="card-title title-important">Afgelopen week</h2>
							<div class='chart'>
								<canvas id="week" width="100%" height="180px"></canvas>
							</div>
							<?php $stats = station::repeatPerc($_GET['id'], "week"); ?>
							<div class='card-group card-radio-station'>
								<div class='card'>
									<div class='card-block '>
										<h2 class='card-title'><?php echo $stats['total'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal nummers</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['repeated'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal herhalingen</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['percentage'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Herhaalpercentage</small></p>
									</div>
								</div>
							</div>
							<h3 class='card-title title-important'>Populaire nummers</h3>
							<table class='table'>
								<?php foreach(top5::station($_GET['id'], "week") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
							<h3 class='card-title title-important'>Populaire artiesten</h3>
							<table class='table'>
								<?php foreach(top5::stationArtists($_GET['id'], "week") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
						</div>
						<div class='tab-pane fade' id='statsmonth' role='tabpanel'>
							<h2 class="card-title title-important">Afgelopen maand</h2>
							<div class='chart'>
								<canvas id="month" width="100%" height="180px"></canvas>
							</div>
							<?php $stats = station::repeatPerc($_GET['id'], "month"); ?>
							<div class='card-group card-radio-station'>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['total'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal nummers</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['repeated'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal herhalingen</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['percentage'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Herhaalpercentage</small></p>
									</div>
								</div>
							</div>
							<h3 class='card-title title-important'>Populaire nummers</h3>
							<table class='table'>
								<?php foreach(top5::station($_GET['id'], "month") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
							<h3 class='card-title title-important'>Populaire artiesten</h3>
							<table class='table'>
								<?php foreach(top5::stationArtists($_GET['id'], "month") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
						</div>
						<div class='tab-pane fade' id='statsyear' role='tabpanel'>
							<h2 class="card-title title-important">Afgelopen jaar</h2>
							<div class='chart'>
								<canvas id="year" width="100%" height="180px"></canvas>
							</div>
							<?php $stats = station::repeatPerc($_GET['id'], "year"); ?>
							<div class='card-group card-radio-station'>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['total'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal nummers</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['repeated'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Totaal aantal herhalingen</small></p>
									</div>
								</div>
								<div class='card'>
									<div class='card-block'>
										<h2 class='card-title'><?php echo $stats['percentage'];?></h2>
									</div>
									<div class='card-footer'>
										<p class='card-text'><small class='text-muted'>Herhaalpercentage</small></p>
									</div>
								</div>
							</div>
							<h3 class='card-title title-important'>Populaire nummers</h3>
							<table class='table'>
								<?php foreach(top5::station($_GET['id'], "year") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a> - <a href='<?php echo $row['song_url']; ?>'><?php echo $row['song_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
							<h3 class='card-title title-important'>Populaire artiesten</h3>
							<table class='table'>
								<?php foreach(top5::stationArtists($_GET['id'], "year") as $row) : ?>
								<tr><td class='np_cover_img'><img src='<?php echo $row['image']; ?>' /></td><td><a href='<?php echo $row['artist_url']; ?>'><?php echo $row['artist_name']; ?></a></td><td><?php echo $row['count']; ?>X</td></tr>						<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<div class='card side-card'>
				<div class="card-img-station" style='background-image: url("<?php echo station::getInfo($_GET['id'], "image"); ?>");'></div>
				<div class='card-block'>
				
					<h3 class="card-title"><?php echo station::getInfo($_GET['id'], "name"); ?></h3>
					<?php
						if(file_exists(dirname(__FILE__).'/../lib/wikidrain/api.php')) {
							require_once(dirname(__FILE__).'/../lib/wikidrain/api.php');
							$wiki = new wikidrain('wikidrain/1.0 (http://radiovergelijker.nl/)', 'nl');
							$result = $wiki->Search(urlencode(station::getInfo($_GET['id'], "name")), 10);
							$data = json_decode($result, true);
							echo $data[station::getInfo($_GET['id'], "wiki_id")]['description'];
						}
						else {
							die("Wikidrain niet beschikbaar!");
						}
					?>
					<?php $genre = station::getInfo($_GET['id'], "genre"); ?>
				</div>
				<footer class='card-footer'>
					<div class='card-block'>
						<h4>Dit station draait voornamelijk</h4><h5><a href='<?php echo $genre['url']; ?>'><?php echo $genre['name']; ?></a></h5>
						<?php $now = station::now($_GET['id']); ?>
					</div>
					<div class='card-block'>
						<?php if($now['status'] == "Playing") : ?>
							<h5>Nu op dit station</h5>
							<?php echo "<a href='".$now['song_url']."'><h5>".$now['song']."</h5></a>";
							echo "<a href='".$now['artist_url']."'><h6>".$now['artist']."</h6></a>";
							endif;
						?>
						
					</div>
				</footer>
			</div>
			
			<div class='card side-card'>
				<h3 class='card-title'>Playlist van het afgelopen uur</h3>
				<ul class='list-group'>
				<?php
					$db = db::getInstance();
					$q = $db->prepare("SELECT * FROM `radio_data` WHERE `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW() AND `station_id` = :id ORDER BY `played` DESC");
					$q->execute(array("id" => station::getInfo($_GET['id'], "id")));
					foreach($q->fetchAll() as $row) {
						$q = $db->prepare("SELECT * FROM `radio_artists` WHERE `id` = :id");
						$q->execute(array("id" => $row['artist_id']));
						$row_artist = $q->fetch();
						$q = $db->prepare("SELECT * FROM `radio_songs` WHERE `id` = :id");
						$q->execute(array("id" => $row['song_id']));
						$row_song = $q->fetch();
						echo "<li class='list-group-item'>";
						echo "<div class='row'>";
						echo "<div class='col-md-4'>";
						if(!empty($row_song['image'])) {
							echo "<img src='".$row_song['image']."' class='pull-left last-played-img' />";
						}
						elseif(!empty($row_artist['image'])) {
							echo "<img src='".$row_artist['image']."' class='pull-left last-played-img' />";
						}
						else {
							echo "<img src='".BASE_URL . "/images/nocover.png' class='pull-left last-played-img' />";
						}
						echo "</div><div class='col-md-8'>";
						echo "<div class='text-last-played text-xs-right'><a href='".BASE_URL . "/nummer/" . $row['song_id'] . ".html'><h5 class='card-title last-played-song'>".$row_song['name']."</h5></a>";
						echo "<a href='".BASE_URL . "/artiest/" . $row['artist_id'] . ".html'>".$row_artist['name']."</a><br />";
						echo "<small>".date("H:i", strtotime($row['played']))."</small></div></div></div>";
						echo "</li>";
					}
				?>
				</ul>
			</div>
		</div>
	</div>
</div>