<?php
include_once "classes/API/RutrackerAPI.php";
include_once "classes/API/RutorAPI.php";
include "ConfigReader.php";
include_once "classes/Utils.php";

$c = new ConfigReader();
$config = $c->getConfig();

$rt = new RutrackerAPI();
$rt->init_action(
    $config['rutracker']['username'],
    $config['rutracker']['password']);
$rt->parse_user_params();
$flist = $rt->getFutureList();

$rutor = new RutorAPI();

$rt_result = $rutor_search = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rtracker search
        <?
            if(isset($_GET['search'])) {
                echo " - " . trim($_GET['search']);
            }
        ?>
    </title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="bower_components/tablesorter/dist/css/theme.metro-dark.min.css">
    <link rel="stylesheet" href="bower_components/css/style.css">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bower_components/tablesorter/dist/js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="bower_components/tablesorter/dist/js/parsers/parser-metric.min.js"></script>
    <script type="text/javascript" src="bower_components/js/main.js"></script>


</head>
<body role="document">


<?php
include "page/header.php";
?>

<div class="container main-cont">

    <div class="row col-md-6 col-md-offset-3">
        <h4 class="align-center">Search</h4>
        <form class="" role="search" method="get" id="additional_search_form"
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
                  <button id="advanced_search_butt"
                      class="btn btn-default btn-sm custom-butt" type="button" data-toggle="collapse"
                          data-target="#advanced_collapse" aria-expanded="false" aria-controls="advanced_collapse">
                      Advanced params
                  </button>
              </div>
                <div class="collapse col-md-12" id="advanced_collapse">
                    <div class="well">
                        <div class="rutracker-label col-gray align-center">Rutracker</div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" id="author_name">author</span>
                            <input type="text" class="form-control" placeholder="search by author name"
                                   name="pn" aria-describedby="basic-addon1">
                        </div>

                        <br>

                        <div class="input-group input-group-sm">
                            <input type="checkbox" name="oop" id="oop"/>
                            <label class="font12" for="oop">&nbsp;Only opened</label>
                        </div>
                        <div class="input-group input-group-sm">
                            <label for="group-select">Select in groups:</label> <br>
                            <select id="group-select" multiple="multiple" name="f[]" class="form-control">
                                <?php
                                include "page/options.php";
                                ?>
                            </select>
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
    $rt_result = $rutor_search = array();
    if(strlen($search_str) == 0){
        echo '<div class="alert alert-danger" role="alert"><p>To short query</p></div>';
    }else{
        echo "<h3 class='align-center'>
                <span class='glyphicon glyphicon-search'></span>
                " . $search_str . "
            </h3>";
        $pn = "";
        $oop = 0;
        $groups = "";

        if (isset($_GET['f'])) {
            if (!is_array($_GET['f'])) {
                $groups = $_GET['f'];
            } else {
                for ($i = 0; $i < count($_GET['f']); $i++) {
                    if ($i == 0) {
                        $groups .= $_GET['f'][$i];
                    } else {
                        $groups .= "," . $_GET['f'][$i];
                    }
                }
            }

        }else{ $groups = 'f[]=-1';}

        if(isset($_GET['pn'])){ $pn = $_GET['pn'];}
        if(isset($_GET['oop'])){ $oop = $_GET['oop'];}

        $options = array(
            'f' => $groups,
            'pn' =>  mb_convert_encoding($pn, "cp1251"),
            'oop' => $oop,
            'nm' => $search_str
        );

        if($config['search-in']['rutracker']) $rt_result = $rt->search($search_str, $options);
        if($config['search-in']['rutor']) $rutor_search= $rutor->search($search_str);

    }

    if(count($rt_result) !== 0 && !array_key_exists('error', $rt_result)){
        if(array_key_exists('pages', $rt_result) && array_key_exists('search_id', $rt_result)){
            echo "<p> <span class='search-word'>Rutracker</span> Found: ". (count($rt_result) -2) . " results</p>";
        }else{
            echo "<p> <span class='search-word'>Rutracker</span> Found: ". (count($rt_result)) . " results</p>";
        }

        ?>
        <table class="table table-striped table-hover table-bordered  table-condensed tablesorter"
               id="rutracker_result">
            <thead>
            <tr class="th-middle">
                <th class="align-center" data-sortable="true">Status</th> <!--1-->
                <th class="align-center" data-sortable="true">Topic</th> <!--2-->
                <th class="align-center" data-sortable="true">Torrent</th><!--3-->
                <th class="align-center" data-sortable="true">Author</th><!--4-->
                <th class="align-center sorter-metric" data-metric-name-full="byte|Byte|BYTE"
                    data-metric-name-abbr="b|B" data-sortable="true">Size</th><!--5-->
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
                echo '<td><a href="index.php?' . $rt_result[$i]["section_link_search"] . '&search='.$search_str.'">
                       ' . $rt_result[$i]['section'] . ' </a></td>';
                echo '<td><a href="' . $rt_result[$i]['torrent_view_link'] . '" class="item-data">
                    ' . $rt_result[$i]['torrent_text'] . '</a>
                     <a class="color-orange" href="'.$rt_result[$i]['torrent_link'].'"
                     title="Download torrent file">
                        <span class="glyphicon glyphicon-arrow-down cursor-pointer"></span>
                    </a>
                    <span title="Add to future list"
                    onclick="add_single_item(this)"
                    data-action-type="add"
                    class="glyphicon glyphicon-ok-circle color-green cursor-pointer"></span>

                </td>';
                echo '<td class="font12"><a href="' . $rt->getProfilePage() . $rt_result[$i]['author_link'] . '">
                    ' . $rt_result[$i]['author'] . '</a></td>';
                echo '<td class="align-center sorter-metric" data-metric-name-full="byte|Byte|BYTE"
                    data-metric-name-abbr="b|B">' . $rt_result[$i]['size'] . '</td>';

                if(strpos($rt_result[$i]['seeds'], 'days') !== false){
                    echo '<td class="col-gray align-center">' . $rt_result[$i]['seeds'] . '</td>';
                }else{
                    echo '<td class="color-green align-center">' . $rt_result[$i]['seeds'] . '</td>';
                }
                echo '<td class="color-red align-center">' . $rt_result[$i]['leeches'] . '</td>';
                echo '<td class="align-center">' . $rt_result[$i]['downloads_count'] . '</td>';
                echo '<td class="single-line">' . $rt_result[$i]['added'] . '</td>';
                echo "</tr>";
            } ?>
            </tbody>
        </table> <br><br>
        <?
    }else{
        echo "<p> <span class='search-word'>Rutracker</span> Nothing found</p><br>";
    }

    if(count($rutor_search) !== 0 && !array_key_exists('error', $rutor_search)){
        echo "<p> <span class='search-word'>Rutor</span> Found: ". (count($rutor_search) -2) . " results</p>";
        ?>
        <table class="table table-striped table-hover table-bordered  table-condensed tablesorter"
               id="rutor_result">
            <thead>
            <tr class="th-middle">
                <th class="align-center" data-sortable="true">Added</th><!--9-->
                <th class="align-center" data-sortable="true">Torrent</th><!--3-->
                <th class="align-center sorter-metric" data-metric-name-full="byte|Byte|BYTE"
                    data-metric-name-abbr="b|B" data-sortable="true">Size</th><!--5-->
                <th class="align-center color-green" data-sortable="true">
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </th><!--6-->
                <th class="align-center color-red" data-sortable="true">
                    <span class="glyphicon glyphicon-arrow-down"></span>
                </th><!--7-->
                <th class="align-center">Link</th><!--9-->
            </tr>
            </thead>
            <tbody>
            <?
                for($i=0 ; $i<count($rutor_search); $i++){
                    if(!isset($rutor_search[$i]['torrent_text'])){
                        continue;
                    }
                    echo "<tr>";
                    echo "<td class='align-center'>" . $rutor_search[$i]['added']."</td>";
                    echo "<td><a href='" .$rutor_search[$i]['torrent_view_link'] . " '
                    class='item-data'>".
                        $rutor_search[$i]['torrent_text']."</a>"
                        .'&nbsp; <span title="Add to future list"
                        onclick="add_single_item(this)"
                        data-action-type="add"
                        class="glyphicon glyphicon-ok-circle color-green cursor-pointer"></span>
                    </td>';
                    echo "<td class='align-center'>" . $rutor_search[$i]['size']."</td>";
                    echo "<td class='align-center color-green'>" . $rutor_search[$i]['seeders']."</td>";
                    echo "<td class='align-center color-red'>" . $rutor_search[$i]['leeches']."</td>";
                    echo "<td class='align-center'><a title='Download torrent'
                    href='" . $rutor_search[$i]['torrent_link']."'>
                      <span class='glyphicon glyphicon-arrow-down cursor-pointer'></span>
                    </a></td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
            </table><br><br>

<?
    }else{
        echo "<p> <span class='search-word'>Rutor</span> Nothing found</p><br>";
    }

}

?>
    </div>

</div> <!--//end of .container .main-cont-->

</body>
</html>

