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
$q = $db->query("SELECT * FROM `radio_songs` WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) >= `last_updated` OR `last_updated` IS NULL LIMIT 50");
$i = 0;
foreach($q->fetchAll() as $row) {
	echo "<pre>";
	print_r($row);
	echo "</pre>";
	$update = [];
	$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
	$q->execute(array("id" => $row['artist_id']));
	$row_artist = $q->fetch();
	echo "<pre>";
	print_r($row_artist);
	echo  "</pre>";
	$update['album'] = null;
	$update['album_image'] = null;
	$update['mb_id_song'] = null;
	$update['mb_id_artist'] = null;
	$update['artist_image'] = null;
	
	if(!empty($row['album'])) {
		$update['album'] = $row['album'];
	}
	if(!empty($row['album_image'])) {
		$update['album_image'] = $row['album_image'];
	}
	if(!empty($row['mb_id'])) {
		$update['mb_id_song'] = $row['mb_id'];
	}
	if(!empty($row_artist['mb_id'])) {
		$update['mb_id_artist'] = $row_artist['mb_id'];
	}
	if(!empty($row_artist['image'])) {
		$update['artist_image'] = $row_artist['image'];
	}
	echo "<pre>";
	print_r($update);
	echo  "</pre>";
	$call = file_get_contents('http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=' . LASTFM_API_KEY . '&artist=' . urlencode($row_artist['name']) . '&track=' . urlencode($row['name']) . '&format=json&autocorrect=1');
	$json = json_decode($call);
	$album = $json->track->album->title;
	if(property_exists($json->track->album, 'image')) {
		foreach($json->track->album->image as $lfm_alb_img) {
			$info = (array) $lfm_alb_img;
			if($info['size'] == "extralarge") {
				$album_image = $info['#text'];
			}
		}
	}
	$mb_id_song = $json->track->mbid;
	$mb_id_artist = $json->track->artist->mbid;
	
	$call = file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist='.urlencode($row_artist['name']).'&api_key='.LASTFM_API_KEY.'&format=json&autocorrect=1');
	$json = json_decode($call);
	if(property_exists($json->artist, 'image')) {
		foreach($json->artist->image as $lfm_alb_img) {
			$info = (array) $lfm_alb_img;
			if($info['size'] == "extralarge") {
				$artist_image = $info['#text'];
			}
		}
	}
	$update['album'] = ($update['album'] === $album ? $update['album'] : $album);
	$update['album_image'] = ($update['album_image'] === $album_image ? $update['album_image'] : $album_image);
	$update['mb_id_song'] = ($update['mb_id_song'] === $mb_id_song ? $update['mb_id_song'] : $mb_id_song);
	$update['mb_id_artist'] = ($update['mb_id_artist'] === $mb_id_artist ? $update['mb_id_artist'] : $mb_id_artist);
	$update['artist_image'] = ($update['artist_image'] === $artist_image ? $update['artist_image'] : $artist_image);
	echo "<pre>";
	print_r($update);
	echo "</pre>";
	$q = $db->prepare("UPDATE `radio_artists` SET `image` = :image, `last_updated` = NOW() WHERE id = :id LIMIT 1");
	if($q->execute(array("image" => $update['artist_image'], "id" => $row['artist_id']))) {
		$q = $db->prepare("UPDATE `radio_songs` SET `album` = :album, `album_image` = :album_image, `mb_id` = :mb_id, `last_updated` = NOW() WHERE id = :id LIMIT 1");
		if($q->execute(array("album" => $update['album'], "album_image" => $update['album_image'], "mb_id" => $update['mb_id_song'], "id" => $row['id']))) {
			$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
			if($q->execute(array("id" => $row['id']))) {
				if($q->rowCount() == 1) {
					$row_song = $q->fetch();
					$q = $db->prepare("UPDATE `radio_data` SET `genre_id` = :id WHERE `song_id` = :song_id");
					if($q->execute(array("id" => $row_song['genre_id'], "song_id" => $row_song['id']))) {
						$passed = true;
					}
				}
			}
		}
		else {
			$passed = false;
		}
	}
	else {
		$passed = false;
	}
	if($passed) {
		$i++;
	}
}
echo $i . " van de 50 geupdate";

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
?>