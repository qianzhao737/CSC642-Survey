<?php 

require "db.php";

function verifyGoogleRecaptcha($g_recaptcha_response) {
	$responseKey = $g_recaptcha_response;
	$secretKey = "6Ld05LsUAAAAAIMpIi35W_9OigNlXfjJXK57kTqg";
	$userIP = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
  $response = file_get_contents($url);
  $response = json_decode($response);

  if ($response->success)
		return true;
  else
  	return false;
}

if (isset($_POST['submit'])) {
	$firstname = clean_input($_POST['firstname']);
	$lastname = clean_input($_POST['lastname']);
	$address = clean_input($_POST['address']);
	$latitude_input = clean_input($_POST['latitude_input']);
	$longitude_input = clean_input($_POST['longitude_input']);
	$birthdate = clean_input($_POST['birthdate']);
	$education_level = clean_input($_POST['education_level']);
	$height = clean_input($_POST['height']);
	$phone = clean_input($_POST['phone']);
	$email = clean_input($_POST['email']);
	$confirm_email = clean_input($_POST['confirm_email']);
	$iattac = (isset($_POST['iattac'])) ? true : false;
	$g_recaptcha_response = clean_input($_POST['g-recaptcha-response']);

	$_SESSION['firstname'] = $firstname;
	$_SESSION['lastname'] = $lastname;
	$_SESSION['address'] = $address;
	$_SESSION['latitude_input'] = $latitude_input;
	$_SESSION['longitude_input'] = $longitude_input;
	$_SESSION['birthdate'] = $birthdate;
	$_SESSION['education_level'] = $education_level;
	$_SESSION['height'] = $height;
	$_SESSION['phone'] = $phone;
	$_SESSION['email'] = $email;

	if ($firstname == '' || $lastname == '' || $address == '' || $birthdate == '' || $phone == '' || $email == '' || $confirm_email == '' || !$iattac) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Please enter required fields.
    </div>';

    header('Location: ' . URL);
    exit;
	} else if (strlen($phone) < 7 || !is_numeric($phone)) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Please enter proper phone number ie 926675645321.
    </div>';

    unset($_SESSION['phone']);
    header('Location: ' . URL);
    exit;
	} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Please enter proper email.
    </div>';

    unset($_SESSION['email']);
    header('Location: ' . URL);
    exit;
	} else if ($email != $confirm_email) {
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Incorrect confirm email.
    </div>';

    unset($_SESSION['email']);
    header('Location: ' . URL);
    exit;
	} else if (!verifyGoogleRecaptcha($g_recaptcha_response)) { // !verifyGoogleRecaptcha('$')
		$_SESSION['message'] = '<div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Error!</strong> Please confirm you are not rebot!
    </div>';

    header('Location: ' . URL);
    exit;
	} else {
		header('Location: '. URL . 'verify.php');
		exit;
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Survey</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/bootstrap.min.css">
  <link rel="icon" href="assets/images/survey.png">
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="assets/jquery.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <script src="assets/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyBDvZwVV6sBw7bpYFX7nolSYH5cCZ0jqE0"></script>
  <script>
  	var searchInput = 'search_input';

	$(document).ready(function () {
		var autocomplete;
		autocomplete = new google.maps.places.Autocomplete((document.querySelector('input[name="address"]')), {
			types: ['geocode'],
			/*componentRestrictions: {
				country: "USA"
			}*/
		});

		// autocomplete.setComponentRestrictions(
  //           {'country': ['us', 'pr', 'vi', 'gu', 'mp', 'pk']});

  //       // Specify only the data fields that are needed.
  //       autocomplete.setFields(
  //           ['address_components', 'geometry', 'icon', 'name']);
		
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var near_place = autocomplete.getPlace();
			$('input[name="latitude_input"]').val(near_place.geometry.location.lat());
			$('input[name="longitude_input"]').val(near_place.geometry.location.lng());
		});
	});

	$(document).on('change', 'input[name="address"]', function () {
		$('input[name="latitude_input"]').val('');
		$('input[name="longitude_input"]').val('');
	});
  </script>
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
  </style>
</head>
<body>

