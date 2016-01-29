<?php
include_once "classes/API/RutrackerAPI.php";
include "ConfigReader.php";

$c = new ConfigReader();
$config = $c->getConfig();

$rt = new RutrackerAPI();
$rt->parse_user_params();
$flist = $rt->getFutureList();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rtracker lists</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bower_components/tablesorter/dist/css/theme.metro-dark.min.css">
    <link rel="stylesheet" href="bower_components/css/style.css">

    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
                    My list <span class="badge"><?= $rt->getFutureListSize() ?></span>
                </button>
            </a>

            <ul class="nav navbar-nav navbar-right">
                <li class="username">
                    <a title="rutracker user"
                       href="http://rutracker.org/forum/profile.php?mode=viewprofile&u=<?= $rt->getUser() ?>">
                        <?= $rt->getUser() ?></a></li>
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
    <div class="col-md-12">
        <?
        $file = $rt->read_from_file();
        if(array_key_exists('error', $file)) {
            echo "<h4>" . $file['error'] . "</h4>";
        } else {
            foreach ($file as $error) {
                Utils::preOut($error);
            }
        }


//        $save_er = $rt->save_future_list($flist);
//        if(count($save_er) > 0){
//            $errors = '';
//            foreach($save_er as $err){
//                $errors .=  "<h4>" . $err . "</h4><br>";
//            }
//        }else{
//            echo "<h4>Succesfully saved</h4>";
//        }



        ?>

        <h3 class="align-center"><span class="label label-default">From rutracker</span></h3>
        <table class="table table-striped table-hover table-bordered  table-condensed tablesorter"
               id="future_table">
            <thead>
            <tr class="th-middle">
                <th class="align-center" data-sortable="true">#</th>
                <th class="align-center" data-sortable="true">Topic</th>
                <th class="align-center" data-sortable="true">Torrent</th>
                <th class="align-center" data-sortable="true">Seeders</th>
                <th class="align-center" data-sortable="true">Leechers</th>
            </tr>
            </thead>
            <tbody>
                <?
                for ($i = 0; $i < count($flist); $i++) {
                    echo "<tr>";
                    echo '<td>' . ($i + 1 ) . '</td>';
                    echo '<td><a href="' . $flist[$i]["topic_link"] . '">
                       ' . $flist[$i]['topic_name'] . ' </a></td>';
                    echo '<td><a href="' . $flist[$i]['link'] . '">
                    ' . $flist[$i]['name'] . '</a></td>';
                    echo '<td class="color-green">' . $flist[$i]['seeds'] . '</td>';
                    echo '<td class="color-red">' . $flist[$i]['leeches'] . '</td>';
                    echo "</tr>";
                } ?>

            </tbody>
        </table>
    </div>
</div>


</body>
</html>


