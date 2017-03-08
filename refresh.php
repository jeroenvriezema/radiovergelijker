<?php
	if(file_exists(dirname(__FILE__).'/functions.php')) {
		require_once(dirname(__FILE__).'/functions.php');
	}
	else {
		die("Functions.php niet gevonden!");
	}
	if(file_exists(dirname(__FILE__).'/config.php')) {
		require_once(dirname(__FILE__).'/config.php');
	}
	else {
		die("Functions.php niet gevonden!");
	}
if(isset($_GET['type'])) {
	if($_GET['type'] == "nowPlaying") {
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
	}
}