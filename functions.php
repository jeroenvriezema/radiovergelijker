<?php
	if(file_exists(dirname(__FILE__).'/config.php')) {
		require_once(dirname(__FILE__).'/config.php');
	}
	else {
		die("Config.php niet gevonden!");
	}
	if(file_exists(dirname(__FILE__).'/db.php')) {
		require_once(dirname(__FILE__).'/db.php');
	}
	else {
		die("Db.php niet gevonden!");
	}
	
	class general {
		public function __clone() {}		
		public static function timeIsBetween($from, $till, $input) {
			$fromTime = strtotime($from);
			$toTime = strtotime($till);
			$inputTime = strtotime($input);
			
			return($inputTime >= $fromTime and $inputTime <= $toTime);
		}
	}
	
	class top5 {
		public function __clone() {}				//Data of top 5 genres
		public static function genres($id, $period, $type) {
			$db = db::getInstance();			//Prepare to fetch data from MySQL
			$q = $db->prepare("SELECT * FROM `radio_genres` WHERE name = :name");
			if($q->execute(array("name" => $id))) {
				$row = $q->fetch();
				//Bind Database GENRE ID to string				$db_id = $row['id'];				//Determine the data that need to be send back
				if($type == "artists") {					//Determine the data period to send back
					if($period == "day") {
						$q = $db->prepare("SELECT COUNT(`id`) AS `counter`, `artist_id` FROM `radio_data` WHERE `genre_id` = :g_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
					}
					if($period == "week") {
						$q = $db->prepare("SELECT COUNT(`id`) AS `counter`, `artist_id` FROM `radio_data` WHERE `genre_id` = :g_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
					}
					if($period == "month") {
						$q = $db->prepare("SELECT COUNT(`id`) AS `counter`, `artist_id` FROM `radio_data` WHERE `genre_id` = :g_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
					}
					if($period == "year") {
						$q = $db->prepare("SELECT COUNT(`id`) AS `counter`, `artist_id` FROM `radio_data` WHERE `genre_id` = :g_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
					}
					$i = 0;					//Fetch data with genre ID
					$q->execute(array("g_id" => $db_id));					//If data is returned
					if($q->rowCount() != 0) {
						foreach($q->fetchAll() as $row) {							//Bind found data for genre to artists
							$q = $db->prepare("SELECT * FROM `radio_artists` WHERE `id` = :id");
							if($q->execute(array("id" => $row['artist_id']))) {
								$row_artist = $q->fetch();
								$output[$i]['name'] = $row_artist['name'];
								$output[$i]['url'] = BASE_URL . '/artiest/' . $row_artist['id'] . '.html';
								$output[$i]['count'] = $row['counter'];								//If an album cover has been fetched by the Last.FM api, provide it here.. Else return no cover.
								if(isset($row_artist['image'])) {
									$output[$i]['image'] = $row_artist['image'];
								}
								else {
									$output[$i]['image'] = BASE_URL . '/images/nocover.png';
								}
								$i++;
							}
						}
						return $output;
					}
					else {						//If no data has been sent, return null. This needs rewriting towards an exception handler
						return null;
					}
				}
			}
		}
		public static function allStations($period, $type = "songs") {
			$db = db::getInstance();
			//Define output as array			$output = [];
			if($type == "songs") {
				if($period == "hour") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 3 HOUR GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "day") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 DAY GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "week") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 WEEK GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "month") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 MONTH GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "year") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 YEAR GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "all") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
			}
			if($type == "artists") {
				if($period == "hour") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 3 HOUR GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "day") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 DAY GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "week") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 WEEK GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "month") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 MONTH GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "year") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` WHERE `played` >= NOW() - INTERVAL 1 YEAR GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "all") {
					$q = $db->query("SELECT count(`id`) as `counter`, `artist_id`, `song_id`, `genre_id` FROM `radio_data` GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
			}
			
			$i = 0;
			foreach($q->fetchAll() as $row) {
				$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
				$q->execute(array("id" => $row['artist_id']));
				$row_artist = $q->fetch();
				$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
				$q->execute(array("id" => $row['song_id']));
				$row_song = $q->fetch();
				$q = $db->prepare("SELECT * FROM `radio_genres` WHERE id = :id");
				$q->execute(array("id" => $row['genre_id']));
				$row_genre = $q->fetch();
				$output[$i]['count'] = $row['counter'];
				$output[$i]['song_id'] = $row['song_id'];
				$output[$i]['song_name'] = $row_song['name'];
				$output[$i]['artist_id'] = $row['artist_id'];
				$output[$i]['artist_name'] = $row_artist['name'];
				$output[$i]['artist_image'] = $row_artist['image'];
				$output[$i]['genre_id'] = $row_genre['id'];
				$output[$i]['genre_name'] = $row_genre['name'];
				$output[$i]['album'] = $row_song['album'];
				$output[$i]['album_image'] = $row_song['album_image'];
				if(isset($row_song['album_image'])) {
					$output[$i]['image'] = $row_song['album_image'];
				}
				elseif(isset($row_artist['image'])) {
					$output[$i]['image'] = $row_artist['image'];
				}
				else {
					$output[$i]['image'] = BASE_URL . '/images/nocover.png';
				}
				$output[$i]['artist_url'] = BASE_URL . '/artiest/' . $row['artist_id'] . '.html';
				$output[$i]['song_url'] = BASE_URL . '/nummer/' . $row['song_id'] . '.html';
				$i++;
			}
			return $output;
		}
		
		public static function artist($id, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "all") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id` FROM `radio_data` WHERE `artist_id` = :id GROUP BY `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period) {
				$q->execute(array("id" => $id));
				$i = 0;
				$output = [];
				foreach($q->fetchAll() as $row) {
					$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
					if($q->execute(array("id" => $row['song_id']))) {
						$row_song = $q->fetch();
						$output[$i]['count'] = $row['counter'];
						$output[$i]['song_id'] = $row['song_id'];
						$output[$i]['song_name'] = $row_song['name'];
						$output[$i]['album'] = $row_song['album'];
						$output[$i]['album_image'] = $row_song['album_image'];
						if(!empty($row_song['album_image'])) {
							$output[$i]['image'] = $row_song['album_image'];
						}
						elseif(!empty($row_artist['image'])) {
							$output[$i]['image'] = $row_artist['image'];
						}
						else {
							$output[$i]['image'] = BASE_URL . '/images/nocover.png';
						}
						$output[$i]['song_url'] = BASE_URL . '/nummer/' . $row['song_id'] . '.html';
						$i++;
					}
					else {
						//DEBUGGER
					}
				}
			}
			return $output;
		}
		
		public static function song($song_id, $artist_id, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `artist_id` = :id AND `song_id` = :s_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY `station_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `artist_id` = :id AND `song_id` = :s_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY `station_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `artist_id` = :id AND `song_id` = :s_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY `station_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `artist_id` = :id AND `song_id` = :s_id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY `station_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period == "all") {
				$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `artist_id` = :id AND `song_id` = :s_id GROUP BY `station_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
			}
			if($period) {
				$q->execute(array("id" => $artist_id, "s_id" => $song_id));
				$i = 0;
				$output = [];
				foreach($q->fetchAll() as $row) {
					$q = $db->prepare("SELECT * FROM `radio_stations` WHERE id = :id");
					if($q->execute(array("id" => $row['station_id']))) {
						$row_station = $q->fetch();
						$output[$i]['count'] = $row['counter'];
						$output[$i]['song_id'] = $row['song_id'];
						$output[$i]['station_name'] = $row_station['name'];
						$output[$i]['station_url'] = BASE_URL . '/station/' . $row_station['name_meta'] . '.html';
						$output[$i]['station_image'] = BASE_URL . '/images/stations/' . $row_station['name_meta'] . '.png';
						$i++;
					}
					else {
						//DEBUGGER
					}
				}
			}
			return $output;
		}
		
		public static function station($id, $period) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
			if($q->execute(array("name" => $id))) {
				$station = $q->fetch();
				if($period == "day") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "week") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "month") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "year") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "all") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id GROUP BY `artist_id`, `song_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period) {
					$q->execute(array("id" => $station['id']));
					$i = 0;
					$output = [];
					foreach($q->fetchAll() as $row) {
						$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
						if($q->execute(array("id" => $row['song_id']))) {
							$row_song = $q->fetch();
							$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
							$q->execute(array("id" => $row['artist_id']));
							$row_artist = $q->fetch();
							$output[$i]['count'] = $row['counter'];
							$output[$i]['song_id'] = $row['song_id'];
							$output[$i]['song_name'] = $row_song['name'];
							$output[$i]['artist_name'] = $row_artist['name'];
							$output[$i]['artist_url'] = BASE_URL . '/artiest/' . $row['artist_id'] . '.html';
							$output[$i]['album'] = $row_song['album'];
							$output[$i]['album_image'] = $row_song['album_image'];
							if(!empty($row_song['album_image'])) {
								$output[$i]['image'] = $row_song['album_image'];
							}
							elseif(!empty($row_artist['image'])) {
								$output[$i]['image'] = $row_artist['image'];
							}
							else {
								$output[$i]['image'] = BASE_URL . '/images/nocover.png';
							}
							$output[$i]['song_url'] = BASE_URL . '/nummer/' . $row['song_id'] . '.html';
							$i++;
						}
					}
					return $output;
				}
			}
		}
		
		public static function stationArtists($id, $period) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
			if($q->execute(array("name" => $id))) {
				$station = $q->fetch();
				if($period == "day") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "week") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "month") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "year") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period == "all") {
					$q = $db->prepare("SELECT count(`id`) as `counter`, `song_id`, `station_id`, `artist_id` FROM `radio_data` WHERE `station_id` = :id GROUP BY `artist_id` ORDER BY `counter` DESC, `played` DESC LIMIT 5");
				}
				if($period) {
					$q->execute(array("id" => $station['id']));
					$i = 0;
					$output = [];
					foreach($q->fetchAll() as $row) {
						$row_song = $q->fetch();
						$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
						$q->execute(array("id" => $row['artist_id']));
						$row_artist = $q->fetch();
						$output[$i]['count'] = $row['counter'];
						$output[$i]['artist_name'] = $row_artist['name'];
						$output[$i]['artist_url'] = BASE_URL . '/artiest/' . $row['artist_id'] . '.html';
						if(!empty($row_artist['image'])) {
							$output[$i]['image'] = $row_artist['image'];
						}
						else {
							$output[$i]['image'] = BASE_URL . '/images/nocover.png';
						}
						$output[$i]['song_url'] = BASE_URL . '/nummer/' . $row['song_id'] . '.html';
						$i++;
					}
					return $output;
				}
			}
		}
	}
	
	class genres {
		public function __clone() {}
		
		public static function getInfo($id, $type) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_genres` WHERE name = :name");
			if($q->execute(array("name" => $id))) {
				$row = $q->fetch();
				if($type == "name") {
					return $row['name'];
				}
				if($type == "id") {
					return $row['id'];
				}
			}
		}
		
		public static function chart($id = 1000, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY HOUR(`played`) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY MONTH(`played`) ORDER BY `played`");
			}
			$q->execute(array("id" => self::getInfo($id, "id")));
			$i = 0;
			$data = [];
			foreach($q->fetchAll() as $row) {
				if($period == "day") {
					$data[$i]['date'] = date('d-m-Y H:00', strtotime($row['played']));
				}
				if($period == "week") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "month") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "year") {
					$data[$i]['date'] = date('m-Y', strtotime($row['played']));
				}
				$data[$i]['count'] = $row['counter'];
				$i++;
			}
			$output = [];
			$arr_count = 0;
			if($period == "day") {
				$start = date('d-m-Y H:00', strtotime("-1 day"));
				for($i = 0; $i <= 24; $i++) {
					$output['labels'][$i] = date('H:00', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start) + 60*60;
					$start = date("d-m-Y H:00", $timestamp);
				}
			}
			if($period == "week") {
				$start = date('d-m-Y', strtotime("-1 week")); //1 week ago
				for($i = 0; $i <= 7; $i++) {
					$output['labels'][$i] = $start;
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "month") {
				$start = date('d-m-Y', strtotime("-31 days")); //1 month ago
				for($i = 0; $i <= 31; $i++) {
					$output['labels'][$i] = date('d-m', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "year") {
				$start = date('d-m-Y', strtotime("-1 year")); //1 year ago
				for($i = 0; $i <= 12; $i++) {
					$output['labels'][$i] = date('m-Y', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == date('m-Y', strtotime($start))) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . "+1 month");
					$start = date("d-m-Y", $timestamp);
				}
			}
			return $output;
		}
		
		
	}
	
	class station {
		public function __clone() {}
		
		public static function event($id, $type = "name") {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_event` WHERE `station_id` = :id");
			if($q->execute(array("id" => $id))) {
				if($q->rowCount() >= 1) {
					$now =	date('Y-m-d');
					$time = date("H:i:s", time());
					foreach($q->fetchAll() as $row) {
						if($row['recurring'] == 0) {
							//Evenement wordt niet herhaald
							if (general::timeIsBetween($row['event_start'], $row['event_end'], $now)) {
								$time = date("H:i:s", time());
								if(strtotime($now) == strtotime($row['event_start'])) {
									if(strtotime($time) >= strtotime($row['event_time_start'])) {
										if($type == "name") {
											$output = $row['name'];
										}
										if($type == "id") {
											$output = $row['id'];
										}
									}
									else {
										$output = false;
									}
								}
								elseif(strtotime($now) == strtotime($row['event_end'])) {
									if(strtotime($time) <= strtotime($row['event_time_stop'])) {
										if($type == "name") {
											$output = $row['name'];
										}
										if($type == "id") {
											$output = $row['id'];
										}
									}
									else {
										$output = false;
									}
								}
								else {
									if($type == "name") {
										$output = $row['name'];
									}
									if($type == "id") {
										$output = $row['id'];
									}
								}
							}
							else {
								$output = false;
							}
						}
						
						//Evenement word herhaald
						elseif($row['recurring'] == 1) {
							//Evenement wordt dagelijks herhaald
							if($row['rcurring_interval'] == 0) {
								if($row['event_start'] != null) {
									if (general::timeIsBetween($row['event_start'], $row['event_end'], $now)) {
										if (general::timeIsBetween($row['event_time_start'], $row['event_time_stop'], $time)) {
											if($type == "name") {
												$output = $row['name'];
											}
											if($type == "id") {
												$output = $row['id'];
											}
										}
										else {
											$output = false;
										}
									}
									else {
										$output = false;
									}
								}
								else {
									$output = false;
								}
							}
							//Evenement wordt wekelijks herhaald
							if($row['rcurring_interval'] == 1) {
								$date = cal_to_jd(CAL_GREGORIAN,date('m'),date('d'),date('Y'));
								$weekday = jddayofweek($date, 0);
								if($weekday == $row['recurring_val']) {
									if (general::timeIsBetween($row['event_time_start'], $row['event_time_stop'], $time)) {
										if($type == "name") {
											$output = $row['name'];
										}
										if($type == "id") {
											$output = $row['id'];
										}
									}
									else {
										$output = false;
									}
								}
								else {
									$output = false;
								}
							}
						}
						else {
							$output = false;
						}
						
					}
					
				}
				else {
					$output = false;
				}
				
			}
			else {
				$output = false;
			}
			return $output;
		}
		
		public static function repeatPerc($station_meta, $period =  "day") {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
			$q->execute(array("name" => $station_meta));
			$row = $q->fetch();
			if($period == "day") {
				$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 WEEK) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 YEAR) ORDER BY `played`");
			}
			$q->execute(array("id" => $row['id']));
			$row_count = $q->rowCount();
			if($period == "day") {
				$q = $db->prepare("SELECT `id`, `song_id`, `artist_id`, COUNT(`id`) AS `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY `song_id` ORDER BY `counter` DESC");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT `id`, `song_id`, `artist_id`, COUNT(`id`) AS `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY `song_id` ORDER BY `counter` DESC");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT `id`, `song_id`, `artist_id`, COUNT(`id`) AS `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY `song_id` ORDER BY `counter` DESC");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT `id`, `song_id`, `artist_id`, COUNT(`id`) AS `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY `song_id` ORDER BY `counter` DESC");
			}
			$q->execute(array('id' => $row['id']));
			$counter = 0;
			foreach($q->fetchAll() as $row_data) {
				if($row_data['counter'] >= 2) {
					$counter += $row_data['counter'];
				}
			}
			$sum = ($counter / $row_count) * 100;
			$output = [];
			$output['total'] = $row_count;
			$output['repeated'] = $counter;
			$output['percentage'] = round($sum, 2) . "%";
			return $output;
		}
		
		public static function now($station_meta) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
			$q->execute(array("name" => $station_meta));
			$row = $q->fetch();
			$q = $db->prepare("SELECT * FROM `radio_data` WHERE `station_id` = :id AND `played` > DATE_SUB(NOW(), INTERVAL 6 MINUTE) ORDER BY `played` DESC LIMIT 1");
			$q->execute(array("id" => $row['id']));
			if($q->rowCount() == 1) {
				$row_data = $q->fetch();
			}
			else {
				$row_data = false;
			}
			$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
			$q->execute(array("id" => $row_data['artist_id']));
			$row_artist = $q->fetch();
			$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
			$q->execute(array("id" => $row_data['song_id']));
			$row_song = $q->fetch();
			$output = [];
			if($row_data) {
				if(self::event($row['id'])) {
					$output['event'] = self::event($row['id']);
					$output['event_url'] = BASE_URL . '/special/' . self::event($row['id'], "id") . '.html';
				}
				$output['status'] = "Playing";
				$output['station'] = $row['name'];
				$output['station_id'] = $row['id'];
				$output['station_meta'] = $row['name_meta'];
				$output['station_url'] = BASE_URL . '/station/' . $row['name_meta'] . '.html';
				$output['song'] = $row_song['name'];
				$output['artist'] = $row_artist['name'];
				$output['artist_id'] = $row_artist['id'];
				$output['song_id'] = $row_song['id'];
				$output['artist_url'] = BASE_URL . '/artiest/' . $row_artist['id'] . '.html';
				$output['song_url'] = BASE_URL . '/nummer/' . $row_song['id'] . '.html';
				if(isset($row_song['album_image'])) {
					$output['image'] = $row_song['album_image'];
				}
				elseif(isset($row_artist['image'])) {
					$output['image'] = $row_artist['image'];
				}
				else {
					$output['image'] = BASE_URL . '/images/nocover.png';
				}
			}
			else {
				if(self::event($row['id'])) {
					$output['event'] = self::event($row['id']);
					$output['event_url'] = BASE_URL . '/special/' . self::event($row['id'], "id") . '.html';
				}
				$output['status'] = "Paused";
				$output['station'] = $row['name'];
				$output['station_id'] = $row['id'];
				$output['station_meta'] = $row['name_meta'];
				$output['station_url'] = BASE_URL . '/station/' . $row['name_meta'] . '.html';
				$output['image'] = BASE_URL . '/images/nocover.png';
			}
			
			return $output;
		}
		
		public static function allNow() {
			$db = db::getInstance();
			$q = $db->query("SELECT * FROM `radio_stations` ORDER BY `position` ASC");
			$output = [];
			$i = 0;
			foreach($q->fetchAll() as $row) {
				$output[$i] = self::now($row['name_meta']);
				$i++;
			}
			return $output;
		}
		
		public static function getInfo($id,$type) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
			if($q->execute(array("name" => $id))) {
				$row = $q->fetch();
				if($type == "name") {
					return $row['name'];
				}
				if($type == "id") {
					return $row['id'];
				}
				if($type=="wiki_id") {
					return $row['wiki_arr_id'];
				}
				if($type == "genre") {
					$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `genre_id` FROM `radio_data` WHERE `station_id` = :id GROUP BY(`genre_id`) ORDER BY `counter` DESC");
					if($q->execute(array("id" => $row['id']))) {
						$row = $q->fetch();
						$q = $db->prepare("SELECT * FROM `radio_genres` WHERE id = :id");
						$q->execute(array("id" => $row['genre_id']));
						$row_genre = $q->fetch();
						$output = [];
						$output['name'] = $row_genre['nice_name'];
						$output['url'] = BASE_URL . '/genre/'.$row_genre['name'].'.html';
						return $output;
					}
				}
				if($type == "image") {
					$output = BASE_URL . '/images/stations/' . $row['name_meta'] . '.png';
					return $output;
				}
				
				if($type == "id") {
					return $row['id'];
				}
			}
			else {
				page::throwError(500);
			}
		}
		
		public static function chart($id, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY HOUR(`played`) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `station_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY MONTH(`played`) ORDER BY `played`");
			}
			$q->execute(array("id" => self::getInfo($id, "id")));
			$i = 0;
			$data = [];
			foreach($q->fetchAll() as $row) {
				if($period == "day") {
					$data[$i]['date'] = date('d-m-Y H:00', strtotime($row['played']));
				}
				if($period == "week") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "month") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "year") {
					$data[$i]['date'] = date('m-Y', strtotime($row['played']));
				}
				$data[$i]['count'] = $row['counter'];
				$i++;
			}
			$output = [];
			$arr_count = 0;
			if($period == "day") {
				$start = date('d-m-Y H:00', strtotime("-1 day"));
				for($i = 0; $i <= 24; $i++) {
					$output['labels'][$i] = date('H:00', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start) + 60*60;
					$start = date("d-m-Y H:00", $timestamp);
				}
			}
			if($period == "week") {
				$start = date('d-m-Y', strtotime("-1 week")); //1 week ago
				for($i = 0; $i <= 7; $i++) {
					$output['labels'][$i] = $start;
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "month") {
				$start = date('d-m-Y', strtotime("-31 days")); //1 month ago
				for($i = 0; $i <= 31; $i++) {
					$output['labels'][$i] = date('d-m', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "year") {
				$start = date('d-m-Y', strtotime("-1 year")); //1 year ago
				for($i = 0; $i <= 12; $i++) {
					$output['labels'][$i] = date('m-Y', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == date('m-Y', strtotime($start))) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . "+1 month");
					$start = date("d-m-Y", $timestamp);
				}
			}
			return $output;
		}
	}
	
	class artist {
		public function __clone() {}
		
		public static function getInfo($id, $type) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");
			if($q->execute(array("id" => $id))) {
				if($q->rowCount() == 1) {
					$row = $q->fetch();
					if($type == "exists") {
						return true;
					}
					if($type == "name") {
						return $row['name'];
					}
					if($type == "image") {
						if(!empty($row['image'])) {
							return $row['image'];
						}
						else {
							return BASE_URL . "/images/nocover.png";
						}
					}
					if($type == "station") {
						$q = $db->prepare("SELECT COUNT(`id`) AS `counter`, `station_id` FROM `radio_data` WHERE `artist_id` = :id GROUP BY `station_id` ORDER BY `counter` DESC");
						$q->execute(array("id" => $row['id']));
						$row_id = $q->fetch();
						$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `id` = :id");
						$q->execute(array("id" => $row_id['station_id']));
						$row_station = $q->fetch();
						$output = [];
						$output['name'] = $row_station['name'];
						$output['url'] = BASE_URL . '/station/'.$row_station['name_meta'].'.html';
						return $output;
					}
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		
		public static function chart($id = 1000, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY HOUR(`played`) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `artist_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY MONTH(`played`) ORDER BY `played`");
			}
			$q->execute(array("id" => $id));
			$i = 0;
			$data = [];
			foreach($q->fetchAll() as $row) {
				if($period == "day") {
					$data[$i]['date'] = date('d-m-Y H:00', strtotime($row['played']));
				}
				if($period == "week") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "month") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "year") {
					$data[$i]['date'] = date('m-Y', strtotime($row['played']));
				}
				$data[$i]['count'] = $row['counter'];
				$i++;
			}
			$output = [];
			$arr_count = 0;
			if($period == "day") {
				$start = date('d-m-Y H:00', strtotime("-1 day"));
				for($i = 0; $i <= 24; $i++) {
					$output['labels'][$i] = date('H:00', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start) + 60*60;
					$start = date("d-m-Y H:00", $timestamp);
				}
			}
			if($period == "week") {
				$start = date('d-m-Y', strtotime("-1 week")); //1 week ago
				for($i = 0; $i <= 7; $i++) {
					$output['labels'][$i] = $start;
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "month") {
				$start = date('d-m-Y', strtotime("-31 days")); //1 month ago
				for($i = 0; $i <= 31; $i++) {
					$output['labels'][$i] = date('d-m', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "year") {
				$start = date('d-m-Y', strtotime("-1 year")); //1 year ago
				for($i = 0; $i <= 12; $i++) {
					$output['labels'][$i] = date('m-Y', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == date('m-Y', strtotime($start))) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . "+1 month");
					$start = date("d-m-Y", $timestamp);
				}
			}
			return $output;
		}
	}
	
	class song {
		public function __clone() {}
		
		public static function getInfo($id, $type) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_songs` WHERE id = :id");
			if($q->execute(array("id" => $id))) {
				if($q->rowCount() == 1) {
					$row = $q->fetch();
					if($type == "exists") {
						return true;
					}
					if($type == "name") {
						$q = $db->prepare("SELECT `name`, `id` FROM `radio_artists` WHERE `id` = :id");
						$q->execute(array("id" => $row['artist_id']));
						if($q->rowCount() == 1) {
							$row_artist = $q->fetch();
							$output = [];
							$output['artist'] = $row_artist['name'];
							$output['artist_id'] = $row_artist['id'];
							$output['song'] = $row['name'];
							$output['song_id'] = $row['id'];
							return $output;
						}
						else {
							page::throwError(404);
						}
					}
					if($type == "image") {
						if(!empty($row['album_image'])) {
							return $row['album_image'];
						}
						else {
							return BASE_URL . "/images/nocover.png";
						}
					}
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		
		public static function chart($id = 1000, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `song_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY HOUR(`played`) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `song_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `song_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `song_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY MONTH(`played`) ORDER BY `played`");
			}
			$q->execute(array("id" => $id));
			$i = 0;
			$data = [];
			foreach($q->fetchAll() as $row) {
				if($period == "day") {
					$data[$i]['date'] = date('d-m-Y H:00', strtotime($row['played']));
				}
				if($period == "week") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "month") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "year") {
					$data[$i]['date'] = date('m-Y', strtotime($row['played']));
				}
				$data[$i]['count'] = $row['counter'];
				$i++;
			}
			$output = [];
			$arr_count = 0;
			if($period == "day") {
				$start = date('d-m-Y H:00', strtotime("-1 day"));
				for($i = 0; $i <= 24; $i++) {
					$output['labels'][$i] = date('H:00', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start) + 60*60;
					$start = date("d-m-Y H:00", $timestamp);
				}
			}
			if($period == "week") {
				$start = date('d-m-Y', strtotime("-1 week")); //1 week ago
				for($i = 0; $i <= 7; $i++) {
					$output['labels'][$i] = $start;
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "month") {
				$start = date('d-m-Y', strtotime("-31 days")); //1 month ago
				for($i = 0; $i <= 31; $i++) {
					$output['labels'][$i] = date('d-m', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "year") {
				$start = date('d-m-Y', strtotime("-1 year")); //1 year ago
				for($i = 0; $i <= 12; $i++) {
					$output['labels'][$i] = date('m-Y', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == date('m-Y', strtotime($start))) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . "+1 month");
					$start = date("d-m-Y", $timestamp);
				}
			}
			return $output;
		}
	}
	
	class special {
		public function __clone() {}
		
		public static function getInfo($id, $return) {
			$db = db::getInstance();
			$q = $db->prepare("SELECT * FROM `radio_event` WHERE `id` = :id");
			if($q->execute(array('id' => $id))) {
				$row = $q->fetch();
				$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `id` = :id");
				if($q->execute(array("id" => $row['station_id']))) {
					$row_station = $q->fetch();
				}
				if($return == "name") {
					return $row['name'];
				}
				if($return == "radioName") {
					return $row_station['name'];
				}
			}
		}
		public static function chart($id = 1000, $period) {
			$db = db::getInstance();
			if($period == "day") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() GROUP BY HOUR(`played`) ORDER BY `played`");
			}
			if($period == "week") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 WEEK) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "month") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() GROUP BY DAY(`played`) ORDER BY `played`");
			}
			if($period == "year") {
				$q = $db->prepare("SELECT COUNT(`id`) as `counter`, `played` FROM `radio_data` WHERE `genre_id` = :id AND `played` BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() GROUP BY MONTH(`played`) ORDER BY `played`");
			}
			$q->execute(array("id" => self::getInfo($id, "id")));
			$i = 0;
			$data = [];
			foreach($q->fetchAll() as $row) {
				if($period == "day") {
					$data[$i]['date'] = date('d-m-Y H:00', strtotime($row['played']));
				}
				if($period == "week") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "month") {
					$data[$i]['date'] = date('d-m-Y', strtotime($row['played']));
				}
				if($period == "year") {
					$data[$i]['date'] = date('m-Y', strtotime($row['played']));
				}
				$data[$i]['count'] = $row['counter'];
				$i++;
			}
			$output = [];
			$arr_count = 0;
			if($period == "day") {
				$start = date('d-m-Y H:00', strtotime("-1 day"));
				for($i = 0; $i <= 24; $i++) {
					$output['labels'][$i] = date('H:00', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start) + 60*60;
					$start = date("d-m-Y H:00", $timestamp);
				}
			}
			if($period == "week") {
				$start = date('d-m-Y', strtotime("-1 week")); //1 week ago
				for($i = 0; $i <= 7; $i++) {
					$output['labels'][$i] = $start;
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "month") {
				$start = date('d-m-Y', strtotime("-31 days")); //1 month ago
				for($i = 0; $i <= 31; $i++) {
					$output['labels'][$i] = date('d-m', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == $start) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . " +1 day");
					$start = date("d-m-Y", $timestamp);
				}
			}
			if($period == "year") {
				$start = date('d-m-Y', strtotime("-1 year")); //1 year ago
				for($i = 0; $i <= 12; $i++) {
					$output['labels'][$i] = date('m-Y', strtotime($start));
					if(array_key_exists($arr_count, $data)) {
						if($data[$arr_count]['date'] == date('m-Y', strtotime($start))) {
							$output['data'][$i] = $data[$arr_count]['count'];
							$arr_count++;
						}
						else {
							$output['data'][$i] = 0;
						}
					}
					else {
						$output['data'][$i] = 0;
					}
					$timestamp = strtotime($start . "+1 month");
					$start = date("d-m-Y", $timestamp);
				}
			}
			return $output;
		}
		
	}
	
	class page {
		public function __clone() {}
		
		public static function current($returnType) {
			$db = db::getInstance();
			$curPage = array();
			$curPage[0]['name'] = "Home";
			$curPage[0]['url'] = BASE_URL;
			if(isset($_GET['page'])) {
				$pages = array('artiest', 'nummer', 'station', 'genre', 'special');
				if($returnType == "includes") {
					foreach($pages as $page) {
						if($page == $_GET['page']) {
							$check = self::pageExists($page);
							if($check) {
								require_once($check);
							}
							else {
								self::throwError(404);
							}
						}
					}
				}
				if($returnType == "includesJS") {
					foreach($pages as $page) {
						if($page == $_GET['page']) {
							$check = self::pageExists($page);
							if($check) {
								if(file_exists(dirname(__FILE__).'/pages_js/'.$_GET['page'].'.php')) {
									require_once(dirname(__FILE__).'/pages_js/'.$_GET['page'].'.php');
								}
							}
						}
					}
				}
				if($returnType == "names") {
					if(isset($_GET['id'])) {
						if($_GET['page'] == "artiest") {
							$q = $db->prepare("SELECT `name`, `id` FROM `radio_artists` WHERE `id` = :id");
							if($q->execute(array("id" => $_GET['id']))) {
								$row_artist = $q->fetch();
								$curPage[1]['name'] = $row_artist['name'];
								$curPage[1]['url'] = BASE_URL . '/artiest/' . $row_artist['id'] . '.html';
							}
						}
						if($_GET['page'] == "nummer") {
							$q = $db->prepare("SELECT * FROM `radio_songs` WHERE `id` = :id");
							if($q->execute(array("id" => $_GET['id']))) {
								$row_song = $q->fetch();
								$q = $db->prepare("SELECT `name`, `id` FROM `radio_artists` WHERE `id` = :id");
								$q->execute(array("id" => $row_song['artist_id']));
								$row_artist = $q->fetch();
								$curPage[2]['name'] = $row_artist['name'];
								$curPage[2]['url'] = BASE_URL . '/artiest/' . $row_artist['id'] . '.html';
								$curPage[3]['name'] = $row_song['name'];
								$curPage[3]['url'] = BASE_URL . '/nummer/' . $row_song['id'] . '.html';
							}
						}
						if($_GET['page'] == "station") {
							$q = $db->prepare("SELECT * FROM `radio_stations` WHERE `name_meta` = :name");
							if($q->execute(array("name" => $_GET['id']))) {
								$row_station = $q->fetch();
								$curPage[1]['name'] = $row_station['name'];
								$curPage[1]['url'] = BASE_URL . '/station/' . $row_station['name_meta'] . '.html';
							}
						}
						if($_GET['page'] == "genre") {
							$q = $db->prepare("SELECT * FROM `radio_genres` WHERE `nice_name` = :id");
							if($q->execute(array("id" => $_GET['id']))) {
								$row_genre = $q->fetch();
								$curPage[1]['name'] = $row_genre['name'];
								$curPage[1]['url'] = BASE_URL . '/genre/' . $row_genre['nice_name'] . '.html';
							}
						}
						if($_GET['page'] == "special") {
							$q = $db->prepare("SELECT * FROM `radio_event` WHERE `id` = :id");
							if($q->execute(array("id" => $_GET['id']))) {
								$row_special = $q->fetch();
								$curPage[1]['name'] = $row_special['name'];
								$curPage[1]['url'] = BASE_URL . '/special/' . $row_special['id'] . '.html';
							}
						}
					}
				}
				
			}
			else {
				if($returnType == "includes") {
					$check = self::pageExists("home");
					if($check) {
						require_once($check);
					}
					else {
						self::throwError(404);
					}
				}
				if($returnType == "includesJS") {
					$check = self::pageExists("home");
					if($check) {
						if(file_exists(dirname(__FILE__).'/pages_js/home.php')) {
							require_once(dirname(__FILE__).'/pages_js/home.php');
						}
					}
				}
			}
			if($returnType == "names") {
				return $curPage;
			}
		}
		
		public static function background() {
			if(isset($_GET['page'])) {
				if(isset($_GET['id'])) {
					if($_GET['page'] == "artiest") {
						return "background-image: url(\"" . artist::getInfo($_GET['id'], "image") . "\");";
					}
				}
			}
		}
		
		public static function breadCrumbs() {
			$output = '<ol class="breadcrumb">';
			$arr = self::current("names");
			$count = count($arr);
			$i = 1;
			foreach($arr as $bread) {
				if($i == $count) {
					$output .= '<li class="breadcrumb-item active">'.$bread['name'].'</li>';
				}
				else {
					if($bread['url']) {
						$output .= '<li class="breadcrumb-item"><a href="'.$bread['url'].'">'.$bread['name'].'</a></li>';
					}
					else {
						$output .= '<li class="breadcrumb-item">'.$bread['name'].'</li>';
					}
				}
				$i++;
			}
			$output .= '</ol>';
			return $output;
		}
		
		public static function pageExists($file) {
			if(strpos($file, '.php') !== false) {
				if(file_exists(dirname(__FILE__).'/pages/'.$file)) {
					return dirname(__FILE__).'/pages/'.$file;
				}
				else {
					return false;
				}
			}
			else {
				if(file_exists(dirname(__FILE__).'/pages/'.$file.'.php')) {
					return dirname(__FILE__).'/pages/'.$file.'.php';
				}
				else {
					return false;
				}
			}
			
		}
		
		public static function throwError($code) {
			if($code == 404) {
				//Page does not exist;
				echo "<H1 class='section-title'>Fout</H1>";
				echo "<div class='card-block bg-danger'><div class='text-md-center card'><h1>Deze pagina bestaat niet</h1></div></div></div>";
			}
		}
	}
?>