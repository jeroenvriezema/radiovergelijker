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
	header('Content-Type: application/json');
	
	if(isset($_GET['data'])) {
		if($_GET['data'] == "chartData") {
			if(isset($_GET['type'])) {
				if($_GET['type'] == "song") {
					if(isset($_GET['period'])) {
						if($_GET['period'] == "day") {
							$data = song::chart($_GET['id'], "day");
						}
						if($_GET['period'] == "week") {
							$data = song::chart($_GET['id'], "week");
						}
						if($_GET['period'] == "month") {
							$data = song::chart($_GET['id'], "month");
						}
						if($_GET['period'] == "year") {
							$data = song::chart($_GET['id'], "year");
						}
					}
					if(isset($_GET['return'])) {
						if($_GET['return'] == "data") {
							if($data) {
								echo json_encode($data['data'], JSON_NUMERIC_CHECK);
							}
						}
						if($_GET['return'] == "labels") {
							if($data) {
								echo json_encode($data['labels']);
							}
						}
					}	
				}
				if($_GET['type'] == "artist") {
					if(isset($_GET['period'])) {
						if($_GET['period'] == "day") {
							$data = artist::chart($_GET['id'], "day");
						}
						if($_GET['period'] == "week") {
							$data = artist::chart($_GET['id'], "week");
						}
						if($_GET['period'] == "month") {
							$data = artist::chart($_GET['id'], "month");
						}
						if($_GET['period'] == "year") {
							$data = artist::chart($_GET['id'], "year");
						}
					}
					if(isset($_GET['return'])) {
						if($_GET['return'] == "data") {
							if($data) {
								echo json_encode($data['data'], JSON_NUMERIC_CHECK);
							}
						}
						if($_GET['return'] == "labels") {
							if($data) {
								echo json_encode($data['labels']);
							}
						}
					}	
				}
				if($_GET['type'] == "genre") {
					if(isset($_GET['period'])) {
						if($_GET['period'] == "day") {
							$data = genres::chart($_GET['id'], "day");
						}
						if($_GET['period'] == "week") {
							$data = genres::chart($_GET['id'], "week");
						}
						if($_GET['period'] == "month") {
							$data = genres::chart($_GET['id'], "month");
						}
						if($_GET['period'] == "year") {
							$data = genres::chart($_GET['id'], "year");
						}
					}
					if(isset($_GET['return'])) {
						if($_GET['return'] == "data") {
							if($data) {
								echo json_encode($data['data'], JSON_NUMERIC_CHECK);
							}
						}
						if($_GET['return'] == "labels") {
							if($data) {
								echo json_encode($data['labels']);
							}
						}
					}	
				}
				if($_GET['type'] == "station") {
					if(isset($_GET['period'])) {
						if($_GET['period'] == "day") {
							$data = station::chart($_GET['id'], "day");
						}
						if($_GET['period'] == "week") {
							$data = station::chart($_GET['id'], "week");
						}
						if($_GET['period'] == "month") {
							$data = station::chart($_GET['id'], "month");
						}
						if($_GET['period'] == "year") {
							$data = station::chart($_GET['id'], "year");
						}
					}
					if(isset($_GET['return'])) {
						if($_GET['return'] == "data") {
							if($data) {
								echo json_encode($data['data'], JSON_NUMERIC_CHECK);
							}
						}
						if($_GET['return'] == "labels") {
							if($data) {
								echo json_encode($data['labels']);
							}
						}
					}	
				}
			}
		}
	}
?>

