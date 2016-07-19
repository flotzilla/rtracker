<?php
include_once "classes/API/RutrackerAPI.php";
include_once "classes/API/RutorAPI.php";
include "ConfigReader.php";
include_once "classes/Utils.php";

$c = new ConfigReader();
$config = $c->getConfig();
$rt = new RutrackerAPI();
$rt->parse_user_params();
$flist = $rt->getFutureList();
$response = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $c->save_param('rutracker', 'username', $_POST['rutracker-username'], false);
    $c->save_param('rutracker', 'password', $_POST['rutracker-password'], false);
    $c->save_param('search-in', 'rutracker', isset($_POST['search-in-rutracker']), false);
    $c->save_param('search-in', 'rutor', isset($_POST['search-in-rutor']), false);
    $response = $c->save_config_to_file();
    $config = $c->getConfig();
}
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
    <form action="" method="post" name="config_params">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header">Config page</h2>
            </div>
        </div>
        <?php if($response !== false):?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <?= $response ?>
                    </div>
                </div>
            </div>
        <?endif?>

        <div class="row">
            <div class="col-md-2">
                <p>Search in:</p>
                <ul class="ul-list col-md-12">
                    <li class="checkbox">
                        <label>
                            <input type="checkbox" name="search-in-rutracker"
                                <?= $config['search-in']['rutracker']? 'checked' : ''?>>
                            Rutracker
                        </label>
                    </li>
                    <li class="checkbox">
                        <label>
                            <input type="checkbox" name="search-in-rutor"
                                <?= $config['search-in']['rutor']? 'checked' : ''?>>
                            Rutor
                        </label>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <p>Rutracker params: </p>

                <div class="input-group">
                    <span class="input-group-addon" id="rutracker-username">Username</span>
                    <input type="text" class="form-control" autocomplete="off"
                           value="<?= $config['rutracker']['username'] ?>"
                           name="rutracker-username" aria-describedby="rutracker-username">
                </div>
                <br>

                <div class="input-group">
                    <span class="input-group-addon" id="rutracker-password">Password</span>
                    <input type="password" class="form-control"
                           value="<?= $config['rutracker']['password'] ?>"
                           name="rutracker-password" aria-describedby="rutracker-password">
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>
