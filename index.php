<?php
/**
 * Created by PhpStorm.
 * User: bitybite
 * Date: 1/20/16
 * Time: 6:17 PM
 */

include "RutrackerAPI.php";
include "ConfigReader.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

$c = new ConfigReader();
$config = $c->getConfig();

$rt = new RutrackerAPI(
    $config['rutracker']['username'],
    $config['rutracker']['password']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rtracker management</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bower_components/css/style.css">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>
<body role="document">


<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">rtracker</a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
                <button class="btn btn-warning btn-sm navbar-btn navbar-left" type="button">
                    My list <span class="badge"><?=$rt->getFutureListSize()?></span>
                </button>


            <ul class="nav navbar-nav navbar-right">
                <li class="username"><a title="rutracker user" href="#"><?= $rt->getUser()  ?></a></li>
            </ul>
            <form class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="type here...">
                </div>
                <button type="submit" class="btn btn-success btn-sm">Search</button>
            </form>

            <ul class="nav navbar-nav navbar-left">
                <li class="username"><a href="#">Config</a></li>
                <li class="username"><a href="#">About</a></li>
            </ul>

        </div>

    </div>
</nav>

<div class="container main-cont">
    <div class="col-md-10">
        <ul class="list-unstyled">
<?
foreach($rt->getFutureList() as $p){
    echo "<li class=' '><a href='".$p['topic_link']. "'>". $p['topic_name']. "</a>"
        . " - <a href='".$p['link']. "'>". $p['name']. "</a></li>";
}
?>
        </ul>
    </div>
</div>
</body>
</html>