<div class="container pt-4">
  <h1>"CSC 642 Summer 2020 Individual Assignment Qianqian Zhao Data survey form"</h1>
  <p><span class="required"></span> fields are required</p>
  <div class="row">
  	<div class="col-sm-12 col-md-10 col-lg-8">
  		<form action="" method="POST" name="survey_form" class="mt-2">
  			<?php 
  				if (isset($_SESSION['message'])) {
  					echo $_SESSION['message'];
  					unset($_SESSION['message']);
  				}
  			?>
		  	<div class="row">
		  		<div class="form-group col-sm-6 col-md-6">
			  		<label for="">Firstname <span class="required"></span></label>
			  		<input type="text" name="firstname" class="form-control" value="<?= (isset($_SESSION['firstname']))? $_SESSION['firstname']:false; ?>">
			  	</div>

			  	<div class="form-group col-sm-6 col-md-6">
			  		<label for="">Lastname <span class="required"></span></label>
			  		<input type="text" name="lastname" class="form-control" value="<?= (isset($_SESSION['lastname']))? $_SESSION['lastname']:false; ?>">
			  	</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col-sm-12 col-md-12">
		  			<label for="">Address <span class="required"></span></label>
		  			<!-- <textarea name="address" id="" cols="30" rows="3" class="form-control"><?= (isset($_SESSION['address']))? $_SESSION['address']:false; ?></textarea> -->
		  			<input type="text" name="address" value="" class="form-control">
		  			<input type="hidden" name="latitude_input" value="">
		  			<input type="hidden" name="longitude_input" value="">
		  		</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Birthdate <span class="required"></span></label>
		  			<input type="date" name="birthdate" class="form-control" value="<?= (isset($_SESSION['birthdate']))? $_SESSION['birthdate']:false; ?>">
		  		</div>

		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Education level</label>
		  			<select name="education_level" id="" class="form-control">
		  				<option value="">Select</option>
		  				<option value="high school" <?= (isset($_SESSION['education_level']) && $_SESSION['education_level'] == 'high school')? 'selected':false; ?>>high school</option>
		  				<option value="college" <?= (isset($_SESSION['education_level']) && $_SESSION['education_level'] == 'college')? 'selected':false; ?>>college</option>
		  				<option value="graduate studies" <?= (isset($_SESSION['education_level']) && $_SESSION['education_level'] == 'graduate studies')? 'selected':false; ?>>graduate studies</option>
		  				<option value="Ph.D" <?= (isset($_SESSION['education_level']) && $_SESSION['education_level'] == 'Ph.D')? 'selected':false; ?>>Ph.D</option>
		  			</select>
		  		</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Height</label>
		  			<input type="number" name="height" class="form-control" step="0.1" value="<?= (isset($_SESSION['height']))? $_SESSION['height']:false; ?>">
		  		</div>

		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Phone <span class="required"></span></label>
		  			<input type="text" name="phone" class="form-control" value="<?= (isset($_SESSION['phone']))? $_SESSION['phone']:false; ?>">
		  		</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Email <span class="required"></span></label>
		  			<input type="text" name="email" class="form-control" value="<?= (isset($_SESSION['email']))? $_SESSION['email']:false; ?>">
		  		</div>

		  		<div class="form-group col-sm-12 col-md-6">
		  			<label for="">Confirm email <span class="required"></span></label>
		  			<input type="text" name="confirm_email" class="form-control">
		  		</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col">
				    <div class="form-check">
				      <input class="form-check-input" name="iattac" type="checkbox" value="" id="invalidCheck">
				      <label class="form-check-label" for="invalidCheck">
				        I agree to terms and conditions <span class="required"></span>
				      </label>
				      <div class="invalid-feedback">
				        You must agree before submitting.
				      </div>
				    </div>
				  </div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col">
		  			<div class="g-recaptcha" data-sitekey="6Ld05LsUAAAAAA-b5yRa-GKHwgQYuoOhwYsamoFK"></div>
		  		</div>
		  	</div>

		  	<div class="row">
		  		<div class="form-group col">
		  			<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-sm">
		  		</div>
		  	</div>
		  </form>
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
$(document).ready(function() {
	function isCaptchaChecked() {
	  return grecaptcha && grecaptcha.getResponse().length !== 0;
	}

	function validatePhone(phone) {
		const re = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/g;
	  return re.test(phone);
	}

	function validateEmail(email) {
	  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(String(email).toLowerCase());
	}

	$('form[name="survey_form"]').submit(function(e) {
		// e.preventDefault();
		$('.alert').remove();

		var form = $(this);
		var firstname = ($('input[name="firstname"]').val()).trim();
		var lastname = ($('input[name="lastname"]').val()).trim();
		var address = ($('input[name="address"]').val()).trim();
		var birthdate = ($('input[name="birthdate"]').val()).trim();
		var education_level = ($('select[name="education_level"]').val()).trim();
		var height = ($('input[name="height"]').val()).trim();
		var phone = ($('input[name="phone"]').val()).trim();
		var email = ($('input[name="email"]').val()).trim();
		var confirm_email = ($('input[name="confirm_email"]').val()).trim();
		var iattac = $('input[type="checkbox"]').prop('checked');

		if (firstname == '' || lastname == '' || address == '' || birthdate == '' || phone == '' || email == '' || confirm_email == '' || !iattac || !isCaptchaChecked()) {
			e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Please enter required fields.
      </div>`);
      $(form).prepend(message);
      $(message).fadeIn();
      $([document.documentElement, document.body]).animate({
			  scrollTop: $(message).closest('div.col-sm-12.col-md-10.col-lg-8').offset().top
			}, 100);
		} else if (firstname.length > 40 || lastname.length > 40 || address.length > 40 || birthdate.length > 40 || phone.length > 40 || email.length > 40 || confirm_email.length > 40 || !iattac || !isCaptchaChecked()) {
			e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Each entry should be no longer then 40 characters.
      </div>`);
      $(form).prepend(message);
      $(message).fadeIn();
      $([document.documentElement, document.body]).animate({
			  scrollTop: $(message).closest('div.col-sm-12.col-md-10.col-lg-8').offset().top
			}, 100);
		} else if (phone.toString().length < 7 || isNaN(phone)) {
			e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Enter proper phone number.
      </div>`);
      $(form).prepend(message);
      $(message).fadeIn();
      $([document.documentElement, document.body]).animate({
			  scrollTop: $(message).closest('div.col-sm-12.col-md-10.col-lg-8').offset().top
			}, 100);
		} else if (!email.includes("@")) {
			e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Enter proper email.
      </div>`);
      $(form).prepend(message);
      $(message).fadeIn();
      $([document.documentElement, document.body]).animate({
			  scrollTop: $(message).closest('div.col-sm-12.col-md-10.col-lg-8').offset().top
			}, 100);
		} else if (email != confirm_email) {
			e.preventDefault();
      var message = $(`<div class="alert alert-danger alert-dismissible" style="display: none;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong> Incorrect confirm email.
      </div>`);
      $(form).prepend(message);
      $(message).fadeIn();
      $([document.documentElement, document.body]).animate({
			  scrollTop: $(message).closest('div.col-sm-12.col-md-10.col-lg-8').offset().top
			}, 100);
		}
	});
});
</script>
</body>
</html>