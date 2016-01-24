<?php

include_once "RutrackerAPI.php";
include "ConfigReader.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

$c = new ConfigReader();
$config = $c->getConfig();

$rt = new RutrackerAPI();
$rt->init_action(
    $config['rutracker']['username'],
    $config['rutracker']['password']);
$rt->parse_user_params();
$flist = $rt->getFutureList();


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
    <script src="bower_components/js/main.js"></script>

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
            <a href="mylist.php">
                <button class="btn btn-warning btn-sm navbar-btn navbar-left" type="button">
                    My list <span class="badge"><?=$rt->getFutureListSize()?></span>
                </button>
            </a>

            <ul class="nav navbar-nav navbar-right">
                <li class="username">
                    <a title="rutracker user"
                       href="http://rutracker.org/forum/profile.php?mode=viewprofile&u=<?= $rt->getUser()   ?>">
                        <?= $rt->getUser()  ?></a></li>
            </ul>
            <form class="navbar-form navbar-right" role="search" method="get"
                action="index.php">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="type here..."
                           name="search">
                </div>
                <button type="submit" class="btn btn-success btn-sm">
                    <span class="glyphicon glyphicon-search"></span>
                    Search
                </button>
            </form>

            <ul class="nav navbar-nav navbar-left">
                <li class="username">
                    <a href="config.php">
                        <span class="glyphicon glyphicon-cog"></span>
                        Config
                    </a>
                </li>
                <li class="username">
                    <a href="about.php">
                        <span class=" glyphicon glyphicon-info-sign"></span>
                        About
                    </a>
                </li>
            </ul>

        </div>

    </div>
</nav>

<div class="container main-cont">

    <div class="row col-md-6 col-md-offset-3">
        <h4 class="align-center">Search</h4>
        <form class="" role="search" method="get"
              action="index.php">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="type here..."
                       name="search">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-success">
<!--                        <span class="glyphicon glyphicon-search"></span>-->
                        Search
                    </button>
                </span>
            </div>
            <div>
              <div class="row col-md-12 align-center">
                  <button class="btn btn-default btn-sm custom-butt" type="button" data-toggle="collapse"
                          data-target="#advanced_collapse" aria-expanded="false" aria-controls="advanced_collapse">
                      Advanced params
                  </button>
              </div>
                <div class="collapse col-md-12" id="advanced_collapse">
                    <div class="well">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" id="author_name">author</span>
                            <input type="text" class="form-control" placeholder="search by author name"
                                   name="pn" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>

    <div class="row col-md-12">
<?
if(isset($_GET['search'])){
    $search_str = trim($_GET['search']);
    echo "<h3 class='align-center'>
                <span class='glyphicon glyphicon-search'></span>
                " . $search_str . "
            </h3>";
    $rt_result = $rt->search($search_str);

    if(count($rt_result) !== 0){
        echo "<p> <span class='search-word'>Rutracker</span> Found: ". count($rt_result). " results</p><br>";
        foreach ($rt_result as $t){
            if(isset($t['torrent_view_link']) && isset($t['torrent_text'])){
            echo "<a href='".$t['torrent_view_link']. "'>". $t['torrent_text']."</a>'<br>";
            }
        }
    }else{
        echo "<p> <span class='search-word'>Rutracker</span> Nothing found</p><br>";
    }

}
?>
    </div>

</div> <!--//end of .container .main-cont-->

</body>
</html>

