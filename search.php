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
	//Start a new session
	session_start();
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <!-- Required meta tags always come first -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="x-ua-compatible" content="ie=edge">



    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">

	<link rel='stylesheet' href='<?php echo BASE_URL; ?>/style.css' />

	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' />

  </head>

  <body>
	<!-- Jumbotron with header -->
    <div class='jumbotron page-header'>

		<div class='container'>

			<h1 id='site_name'>Radio<strong>vergelijker</strong>.nl</h1>

		</div>

	</div>
	<!-- Breadcrumbs -->
	<div class='breadcrumbs'>

		<div class='container'>

			<div class='row'>

				<div class='col-md-8'>

					<ol class="breadcrumb"><li class='breadcrumb-item'>Home</li><li class="breadcrumb-item active">Zoeken</li></ol>

				</div>

				<div class='col-md-4'>

					<form method='POST' action='search.php'>

						<div class='input-group search-form'>

							<span class='input-group-addon form-control-sm' id='zoeken'><i class='fa fa-search'></i></span>

							<input type='text' class='form-control form-control-sm' name='search' placeholder='Zoeken' aria-describedby='zoeken'>

						</div>

					</form>

				</div>

			</div>

		</div>

	</div>

	

	<div class='container'>

		<?php
			if(isset($_POST['search'])) {

				$i = 0;

				$db = db::getInstance();

				$q = $db->prepare("SELECT * FROM `radio_songs` WHERE `name` LIKE :search");

				$q->execute(array("search" => "%" . $_POST['search'] . "%"));

				echo "<h1 class='section-title'>Zoekresultaten</h1>";

				foreach($q->fetchAll() as $row) {

					$q = $db->prepare("SELECT * FROM `radio_artists` WHERE id = :id");

					$q->execute(array("id" => $row['artist_id']));

					$row_artist = $q->fetch();

					$output[$i]['song'] = $row['name'];

					$output[$i]['artist'] = $row_artist['name'];

					$output[$i]['album_image'] = $row['album_image'];

					$i++;

				}

				echo "<ul class='list-group'>";

				foreach($output as $results) {

					echo "<li class='list-group-item'><h3 class='card-title'>".$results['song']."</h3><h5>".$results['artist']."</h5></li>";

				}

				echo "</ul>";

			}

		?>

	</div>



    <!-- jQuery first, then Tether, then Bootstrap JS. -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>

	<?php page::current("includesJS"); ?>

 </body>

</html>
