<?php 

require "db.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Final</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/bootstrap.min.css">
  <link rel="icon" href="assets/images/survey.png">
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script src="assets/jquery.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <script src="assets/bootstrap.min.js"></script>
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

<div class="container pt-4 pb-4">
  <div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead">
    <strong>Thanks</strong> for submitting survey.
  </p>
  <hr>
  <p class="lead">
    <a class="btn btn-primary btn-sm" href="<?= URL ?>" role="button">Continue to homepage</a>
  </p>
</div>
</div>
</body>
</html>