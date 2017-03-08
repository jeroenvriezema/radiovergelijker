<div class='row'>
	<div class='col-md-8'>
		<h1 class='section-title'><?php echo special::getInfo($_GET['id'], "radioName"); ?>: <?php echo special::getInfo($_GET['id'], "name"); ?></h1>
		
	</div>
	<div class='col-md-4'>
		<div class="card">
			<div class="card-img-top" style='background-image: url("<?php echo artist::getInfo($_GET['id'], "image"); ?>")'>
			</div>
			<div class='card-block'>
				<h4 class='card-title'><?php echo special::getInfo($_GET['id'], "name"); ?></h4>
				<?php
				if(file_exists(dirname(__FILE__).'/../lib/wikidrain/api.php')) {
					require_once(dirname(__FILE__).'/../lib/wikidrain/api.php');
					$wiki = new wikidrain('wikidrain/1.0 (http://radiovergelijker.nl/)', 'nl');
					$result = $wiki->Search(urlencode(special::getInfo($_GET['id'], "name")), 10);
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