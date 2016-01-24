<?php

include_once "RutrackerAPI.php";
include "ConfigReader.php";
include "Utils.php";

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
    <script type="text/javascript" src="bower_components/tablesorter/dist/js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="bower_components/js/main.js"></script>


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
                <input type="text" class="form-control" placeholder="or here..."
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

    <div class="row col-md-12 margin-top20">
<?
if(isset($_GET['search'])){
    $search_str = trim($_GET['search']);
    $rt_result = array();
    if(strlen($search_str) == 0){
        echo '<div class="alert alert-danger" role="alert"><p>To short query</p></div>';
    }else{
        echo "<h3 class='align-center'>
                <span class='glyphicon glyphicon-search'></span>
                " . $search_str . "
            </h3>";
        $rt_result = $rt->search($search_str);
    }

    if(count($rt_result) !== 0){
        echo "<p> <span class='search-word'>Rutracker</span> Found: ". count($rt_result). " results</p><br>";

        ?>
        <table class="table table-striped table-hover table-bordered  table-condensed tablesorter"
               id="rutracker_result">
            <thead>
            <tr class="th-middle">
                <th class="align-center" data-sortable="true">Status</th> <!--1-->
                <th class="align-center" data-sortable="true">Topic</th> <!--2-->
                <th class="align-center" data-sortable="true">Torrent</th><!--3-->
                <th class="align-center" data-sortable="true">Author</th><!--4-->
                <th class="align-center" data-sortable="true">Size</th><!--5-->
                <th class="align-center color-green" data-sortable="true">
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </th><!--6-->
                <th class="align-center color-red" data-sortable="true">
                    <span class="glyphicon glyphicon-arrow-down"></span>
                </th><!--7-->
                <th class="align-center" data-sortable="true">Down.</th><!--8-->
                <th class="align-center" data-sortable="true">Added</th><!--9-->
            </tr>
            </thead>
            <tbody>
            <?
            for ($i = 0; $i < count($rt_result); $i++) {
                if(!isset($rt_result[$i]['torrent_view_link']) && !isset($rt_result[$i]['torrent_text'])){
                        continue;
                }
                echo "<tr>";
                if($rt_result[$i]['status'] == 'проверено'){
                    echo '<td class="align-center cursor-pointer" title="'.$rt_result[$i]['status'].'"><span class="glyphicon glyphicon-ok"></span></td>';
                }elseif($rt_result[$i]['status'] == 'недооформлено'){
                    echo '<td class="align-center cursor-pointer" title="'.$rt_result[$i]['status'].'"><span class="glyphicon glyphicon-pencil"></span></td>';
                }else{
                    echo '<td class="align-center cursor-pointer" title="'.$rt_result[$i]['status'].'"><span class="glyphicon glyphicon glyphicon-asterisk"></span></td>';
                }
                echo '<td><a href="' . $rt_result[$i]["section_link_search"] . '">
                       ' . $rt_result[$i]['section'] . ' </a></td>';
                echo '<td><a href="' . $rt_result[$i]['torrent_view_link'] . '">
                    ' . $rt_result[$i]['torrent_text'] . '</a>
                     <a class="color-orange" href="'.$rt_result[$i]['torrent_link'].'"
                     title="Download torrent file">
                        <span class="glyphicon glyphicon-arrow-down cursor-pointer"></span>
                    </a>
                </td>';
                echo '<td class="font12"><a href="' . $rt->getProfilePage() . $rt_result[$i]['author_link'] . '">
                    ' . $rt_result[$i]['author'] . '</a></td>';
                echo '<td class="align-center">' . $rt_result[$i]['size'] . '</td>';

                if(strpos($rt_result[$i]['seeds'], 'days') !== false){
                    echo '<td class="col-gray align-center">' . $rt_result[$i]['seeds'] . '</td>';
                }else{
                    echo '<td class="color-green align-center">' . $rt_result[$i]['seeds'] . '</td>';
                }
                echo '<td class="color-red align-center">' . $rt_result[$i]['leeches'] . '</td>';
                echo '<td class="align-center">' . $rt_result[$i]['downloads_count'] . '</td>';
                echo '<td>' . $rt_result[$i]['added'] . '</td>';
                echo "</tr>";
            } ?>
            </tbody>
        </table>
        <?
    }else{
        echo "<p> <span class='search-word'>Rutracker</span> Nothing found</p><br>";
    }

}
?>
    </div>

</div> <!--//end of .container .main-cont-->

</body>
</html>

