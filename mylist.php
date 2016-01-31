<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL );

include_once "classes/API/RutrackerAPI.php";
include "ConfigReader.php";
include_once "classes/Utils.php";

$c = new ConfigReader();
$config = $c->getConfig();

$rt = new RutrackerAPI();
$rt->parse_user_params();
// Items from tracker
$flist = $rt->getFutureList();
// Items from saved file
//$file = $rt->read_from_file();
$file = Utils::read_from_file();

$items_from_file = array();
$new_items_counter = 0;

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
                    My list <span class="items_counter"></span>
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
    <div class="row">
        <div class="col-md-2 align-center">
            <h4>
                <span class="label label-default">Rutracker list</span>
            </h4>
        </div>
        <div class="col-md-6">
            <div id="info-block" class="alert alert-info" role="alert">
                <?if(count($file) > 1 && array_key_exists('error', $file)) {
                    echo  $file['error'];
                }else{
                    echo 'Successfully read saved torrents from bookmark file';
                    $items_from_file = $rt->compare_file_to_tracker_future_list($file, $flist);
                }
                ?>
                </div>
        </div>
        <div class="col-md-2 col-md-offset-1 button-div align-center">
            <label for="save-btn">
                Save new items to file
                <span class="items_counter"></span>
            </label>
            <button type="button" id="save-btn" class="btn btn-default" value="Save to file">
                <span class="glyphicon glyphicon glyphicon-floppy-save color-green"></span>
                Save
            </button>
        </div>
    </div>
    <div class="col-md-12">
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
                    if(array_key_exists('type', $flist[$i])){
                           if($flist[$i]['type'] == 'old'){
                               echo "<tr data-item-type='old'>";
                               echo '<td class="align-center" title="Saved in file and in tracker list">
                                        <span class=" glyphicon glyphicon-asterisk color-orange"></span>
                                    </td>';
                           }elseif($flist[$i]['type'] == 'new'){
                               echo "<tr data-item-type='new'>";
                               echo '<td class="align-center"
                                    title="New item from tracker. Recommend save to file">
                                        <span class="glyphicon glyphicon-flash color-green"></span>
                                    </td>';
                               $new_items_counter++;
                           }
                    }else{
                        echo "<tr>";
                        echo '<td> - </td>';
                    }
                    echo '<td><a href="' . $flist[$i]["topic_link"] . '">
                       ' . $flist[$i]['topic_name'] . ' </a></td>';
                    echo '<td class="data-item"><a href="' . $flist[$i]['link'] . '">
                    ' . $flist[$i]['name'] . '</a></td>';
                    echo '<td class="color-green align-center">' . $flist[$i]['seeds'] . '</td>';
                    echo '<td class="color-red align-center">' . $flist[$i]['leeches'] . '</td>';
                    echo "</tr>";
                }
                if(count($items_from_file) > 0) {
                    foreach ($items_from_file as $item) {
                        echo "<tr>";
                        echo "<td class='align-center'
                              title='Saved only in file. Probably removed from tracker list'>
                            <span class='glyphicon glyphicon-floppy-disk col-more-gray'></span>
                            </td>";
                        echo "<td class='align-center'> - </td>";
                        echo "<td><a href='".$item['link'] . "'>" . $item['name'] . "</a></td>";
                        echo "<td class='align-center cursor-pointer' title='Get seeders count'> ? </td>";
                        echo "<td class='align-center cursor-pointer' title='Get leechers count'> ? </td>";
                        echo "</tr>";
                    }
                }
                ?>

            </tbody>
        </table>
    </div>

</div>

<div id="new_items_counter" data-count="<?=$new_items_counter?>">
    <br>
    <?= $c->udate_pending_items_count($new_items_counter); ?>
</div>

</body>
</html>


