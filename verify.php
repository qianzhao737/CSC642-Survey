<?php 

require "db.php";

if (!isset($_SESSION['email'])) {
	header("Location: ".URL);
	exit;
}

if (isset($_GET['clear'])) {
	session_unset();
	session_destroy();
	header("Location: ".URL);
	exit;
}

if (isset($_GET['confirm'])) {
	$data = Array(
		'firstname' => $_SESSION['firstname'],
		'lastname' => $_SESSION['lastname'],
		'address' => $_SESSION['address'],
		'birthdate' => $_SESSION['birthdate'],
		'education_level' => $_SESSION['education_level'],
		'height' => $_SESSION['height'],
		'phone' => $_SESSION['phone'],
		'email' => $_SESSION['email']
	);

	if ($db->insert('surveys', $data)) {
		// $_SESSION['message'] = '<div class="alert alert-success alert-dismissible">
  	//     <button type="button" class="close" data-dismiss="alert">&times;</button>
  	//     <strong>Success!</strong> Please enter required fields.
  	//   </div>';
		session_unset();
		session_destroy();
    header('Location: ' . URL . 'final.php');
    exit;
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Verify</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/bootstrap.min.css">
  <link rel="icon" href="assets/images/survey.png">
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="assets/jquery.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <script src="assets/bootstrap.min.js"></script>
  <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDvZwVV6sBw7bpYFX7nolSYH5cCZ0jqE0&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
  <style>
  body {
  	background: url(/assets/images/bg.png);
  }
  .required:after {
  	color: red;
  	content: "*";
  }
  .container {
  	background: white;
  }
  #map {
		height: 100%;
		width: 100%;
	}
  </style>
</head>
<body>

<div class="container pt-4 pb-4">
  <h1>Verification page:</h1>
  <div class="row">
  	<div class="col-lg-8">
  		<table class="table table-bordered">
		  	<thead>
		  		<tr>
		  			<th>Field</th>
		  			<th>Value</th>
		  		</tr>
		  	</thead>
		  	<tbody>
		  		<tr>
		  			<td>Firstname</td>
		  			<td><?= $_SESSION['firstname'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Lastname</td>
		  			<td><?= $_SESSION['lastname'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Birthdate</td>
		  			<td><?= date_format(date_create($_SESSION['birthdate']), "d/m/Y"); ?></td>
		  		</tr>
		  		<tr>
		  			<td>Education level</td>
		  			<td><?= $_SESSION['education_level'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Height</td>
		  			<td><?= $_SESSION['height'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Phone</td>
		  			<td><?= $_SESSION['phone'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Email</td>
		  			<td><?= $_SESSION['email'] ?></td>
		  		</tr>
		  		<tr>
		  			<td>Address</td>
		  			<td><?= $_SESSION['address'] ?></td>
		  		</tr>
		  		<tr>
		  			<td colspan="2" style="height: 500px;"><div id="map"></div></td>
		  		</tr>
		  	</tbody>
		  </table>

		  <a onclick="return confirm('Are you sure?');" href="<?= URL ?>verify.php?clear=true"><button class="btn btn-danger btn-sm">Clear</button></a>
		  <a href="<?= URL ?>verify.php?confirm=true"><button class="btn btn-success btn-sm">Confirm</button></a>
  	</div>
  </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<script>
  (function(exports) {
    "use strict";

    function initMap() {
      var myLatLng = {
          lat: <?= $_SESSION['latitude_input'] ?>,
          lng: <?= $_SESSION['longitude_input'] ?>
        };
      exports.map = new google.maps.Map(document.getElementById("map"), {
        center: myLatLng,
        zoom: 8
      });

      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Hello World!'
      });
    }

    exports.initMap = initMap;
  })((this.window = this.window || {}));
</script>
</body>
</html>