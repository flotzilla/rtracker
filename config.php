<?php
include_once "classes/API/RutrackerAPI.php";
include_once "classes/API/RutorAPI.php";
include "ConfigReader.php";
include_once "classes/Utils.php";

$c = new ConfigReader();
$config = $c->getConfig();
//
$rt = new RutrackerAPI();
$rt->parse_user_params();
$flist = $rt->getFutureList();
//
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rtracker - Config page</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bower_components/css/style.css">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/js/main.js"></script>

</head>
<body role="document">

<?php
include "page/header.php";
?>
<div class="container main-cont">
    <h2 class="align-center">Config page</h2>
</div>


</body>
</html>
