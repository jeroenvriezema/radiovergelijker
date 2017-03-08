<?php
if(file_exists(DIRNAME(__FILE__)."/config.php")) {
	require_once(DIRNAME(__FILE__)."/config.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}
if(file_exists(DIRNAME(__FILE__)."/db.php")) {
	require_once(DIRNAME(__FILE__)."/db.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}
if(file_exists(DIRNAME(__FILE__)."/functions.php")) {
	require_once(DIRNAME(__FILE__)."/functions.php");
}
else {
	die("Veresite bestanden niet gevonden!");
}
$db = db::getInstance();
$q = $db->query("SELECT * FROM `radio_songs` WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) >= `last_updated` LIMIT 50");
if($q->rowCount() > 0) {
	foreach($q->fetchAll() as $row) {
		$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
		if($q->execute(array("id" => $row['artist_id']))) {
			$row_artist = $q->fetch();
			$song = $row['name'];
			$artist = $row_artist['name'];
			$artist_id = $row_artist['id'];
			$song_id = $row['id'];
			echo $artist . " - " . $song;
			$url = 'http://ws.audioscrobbler.com/2.0/?method=track.gettoptags&artist='.urlencode($artist).'&track='.urlencode($song).'&api_key=' . LASTFM_API_KEY . '&format=json';
			$file = file_get_contents($url);
			$array = json_decode($file, true);

			if(array_key_exists('toptags', $array)) {
				if(array_key_exists('tag', $array['toptags'])) {
					if(array_key_exists(0, $array['toptags']['tag'])) {
						if(array_key_exists('name', $array['toptags']['tag'][0])) {
							$tag = $array['toptags']['tag'][0]['name'];
						}
						else {
							$tag = false;
						}
					}
					else {
						$tag = false;
					}
				}
				else {
					$tag = false;
				}
			}
			else {
				$tag = false;
			}
			if($tag) {
				echo "<pre>";
				print_r($tag);
				echo "</pre>";
				$ren = array('00s', '10s', '20s', '30s', '40s', '50s', '60s', '70s', '80s', '90s');
				if(in_array($tag, $ren)) {
					$tag = 'pop';
				}
				$q = $db->prepare("SELECT * FROM `radio_genres` WHERE `name` = :name");
				if($q->execute(array("name" => $tag))) {
					if($q->rowCount() == 1) {
						$row_genre = $q->fetch();
						if($row['genre_id'] != $row_genre['id']) {
							$q = $db->prepare("UPDATE `radio_genres` SET `name` = :name WHERE id = :id LIMIT 1");
							if($q->execute(array("name" => $tag, "id" => $row_genre['id']))) {
								$q = $db->prepare("UPDATE `radio_data` SET `genre_id` = :id WHERE `artist_id` = :a_id AND `song_id` = :s_id");
								if($q->execute(array("id" => $row_genre['id'], "a_id" => $artist_id, "s_id" => $song_id))) {
									
									echo "Alles bijgewerkt!";
								}
							}
						}
						
					}
				}
			}
		}
	}
}