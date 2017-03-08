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
if(file_exists(DIRNAME(__FILE__)."/Gracenote.class.php")) {
	require_once(DIRNAME(__FILE__).'/Gracenote.class.php');
}
else {
	die("Gracenote class niet gevonden!");
}

$api = new Gracenote\WebAPI\GracenoteWebAPI(GRACENOTE_CLIENT_ID, GRACENOTE_CLIENT_TAG);
$userID = $api->register();

$i = 0;
$db = db::getInstance();
$q = $db->query("SELECT * FROM `radio_stations`");
foreach($q->fetchAll() as $row) {
	$artist = false;
	$song = false;
	$artist_origin = null;
	$genre_id = 1;
	$ignore = false;
	$addToDb = true;
	$skip_gracenote = false;
	$album = '';
	if($row['data_type'] == "xml/cdata") {
		$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$content = file_get_contents($row['url'], false, $context);
		$xml = simplexml_load_string($content);
		$song = strval($xml->cuepoint->attributes->attribute[0]);
		$artist = strval($xml->cuepoint->attributes->attribute[4]);
	}
	if($row['provider'] == "npojson") {
		$json = file_get_contents($row['url']);
		if($json) {
			$data = json_decode($json);
			$artist = $data->results[0]->songfile->artist;
			$song = $data->results[0]->songfile->title;
		}
	}
	if($row['provider'] == "muziekopjewerk") {
		$content = file_get_contents($row['url']);
		$expl = explode(" - ", $content);
		if(array_key_exists(1, $expl)) {
			$song = $expl[1];
		}
		if(array_key_exists(0, $expl)) {
			$str = preg_replace('/[\n\r]+/', '', $expl[0]);
			$artist = $str;
		}
	}
	if($row['provider'] == "muziekopjewerk2") {
		$nowplaying = file_get_contents($row['url']);
		if($nowplaying) {
			$expl = explode("<br>", $nowplaying);
			if(array_key_exists(1, $expl)) {
				$str = $expl[1];
				$str = strip_tags($str);
				$str = substr($str, 0, strpos($str, "NEXT:"));
				
				$str = ucwords(strtolower($str));
				$song = $str;
			}
			else {
				$ignore = true;
			}
			if(array_key_exists(0, $expl)) {
				$expl2 = explode("<b>", $expl[0]);
				$str = ucwords(strtolower($expl2[1]));
				$str = strip_tags($str);
				$artist = $str;
			}
			else {
				$ignore = true;
			}
		}
		else {
			$ignore = true;
		}
	}
	$q = $db->prepare("SELECT * FROM `radio_ignore` WHERE `station_id` = :id");
	$q->execute(array("id" => $row['id']));
	foreach($q->fetchAll() as $row_ignore) {
		if($row_ignore['column'] == "song") {
			if($song == $row_ignore['word']) {
				$ignore = true;
			}
		}
		if($row_ignore['column'] == "artist") {
			if($artist == $row_ignore['word']) {
				$ignore = true;
			}
		}
	}
	$q = $db->prepare("SELECT * FROM radio_artists WHERE name = :name");
	$q->execute(array("name" => $artist));
	if($q->rowCount() == 1) {
		$r_artist = $q->fetch();
		$q = $db->prepare("SELECT * FROM `radio_songs` WHERE `name` = :name AND `artist_id` = :id");
		$q->execute(array("name" => $song, "id" => $r_artist['id']));
		if($q->rowCount() == 1) {
			$skip_gracenote = true;
		}
	}
	echo transform::textMarkup($artist) . " - " . transform::textMarkup($song) . "<br />";
	$q = $db->prepare("SELECT * FROM `radio_current` WHERE `station_id` = :station AND `artist` = :artist AND `song` = :song");
	$q->execute(array("station" => $row['id'], "artist" => transform::textMarkup($artist), "song" => transform::textMarkup($song)));
	if($q->rowCount() == 1) {
		$ignore = true;
	}
	else {
		$q = $db->prepare("UPDATE `radio_current` SET `artist` = :artist, `song` = :song WHERE `station_id` = :id LIMIT 1");
		$q->execute(array("artist" => transform::textMarkup($artist), "song" => transform::textMarkup($song), "id" => $row['id']));
	}
	if($ignore == false) {
		$artist = transform::textMarkup($artist);
		$song = transform::textMarkup($song);
		if($artist) {
			if($song) {
				if($skip_gracenote == false) {
					$artist = str_replace("&", "and", $artist);
					$song = str_replace("&", "and", $song);
					$results = $api->searchTrack($artist, "", $song);
					$artist = $results[0]['tracks'][0]['track_artist_name'];
					$song = $results[0]['tracks'][0]['track_title'];
					if(array_key_exists(0, $results[0]['genre'])) {
						$genre = $results[0]['genre'][0]['text'];
					}
					else {
						$genre = null;
					}
					if(!empty($results[0]['artist_image_url'])) {
						$artist_image = $results[0]['artist_image_url'];
					}
					else {
						$artist_image = null;
					}
					if(!empty($results[0]['artist_image_url'])) {
						$album_image = $artist_image = $results[0]['artist_image_url'];
					}
					else {
						$album_image = null;
					}
					if(!empty($results[0]['album_title'])) {
						$album_title = $results[0]['album_title'];
					}
					else {
						$album_title = null;
					}
					if(array_key_exists('artist_origin', $results[0])) {
						if(!empty($results[0]['artist_origin'])) {
							if(array_key_exists(0, $results[0]['artist_origin'])) {
								$artist_origin = $results[0]['artist_origin'][0]['text'];
							}
						}	
					}
				}
				$q = $db->prepare("SELECT * FROM radio_artists WHERE name = :name");
				$q->execute(array("name" => $artist));
				if($q->rowCount() == 0) {
					$q = $db->prepare("INSERT INTO `radio_artists` (`name`, `image`) VALUES (:name, :img)");
					if($q->execute(array("name" => $artist, "img" => $artist_image))) {
						$q = $db->prepare("SELECT * FROM radio_artists WHERE `name` = :name");
						$q->execute(array("name" => $artist));
						if($q->rowCount() == 1) {
							$row_artist = $q->fetch();
						}
					}
				}
				else {
					$row_artist = $q->fetch();
				}
				$q = $db->prepare("SELECT * FROM `radio_songs` WHERE `name` = :name AND `artist_id` = :id");
				$q->execute(array("name" => $song, "id" => $row_artist['id']));
				if($q->rowCount() == 1) {
					$row_song = $q->fetch();
				}
				else {
					if(!empty($genre)) {
						$q = $db->prepare("SELECT * FROM `radio_genres` WHERE name = :name");
						$q->execute(array("name" => $genre));
						if($q->rowCount() == 1) {
							$row_genre = $q->fetch();
						}
						else {
							$q = $db->prepare("INSERT INTO `radio_genres` (`name`) VALUES (:name)");
							if($q->execute(array("name" => $genre))) {
								$q = $db->prepare("SELECT * FROM `radio_genres` WHERE name = :name");
								$q->execute(array("name" => $genre));
								$row_genre = $q->fetch();
							}
						}
						$genre_id = $row_genre['id'];
					}
					
					$q = $db->prepare("INSERT INTO `radio_songs` (`name`, `artist_id`, `genre_id`, `album`, `album_image`) VALUES (:name, :id, :genre, :album, :album_img)");
					if($q->execute(array("name" => $song, "id" => $row_artist['id'], "genre" => $genre_id, "album" => $album, "album_img" => $album_image))) {
						$q = $db->prepare("SELECT * FROM `radio_songs` WHERE name = :name AND artist_id = :id");
						$q->execute(array("name" => $song, "id" => $row_artist['id']));
						if($q->rowCount() == 1) {
							$row_song = $q->fetch();
						}
					}
				}
			}
			else {
				$addToDb = false;
			}
		}
		else {
			$addToDb = false;
		}
		if(isset($row_artist) && isset($row_song)) {
			$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :st_id ORDER BY id DESC LIMIT 1");
			$q->execute(array("st_id" => $row['id']));
			$last_song = $q->fetch();
			if($last_song['artist_id'] == $row_artist['id']) {
				if($last_song['song_id'] == $row_song['id']) {
					$addToDb = false;
				}
			}
			if($addToDb) {
				$q = $db->prepare("INSERT INTO `radio_data` (`artist_id`, `song_id`, `station_id`, `genre_id`, `played`) VALUES (:a_id, :s_id, :st_id, :g_id, NOW())");
				if($q->execute(array("a_id" => $row_artist['id'], "s_id" => $row_song['id'], "st_id" => $row['id'], "g_id" => $genre_id))) {
					$error = false;
				}
				$i++;
			}
		}
		else {
			$error = true;
		}
	}
}
echo $i . " nummer(s) toegevoegd!";
?>