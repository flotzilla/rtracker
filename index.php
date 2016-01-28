<?php

include_once "classes/API/RutrackerAPI.php";
include "ConfigReader.php";
include "classes/Utils.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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
    <link rel="stylesheet" href="bower_components/tablesorter/dist/css/theme.metro-dark.min.css">
    <link rel="stylesheet" href="bower_components/css/style.css">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bower_components/tablesorter/dist/js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="bower_components/tablesorter/dist/js/parsers/parser-metric.min.js"></script>
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
                                <option id="fs--1" value="-1">&nbsp;Все имеющиеся</option>
                                <optgroup label="&nbsp;ОБХОД БЛОКИРОВОК">
                                    <option id="fs-1958" value="1958" >ОБХОД БЛОКИРОВКИ&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Новости">
                                    <option id="fs-1289" value="1289" >Rutracker Awards (мероприятия и конкурсы)&nbsp;</option>
                                    <option id="fs-2214" value="2214"> |- Rutracker Awards (Раздачи)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Кино, Видео и ТВ">
                                    <option id="fs-7" value="7" >Зарубежное кино&nbsp;</option>
                                    <option id="fs-187" value="187"> |- Классика зарубежного кино&nbsp;</option>
                                    <option id="fs-2090" value="2090"> |- Фильмы до 1990 года&nbsp;</option>
                                    <option id="fs-2221" value="2221"> |- Фильмы 1991-2000&nbsp;</option>
                                    <option id="fs-2091" value="2091"> |- Фильмы 2001-2005&nbsp;</option>
                                    <option id="fs-2092" value="2092"> |- Фильмы 2006-2010&nbsp;</option>
                                    <option id="fs-2093" value="2093"> |- Фильмы 2011-2015&nbsp;</option>
                                    <option id="fs-2200" value="2200"> |- Фильмы 2016&nbsp;</option>
                                    <option id="fs-934" value="934"> |- Азиатские фильмы&nbsp;</option>
                                    <option id="fs-505" value="505"> |- Индийское кино&nbsp;</option>
                                    <option id="fs-212" value="212"> |- Сборники фильмов&nbsp;</option>
                                    <option id="fs-2459" value="2459"> |- Короткий метр&nbsp;</option>
                                    <option id="fs-1235" value="1235"> |- Грайндхаус&nbsp;</option>
                                    <option id="fs-185" value="185"> |- Звуковые дорожки и Переводы&nbsp;</option>
                                    <option id="fs-22" value="22" >Наше кино&nbsp;</option>
                                    <option id="fs-376" value="376"> |- Авторские дебюты&nbsp;</option>
                                    <option id="fs-941" value="941"> |- Кино СССР&nbsp;</option>
                                    <option id="fs-1666" value="1666"> |- Детские отечественные фильмы&nbsp;</option>
                                    <option id="fs-124" value="124" >Арт-хаус и авторское кино&nbsp;</option>
                                    <option id="fs-1543" value="1543"> |- Короткий метр (Арт-хаус и авторское кино)&nbsp;</option>
                                    <option id="fs-709" value="709"> |- Документальные фильмы (Арт-хаус и авторское кино)&nbsp;</option>
                                    <option id="fs-1577" value="1577"> |- Анимация (Арт-хаус и авторское кино)&nbsp;</option>
                                    <option id="fs-511" value="511" >Театр&nbsp;</option>
                                    <option id="fs-656" value="656"> |- Бенефис. Мастера искусств отечественного Театра и Кино&nbsp;</option>
                                    <option id="fs-93" value="93" >DVD Video&nbsp;</option>
                                    <option id="fs-905" value="905"> |- Классика зарубежного кино (DVD Video)&nbsp;</option>
                                    <option id="fs-1576" value="1576"> |- Азиатские фильмы (DVD Video)&nbsp;</option>
                                    <option id="fs-101" value="101"> |- Зарубежное кино (DVD)&nbsp;</option>
                                    <option id="fs-100" value="100"> |- Наше кино (DVD)&nbsp;</option>
                                    <option id="fs-572" value="572"> |- Арт-хаус и авторское кино (DVD)&nbsp;</option>
                                    <option id="fs-2220" value="2220"> |- Индийское кино DVD и HD Video&nbsp;</option>
                                    <option id="fs-1670" value="1670"> |- Грайндхаус DVD и HD Video&nbsp;</option>
                                    <option id="fs-2198" value="2198" >HD Video&nbsp;</option>
                                    <option id="fs-2199" value="2199"> |- Классика зарубежного кино (HD Video)&nbsp;</option>
                                    <option id="fs-313" value="313"> |- Зарубежное кино (HD Video)&nbsp;</option>
                                    <option id="fs-2201" value="2201"> |- Азиатские фильмы (HD Video)&nbsp;</option>
                                    <option id="fs-312" value="312"> |- Наше кино (HD Video)&nbsp;</option>
                                    <option id="fs-2339" value="2339"> |- Арт-хаус и авторское кино (HD Video)&nbsp;</option>
                                    <option id="fs-352" value="352" >3D/Стерео Кино, Видео, TV и Спорт&nbsp;</option>
                                    <option id="fs-549" value="549"> |- 3D Кинофильмы&nbsp;</option>
                                    <option id="fs-1213" value="1213"> |- 3D Мультфильмы&nbsp;</option>
                                    <option id="fs-2109" value="2109"> |- 3D Документальные фильмы&nbsp;</option>
                                    <option id="fs-514" value="514"> |- 3D Спорт&nbsp;</option>
                                    <option id="fs-2097" value="2097"> |- 3D Ролики, Музыкальное видео, Трейлеры к фильмам&nbsp;</option>
                                    <option id="fs-4" value="4" >Мультфильмы&nbsp;</option>
                                    <option id="fs-2343" value="2343"> |- Мультфильмы (Анонсы HD Video)&nbsp;</option>
                                    <option id="fs-930" value="930"> |- Мультфильмы (HD Video)&nbsp;</option>
                                    <option id="fs-2365" value="2365"> |- Короткометражные мультфильмы (HD Video)&nbsp;</option>
                                    <option id="fs-1900" value="1900"> |- Отечественные мультфильмы (DVD)&nbsp;</option>
                                    <option id="fs-521" value="521"> |- Иностранные мультфильмы (DVD)&nbsp;</option>
                                    <option id="fs-2258" value="2258"> |- Иностранные короткометражные мультфильмы (DVD)&nbsp;</option>
                                    <option id="fs-208" value="208"> |- Отечественные мультфильмы&nbsp;</option>
                                    <option id="fs-539" value="539"> |- Отечественные полнометражные мультфильмы&nbsp;</option>
                                    <option id="fs-209" value="209"> |- Иностранные мультфильмы&nbsp;</option>
                                    <option id="fs-484" value="484"> |- Иностранные короткометражные мультфильмы&nbsp;</option>
                                    <option id="fs-822" value="822"> |- Сборники мультфильмов&nbsp;</option>
                                    <option id="fs-921" value="921" >Мультсериалы&nbsp;</option>
                                    <option id="fs-922" value="922"> |- Аватар&nbsp;</option>
                                    <option id="fs-1247" value="1247"> |- Гриффины / Family guy&nbsp;</option>
                                    <option id="fs-923" value="923"> |- Губка Боб Квадратные Штаны&nbsp;</option>
                                    <option id="fs-924" value="924"> |- Симпсоны&nbsp;</option>
                                    <option id="fs-1991" value="1991"> |- Скуби-ду / Scooby-Doo&nbsp;</option>
                                    <option id="fs-925" value="925"> |- Том и Джерри&nbsp;</option>
                                    <option id="fs-1165" value="1165"> |- Трансформеры&nbsp;</option>
                                    <option id="fs-1245" value="1245"> |- Утиные истории / Duck Tales&nbsp;</option>
                                    <option id="fs-928" value="928"> |- Футурама / Futurama&nbsp;</option>
                                    <option id="fs-926" value="926"> |- Человек-Паук / Новые приключения Человека-паука&nbsp;</option>
                                    <option id="fs-1246" value="1246"> |- Черепашки Мутанты Ниндзя / Teenage Mutant Ninja Turtles&nbsp;</option>
                                    <option id="fs-1250" value="1250"> |- Чип и Дейл / Chip And Dale&nbsp;</option>
                                    <option id="fs-927" value="927"> |- Южный Парк / South Park&nbsp;</option>
                                    <option id="fs-1248" value="1248"> |- Для некондиционных раздач&nbsp;</option>
                                    <option id="fs-33" value="33" >Аниме&nbsp;</option>
                                    <option id="fs-281" value="281"> |- Манга&nbsp;</option>
                                    <option id="fs-1386" value="1386"> |- Обои, артбуки и др.&nbsp;</option>
                                    <option id="fs-1387" value="1387"> |- AMV и др. ролики&nbsp;</option>
                                    <option id="fs-1388" value="1388"> |- OST (lossless)&nbsp;</option>
                                    <option id="fs-282" value="282"> |- OST (mp3 и другие lossy-форматы)&nbsp;</option>
                                    <option id="fs-599" value="599"> |- Аниме (DVD)&nbsp;</option>
                                    <option id="fs-1105" value="1105"> |- Аниме (HD Video)&nbsp;</option>
                                    <option id="fs-1389" value="1389"> |- Аниме (основной подраздел)&nbsp;</option>
                                    <option id="fs-1391" value="1391"> |- Аниме (плеерный подраздел)&nbsp;</option>
                                    <option id="fs-2491" value="2491"> |- Аниме (QC подраздел)&nbsp;</option>
                                    <option id="fs-404" value="404"> |- Покемоны&nbsp;</option>
                                    <option id="fs-1390" value="1390"> |- Наруто&nbsp;</option>
                                    <option id="fs-1642" value="1642"> |- Гандам&nbsp;</option>
                                    <option id="fs-893" value="893"> |- Японские мультфильмы&nbsp;</option>
                                    <option id="fs-1478" value="1478"> |- Для некондиционных раздач&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Документалистика и юмор">
                                    <option id="fs-670" value="670" >Вера и религия&nbsp;</option>
                                    <option id="fs-1475" value="1475"> |- Христианство&nbsp;</option>
                                    <option id="fs-2107" value="2107"> |- Ислам&nbsp;</option>
                                    <option id="fs-294" value="294"> |- Религии Индии, Тибета и Восточной Азии&nbsp;</option>
                                    <option id="fs-1453" value="1453"> |- Культы и новые религиозные движения&nbsp;</option>
                                    <option id="fs-46" value="46" >Документальные фильмы и телепередачи&nbsp;</option>
                                    <option id="fs-103" value="103"> |- Документальные (DVD)&nbsp;</option>
                                    <option id="fs-671" value="671"> |- Биографии. Личности и кумиры&nbsp;</option>
                                    <option id="fs-2177" value="2177"> |- Кинематограф и мультипликация&nbsp;</option>
                                    <option id="fs-2538" value="2538"> |- Искусство, история искусств&nbsp;</option>
                                    <option id="fs-2159" value="2159"> |- Музыка&nbsp;</option>
                                    <option id="fs-251" value="251"> |- Криминальная документалистика&nbsp;</option>
                                    <option id="fs-98" value="98"> |- Тайны века / Спецслужбы / Теории Заговоров&nbsp;</option>
                                    <option id="fs-97" value="97"> |- Военное дело&nbsp;</option>
                                    <option id="fs-851" value="851"> |- Вторая мировая война&nbsp;</option>
                                    <option id="fs-2178" value="2178"> |- Аварии / Катастрофы / Катаклизмы&nbsp;</option>
                                    <option id="fs-821" value="821"> |- Авиация&nbsp;</option>
                                    <option id="fs-2076" value="2076"> |- Космос&nbsp;</option>
                                    <option id="fs-56" value="56"> |- Научно-популярные фильмы&nbsp;</option>
                                    <option id="fs-2123" value="2123"> |- Флора и фауна&nbsp;</option>
                                    <option id="fs-876" value="876"> |- Путешествия и туризм&nbsp;</option>
                                    <option id="fs-2380" value="2380"> |- Социальные ток-шоу&nbsp;</option>
                                    <option id="fs-1467" value="1467"> |- Информационно-аналитические и общественно-политические п..&nbsp;</option>
                                    <option id="fs-1469" value="1469"> |- Архитектура и строительство&nbsp;</option>
                                    <option id="fs-672" value="672"> |- Всё о доме, быте и дизайне&nbsp;</option>
                                    <option id="fs-249" value="249"> |- BBC&nbsp;</option>
                                    <option id="fs-552" value="552"> |- Discovery&nbsp;</option>
                                    <option id="fs-500" value="500"> |- National Geographic&nbsp;</option>
                                    <option id="fs-2112" value="2112"> |- История: Древний мир / Античность / Средневековье&nbsp;</option>
                                    <option id="fs-1327" value="1327"> |- История: Новое и Новейшее время&nbsp;</option>
                                    <option id="fs-1468" value="1468"> |- Эпоха СССР&nbsp;</option>
                                    <option id="fs-1280" value="1280"> |- Битва экстрасенсов / Теория невероятности / Искатели / Г..&nbsp;</option>
                                    <option id="fs-752" value="752"> |- Русские сенсации / Программа Максимум / Профессия репорт..&nbsp;</option>
                                    <option id="fs-1114" value="1114"> |- Паранормальные явления&nbsp;</option>
                                    <option id="fs-2168" value="2168"> |- Альтернативная история и наука&nbsp;</option>
                                    <option id="fs-2160" value="2160"> |- Внежанровая документалистика&nbsp;</option>
                                    <option id="fs-2176" value="2176"> |- Разное / некондиция&nbsp;</option>
                                    <option id="fs-314" value="314" >Документальные (HD Video)&nbsp;</option>
                                    <option id="fs-2323" value="2323"> |- Информационно-аналитические и общественно-политические п..&nbsp;</option>
                                    <option id="fs-1278" value="1278"> |- Биографии. Личности и кумиры (HD Video)&nbsp;</option>
                                    <option id="fs-1281" value="1281"> |- Военное дело (HD Video)&nbsp;</option>
                                    <option id="fs-2110" value="2110"> |- Естествознание, наука и техника (HD Video)&nbsp;</option>
                                    <option id="fs-979" value="979"> |- Путешествия и туризм (HD Video)&nbsp;</option>
                                    <option id="fs-2169" value="2169"> |- Флора и фауна (HD Video)&nbsp;</option>
                                    <option id="fs-2166" value="2166"> |- История (HD Video)&nbsp;</option>
                                    <option id="fs-2164" value="2164"> |- BBC, Discovery, National Geographic (HD Video)&nbsp;</option>
                                    <option id="fs-2163" value="2163"> |- Криминальная документалистика (HD Video)&nbsp;</option>
                                    <option id="fs-24" value="24" >Развлекательные телепередачи и шоу, приколы и юмор&nbsp;</option>
                                    <option id="fs-1959" value="1959"> |- Интеллектуальные игры и викторины&nbsp;</option>
                                    <option id="fs-939" value="939"> |- Реалити и ток-шоу / номинации / показы&nbsp;</option>
                                    <option id="fs-1481" value="1481"> |- Детские телешоу&nbsp;</option>
                                    <option id="fs-113" value="113"> |- КВН&nbsp;</option>
                                    <option id="fs-115" value="115"> |- Пост КВН&nbsp;</option>
                                    <option id="fs-882" value="882"> |- Кривое Зеркало / Городок / В Городке&nbsp;</option>
                                    <option id="fs-1482" value="1482"> |- Ледовые шоу&nbsp;</option>
                                    <option id="fs-393" value="393"> |- Музыкальные шоу&nbsp;</option>
                                    <option id="fs-1569" value="1569"> |- Званый ужин&nbsp;</option>
                                    <option id="fs-373" value="373"> |- Хорошие Шутки&nbsp;</option>
                                    <option id="fs-1186" value="1186"> |- Вечерний Квартал&nbsp;</option>
                                    <option id="fs-137" value="137"> |- Фильмы со смешным переводом (пародии)&nbsp;</option>
                                    <option id="fs-2537" value="2537"> |- Stand-up comedy&nbsp;</option>
                                    <option id="fs-532" value="532"> |- Украинские Шоу&nbsp;</option>
                                    <option id="fs-827" value="827"> |- Танцевальные шоу, концерты, выступления&nbsp;</option>
                                    <option id="fs-1484" value="1484"> |- Цирк&nbsp;</option>
                                    <option id="fs-1485" value="1485"> |- Школа злословия&nbsp;</option>
                                    <option id="fs-114" value="114"> |- Сатирики и юмористы&nbsp;</option>
                                    <option id="fs-1332" value="1332"> |- Юмористические аудиопередачи&nbsp;</option>
                                    <option id="fs-1495" value="1495"> |- Аудио и видео ролики (Приколы и юмор)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Спорт">
                                    <option id="fs-255" value="255" >Спортивные турниры, фильмы и передачи&nbsp;</option>
                                    <option id="fs-256" value="256"> |- Автоспорт&nbsp;</option>
                                    <option id="fs-1986" value="1986"> |- Мотоспорт&nbsp;</option>
                                    <option id="fs-660" value="660"> |- Формула-1 2015&nbsp;</option>
                                    <option id="fs-1551" value="1551"> |- Формула-1 2012-2014&nbsp;</option>
                                    <option id="fs-626" value="626"> |- Формула 1&nbsp;</option>
                                    <option id="fs-262" value="262"> |- Велоспорт&nbsp;</option>
                                    <option id="fs-1326" value="1326"> |- Волейбол/Гандбол&nbsp;</option>
                                    <option id="fs-978" value="978"> |- Бильярд&nbsp;</option>
                                    <option id="fs-1287" value="1287"> |- Покер&nbsp;</option>
                                    <option id="fs-1188" value="1188"> |- Бодибилдинг/Силовые виды спорта&nbsp;</option>
                                    <option id="fs-1667" value="1667"> |- Бокс&nbsp;</option>
                                    <option id="fs-1675" value="1675"> |- Классические единоборства&nbsp;</option>
                                    <option id="fs-257" value="257"> |- Смешанные единоборства и K-1&nbsp;</option>
                                    <option id="fs-875" value="875"> |- Американский футбол&nbsp;</option>
                                    <option id="fs-263" value="263"> |- Регби&nbsp;</option>
                                    <option id="fs-2073" value="2073"> |- Бейсбол&nbsp;</option>
                                    <option id="fs-550" value="550"> |- Теннис&nbsp;</option>
                                    <option id="fs-2124" value="2124"> |- Бадминтон/Настольный теннис&nbsp;</option>
                                    <option id="fs-1470" value="1470"> |- Гимнастика/Соревнования по танцам&nbsp;</option>
                                    <option id="fs-528" value="528"> |- Лёгкая атлетика/Водные виды спорта&nbsp;</option>
                                    <option id="fs-486" value="486"> |- Зимние виды спорта&nbsp;</option>
                                    <option id="fs-854" value="854"> |- Фигурное катание&nbsp;</option>
                                    <option id="fs-2079" value="2079"> |- Биатлон&nbsp;</option>
                                    <option id="fs-260" value="260"> |- Экстрим&nbsp;</option>
                                    <option id="fs-1608" value="1608" >Футбол&nbsp;</option>
                                    <option id="fs-2075" value="2075"> |- Россия 2015-2016&nbsp;</option>
                                    <option id="fs-1952" value="1952"> |- Россия 2014-2015&nbsp;</option>
                                    <option id="fs-1613" value="1613"> |- Россия/СССР&nbsp;</option>
                                    <option id="fs-1614" value="1614"> |- Англия&nbsp;</option>
                                    <option id="fs-1623" value="1623"> |- Испания&nbsp;</option>
                                    <option id="fs-1615" value="1615"> |- Италия&nbsp;</option>
                                    <option id="fs-1630" value="1630"> |- Германия&nbsp;</option>
                                    <option id="fs-2425" value="2425"> |- Франция&nbsp;</option>
                                    <option id="fs-2514" value="2514"> |- Украина&nbsp;</option>
                                    <option id="fs-1616" value="1616"> |- Другие национальные чемпионаты и кубки&nbsp;</option>
                                    <option id="fs-2014" value="2014"> |- Международные турниры&nbsp;</option>
                                    <option id="fs-1491" value="1491"> |- Еврокубки 2015-2016&nbsp;</option>
                                    <option id="fs-2171" value="2171"> |- Еврокубки 2014-2015&nbsp;</option>
                                    <option id="fs-1987" value="1987"> |- Еврокубки 2011-2014&nbsp;</option>
                                    <option id="fs-1617" value="1617"> |- Еврокубки&nbsp;</option>
                                    <option id="fs-1610" value="1610"> |- Чемпионат Европы 2016&nbsp;</option>
                                    <option id="fs-1620" value="1620"> |- Чемпионаты Европы&nbsp;</option>
                                    <option id="fs-1668" value="1668"> |- Чемпионат Мира 2018&nbsp;</option>
                                    <option id="fs-1621" value="1621"> |- Чемпионаты Мира&nbsp;</option>
                                    <option id="fs-1998" value="1998"> |- Товарищеские турниры и матчи&nbsp;</option>
                                    <option id="fs-1343" value="1343"> |- Обзорные и аналитические передачи 2014-2016&nbsp;</option>
                                    <option id="fs-751" value="751"> |- Обзорные и аналитические передачи&nbsp;</option>
                                    <option id="fs-1697" value="1697"> |- Мини-футбол/Пляжный футбол&nbsp;</option>
                                    <option id="fs-2004" value="2004" >Баскетбол&nbsp;</option>
                                    <option id="fs-2001" value="2001"> |- Международные соревнования&nbsp;</option>
                                    <option id="fs-2002" value="2002"> |- NBA / NCAA (до 2000 г.)&nbsp;</option>
                                    <option id="fs-283" value="283"> |- NBA / NCAA (2000-2010 гг.)&nbsp;</option>
                                    <option id="fs-1997" value="1997"> |- NBA / NCAA (2010-2016 гг.)&nbsp;</option>
                                    <option id="fs-2003" value="2003"> |- Европейский клубный баскетбол&nbsp;</option>
                                    <option id="fs-2009" value="2009" >Хоккей&nbsp;</option>
                                    <option id="fs-2010" value="2010"> |- Хоккей с мячом / Бенди&nbsp;</option>
                                    <option id="fs-2006" value="2006"> |- Международные турниры&nbsp;</option>
                                    <option id="fs-2007" value="2007"> |- КXЛ&nbsp;</option>
                                    <option id="fs-2005" value="2005"> |- НХЛ (до 2011/12)&nbsp;</option>
                                    <option id="fs-259" value="259"> |- НХЛ (с 2013)&nbsp;</option>
                                    <option id="fs-2008" value="2008"> |- СССР - Канада&nbsp;</option>
                                    <option id="fs-126" value="126"> |- Документальные фильмы и аналитика&nbsp;</option>
                                    <option id="fs-845" value="845" >Рестлинг&nbsp;</option>
                                    <option id="fs-343" value="343"> |- Professional Wrestling&nbsp;</option>
                                    <option id="fs-2111" value="2111"> |- Independent Wrestling&nbsp;</option>
                                    <option id="fs-1527" value="1527"> |- International Wrestling&nbsp;</option>
                                    <option id="fs-2069" value="2069"> |- Oldschool Wrestling&nbsp;</option>
                                    <option id="fs-1323" value="1323"> |- Documentary Wrestling&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Сериалы">
                                    <option id="fs-9" value="9" >Русские сериалы&nbsp;</option>
                                    <option id="fs-104" value="104"> |- Автономка / Врачебная Тайна / Зона. Тюремный роман&nbsp;</option>
                                    <option id="fs-1408" value="1408"> |- Агент национальной безопасности&nbsp;</option>
                                    <option id="fs-1535" value="1535"> |- Адвокат&nbsp;</option>
                                    <option id="fs-91" value="91"> |- Бандитский Петербург&nbsp;</option>
                                    <option id="fs-1356" value="1356"> |- Возвращение Мухтара&nbsp;</option>
                                    <option id="fs-990" value="990"> |- Гончие&nbsp;</option>
                                    <option id="fs-856" value="856"> |- Глухарь / Пятницкий / Карпов&nbsp;</option>
                                    <option id="fs-188" value="188"> |- Дарья Донцова&nbsp;</option>
                                    <option id="fs-310" value="310"> |- Кадетство / Кремлёвские курсанты&nbsp;</option>
                                    <option id="fs-202" value="202"> |- Каменская&nbsp;</option>
                                    <option id="fs-935" value="935"> |- Кодекс чести&nbsp;</option>
                                    <option id="fs-172" value="172"> |- Мент в законе&nbsp;</option>
                                    <option id="fs-805" value="805"> |- Ментовские войны&nbsp;</option>
                                    <option id="fs-80" value="80"> |- Моя прекрасная няня&nbsp;</option>
                                    <option id="fs-119" value="119"> |- Осторожно, Модерн!&nbsp;</option>
                                    <option id="fs-812" value="812"> |- Паутина&nbsp;</option>
                                    <option id="fs-175" value="175"> |- След&nbsp;</option>
                                    <option id="fs-79" value="79"> |- Солдаты и пр.&nbsp;</option>
                                    <option id="fs-123" value="123"> |- Убойная сила / Менты / Опера&nbsp;</option>
                                    <option id="fs-189" value="189" >Зарубежные сериалы&nbsp;</option>
                                    <option id="fs-271" value="271"> |- 24 часа / 24&nbsp;</option>
                                    <option id="fs-273" value="273"> |- Альф / ALF&nbsp;</option>
                                    <option id="fs-743" value="743"> |- Анатомия Грей / Grey&#039;s Anatomy; Частная Практика / Priva..&nbsp;</option>
                                    <option id="fs-184" value="184"> |- Баффи - Истребительница Вампиров / Buffy; Ангел / Angel&nbsp;</option>
                                    <option id="fs-842" value="842"> |- Беверли Хиллз 90210 / Beverly Hills 90210&nbsp;</option>
                                    <option id="fs-194" value="194"> |- Блудливая Калифорния / Californication&nbsp;</option>
                                    <option id="fs-85" value="85"> |- Вавилон 5 / Babylon 5&nbsp;</option>
                                    <option id="fs-1417" value="1417"> |- Во все тяжкие / Breaking Bad&nbsp;</option>
                                    <option id="fs-1144" value="1144"> |- Возвращение Шерлока Холмса / Return of Sherlock Holmes&nbsp;</option>
                                    <option id="fs-595" value="595"> |- Герои / Heroes&nbsp;</option>
                                    <option id="fs-1288" value="1288"> |- Декстер / Dexter&nbsp;</option>
                                    <option id="fs-1605" value="1605"> |- Два с половиной человека / Two and a Half Men&nbsp;</option>
                                    <option id="fs-1171" value="1171"> |- Дживс и Вустер / Jeeves and Wooster ; Шоу Фрая и Лори / ..&nbsp;</option>
                                    <option id="fs-1694" value="1694"> |- Династия / Dynasty&nbsp;</option>
                                    <option id="fs-1690" value="1690"> |- Дневники вампира / The Vampire Diaries; Настоящая кровь ..&nbsp;</option>
                                    <option id="fs-820" value="820"> |- Доктор Кто / Doctor Who; Торчвуд / Torchwood&nbsp;</option>
                                    <option id="fs-819" value="819"> |- Доктор Куин - женщина врач / Dr. Quinn, Medicine Woman&nbsp;</option>
                                    <option id="fs-625" value="625"> |- Доктор Хаус / House M.D.&nbsp;</option>
                                    <option id="fs-84" value="84"> |- Друзья / Friends + Джоуи / Joey&nbsp;</option>
                                    <option id="fs-242" value="242"> |- Женаты... с детьми / Married... with Children&nbsp;</option>
                                    <option id="fs-623" value="623"> |- За Гранью / Fringe&nbsp;</option>
                                    <option id="fs-1798" value="1798"> |- Звёздные Врата: Атлантида / Stargate: Atlantis&nbsp;</option>
                                    <option id="fs-106" value="106"> |- Звёздные Врата: СГ1 / Stargate: SG1&nbsp;</option>
                                    <option id="fs-166" value="166"> |- Звёздный крейсер Галактика / Battlestar Galactica; Капри..&nbsp;</option>
                                    <option id="fs-236" value="236"> |- Звёздный путь / Star Trek&nbsp;</option>
                                    <option id="fs-1449" value="1449"> |- Игра престолов / Game of Thrones&nbsp;</option>
                                    <option id="fs-507" value="507"> |- Как Я Встретил Вашу Маму ; Теория Большого Взрыва&nbsp;</option>
                                    <option id="fs-504" value="504"> |- Клан Сопрано / The Sopranos&nbsp;</option>
                                    <option id="fs-536" value="536"> |- Клиника / Scrubs&nbsp;</option>
                                    <option id="fs-173" value="173"> |- Коломбо / Columbo&nbsp;</option>
                                    <option id="fs-918" value="918"> |- Комиссар Рекс / Komissar Rex&nbsp;</option>
                                    <option id="fs-920" value="920"> |- Кости / Bones&nbsp;</option>
                                    <option id="fs-203" value="203"> |- Косяки / Weeds&nbsp;</option>
                                    <option id="fs-1243" value="1243"> |- Крутой Уокер. Правосудие по-техасски / Walker, Texas Ran..&nbsp;</option>
                                    <option id="fs-1120" value="1120"> |- Любовь и тайны Сансет Бич / Sunset Beach&nbsp;</option>
                                    <option id="fs-140" value="140"> |- Мастера Ужасов / Masters of Horror&nbsp;</option>
                                    <option id="fs-636" value="636"> |- Менталист / The Mentalist; Касл / Castle&nbsp;</option>
                                    <option id="fs-606" value="606"> |- Место преступления / CSI: Crime Scene Investigation&nbsp;</option>
                                    <option id="fs-776" value="776"> |- Мисс Марпл / Miss Marple&nbsp;</option>
                                    <option id="fs-181" value="181"> |- Морская полиция: Спецотдел; Лос-Анджелес; Новый Орлеан&nbsp;</option>
                                    <option id="fs-235" value="235"> |- На краю вселенной / FarScape&nbsp;</option>
                                    <option id="fs-1499" value="1499"> |- Она написала убийство / Murder, She Wrote; Перри Мейсон ..&nbsp;</option>
                                    <option id="fs-81" value="81"> |- Остаться в Живых / LOST&nbsp;</option>
                                    <option id="fs-266" value="266"> |- Отчаянные домохозяйки / Desperate Housewives&nbsp;</option>
                                    <option id="fs-252" value="252"> |- Побег из тюрьмы / Prison Break&nbsp;</option>
                                    <option id="fs-1102" value="1102"> |- Полиция Майами: Отдел нравов / Miami Vice&nbsp;</option>
                                    <option id="fs-196" value="196"> |- Санта Барбара / Santa Barbara&nbsp;</option>
                                    <option id="fs-372" value="372"> |- Сверхъестественное / Supernatural&nbsp;</option>
                                    <option id="fs-110" value="110"> |- Секретные материалы / The X-Files&nbsp;</option>
                                    <option id="fs-193" value="193"> |- Секс в большом городе / Sex And The City&nbsp;</option>
                                    <option id="fs-1531" value="1531"> |- Секс в другом городе / The L word; Близкие друзья / Quee..&nbsp;</option>
                                    <option id="fs-237" value="237"> |- Скользящие / Sliders&nbsp;</option>
                                    <option id="fs-265" value="265"> |- Скорая помощь / ER&nbsp;</option>
                                    <option id="fs-1214" value="1214"> |- Тайны Ниро Вульфа / A Nero Wolfe Mystery&nbsp;</option>
                                    <option id="fs-497" value="497"> |- Тайны Смолвиля / Smallville&nbsp;</option>
                                    <option id="fs-121" value="121"> |- Твин пикс / Twin Peaks&nbsp;</option>
                                    <option id="fs-721" value="721"> |- Теория Лжи / Lie To Me&nbsp;</option>
                                    <option id="fs-1117" value="1117"> |- Холм одного дерева / One Tree Hill&nbsp;</option>
                                    <option id="fs-1359" value="1359"> |- Части Тела / Nip Tuck&nbsp;</option>
                                    <option id="fs-387" value="387"> |- Элен и ребята / Helene et les garcons&nbsp;</option>
                                    <option id="fs-134" value="134"> |- Эркюль Пуаро / Hercule Poirot&nbsp;</option>
                                    <option id="fs-195" value="195"> |- Для некондиционных раздач&nbsp;</option>
                                    <option id="fs-2366" value="2366" >Зарубежные сериалы (HD Video)&nbsp;</option>
                                    <option id="fs-2401" value="2401"> |- Блудливая Калифорния / Californication (HD Video)&nbsp;</option>
                                    <option id="fs-2390" value="2390"> |- Два с половиной человека / Two and a Half Men (HD Video)&nbsp;</option>
                                    <option id="fs-2391" value="2391"> |- Декстер / Dexter (HD Video)&nbsp;</option>
                                    <option id="fs-2392" value="2392"> |- Друзья / Friends (HD Video)&nbsp;</option>
                                    <option id="fs-2407" value="2407"> |- Доктор Кто / Doctor Who; Торчвуд / Torchwood (HD Video)&nbsp;</option>
                                    <option id="fs-2393" value="2393"> |- Доктор Хаус / House M.D. (HD Video)&nbsp;</option>
                                    <option id="fs-2370" value="2370"> |- За Гранью / Fringe (HD Video)&nbsp;</option>
                                    <option id="fs-2394" value="2394"> |- Звёздные Врата: СГ1 ; Атлантида ; Вселенная  (HD Video)&nbsp;</option>
                                    <option id="fs-2408" value="2408"> |- Звёздный крейсер Галактика / Battlestar Galactica; Капри..&nbsp;</option>
                                    <option id="fs-2395" value="2395"> |- Звёздный путь / Star Trek (HD Video)&nbsp;</option>
                                    <option id="fs-2396" value="2396"> |- Как Я Встретил Вашу Маму ; Теория Большого Взрыва (HD Vi..&nbsp;</option>
                                    <option id="fs-2397" value="2397"> |- Кости / Bones (HD Video)&nbsp;</option>
                                    <option id="fs-2398" value="2398"> |- Косяки / Weeds (HD Video)&nbsp;</option>
                                    <option id="fs-2399" value="2399"> |- Менталист / The Mentalist; Касл / Castle (HD Video)&nbsp;</option>
                                    <option id="fs-2400" value="2400"> |- Место преступления / CSI: Crime Scene Investigation (HD ..&nbsp;</option>
                                    <option id="fs-2402" value="2402"> |- Остаться в Живых / LOST (HD Video)&nbsp;</option>
                                    <option id="fs-2403" value="2403"> |- Побег из тюрьмы / Prison Break (HD Video)&nbsp;</option>
                                    <option id="fs-2404" value="2404"> |- Сверхъестественное / Supernatural (HD Video)&nbsp;</option>
                                    <option id="fs-2405" value="2405"> |- Спартак : Кровь и песок ; Боги арены ; Месть ; Война про..&nbsp;</option>
                                    <option id="fs-2406" value="2406"> |- Тайны Смолвиля / Smallville (HD Video)&nbsp;</option>
                                    <option id="fs-911" value="911" >Сериалы Испании, Италии, Латинской Америки, Турции и Индии&nbsp;</option>
                                    <option id="fs-1493" value="1493"> |- Актёры и актрисы латиноамериканских сериалов&nbsp;</option>
                                    <option id="fs-1301" value="1301"> |- Индийские сериалы&nbsp;</option>
                                    <option id="fs-1691" value="1691"> |- Испанские сериалы&nbsp;</option>
                                    <option id="fs-860" value="860"> |- Итальянские сериалы&nbsp;</option>
                                    <option id="fs-704" value="704"> |- Турецкие сериалы&nbsp;</option>
                                    <option id="fs-1940" value="1940"> |- Официальные краткие версии Латиноамериканских сериалов&nbsp;</option>
                                    <option id="fs-1574" value="1574"> |- Латиноамериканские сериалы с озвучкой (раздачи папками)&nbsp;</option>
                                    <option id="fs-1539" value="1539"> |- Латиноамериканские сериалы с субтитрами&nbsp;</option>
                                    <option id="fs-1500" value="1500"> |- OST&nbsp;</option>
                                    <option id="fs-823" value="823"> |- Богатые тоже плачут / Los Ricos Tambien Lloran&nbsp;</option>
                                    <option id="fs-1006" value="1006"> |- Вдова бланко / La Viuda de Blanco&nbsp;</option>
                                    <option id="fs-877" value="877"> |- Великолепный век / Muhtesem Yuzyil&nbsp;</option>
                                    <option id="fs-972" value="972"> |- Во имя любви / Por Amor&nbsp;</option>
                                    <option id="fs-781" value="781"> |- Девушка по имени Судьба / Milagros&nbsp;</option>
                                    <option id="fs-1300" value="1300"> |- Дикий ангел / Muneca Brava&nbsp;</option>
                                    <option id="fs-1803" value="1803"> |- Донья Барбара / Dona Barbara&nbsp;</option>
                                    <option id="fs-1298" value="1298"> |- Дороги Индии / Caminho das &#205;ndias&nbsp;</option>
                                    <option id="fs-825" value="825"> |- Дурнушка Бетти / Yo Soy Betty la Fea&nbsp;</option>
                                    <option id="fs-1606" value="1606"> |- Жена Иуды (Вино любви) / La Mujer de Judas&nbsp;</option>
                                    <option id="fs-1458" value="1458"> |- Жестокий ангел / Anjo Mau&nbsp;</option>
                                    <option id="fs-1463" value="1463"> |- Замарашка / Cara Sucia&nbsp;</option>
                                    <option id="fs-1459" value="1459"> |- История Золушки (Красивая Неудачница) / Bella Calamidade..&nbsp;</option>
                                    <option id="fs-1461" value="1461"> |- Качорра / Kachorra&nbsp;</option>
                                    <option id="fs-718" value="718"> |- Клон / O Clone&nbsp;</option>
                                    <option id="fs-1498" value="1498"> |- Клятва / El Juramento&nbsp;</option>
                                    <option id="fs-907" value="907"> |- Лалола / Lalola&nbsp;</option>
                                    <option id="fs-992" value="992"> |- Морена Клара / Morena Clara&nbsp;</option>
                                    <option id="fs-607" value="607"> |- Моя вторая мама / Mi segunda Madre&nbsp;</option>
                                    <option id="fs-594" value="594"> |- Мятежный дух / Rebelde Way&nbsp;</option>
                                    <option id="fs-775" value="775"> |- Наследница / La Heredera&nbsp;</option>
                                    <option id="fs-534" value="534"> |- Никто, кроме тебя / Tu o Nadie&nbsp;</option>
                                    <option id="fs-1462" value="1462"> |- Падре Корахе / Padre Coraje&nbsp;</option>
                                    <option id="fs-1678" value="1678"> |- Падший ангел / Mas Sabe el Diablo&nbsp;</option>
                                    <option id="fs-904" value="904"> |- Предательство / La Traicion&nbsp;</option>
                                    <option id="fs-1460" value="1460"> |- Призрак Элены / El Fantasma de Elena&nbsp;</option>
                                    <option id="fs-816" value="816"> |- Прожить жизнь / Viver a vida&nbsp;</option>
                                    <option id="fs-815" value="815"> |- Просто Мария / Simplemente Maria&nbsp;</option>
                                    <option id="fs-325" value="325"> |- Рабыня Изаура / Escrava Isaura&nbsp;</option>
                                    <option id="fs-1457" value="1457"> |- Реванш 2000 / Revanch 2000&nbsp;</option>
                                    <option id="fs-1692" value="1692"> |- Семейные узы / Lacos de Familia&nbsp;</option>
                                    <option id="fs-1540" value="1540"> |- Совершенная красота / Beleza pura&nbsp;</option>
                                    <option id="fs-1299" value="1299"> |- Спрут / La Piovra&nbsp;</option>
                                    <option id="fs-694" value="694"> |- Тайны любви / Los Misterios del Amor&nbsp;</option>
                                    <option id="fs-1949" value="1949"> |- Фаворитка / A Favorita&nbsp;</option>
                                    <option id="fs-1541" value="1541"> |- Цыганская кровь / Soy gitano&nbsp;</option>
                                    <option id="fs-1941" value="1941"> |- Шторм / La Tormenta&nbsp;</option>
                                    <option id="fs-1537" value="1537"> |- Для некондиционных раздач&nbsp;</option>
                                    <option id="fs-2100" value="2100" >Азиатские сериалы&nbsp;</option>
                                    <option id="fs-717" value="717"> |- Китайские сериалы с субтитрами&nbsp;</option>
                                    <option id="fs-915" value="915"> |- Корейские сериалы с озвучкой&nbsp;</option>
                                    <option id="fs-1242" value="1242"> |- Корейские сериалы с субтитрами&nbsp;</option>
                                    <option id="fs-2412" value="2412"> |- Прочие азиатские сериалы с озвучкой&nbsp;</option>
                                    <option id="fs-1938" value="1938"> |- Тайваньские сериалы с субтитрами&nbsp;</option>
                                    <option id="fs-2104" value="2104"> |- Японские сериалы с субтитрами&nbsp;</option>
                                    <option id="fs-1939" value="1939"> |- Японские сериалы с озвучкой&nbsp;</option>
                                    <option id="fs-2102" value="2102"> |- VMV и др. ролики&nbsp;</option>
                                    <option id="fs-2103" value="2103"> |- OST&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Книги и журналы">
                                    <option id="fs-1411" value="1411"> |- Сканирование, обработка сканов&nbsp;</option>
                                    <option id="fs-21" value="21" >Книги&nbsp;</option>
                                    <option id="fs-2157" value="2157"> |- Кино, театр, ТВ, мультипликация&nbsp;</option>
                                    <option id="fs-765" value="765"> |- Рисунок, графический дизайн&nbsp;</option>
                                    <option id="fs-2019" value="2019"> |- Фото и видеосъемка&nbsp;</option>
                                    <option id="fs-31" value="31"> |- Журналы и газеты (общий раздел)&nbsp;</option>
                                    <option id="fs-1427" value="1427"> |- Эзотерика, гадания, магия, фен-шуй&nbsp;</option>
                                    <option id="fs-2422" value="2422"> |- Астрология&nbsp;</option>
                                    <option id="fs-2195" value="2195"> |- Для женщин&nbsp;</option>
                                    <option id="fs-2223" value="2223"> |- Путешествия и туризм&nbsp;</option>
                                    <option id="fs-2447" value="2447"> |- Знаменитости и кумиры&nbsp;</option>
                                    <option id="fs-39" value="39"> |- Разное&nbsp;</option>
                                    <option id="fs-1101" value="1101" >Для детей, родителей и учителей&nbsp;</option>
                                    <option id="fs-745" value="745"> |- Учебная литература для детского сада и начальной школы (..&nbsp;</option>
                                    <option id="fs-1689" value="1689"> |- Учебная литература для старших классов (5-11 класс)&nbsp;</option>
                                    <option id="fs-2336" value="2336"> |- Учителям и педагогам&nbsp;</option>
                                    <option id="fs-2337" value="2337"> |- Научно-популярная и познавательная литература (для детей..&nbsp;</option>
                                    <option id="fs-1353" value="1353"> |- Досуг и творчество&nbsp;</option>
                                    <option id="fs-1400" value="1400"> |- Воспитание и развитие&nbsp;</option>
                                    <option id="fs-1415" value="1415"> |- Худ. лит-ра для дошкольников и младших классов&nbsp;</option>
                                    <option id="fs-2046" value="2046"> |- Худ. лит-ра для средних и старших классов&nbsp;</option>
                                    <option id="fs-1802" value="1802" >Спорт, физическая культура, боевые искусства&nbsp;</option>
                                    <option id="fs-2189" value="2189"> |- Футбол&nbsp;</option>
                                    <option id="fs-2190" value="2190"> |- Хоккей&nbsp;</option>
                                    <option id="fs-2443" value="2443"> |- Игровые виды спорта&nbsp;</option>
                                    <option id="fs-1477" value="1477"> |- Легкая атлетика. Плавание. Гимнастика. Тяжелая атлетика...&nbsp;</option>
                                    <option id="fs-669" value="669"> |- Автоспорт. Мотоспорт. Велоспорт&nbsp;</option>
                                    <option id="fs-2196" value="2196"> |- Шахматы. Шашки&nbsp;</option>
                                    <option id="fs-2056" value="2056"> |- Боевые искусства, единоборства&nbsp;</option>
                                    <option id="fs-1436" value="1436"> |- Экстрим&nbsp;</option>
                                    <option id="fs-2191" value="2191"> |- Физкультура, фитнес, бодибилдинг&nbsp;</option>
                                    <option id="fs-2477" value="2477"> |- Спортивная пресса&nbsp;</option>
                                    <option id="fs-1680" value="1680" >Гуманитарные науки&nbsp;</option>
                                    <option id="fs-1684" value="1684"> |- Искусствоведение. Культурология&nbsp;</option>
                                    <option id="fs-2446" value="2446"> |- Фольклор. Эпос. Мифология&nbsp;</option>
                                    <option id="fs-2524" value="2524"> |- Литературоведение&nbsp;</option>
                                    <option id="fs-2525" value="2525"> |- Лингвистика&nbsp;</option>
                                    <option id="fs-995" value="995"> |- Философия&nbsp;</option>
                                    <option id="fs-2022" value="2022"> |- Политология&nbsp;</option>
                                    <option id="fs-2471" value="2471"> |- Социология&nbsp;</option>
                                    <option id="fs-2375" value="2375"> |- Публицистика, журналистика&nbsp;</option>
                                    <option id="fs-764" value="764"> |- Бизнес, менеджмент&nbsp;</option>
                                    <option id="fs-1685" value="1685"> |- Маркетинг&nbsp;</option>
                                    <option id="fs-1688" value="1688"> |- Экономика&nbsp;</option>
                                    <option id="fs-2472" value="2472"> |- Финансы&nbsp;</option>
                                    <option id="fs-1687" value="1687"> |- Юридические науки. Право. Криминалистика&nbsp;</option>
                                    <option id="fs-2020" value="2020" >Исторические науки&nbsp;</option>
                                    <option id="fs-1349" value="1349"> |- Методология и философия истории&nbsp;</option>
                                    <option id="fs-1967" value="1967"> |- Исторические источники&nbsp;</option>
                                    <option id="fs-2049" value="2049"> |- Исторические персоны&nbsp;</option>
                                    <option id="fs-1681" value="1681"> |- Альтернативные исторические теории&nbsp;</option>
                                    <option id="fs-2319" value="2319"> |- Археология&nbsp;</option>
                                    <option id="fs-2434" value="2434"> |- Древний мир. Античность&nbsp;</option>
                                    <option id="fs-1683" value="1683"> |- Средние века&nbsp;</option>
                                    <option id="fs-2444" value="2444"> |- История Нового и Новейшего времени&nbsp;</option>
                                    <option id="fs-2427" value="2427"> |- История Европы&nbsp;</option>
                                    <option id="fs-2452" value="2452"> |- История Азии и Африки&nbsp;</option>
                                    <option id="fs-2445" value="2445"> |- История Америки, Австралии, Океании&nbsp;</option>
                                    <option id="fs-2435" value="2435"> |- История России&nbsp;</option>
                                    <option id="fs-2436" value="2436"> |- Эпоха СССР&nbsp;</option>
                                    <option id="fs-2453" value="2453"> |- История стран бывшего СССР&nbsp;</option>
                                    <option id="fs-2320" value="2320"> |- Этнография, антропология&nbsp;</option>
                                    <option id="fs-1801" value="1801"> |- Международные отношения. Дипломатия&nbsp;</option>
                                    <option id="fs-2023" value="2023" >Точные, естественные и инженерные науки&nbsp;</option>
                                    <option id="fs-2024" value="2024"> |- Авиация / космонавтика&nbsp;</option>
                                    <option id="fs-2026" value="2026"> |- Физика&nbsp;</option>
                                    <option id="fs-2192" value="2192"> |- Астрономия&nbsp;</option>
                                    <option id="fs-2027" value="2027"> |- Биология / Экология&nbsp;</option>
                                    <option id="fs-295" value="295"> |- Химия / Биохимия&nbsp;</option>
                                    <option id="fs-2028" value="2028"> |- Математика&nbsp;</option>
                                    <option id="fs-2029" value="2029"> |- География / Геология / Геодезия&nbsp;</option>
                                    <option id="fs-1325" value="1325"> |- Электроника / Радио&nbsp;</option>
                                    <option id="fs-2386" value="2386"> |- Схемы и сервис-мануалы (оригинальная документация)&nbsp;</option>
                                    <option id="fs-2031" value="2031"> |- Архитектура / Строительство / Инженерные сети&nbsp;</option>
                                    <option id="fs-2030" value="2030"> |- Машиностроение&nbsp;</option>
                                    <option id="fs-2526" value="2526"> |- Сварка / Пайка / Неразрушающий контроль&nbsp;</option>
                                    <option id="fs-2527" value="2527"> |- Автоматизация / Робототехника&nbsp;</option>
                                    <option id="fs-2254" value="2254"> |- Металлургия / Материаловедение&nbsp;</option>
                                    <option id="fs-2376" value="2376"> |- Механика, сопротивление материалов&nbsp;</option>
                                    <option id="fs-2054" value="2054"> |- Энергетика / электротехника&nbsp;</option>
                                    <option id="fs-770" value="770"> |- Нефтяная, газовая и химическая промышленность&nbsp;</option>
                                    <option id="fs-2476" value="2476"> |- Сельское хозяйство и пищевая промышленность&nbsp;</option>
                                    <option id="fs-2494" value="2494"> |- Железнодорожное дело&nbsp;</option>
                                    <option id="fs-1528" value="1528"> |- Нормативная документация&nbsp;</option>
                                    <option id="fs-2032" value="2032"> |- Журналы: научные, научно-популярные, радио и др.&nbsp;</option>
                                    <option id="fs-768" value="768" >Военное дело&nbsp;</option>
                                    <option id="fs-2099" value="2099"> |- Милитария&nbsp;</option>
                                    <option id="fs-2021" value="2021"> |- Военная история&nbsp;</option>
                                    <option id="fs-2437" value="2437"> |- История Второй мировой войны&nbsp;</option>
                                    <option id="fs-1447" value="1447"> |- Военная техника&nbsp;</option>
                                    <option id="fs-2468" value="2468"> |- Стрелковое оружие&nbsp;</option>
                                    <option id="fs-2469" value="2469"> |- Учебно-методическая литература&nbsp;</option>
                                    <option id="fs-2470" value="2470"> |- Спецслужбы мира&nbsp;</option>
                                    <option id="fs-1686" value="1686" >Вера и религия&nbsp;</option>
                                    <option id="fs-2215" value="2215"> |- Христианство&nbsp;</option>
                                    <option id="fs-2216" value="2216"> |- Ислам&nbsp;</option>
                                    <option id="fs-2217" value="2217"> |- Религии Индии, Тибета и Восточной Азии / Иудаизм&nbsp;</option>
                                    <option id="fs-2218" value="2218"> |- Нетрадиционные религиозные, духовные и мистические учени..&nbsp;</option>
                                    <option id="fs-2252" value="2252"> |- Религиоведение. История Религии. Атеизм&nbsp;</option>
                                    <option id="fs-767" value="767" >Психология&nbsp;</option>
                                    <option id="fs-2515" value="2515"> |- Общая и прикладная психология&nbsp;</option>
                                    <option id="fs-2516" value="2516"> |- Психотерапия и консультирование&nbsp;</option>
                                    <option id="fs-2517" value="2517"> |- Психодиагностика и психокоррекция&nbsp;</option>
                                    <option id="fs-2518" value="2518"> |- Социальная психология и психология отношений&nbsp;</option>
                                    <option id="fs-2519" value="2519"> |- Тренинг и коучинг&nbsp;</option>
                                    <option id="fs-2520" value="2520"> |- Саморазвитие и самосовершенствование&nbsp;</option>
                                    <option id="fs-1696" value="1696"> |- Популярная психология&nbsp;</option>
                                    <option id="fs-2253" value="2253"> |- Сексология. Взаимоотношения полов&nbsp;</option>
                                    <option id="fs-2033" value="2033" >Коллекционирование, увлечения и хобби&nbsp;</option>
                                    <option id="fs-1412" value="1412"> |- Коллекционирование и вспомогательные ист. дисциплины&nbsp;</option>
                                    <option id="fs-1446" value="1446"> |- Вышивание&nbsp;</option>
                                    <option id="fs-753" value="753"> |- Вязание&nbsp;</option>
                                    <option id="fs-2037" value="2037"> |- Шитье, пэчворк&nbsp;</option>
                                    <option id="fs-2224" value="2224"> |- Кружевоплетение&nbsp;</option>
                                    <option id="fs-2194" value="2194"> |- Бисероплетение&nbsp;</option>
                                    <option id="fs-2418" value="2418"> |- Бумажный арт&nbsp;</option>
                                    <option id="fs-1410" value="1410"> |- Другие виды декоративно-прикладного искусства&nbsp;</option>
                                    <option id="fs-2034" value="2034"> |- Домашние питомцы и аквариумистика&nbsp;</option>
                                    <option id="fs-2433" value="2433"> |- Охота и рыбалка&nbsp;</option>
                                    <option id="fs-1961" value="1961"> |- Кулинария (книги)&nbsp;</option>
                                    <option id="fs-2432" value="2432"> |- Кулинария (газеты и журналы)&nbsp;</option>
                                    <option id="fs-565" value="565"> |- Моделизм&nbsp;</option>
                                    <option id="fs-1523" value="1523"> |- Приусадебное хозяйство / Цветоводство&nbsp;</option>
                                    <option id="fs-1575" value="1575"> |- Ремонт, частное строительство, дизайн интерьеров&nbsp;</option>
                                    <option id="fs-2424" value="2424"> |- Настольные игры&nbsp;</option>
                                    <option id="fs-769" value="769"> |- Прочие хобби&nbsp;</option>
                                    <option id="fs-2038" value="2038" >Художественная литература&nbsp;</option>
                                    <option id="fs-2043" value="2043"> |- Русская литература&nbsp;</option>
                                    <option id="fs-2042" value="2042"> |- Зарубежная литература (до 1900 г.)&nbsp;</option>
                                    <option id="fs-2041" value="2041"> |- Зарубежная литература (XX и XXI век)&nbsp;</option>
                                    <option id="fs-2044" value="2044"> |- Детектив, боевик&nbsp;</option>
                                    <option id="fs-2039" value="2039"> |- Женский роман&nbsp;</option>
                                    <option id="fs-2045" value="2045"> |- Отечественная фантастика / фэнтези / мистика&nbsp;</option>
                                    <option id="fs-2080" value="2080"> |- Зарубежная фантастика / фэнтези / мистика&nbsp;</option>
                                    <option id="fs-2047" value="2047"> |- Приключения&nbsp;</option>
                                    <option id="fs-2193" value="2193"> |- Литературные журналы&nbsp;</option>
                                    <option id="fs-1418" value="1418" >Компьютерная литература&nbsp;</option>
                                    <option id="fs-1422" value="1422"> |- Программы от Microsoft&nbsp;</option>
                                    <option id="fs-1423" value="1423"> |- Другие программы&nbsp;</option>
                                    <option id="fs-1424" value="1424"> |- Mac OS; Linux, FreeBSD и прочие *NIX&nbsp;</option>
                                    <option id="fs-1445" value="1445"> |- СУБД&nbsp;</option>
                                    <option id="fs-1425" value="1425"> |- Веб-дизайн и программирование&nbsp;</option>
                                    <option id="fs-1426" value="1426"> |- Программирование&nbsp;</option>
                                    <option id="fs-1428" value="1428"> |- Графика, обработка видео&nbsp;</option>
                                    <option id="fs-1429" value="1429"> |- Сети / VoIP&nbsp;</option>
                                    <option id="fs-1430" value="1430"> |- Хакинг и безопасность&nbsp;</option>
                                    <option id="fs-1431" value="1431"> |- Железо (книги о ПК)&nbsp;</option>
                                    <option id="fs-1433" value="1433"> |- Инженерные и научные программы&nbsp;</option>
                                    <option id="fs-1432" value="1432"> |- Компьютерные журналы и приложения к ним&nbsp;</option>
                                    <option id="fs-2202" value="2202"> |- Дисковые приложения к игровым журналам&nbsp;</option>
                                    <option id="fs-862" value="862" >Комиксы&nbsp;</option>
                                    <option id="fs-2461" value="2461"> |- Комиксы на русском языке&nbsp;</option>
                                    <option id="fs-2462" value="2462"> |- Комиксы издательства Marvel&nbsp;</option>
                                    <option id="fs-2463" value="2463"> |- Комиксы издательства DC&nbsp;</option>
                                    <option id="fs-2464" value="2464"> |- Комиксы других издательств&nbsp;</option>
                                    <option id="fs-2473" value="2473"> |- Комиксы на других языках&nbsp;</option>
                                    <option id="fs-2465" value="2465"> |- Манга (на иностранных языках)&nbsp;</option>
                                    <option id="fs-2048" value="2048" >Коллекции книг и библиотеки&nbsp;</option>
                                    <option id="fs-1238" value="1238"> |- Библиотеки (зеркала сетевых библиотек/коллекций)&nbsp;</option>
                                    <option id="fs-2055" value="2055"> |- Тематические коллекции (подборки)&nbsp;</option>
                                    <option id="fs-754" value="754"> |- Многопредметные коллекции (подборки)&nbsp;</option>
                                    <option id="fs-2114" value="2114" >Мультимедийные и интерактивные издания&nbsp;</option>
                                    <option id="fs-2438" value="2438"> |- Мультимедийные энциклопедии&nbsp;</option>
                                    <option id="fs-2439" value="2439"> |- Интерактивные обучающие и развивающие материалы&nbsp;</option>
                                    <option id="fs-2440" value="2440"> |- Обучающие издания для детей&nbsp;</option>
                                    <option id="fs-2441" value="2441"> |- Кулинария. Цветоводство. Домоводство&nbsp;</option>
                                    <option id="fs-2442" value="2442"> |- Культура. Искусство. История&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Обучение иностранным языкам">
                                    <option id="fs-2362" value="2362" >Иностранные языки для взрослых&nbsp;</option>
                                    <option id="fs-1265" value="1265"> |- Английский язык (для взрослых)&nbsp;</option>
                                    <option id="fs-1266" value="1266"> |- Немецкий язык&nbsp;</option>
                                    <option id="fs-1267" value="1267"> |- Французский язык&nbsp;</option>
                                    <option id="fs-1358" value="1358"> |- Испанский язык&nbsp;</option>
                                    <option id="fs-2363" value="2363"> |- Итальянский язык&nbsp;</option>
                                    <option id="fs-1268" value="1268"> |- Другие европейские языки&nbsp;</option>
                                    <option id="fs-1673" value="1673"> |- Арабский язык&nbsp;</option>
                                    <option id="fs-1269" value="1269"> |- Китайский язык&nbsp;</option>
                                    <option id="fs-1270" value="1270"> |- Японский язык&nbsp;</option>
                                    <option id="fs-1275" value="1275"> |- Другие восточные языки&nbsp;</option>
                                    <option id="fs-2364" value="2364"> |- Русский язык как иностранный&nbsp;</option>
                                    <option id="fs-1276" value="1276"> |- Мультиязычные сборники&nbsp;</option>
                                    <option id="fs-1274" value="1274"> |- Разное (иностранные языки)&nbsp;</option>
                                    <option id="fs-1264" value="1264" >Иностранные языки для детей&nbsp;</option>
                                    <option id="fs-2358" value="2358"> |- Английский язык (для детей)&nbsp;</option>
                                    <option id="fs-2359" value="2359"> |- Другие европейские языки (для детей)&nbsp;</option>
                                    <option id="fs-2360" value="2360"> |- Восточные языки (для детей)&nbsp;</option>
                                    <option id="fs-2361" value="2361"> |- Школьные учебники, ЕГЭ (для детей)&nbsp;</option>
                                    <option id="fs-2057" value="2057" >Художественная литература&nbsp;</option>
                                    <option id="fs-2355" value="2355"> |- Художественная литература на английском языке&nbsp;</option>
                                    <option id="fs-2474" value="2474"> |- Художественная литература на французском языке&nbsp;</option>
                                    <option id="fs-2356" value="2356"> |- Художественная литература на других европейских языках&nbsp;</option>
                                    <option id="fs-2357" value="2357"> |- Художественная литература на восточных языках&nbsp;</option>
                                    <option id="fs-2413" value="2413" >Аудиокниги на иностранных языках&nbsp;</option>
                                    <option id="fs-1501" value="1501"> |- Аудиокниги на английском языке&nbsp;</option>
                                    <option id="fs-1580" value="1580"> |- Аудиокниги на немецком языке&nbsp;</option>
                                    <option id="fs-525" value="525"> |- Аудиокниги на других иностранных языках&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Обучающее видео">
                                    <option id="fs-610" value="610" >Видеоуроки и обучающие интерактивные DVD&nbsp;</option>
                                    <option id="fs-1568" value="1568"> |- Кулинария&nbsp;</option>
                                    <option id="fs-1542" value="1542"> |- Спорт&nbsp;</option>
                                    <option id="fs-2335" value="2335"> |- Фитнес - Кардио-Силовые Тренировки&nbsp;</option>
                                    <option id="fs-1544" value="1544"> |- Фитнес - Разум и Тело&nbsp;</option>
                                    <option id="fs-1545" value="1545"> |- Экстрим&nbsp;</option>
                                    <option id="fs-1546" value="1546"> |- Бодибилдинг&nbsp;</option>
                                    <option id="fs-1549" value="1549"> |- Оздоровительные практики&nbsp;</option>
                                    <option id="fs-1597" value="1597"> |- Йога&nbsp;</option>
                                    <option id="fs-1552" value="1552"> |- Видео- и фотосъёмка&nbsp;</option>
                                    <option id="fs-1550" value="1550"> |- Уход за собой&nbsp;</option>
                                    <option id="fs-1553" value="1553"> |- Рисование&nbsp;</option>
                                    <option id="fs-1554" value="1554"> |- Игра на гитаре&nbsp;</option>
                                    <option id="fs-617" value="617"> |- Ударные инструменты&nbsp;</option>
                                    <option id="fs-1555" value="1555"> |- Другие музыкальные инструменты&nbsp;</option>
                                    <option id="fs-2017" value="2017"> |- Игра на бас-гитаре&nbsp;</option>
                                    <option id="fs-1257" value="1257"> |- Бальные танцы&nbsp;</option>
                                    <option id="fs-1258" value="1258"> |- Танец живота&nbsp;</option>
                                    <option id="fs-2208" value="2208"> |- Уличные и клубные танцы&nbsp;</option>
                                    <option id="fs-677" value="677"> |- Танцы, разное&nbsp;</option>
                                    <option id="fs-1255" value="1255"> |- Охота&nbsp;</option>
                                    <option id="fs-1479" value="1479"> |- Рыболовство и подводная охота&nbsp;</option>
                                    <option id="fs-1261" value="1261"> |- Фокусы и трюки&nbsp;</option>
                                    <option id="fs-614" value="614"> |- Образование&nbsp;</option>
                                    <option id="fs-1259" value="1259"> |- Бизнес, экономика и финансы&nbsp;</option>
                                    <option id="fs-2065" value="2065"> |- Беременность, роды, материнство&nbsp;</option>
                                    <option id="fs-1254" value="1254"> |- Учебные видео для детей&nbsp;</option>
                                    <option id="fs-1260" value="1260"> |- Психология&nbsp;</option>
                                    <option id="fs-2209" value="2209"> |- Эзотерика, саморазвитие&nbsp;</option>
                                    <option id="fs-2210" value="2210"> |- Пикап, знакомства&nbsp;</option>
                                    <option id="fs-1547" value="1547"> |- Строительство, ремонт и дизайн&nbsp;</option>
                                    <option id="fs-1548" value="1548"> |- Дерево- и металлообработка&nbsp;</option>
                                    <option id="fs-2211" value="2211"> |- Растения и животные&nbsp;</option>
                                    <option id="fs-615" value="615"> |- Разное&nbsp;</option>
                                    <option id="fs-1581" value="1581" >Боевые искусства (Видеоуроки)&nbsp;</option>
                                    <option id="fs-1590" value="1590"> |- Айкидо и айки-дзюцу&nbsp;</option>
                                    <option id="fs-1587" value="1587"> |- Вин чун&nbsp;</option>
                                    <option id="fs-1594" value="1594"> |- Джиу-джитсу&nbsp;</option>
                                    <option id="fs-1591" value="1591"> |- Дзюдо и самбо&nbsp;</option>
                                    <option id="fs-1588" value="1588"> |- Каратэ&nbsp;</option>
                                    <option id="fs-1596" value="1596"> |- Ножевой бой&nbsp;</option>
                                    <option id="fs-1585" value="1585"> |- Работа с оружием&nbsp;</option>
                                    <option id="fs-1586" value="1586"> |- Русский стиль&nbsp;</option>
                                    <option id="fs-2078" value="2078"> |- Рукопашный бой&nbsp;</option>
                                    <option id="fs-1929" value="1929"> |- Смешанные стили&nbsp;</option>
                                    <option id="fs-1593" value="1593"> |- Ударные стили&nbsp;</option>
                                    <option id="fs-1592" value="1592"> |- Ушу&nbsp;</option>
                                    <option id="fs-1595" value="1595"> |- Разное&nbsp;</option>
                                    <option id="fs-1556" value="1556" >Компьютерные видеоуроки и обучающие интерактивные DVD&nbsp;</option>
                                    <option id="fs-1560" value="1560"> |- Компьютерные сети и безопасность&nbsp;</option>
                                    <option id="fs-1561" value="1561"> |- ОС и серверные программы Microsoft&nbsp;</option>
                                    <option id="fs-1653" value="1653"> |- Офисные программы Microsoft&nbsp;</option>
                                    <option id="fs-1570" value="1570"> |- ОС и программы семейства UNIX&nbsp;</option>
                                    <option id="fs-1654" value="1654"> |- Adobe Photoshop&nbsp;</option>
                                    <option id="fs-1655" value="1655"> |- Autodesk Maya&nbsp;</option>
                                    <option id="fs-1656" value="1656"> |- Autodesk 3ds Max&nbsp;</option>
                                    <option id="fs-1930" value="1930"> |- Autodesk Softimage (XSI)&nbsp;</option>
                                    <option id="fs-1931" value="1931"> |- ZBrush&nbsp;</option>
                                    <option id="fs-1932" value="1932"> |- Flash, Flex и ActionScript&nbsp;</option>
                                    <option id="fs-1562" value="1562"> |- 2D-графика&nbsp;</option>
                                    <option id="fs-1563" value="1563"> |- 3D-графика&nbsp;</option>
                                    <option id="fs-1626" value="1626"> |- Инженерные и научные программы&nbsp;</option>
                                    <option id="fs-1564" value="1564"> |- Web-дизайн&nbsp;</option>
                                    <option id="fs-1565" value="1565"> |- Программирование&nbsp;</option>
                                    <option id="fs-1559" value="1559"> |- Программы для Mac OS&nbsp;</option>
                                    <option id="fs-1566" value="1566"> |- Работа с видео&nbsp;</option>
                                    <option id="fs-1573" value="1573"> |- Работа со звуком&nbsp;</option>
                                    <option id="fs-1567" value="1567"> |- Разное (Компьютерные видеоуроки)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Аудиокниги">
                                    <option id="fs-2326" value="2326" >Аудиоспектакли, история, мемуары&nbsp;</option>
                                    <option id="fs-1036" value="1036"> |- Жизнь замечательных людей&nbsp;</option>
                                    <option id="fs-400" value="400"> |- Историческая книга&nbsp;</option>
                                    <option id="fs-574" value="574"> |- Аудиоспектакли и литературные чтения&nbsp;</option>
                                    <option id="fs-2389" value="2389" >Фантастика, фэнтези, мистика, ужасы, фанфики&nbsp;</option>
                                    <option id="fs-2387" value="2387"> |- Российская фантастика, фэнтези, мистика, ужасы, фанфики&nbsp;</option>
                                    <option id="fs-2388" value="2388"> |- Зарубежная фантастика, фэнтези, мистика, ужасы, фанфики&nbsp;</option>
                                    <option id="fs-2327" value="2327" >Художественная литература&nbsp;</option>
                                    <option id="fs-695" value="695"> |- Поэзия&nbsp;</option>
                                    <option id="fs-399" value="399"> |- Зарубежная литература&nbsp;</option>
                                    <option id="fs-402" value="402"> |- Русская литература&nbsp;</option>
                                    <option id="fs-490" value="490"> |- Детская литература&nbsp;</option>
                                    <option id="fs-499" value="499"> |- Детективы, приключения, триллеры, боевики&nbsp;</option>
                                    <option id="fs-2324" value="2324" >Религии&nbsp;</option>
                                    <option id="fs-2325" value="2325"> |- Православие&nbsp;</option>
                                    <option id="fs-2342" value="2342"> |- Ислам&nbsp;</option>
                                    <option id="fs-530" value="530"> |- Другие традиционные религии&nbsp;</option>
                                    <option id="fs-2152" value="2152"> |- Нетрадиционные религиозно-философские учения&nbsp;</option>
                                    <option id="fs-2328" value="2328" >Прочая литература&nbsp;</option>
                                    <option id="fs-403" value="403"> |- Учебная и научно-популярная литература&nbsp;</option>
                                    <option id="fs-1279" value="1279"> |- Аудиокниги в lossless-форматах&nbsp;</option>
                                    <option id="fs-716" value="716"> |- Бизнес&nbsp;</option>
                                    <option id="fs-2165" value="2165"> |- Разное&nbsp;</option>
                                    <option id="fs-401" value="401"> |- Некондиционные раздачи&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Все по авто и мото">
                                    <option id="fs-1964" value="1964" >Ремонт и эксплуатация транспортных средств&nbsp;</option>
                                    <option id="fs-1973" value="1973"> |- Оригинальные каталоги по подбору запчастей&nbsp;</option>
                                    <option id="fs-1974" value="1974"> |- Неоригинальные каталоги по подбору запчастей&nbsp;</option>
                                    <option id="fs-1975" value="1975"> |- Программы по диагностике и ремонту&nbsp;</option>
                                    <option id="fs-1976" value="1976"> |- Тюнинг, чиптюнинг, настройка&nbsp;</option>
                                    <option id="fs-1977" value="1977"> |- Книги по ремонту/обслуживанию/эксплуатации ТС&nbsp;</option>
                                    <option id="fs-1203" value="1203"> |- Мультимедийки по ремонту/обслуживанию/эксплуатации ТС&nbsp;</option>
                                    <option id="fs-1978" value="1978"> |- Учет, утилиты и прочее&nbsp;</option>
                                    <option id="fs-1979" value="1979"> |- Виртуальная автошкола&nbsp;</option>
                                    <option id="fs-1980" value="1980"> |- Видеоуроки по вождению транспортных средств&nbsp;</option>
                                    <option id="fs-1981" value="1981"> |- Видеоуроки по ремонту транспортных средств&nbsp;</option>
                                    <option id="fs-1970" value="1970"> |- Журналы по авто/мото&nbsp;</option>
                                    <option id="fs-334" value="334"> |- Водный транспорт&nbsp;</option>
                                    <option id="fs-1202" value="1202" >Фильмы и телепередачи по авто/мото&nbsp;</option>
                                    <option id="fs-1985" value="1985"> |- Документальные/познавательные фильмы&nbsp;</option>
                                    <option id="fs-1982" value="1982"> |- Развлекательные передачи&nbsp;</option>
                                    <option id="fs-2151" value="2151"> |- Top Gear/Топ Гир&nbsp;</option>
                                    <option id="fs-1983" value="1983"> |- Тест драйв/Обзоры/Автосалоны&nbsp;</option>
                                    <option id="fs-1984" value="1984"> |- Тюнинг/форсаж&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Музыка">
                                    <option id="fs-409" value="409" >Классическая и современная академическая музыка&nbsp;</option>
                                    <option id="fs-1660" value="1660"> |- Собственные оцифровки (Классическая музыка)&nbsp;</option>
                                    <option id="fs-1164" value="1164"> |- Многоканальная музыка (классика и классика в современной..&nbsp;</option>
                                    <option id="fs-1884" value="1884"> |- Hi-Res stereo (классика и классика в современной обработ..&nbsp;</option>
                                    <option id="fs-445" value="445"> |- Классическая музыка (Видео)&nbsp;</option>
                                    <option id="fs-984" value="984"> |- Классическая музыка (DVD и HD Видео)&nbsp;</option>
                                    <option id="fs-702" value="702"> |- Опера (Видео)&nbsp;</option>
                                    <option id="fs-983" value="983"> |- Опера (DVD и HD Видео)&nbsp;</option>
                                    <option id="fs-1990" value="1990"> |- Балет и современная хореография (Видео, DVD и HD Видео)&nbsp;</option>
                                    <option id="fs-560" value="560"> |- Полные собрания сочинений и многодисковые издания (lossl..&nbsp;</option>
                                    <option id="fs-794" value="794"> |- Опера (lossless)&nbsp;</option>
                                    <option id="fs-556" value="556"> |- Вокальная музыка (lossless)&nbsp;</option>
                                    <option id="fs-2307" value="2307"> |- Хоровая музыка (lossless)&nbsp;</option>
                                    <option id="fs-557" value="557"> |- Оркестровая музыка (lossless)&nbsp;</option>
                                    <option id="fs-2308" value="2308"> |- Концерт для инструмента с оркестром (lossless)&nbsp;</option>
                                    <option id="fs-558" value="558"> |- Камерная инструментальная музыка (lossless)&nbsp;</option>
                                    <option id="fs-793" value="793"> |- Сольная инструментальная музыка (lossless)&nbsp;</option>
                                    <option id="fs-436" value="436"> |- Полные собрания сочинений и многодисковые издания (lossy..&nbsp;</option>
                                    <option id="fs-2309" value="2309"> |- Вокальная и хоровая музыка (lossy)&nbsp;</option>
                                    <option id="fs-2310" value="2310"> |- Оркестровая музыка (lossy)&nbsp;</option>
                                    <option id="fs-2311" value="2311"> |- Камерная и сольная инструментальная музыка (lossy)&nbsp;</option>
                                    <option id="fs-969" value="969"> |- Классика в современной обработке, Classical Crossover (l..&nbsp;</option>
                                    <option id="fs-1125" value="1125" >Фольклор, Народная и Этническая музыка&nbsp;</option>
                                    <option id="fs-1130" value="1130"> |- Восточноевропейский фолк (lossy)&nbsp;</option>
                                    <option id="fs-1131" value="1131"> |- Восточноевропейский фолк (lossless)&nbsp;</option>
                                    <option id="fs-1132" value="1132"> |- Западноевропейский фолк (lossy)&nbsp;</option>
                                    <option id="fs-1133" value="1133"> |- Западноевропейский фолк (lossless)&nbsp;</option>
                                    <option id="fs-2084" value="2084"> |- Klezmer и Еврейский фольклор (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1128" value="1128"> |- Этническая музыка Сибири, Средней и Восточной Азии (loss..&nbsp;</option>
                                    <option id="fs-1129" value="1129"> |- Этническая музыка Сибири, Средней и Восточной Азии (loss..&nbsp;</option>
                                    <option id="fs-1856" value="1856"> |- Этническая музыка Индии (lossy)&nbsp;</option>
                                    <option id="fs-2430" value="2430"> |- Этническая музыка Индии (lossless)&nbsp;</option>
                                    <option id="fs-1283" value="1283"> |- Этническая музыка Африки и Ближнего Востока (lossy)&nbsp;</option>
                                    <option id="fs-2085" value="2085"> |- Этническая музыка Африки и Ближнего Востока (lossless)&nbsp;</option>
                                    <option id="fs-1282" value="1282"> |- Этническая музыка Кавказа и Закавказья (lossy и lossless..&nbsp;</option>
                                    <option id="fs-1284" value="1284"> |- Этническая музыка Северной и Южной Америки (lossy)&nbsp;</option>
                                    <option id="fs-1285" value="1285"> |- Этническая музыка Северной и Южной Америки (lossless)&nbsp;</option>
                                    <option id="fs-1138" value="1138"> |- Этническая музыка Австралии, Тихого и Индийского океанов..&nbsp;</option>
                                    <option id="fs-1136" value="1136"> |- Country, Bluegrass (lossy)&nbsp;</option>
                                    <option id="fs-1137" value="1137"> |- Country, Bluegrass (lossless)&nbsp;</option>
                                    <option id="fs-1141" value="1141"> |- Фольклор, Народная и Этническая музыка (Видео)&nbsp;</option>
                                    <option id="fs-1142" value="1142"> |- Фольклор, Народная и Этническая музыка (DVD Video)&nbsp;</option>
                                    <option id="fs-2530" value="2530"> |- Фольклор, Народная и Этническая музыка (HD Видео)&nbsp;</option>
                                    <option id="fs-506" value="506"> |- Фольклор, Народная и Этническая музыка (собственные оциф..&nbsp;</option>
                                    <option id="fs-1849" value="1849" >New Age, Relax, Meditative &amp; Flamenco&nbsp;</option>
                                    <option id="fs-1126" value="1126"> |- NewAge &amp; Meditative (lossy)&nbsp;</option>
                                    <option id="fs-1127" value="1127"> |- NewAge &amp; Meditative (lossless)&nbsp;</option>
                                    <option id="fs-1134" value="1134"> |- Фламенко и акустическая гитара (lossy)&nbsp;</option>
                                    <option id="fs-1135" value="1135"> |- Фламенко и акустическая гитара (lossless)&nbsp;</option>
                                    <option id="fs-2352" value="2352"> |- New Age, Relax, Meditative &amp; Flamenco (Видео)&nbsp;</option>
                                    <option id="fs-2351" value="2351"> |- New Age, Relax, Meditative &amp; Flamenco (DVD и HD Видео)&nbsp;</option>
                                    <option id="fs-855" value="855"> |- Звуки природы&nbsp;</option>
                                    <option id="fs-408" value="408" >Рэп, Хип-Хоп, R&#039;n&#039;B&nbsp;</option>
                                    <option id="fs-441" value="441"> |- Отечественный Рэп, Хип-Хоп (lossy)&nbsp;</option>
                                    <option id="fs-1173" value="1173"> |- Отечественный R&#039;n&#039;B (lossy)&nbsp;</option>
                                    <option id="fs-1486" value="1486"> |- Отечественный Рэп, Хип-Хоп, R&#039;n&#039;B (lossless)&nbsp;</option>
                                    <option id="fs-1189" value="1189"> |- Отечественный Рэп, Хип-Хоп (Видео)&nbsp;</option>
                                    <option id="fs-1455" value="1455"> |- Отечественный R&#039;n&#039;B (Видео)&nbsp;</option>
                                    <option id="fs-1172" value="1172"> |- Зарубежный R&#039;n&#039;B (lossy)&nbsp;</option>
                                    <option id="fs-446" value="446"> |- Зарубежный Рэп, Хип-Хоп (lossy)&nbsp;</option>
                                    <option id="fs-909" value="909"> |- Зарубежный Рэп, Хип-Хоп (lossless)&nbsp;</option>
                                    <option id="fs-1665" value="1665"> |- Зарубежный R&#039;n&#039;B (lossless)&nbsp;</option>
                                    <option id="fs-1835" value="1835"> |- Rap, Hip-Hop, R&#039;n&#039;B (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-442" value="442"> |- Зарубежный Рэп, Хип-Хоп (Видео)&nbsp;</option>
                                    <option id="fs-1174" value="1174"> |- Зарубежный R&#039;n&#039;B (Видео)&nbsp;</option>
                                    <option id="fs-1107" value="1107"> |- Рэп, Хип-Хоп, R&#039;n&#039;B (DVD Video)&nbsp;</option>
                                    <option id="fs-2529" value="2529"> |- Рэп, Хип-Хоп, R&#039;n&#039;B (HD Видео)&nbsp;</option>
                                    <option id="fs-1760" value="1760" >Reggae, Ska, Dub&nbsp;</option>
                                    <option id="fs-1764" value="1764"> |- Rocksteady, Early Reggae, Ska-Jazz, Trad.Ska (lossy и lo..&nbsp;</option>
                                    <option id="fs-1766" value="1766"> |- Punky-Reggae, Rocksteady-Punk, Ska Revival (lossy)&nbsp;</option>
                                    <option id="fs-1767" value="1767"> |- 3rd Wave Ska (lossy)&nbsp;</option>
                                    <option id="fs-1769" value="1769"> |- Ska-Punk, Ska-Core (lossy)&nbsp;</option>
                                    <option id="fs-1765" value="1765"> |- Reggae (lossy)&nbsp;</option>
                                    <option id="fs-1771" value="1771"> |- Dub (lossy)&nbsp;</option>
                                    <option id="fs-1770" value="1770"> |- Dancehall, Raggamuffin (lossy)&nbsp;</option>
                                    <option id="fs-1768" value="1768"> |- Reggae, Dancehall, Dub (lossless)&nbsp;</option>
                                    <option id="fs-1774" value="1774"> |- Ska, Ska-Punk, Ska-Jazz (lossless)&nbsp;</option>
                                    <option id="fs-1772" value="1772"> |- Отечественный реггей, даб (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1773" value="1773"> |- Отечественная ска-музыка (lossy и lossless)&nbsp;</option>
                                    <option id="fs-2233" value="2233"> |- Reggae, Ska, Dub (компиляции) (lossy)&nbsp;</option>
                                    <option id="fs-2232" value="2232"> |- Reggae, Ska, Dub (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-1775" value="1775"> |- Reggae, Ska, Dub (Видео)&nbsp;</option>
                                    <option id="fs-1777" value="1777"> |- Reggae, Ska, Dub (DVD и HD Video)&nbsp;</option>
                                    <option id="fs-416" value="416" >Саундтреки и Караоке&nbsp;</option>
                                    <option id="fs-782" value="782"> |- Караоке (аудио)&nbsp;</option>
                                    <option id="fs-2377" value="2377"> |- Караоке (видео)&nbsp;</option>
                                    <option id="fs-468" value="468"> |- Минусовки (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1625" value="1625"> |- Саундтреки (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-691" value="691"> |- Саундтреки к отечественным фильмам (lossless)&nbsp;</option>
                                    <option id="fs-469" value="469"> |- Саундтреки к отечественным фильмам (lossy)&nbsp;</option>
                                    <option id="fs-786" value="786"> |- Саундтреки к зарубежным фильмам (lossless)&nbsp;</option>
                                    <option id="fs-785" value="785"> |- Саундтреки к зарубежным фильмам (lossy)&nbsp;</option>
                                    <option id="fs-796" value="796"> |- Неофициальные саундтреки к фильмам и сериалам (lossy)&nbsp;</option>
                                    <option id="fs-784" value="784"> |- Саундтреки к играм (lossless)&nbsp;</option>
                                    <option id="fs-783" value="783"> |- Саундтреки к играм (lossy)&nbsp;</option>
                                    <option id="fs-2331" value="2331"> |- Неофициальные саундтреки к играм (lossy)&nbsp;</option>
                                    <option id="fs-2431" value="2431"> |- Аранжировки музыки из игр (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1397" value="1397"> |- Hi-Res stereo и Многоканальная музыка (Саундтреки)&nbsp;</option>
                                    <option id="fs-1215" value="1215" >Шансон, Авторская и Военная песня&nbsp;</option>
                                    <option id="fs-1220" value="1220"> |- Отечественный шансон (lossless)&nbsp;</option>
                                    <option id="fs-1221" value="1221"> |- Отечественный шансон (lossy)&nbsp;</option>
                                    <option id="fs-1334" value="1334"> |- Сборники отечественного шансона (lossy)&nbsp;</option>
                                    <option id="fs-1216" value="1216"> |- Военная песня (lossless)&nbsp;</option>
                                    <option id="fs-1223" value="1223"> |- Военная песня (lossy)&nbsp;</option>
                                    <option id="fs-1224" value="1224"> |- Авторская песня (lossless)&nbsp;</option>
                                    <option id="fs-1225" value="1225"> |- Авторская песня (lossy)&nbsp;</option>
                                    <option id="fs-1226" value="1226"> |- Менестрели и ролевики (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1217" value="1217"> |- Собственные оцифровки (Шансон и Авторская песня) lossles..&nbsp;</option>
                                    <option id="fs-1227" value="1227"> |- Видео (Шансон и Авторская песня)&nbsp;</option>
                                    <option id="fs-1228" value="1228"> |- DVD Видео (Шансон и Авторская песня)&nbsp;</option>
                                    <option id="fs-413" value="413" >Музыка других жанров&nbsp;</option>
                                    <option id="fs-974" value="974"> |- Собственные оцифровки (Музыка других жанров)&nbsp;</option>
                                    <option id="fs-463" value="463"> |- Отечественная музыка других жанров (lossy)&nbsp;</option>
                                    <option id="fs-464" value="464"> |- Отечественная музыка других жанров (lossless)&nbsp;</option>
                                    <option id="fs-466" value="466"> |- Зарубежная музыка других жанров (lossy)&nbsp;</option>
                                    <option id="fs-465" value="465"> |- Зарубежная музыка других жанров (lossless)&nbsp;</option>
                                    <option id="fs-2018" value="2018"> |- Музыка для бальных танцев (lossy и lossless)&nbsp;</option>
                                    <option id="fs-1396" value="1396"> |- Православные песнопения (lossy)&nbsp;</option>
                                    <option id="fs-1395" value="1395"> |- Православные песнопения (lossless)&nbsp;</option>
                                    <option id="fs-1351" value="1351"> |- Сборники песен для детей (lossy и lossless)&nbsp;</option>
                                    <option id="fs-475" value="475"> |- Видео (Музыка других жанров)&nbsp;</option>
                                    <option id="fs-988" value="988"> |- DVD Video (Музыка других жанров)&nbsp;</option>
                                    <option id="fs-880" value="880"> |- Мюзикл (lossy и lossless)&nbsp;</option>
                                    <option id="fs-655" value="655"> |- Мюзикл (Видео и DVD Video)&nbsp;</option>
                                    <option id="fs-965" value="965"> |- Неофициальные и внежанровые сборники (lossy)&nbsp;</option>
                                    <option id="fs-919" value="919" >Ноты и Музыкальная литература&nbsp;</option>
                                    <option id="fs-944" value="944"> |- Академическая музыка (Ноты и Media CD)&nbsp;</option>
                                    <option id="fs-980" value="980"> |- Другие направления (Ноты, табулатуры)&nbsp;</option>
                                    <option id="fs-946" value="946"> |- Самоучители и Школы&nbsp;</option>
                                    <option id="fs-977" value="977"> |- Песенники (Songbooks)&nbsp;</option>
                                    <option id="fs-2074" value="2074"> |- Музыкальная литература и Теория&nbsp;</option>
                                    <option id="fs-2349" value="2349"> |- Музыкальные журналы&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Популярная музыка">
                                    <option id="fs-2495" value="2495" >Отечественная поп-музыка&nbsp;</option>
                                    <option id="fs-424" value="424"> |- Отечественная поп-музыка (lossy)&nbsp;</option>
                                    <option id="fs-425" value="425"> |- Отечественная поп-музыка (lossless)&nbsp;</option>
                                    <option id="fs-1361" value="1361"> |- Отечественная поп-музыка (сборники) (lossy)&nbsp;</option>
                                    <option id="fs-1635" value="1635"> |- Советская эстрада, Ретро (lossy)&nbsp;</option>
                                    <option id="fs-1634" value="1634"> |- Советская эстрада, Ретро (lossless)&nbsp;</option>
                                    <option id="fs-2497" value="2497" >Зарубежная поп-музыка&nbsp;</option>
                                    <option id="fs-428" value="428"> |- Зарубежная поп-музыка (lossy)&nbsp;</option>
                                    <option id="fs-1362" value="1362"> |- Зарубежная поп-музыка (сборники) (lossy)&nbsp;</option>
                                    <option id="fs-429" value="429"> |- Зарубежная поп-музыка (lossless)&nbsp;</option>
                                    <option id="fs-1219" value="1219"> |- Зарубежный шансон (lossy)&nbsp;</option>
                                    <option id="fs-1452" value="1452"> |- Зарубежный шансон (lossless)&nbsp;</option>
                                    <option id="fs-1331" value="1331"> |- Восточноазиатская поп-музыка (lossy)&nbsp;</option>
                                    <option id="fs-1330" value="1330"> |- Восточноазиатская поп-музыка (lossless)&nbsp;</option>
                                    <option id="fs-2499" value="2499" >Eurodance, Disco, Hi-NRG&nbsp;</option>
                                    <option id="fs-2503" value="2503"> |- Eurodance, Euro-House, Technopop (lossy)&nbsp;</option>
                                    <option id="fs-2504" value="2504"> |- Eurodance, Euro-House, Technopop (сборники) (lossy)&nbsp;</option>
                                    <option id="fs-2502" value="2502"> |- Eurodance, Euro-House, Technopop (lossless)&nbsp;</option>
                                    <option id="fs-2501" value="2501"> |- Disco, Italo-Disco, Euro-Disco, Hi-NRG (lossy)&nbsp;</option>
                                    <option id="fs-2505" value="2505"> |- Disco, Italo-Disco, Euro-Disco, Hi-NRG (сборники) (lossy..&nbsp;</option>
                                    <option id="fs-2500" value="2500"> |- Disco, Italo-Disco, Euro-Disco, Hi-NRG (lossless)&nbsp;</option>
                                    <option id="fs-2507" value="2507" >Видео, DVD Video, HD Video (поп-музыка)&nbsp;</option>
                                    <option id="fs-1121" value="1121"> |- Отечественная поп-музыка (Видео)&nbsp;</option>
                                    <option id="fs-1122" value="1122"> |- Отечественная поп-музыка (DVD Video)&nbsp;</option>
                                    <option id="fs-2510" value="2510"> |- Советская эстрада, Ретро (видео)&nbsp;</option>
                                    <option id="fs-2509" value="2509"> |- Советская эстрада, Ретро (DVD Video)&nbsp;</option>
                                    <option id="fs-431" value="431"> |- Зарубежная поп-музыка (Видео)&nbsp;</option>
                                    <option id="fs-986" value="986"> |- Зарубежная поп-музыка (DVD Video)&nbsp;</option>
                                    <option id="fs-2532" value="2532"> |- Eurodance, Disco (видео)&nbsp;</option>
                                    <option id="fs-2531" value="2531"> |- Eurodance, Disco (DVD Video)&nbsp;</option>
                                    <option id="fs-2378" value="2378"> |- Восточноазиатская поп-музыка (Видео)&nbsp;</option>
                                    <option id="fs-2379" value="2379"> |- Восточноазиатская поп-музыка (DVD Video)&nbsp;</option>
                                    <option id="fs-2383" value="2383"> |- Зарубежный шансон (Видео)&nbsp;</option>
                                    <option id="fs-2384" value="2384"> |- Зарубежный шансон (DVD Video)&nbsp;</option>
                                    <option id="fs-2088" value="2088"> |- Отечественная поп-музыка (Сборные концерты, док. видео) ..&nbsp;</option>
                                    <option id="fs-2089" value="2089"> |- Зарубежная поп-музыка (Сборные концерты, док. видео) (Ви..&nbsp;</option>
                                    <option id="fs-2426" value="2426"> |- Отечественная Поп-музыка, Шансон, Eurodance, Disco (HD V..&nbsp;</option>
                                    <option id="fs-2508" value="2508"> |- Зарубежная Поп-музыка, Шансон, Eurodance, Disco (HD Vide..&nbsp;</option>
                                    <option id="fs-2512" value="2512" >Многоканальная музыка и собственные оцифровки (поп-музыка)&nbsp;</option>
                                    <option id="fs-1444" value="1444"> |- Зарубежная поп-музыка (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-1785" value="1785"> |- Восточная поп-музыка (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-239" value="239"> |- Отечественная поп-музыка (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-450" value="450"> |- Инструментальная поп-музыка (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-1163" value="1163"> |- Многоканальная музыка (поп-музыка)&nbsp;</option>
                                    <option id="fs-1885" value="1885"> |- Hi-Res stereo (Поп-музыка)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Джазовая и Блюзовая музыка">
                                    <option id="fs-2267" value="2267" >Зарубежный джаз&nbsp;</option>
                                    <option id="fs-2277" value="2277"> |- Early Jazz, Swing, Gypsy (lossless)&nbsp;</option>
                                    <option id="fs-2278" value="2278"> |- Bop (lossless)&nbsp;</option>
                                    <option id="fs-2279" value="2279"> |- Mainstream Jazz, Cool (lossless)&nbsp;</option>
                                    <option id="fs-2280" value="2280"> |- Jazz Fusion (lossless)&nbsp;</option>
                                    <option id="fs-2281" value="2281"> |- World Fusion, Ethnic Jazz (lossless)&nbsp;</option>
                                    <option id="fs-2282" value="2282"> |- Avant-Garde Jazz, Free Improvisation (lossless)&nbsp;</option>
                                    <option id="fs-2353" value="2353"> |- Modern Creative, Third Stream (lossless)&nbsp;</option>
                                    <option id="fs-2284" value="2284"> |- Smooth, Jazz-Pop (lossless)&nbsp;</option>
                                    <option id="fs-2285" value="2285"> |- Vocal Jazz (lossless)&nbsp;</option>
                                    <option id="fs-2283" value="2283"> |- Funk, Soul, R&amp;B (lossless)&nbsp;</option>
                                    <option id="fs-2286" value="2286"> |- Сборники зарубежного джаза (lossless)&nbsp;</option>
                                    <option id="fs-2287" value="2287"> |- Зарубежный джаз (lossy)&nbsp;</option>
                                    <option id="fs-2268" value="2268" >Зарубежный блюз&nbsp;</option>
                                    <option id="fs-2293" value="2293"> |- Blues (Texas, Chicago, Modern and Others) (lossless)&nbsp;</option>
                                    <option id="fs-2292" value="2292"> |- Blues-rock (lossless)&nbsp;</option>
                                    <option id="fs-2290" value="2290"> |- Roots, Pre-War Blues, Early R&amp;B, Gospel (lossless)&nbsp;</option>
                                    <option id="fs-2289" value="2289"> |- Зарубежный блюз (сборники; Tribute VA) (lossless)&nbsp;</option>
                                    <option id="fs-2288" value="2288"> |- Зарубежный блюз (lossy)&nbsp;</option>
                                    <option id="fs-2269" value="2269" >Отечественный джаз и блюз&nbsp;</option>
                                    <option id="fs-2297" value="2297"> |- Отечественный джаз (lossless)&nbsp;</option>
                                    <option id="fs-2295" value="2295"> |- Отечественный джаз (lossy)&nbsp;</option>
                                    <option id="fs-2296" value="2296"> |- Отечественный блюз (lossless)&nbsp;</option>
                                    <option id="fs-2298" value="2298"> |- Отечественный блюз (lossy)&nbsp;</option>
                                    <option id="fs-2270" value="2270" >Многоканальная музыка и собственные оцифровки (Джаз и блюз)&nbsp;</option>
                                    <option id="fs-2303" value="2303"> |- Многоканальная музыка (Джаз и Блюз)&nbsp;</option>
                                    <option id="fs-2302" value="2302"> |- Hi-Res stereo (Джаз и Блюз)&nbsp;</option>
                                    <option id="fs-2301" value="2301"> |- Собственные оцифровки (Джаз и Блюз)&nbsp;</option>
                                    <option id="fs-2271" value="2271" >Видео, DVD Video, HD Video (Джаз и блюз)&nbsp;</option>
                                    <option id="fs-2305" value="2305"> |- Джаз и Блюз (Видео)&nbsp;</option>
                                    <option id="fs-2304" value="2304"> |- Джаз и Блюз (DVD Видео)&nbsp;</option>
                                    <option id="fs-2306" value="2306"> |- Джаз и Блюз (HD Video)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Рок-музыка">
                                    <option id="fs-1698" value="1698" >Зарубежный Rock&nbsp;</option>
                                    <option id="fs-1702" value="1702"> |- Classic Rock &amp; Hard Rock (lossless)&nbsp;</option>
                                    <option id="fs-1703" value="1703"> |- Classic Rock &amp; Hard Rock (lossy)&nbsp;</option>
                                    <option id="fs-1704" value="1704"> |- Progressive &amp; Art-Rock (lossless)&nbsp;</option>
                                    <option id="fs-1705" value="1705"> |- Progressive &amp; Art-Rock (lossy)&nbsp;</option>
                                    <option id="fs-1706" value="1706"> |- Folk-Rock (lossless)&nbsp;</option>
                                    <option id="fs-1707" value="1707"> |- Folk-Rock (lossy)&nbsp;</option>
                                    <option id="fs-2329" value="2329"> |- AOR (Melodic Hard Rock, Arena rock) (lossless)&nbsp;</option>
                                    <option id="fs-2330" value="2330"> |- AOR (Melodic Hard Rock, Arena rock) (lossy)&nbsp;</option>
                                    <option id="fs-1708" value="1708"> |- Pop-Rock &amp; Soft Rock (lossless)&nbsp;</option>
                                    <option id="fs-1709" value="1709"> |- Pop-Rock &amp; Soft Rock (lossy)&nbsp;</option>
                                    <option id="fs-1710" value="1710"> |- Instrumental Guitar Rock (lossless)&nbsp;</option>
                                    <option id="fs-1711" value="1711"> |- Instrumental Guitar Rock (lossy)&nbsp;</option>
                                    <option id="fs-1712" value="1712"> |- Rockabilly, Psychobilly, Rock&#039;n&#039;Roll (lossless)&nbsp;</option>
                                    <option id="fs-1713" value="1713"> |- Rockabilly, Psychobilly, Rock&#039;n&#039;Roll (lossy)&nbsp;</option>
                                    <option id="fs-731" value="731"> |- Сборники зарубежного рока (lossless)&nbsp;</option>
                                    <option id="fs-1799" value="1799"> |- Сборники зарубежного рока (lossy)&nbsp;</option>
                                    <option id="fs-1714" value="1714"> |- Восточноазиатский рок (lossless)&nbsp;</option>
                                    <option id="fs-1715" value="1715"> |- Восточноазиатский рок (lossy)&nbsp;</option>
                                    <option id="fs-1716" value="1716" >Зарубежный Metal&nbsp;</option>
                                    <option id="fs-1796" value="1796"> |- Avant-garde, Experimental Metal (lossless)&nbsp;</option>
                                    <option id="fs-1797" value="1797"> |- Avant-garde, Experimental Metal (lossy)&nbsp;</option>
                                    <option id="fs-1719" value="1719"> |- Black (lossless)&nbsp;</option>
                                    <option id="fs-1778" value="1778"> |- Black (lossy)&nbsp;</option>
                                    <option id="fs-1779" value="1779"> |- Death, Doom (lossless)&nbsp;</option>
                                    <option id="fs-1780" value="1780"> |- Death, Doom (lossy)&nbsp;</option>
                                    <option id="fs-1720" value="1720"> |- Folk, Pagan, Viking (lossless)&nbsp;</option>
                                    <option id="fs-798" value="798"> |- Folk, Pagan, Viking (lossy)&nbsp;</option>
                                    <option id="fs-1724" value="1724"> |- Gothic Metal (lossless)&nbsp;</option>
                                    <option id="fs-1725" value="1725"> |- Gothic Metal (lossy)&nbsp;</option>
                                    <option id="fs-1730" value="1730"> |- Grind, Brutal Death (lossless)&nbsp;</option>
                                    <option id="fs-1731" value="1731"> |- Grind, Brutal Death (lossy)&nbsp;</option>
                                    <option id="fs-1726" value="1726"> |- Heavy, Power, Progressive (lossless)&nbsp;</option>
                                    <option id="fs-1727" value="1727"> |- Heavy, Power, Progressive (lossy)&nbsp;</option>
                                    <option id="fs-1815" value="1815"> |- Sludge, Stoner, Post-Metal (lossless)&nbsp;</option>
                                    <option id="fs-1816" value="1816"> |- Sludge, Stoner, Post-Metal (lossy)&nbsp;</option>
                                    <option id="fs-1728" value="1728"> |- Thrash, Speed (lossless)&nbsp;</option>
                                    <option id="fs-1729" value="1729"> |- Thrash, Speed (lossy)&nbsp;</option>
                                    <option id="fs-2230" value="2230"> |- Сборники (lossless)&nbsp;</option>
                                    <option id="fs-2231" value="2231"> |- Сборники (lossy)&nbsp;</option>
                                    <option id="fs-1732" value="1732" >Зарубежные Alternative, Punk, Independent&nbsp;</option>
                                    <option id="fs-1736" value="1736"> |- Alternative &amp; Nu-metal (lossless)&nbsp;</option>
                                    <option id="fs-1737" value="1737"> |- Alternative &amp; Nu-metal (lossy)&nbsp;</option>
                                    <option id="fs-1738" value="1738"> |- Punk (lossless)&nbsp;</option>
                                    <option id="fs-1739" value="1739"> |- Punk (lossy)&nbsp;</option>
                                    <option id="fs-1740" value="1740"> |- Hardcore (lossless)&nbsp;</option>
                                    <option id="fs-1741" value="1741"> |- Hardcore (lossy)&nbsp;</option>
                                    <option id="fs-1742" value="1742"> |- Indie, Post-Rock &amp; Post-Punk (lossless)&nbsp;</option>
                                    <option id="fs-1743" value="1743"> |- Indie, Post-Rock &amp; Post-Punk (lossy)&nbsp;</option>
                                    <option id="fs-1744" value="1744"> |- Industrial &amp; Post-industrial (lossless)&nbsp;</option>
                                    <option id="fs-1745" value="1745"> |- Industrial &amp; Post-industrial (lossy)&nbsp;</option>
                                    <option id="fs-1746" value="1746"> |- Emocore, Post-hardcore, Metalcore (lossless)&nbsp;</option>
                                    <option id="fs-1747" value="1747"> |- Emocore, Post-hardcore, Metalcore (lossy)&nbsp;</option>
                                    <option id="fs-1748" value="1748"> |- Gothic Rock &amp; Dark Folk (lossless)&nbsp;</option>
                                    <option id="fs-1749" value="1749"> |- Gothic Rock &amp; Dark Folk (lossy)&nbsp;</option>
                                    <option id="fs-2175" value="2175"> |- Avant-garde, Experimental Rock (lossless)&nbsp;</option>
                                    <option id="fs-2174" value="2174"> |- Avant-garde, Experimental Rock (lossy)&nbsp;</option>
                                    <option id="fs-722" value="722" >Отечественный Рок&nbsp;</option>
                                    <option id="fs-737" value="737"> |- Рок, Панк, Альтернатива (lossless)&nbsp;</option>
                                    <option id="fs-738" value="738"> |- Рок, Панк, Альтернатива (lossy)&nbsp;</option>
                                    <option id="fs-739" value="739"> |- Металл (lossless)&nbsp;</option>
                                    <option id="fs-740" value="740"> |- Металл (lossy)&nbsp;</option>
                                    <option id="fs-951" value="951"> |- Рок на языках народов xUSSR (lossless)&nbsp;</option>
                                    <option id="fs-952" value="952"> |- Рок на языках народов xUSSR (lossy)&nbsp;</option>
                                    <option id="fs-1752" value="1752" >Многоканальная музыка и собственные оцифровки (Рок)&nbsp;</option>
                                    <option id="fs-1756" value="1756"> |- Зарубежный рок (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-1758" value="1758"> |- Отечественный рок (собственные оцифровки)&nbsp;</option>
                                    <option id="fs-1757" value="1757"> |- Многоканальная музыка (рок)&nbsp;</option>
                                    <option id="fs-1755" value="1755"> |- Hi-Res stereo (рок)&nbsp;</option>
                                    <option id="fs-453" value="453"> |- Конверсии Quadraphonic (многоканальная музыка)&nbsp;</option>
                                    <option id="fs-1170" value="1170"> |- Конверсии SACD (многоканальная музыка)&nbsp;</option>
                                    <option id="fs-1759" value="1759"> |- Конверсии в Blu-Ray (многоканальная музыка)&nbsp;</option>
                                    <option id="fs-1852" value="1852"> |- Апмиксы-Upmixes/Даунмиксы-Downmix (многоканальная и Hi-R..&nbsp;</option>
                                    <option id="fs-1781" value="1781" >Видео, DVD Video, HD Video (Рок-музыка)&nbsp;</option>
                                    <option id="fs-1782" value="1782"> |- Rock (Видео)&nbsp;</option>
                                    <option id="fs-1783" value="1783"> |- Rock (DVD Video)&nbsp;</option>
                                    <option id="fs-2261" value="2261"> |- Rock (Неофициальные DVD Video)&nbsp;</option>
                                    <option id="fs-1787" value="1787"> |- Metal (Видео)&nbsp;</option>
                                    <option id="fs-1788" value="1788"> |- Metal (DVD Video)&nbsp;</option>
                                    <option id="fs-2262" value="2262"> |- Metal (Неофициальные DVD Video)&nbsp;</option>
                                    <option id="fs-1789" value="1789"> |- Alternative, Punk, Independent (Видео)&nbsp;</option>
                                    <option id="fs-1790" value="1790"> |- Alternative, Punk, Independent (DVD Video)&nbsp;</option>
                                    <option id="fs-2263" value="2263"> |- Alternative, Punk, Independent (Неофициальные DVD Video)&nbsp;</option>
                                    <option id="fs-1791" value="1791"> |- Отечественный Рок, Панк, Альтернатива (Видео)&nbsp;</option>
                                    <option id="fs-1792" value="1792"> |- Отечественный Рок, Панк, Альтернатива (DVD Video)&nbsp;</option>
                                    <option id="fs-1793" value="1793"> |- Отечественный Металл (Видео)&nbsp;</option>
                                    <option id="fs-1794" value="1794"> |- Отечественный Металл (DVD Video)&nbsp;</option>
                                    <option id="fs-2264" value="2264"> |- Отечественный Рок, Панк, Альтернатива, Металл (Неофициал..&nbsp;</option>
                                    <option id="fs-1795" value="1795"> |- Рок-музыка (HD Video)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Электронная музыка">
                                    <option id="fs-1821" value="1821" >Trance, Goa Trance, Psy-Trance, PsyChill, Ambient, Dub&nbsp;</option>
                                    <option id="fs-1844" value="1844"> |- Goa Trance, Psy-Trance (lossless)&nbsp;</option>
                                    <option id="fs-1822" value="1822"> |- Goa Trance, Psy-Trance (lossy)&nbsp;</option>
                                    <option id="fs-1894" value="1894"> |- PsyChill, Ambient, Dub (lossless)&nbsp;</option>
                                    <option id="fs-1895" value="1895"> |- PsyChill, Ambient, Dub (lossy)&nbsp;</option>
                                    <option id="fs-460" value="460"> |- Goa Trance, Psy-Trance, PsyChill, Ambient, Dub (Live Set..&nbsp;</option>
                                    <option id="fs-1818" value="1818"> |- Trance (lossless)&nbsp;</option>
                                    <option id="fs-1819" value="1819"> |- Trance (lossy)&nbsp;</option>
                                    <option id="fs-1847" value="1847"> |- Trance (Singles, EPs) (lossy)&nbsp;</option>
                                    <option id="fs-1824" value="1824"> |- Trance (Radioshows, Podcasts, Live Sets, Mixes) (lossy)&nbsp;</option>
                                    <option id="fs-1807" value="1807" >House, Techno, Hardcore, Hardstyle, Jumpstyle&nbsp;</option>
                                    <option id="fs-1829" value="1829"> |- Hardcore, Hardstyle, Jumpstyle (lossless)&nbsp;</option>
                                    <option id="fs-1830" value="1830"> |- Hardcore, Hardstyle, Jumpstyle (lossy)&nbsp;</option>
                                    <option id="fs-1831" value="1831"> |- Hardcore, Hardstyle, Jumpstyle (vinyl, web)&nbsp;</option>
                                    <option id="fs-1857" value="1857"> |- House (lossless)&nbsp;</option>
                                    <option id="fs-1859" value="1859"> |- House (Radioshow, Podcast, Liveset, Mixes)&nbsp;</option>
                                    <option id="fs-1858" value="1858"> |- House (lossy)&nbsp;</option>
                                    <option id="fs-840" value="840"> |- House (Проморелизы, сборники)&nbsp;</option>
                                    <option id="fs-1860" value="1860"> |- House (Singles, EPs) (lossy)&nbsp;</option>
                                    <option id="fs-1825" value="1825"> |- Techno (lossless)&nbsp;</option>
                                    <option id="fs-1826" value="1826"> |- Techno (lossy)&nbsp;</option>
                                    <option id="fs-1827" value="1827"> |- Techno (Radioshows, Podcasts, Livesets, Mixes)&nbsp;</option>
                                    <option id="fs-1828" value="1828"> |- Techno (Singles, EPs) (lossy)&nbsp;</option>
                                    <option id="fs-1808" value="1808" >Drum &amp; Bass, Jungle, Breakbeat, Dubstep, IDM, Electro&nbsp;</option>
                                    <option id="fs-797" value="797"> |- Electro, Electro-Freestyle, Nu Electro (lossless)&nbsp;</option>
                                    <option id="fs-1805" value="1805"> |- Electro, Electro-Freestyle, Nu Electro (lossy)&nbsp;</option>
                                    <option id="fs-1832" value="1832"> |- Drum &amp; Bass, Jungle (lossless)&nbsp;</option>
                                    <option id="fs-1833" value="1833"> |- Drum &amp; Bass, Jungle (lossy)&nbsp;</option>
                                    <option id="fs-1834" value="1834"> |- Drum &amp; Bass, Jungle (Radioshows, Podcasts, Livesets, Mix..&nbsp;</option>
                                    <option id="fs-1836" value="1836"> |- Breakbeat (lossless)&nbsp;</option>
                                    <option id="fs-1837" value="1837"> |- Breakbeat (lossy)&nbsp;</option>
                                    <option id="fs-1839" value="1839"> |- Dubstep (lossless)&nbsp;</option>
                                    <option id="fs-454" value="454"> |- Dubstep (lossy)&nbsp;</option>
                                    <option id="fs-1838" value="1838"> |- Breakbeat, Dubstep (Radioshows, Podcasts, Livesets, Mixe..&nbsp;</option>
                                    <option id="fs-1840" value="1840"> |- IDM (lossless)&nbsp;</option>
                                    <option id="fs-1841" value="1841"> |- IDM (lossy)&nbsp;</option>
                                    <option id="fs-2229" value="2229"> |- IDM Discography &amp; Collections (lossy)&nbsp;</option>
                                    <option id="fs-1809" value="1809" >Chillout, Lounge, Downtempo, Trip-Hop&nbsp;</option>
                                    <option id="fs-1861" value="1861"> |- Chillout, Lounge, Downtempo (lossless)&nbsp;</option>
                                    <option id="fs-1862" value="1862"> |- Chillout, Lounge, Downtempo (lossy)&nbsp;</option>
                                    <option id="fs-1947" value="1947"> |- Nu Jazz, Acid Jazz, Future Jazz (lossless)&nbsp;</option>
                                    <option id="fs-1946" value="1946"> |- Nu Jazz, Acid Jazz, Future Jazz (lossy)&nbsp;</option>
                                    <option id="fs-1945" value="1945"> |- Trip Hop, Abstract Hip-Hop (lossless)&nbsp;</option>
                                    <option id="fs-1944" value="1944"> |- Trip Hop, Abstract Hip-Hop (lossy)&nbsp;</option>
                                    <option id="fs-1810" value="1810" >Traditional Electronic, Ambient, Modern Classical, Electroac..&nbsp;</option>
                                    <option id="fs-1864" value="1864"> |- Traditional Electronic, Ambient (lossless)&nbsp;</option>
                                    <option id="fs-1865" value="1865"> |- Traditional Electronic, Ambient (lossy)&nbsp;</option>
                                    <option id="fs-1871" value="1871"> |- Modern Classical, Electroacoustic (lossless)&nbsp;</option>
                                    <option id="fs-1867" value="1867"> |- Modern Classical, Electroacoustic (lossy)&nbsp;</option>
                                    <option id="fs-1869" value="1869"> |- Experimental (lossless)&nbsp;</option>
                                    <option id="fs-1873" value="1873"> |- Experimental (lossy)&nbsp;</option>
                                    <option id="fs-1907" value="1907"> |- 8-bit, Chiptune (lossy &amp; lossless)&nbsp;</option>
                                    <option id="fs-1811" value="1811" >Industrial, Noise, EBM, Dark Electro, Aggrotech, Synthpop, N..&nbsp;</option>
                                    <option id="fs-1868" value="1868"> |- EBM, Dark Electro, Aggrotech (lossless)&nbsp;</option>
                                    <option id="fs-1875" value="1875"> |- EBM, Dark Electro, Aggrotech (lossy)&nbsp;</option>
                                    <option id="fs-1877" value="1877"> |- Industrial, Noise (lossless)&nbsp;</option>
                                    <option id="fs-1878" value="1878"> |- Industrial, Noise (lossy)&nbsp;</option>
                                    <option id="fs-1880" value="1880"> |- Synthpop, New Wave (lossless)&nbsp;</option>
                                    <option id="fs-1881" value="1881"> |- Synthpop, New Wave (lossy)&nbsp;</option>
                                    <option id="fs-1866" value="1866"> |- Darkwave, Neoclassical, Ethereal, Dungeon Synth (lossles..&nbsp;</option>
                                    <option id="fs-406" value="406"> |- Darkwave, Neoclassical, Ethereal, Dungeon Synth (lossy)&nbsp;</option>
                                    <option id="fs-1842" value="1842" class="root_forum">Label Packs (lossless)&nbsp;</option>
                                    <option id="fs-1648" value="1648" class="root_forum">Label packs, Scene packs (lossy)&nbsp;</option>
                                    <option id="fs-1812" value="1812" >Электронная музыка (Видео, DVD Video/Audio, HD Video, DTS, S..&nbsp;</option>
                                    <option id="fs-1886" value="1886"> |- Электронная музыка (Официальные DVD Video)&nbsp;</option>
                                    <option id="fs-1887" value="1887"> |- Электронная музыка  (Неофициальные, любительские DVD Vid..&nbsp;</option>
                                    <option id="fs-1912" value="1912"> |- Электронная музыка (Видео)&nbsp;</option>
                                    <option id="fs-1893" value="1893"> |- Hi-Res stereo (электронная музыка)&nbsp;</option>
                                    <option id="fs-1890" value="1890"> |- Многоканальная музыка (электронная музыка)&nbsp;</option>
                                    <option id="fs-1913" value="1913"> |- Электронная музыка (HD Video)&nbsp;</option>
                                    <option id="fs-1754" value="1754"> |- Электронная музыка (собственные оцифровки)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Игры">
                                    <option id="fs-5" value="5" >Игры для PC (раздачи)&nbsp;</option>
                                    <option id="fs-635" value="635"> |- Горячие Новинки&nbsp;</option>
                                    <option id="fs-959" value="959"> |- Демо-версии и игры с ранним доступом&nbsp;</option>
                                    <option id="fs-127" value="127"> |- Аркады&nbsp;</option>
                                    <option id="fs-2204" value="2204"> |- Логические игры&nbsp;</option>
                                    <option id="fs-53" value="53"> |- Приключения и квесты&nbsp;</option>
                                    <option id="fs-1008" value="1008"> |- Квесты в стиле &quot;Поиск предметов&quot;&nbsp;</option>
                                    <option id="fs-51" value="51"> |- Стратегии&nbsp;</option>
                                    <option id="fs-961" value="961"> |- Космические и авиасимуляторы&nbsp;</option>
                                    <option id="fs-962" value="962"> |- Автосимуляторы и гонки&nbsp;</option>
                                    <option id="fs-2187" value="2187"> |- Спортивные симуляторы&nbsp;</option>
                                    <option id="fs-54" value="54"> |- Другие симуляторы&nbsp;</option>
                                    <option id="fs-55" value="55"> |- Action&nbsp;</option>
                                    <option id="fs-2203" value="2203"> |- Файтинги&nbsp;</option>
                                    <option id="fs-52" value="52"> |- RPG&nbsp;</option>
                                    <option id="fs-900" value="900"> |- Аниме-игры&nbsp;</option>
                                    <option id="fs-246" value="246"> |- Эротические игры&nbsp;</option>
                                    <option id="fs-278" value="278"> |- Шахматы&nbsp;</option>
                                    <option id="fs-128" value="128"> |- Для самых маленьких&nbsp;</option>
                                    <option id="fs-2385" value="2385"> |- Флеш-игры&nbsp;</option>
                                    <option id="fs-637" value="637" >Старые Игры&nbsp;</option>
                                    <option id="fs-642" value="642"> |- Аркады и Логические игры (Старые игры)&nbsp;</option>
                                    <option id="fs-643" value="643"> |- Приключения и квесты (Старые игры)&nbsp;</option>
                                    <option id="fs-644" value="644"> |- Стратегии (Старые игры)&nbsp;</option>
                                    <option id="fs-2227" value="2227"> |- Автосимуляторы и гонки (Старые игры)&nbsp;</option>
                                    <option id="fs-2226" value="2226"> |- Авиасимуляторы и авиаигры (Старые игры)&nbsp;</option>
                                    <option id="fs-2225" value="2225"> |- Спортивные симуляторы (Старые игры)&nbsp;</option>
                                    <option id="fs-645" value="645"> |- Прочие симуляторы (Старые Игры)&nbsp;</option>
                                    <option id="fs-646" value="646"> |- Action (Старые игры)&nbsp;</option>
                                    <option id="fs-647" value="647"> |- RPG (Старые игры)&nbsp;</option>
                                    <option id="fs-649" value="649"> |- Эротические игры (Старые игры)&nbsp;</option>
                                    <option id="fs-650" value="650"> |- Для самых маленьких (Старые Игры)&nbsp;</option>
                                    <option id="fs-1098" value="1098"> |- Сборники Игр (Старые Игры)&nbsp;</option>
                                    <option id="fs-2228" value="2228"> |- IBM PC несовместимые (Старые игры)&nbsp;</option>
                                    <option id="fs-2115" value="2115" >Онлайн Игры&nbsp;</option>
                                    <option id="fs-2117" value="2117"> |- World of Warcraft&nbsp;</option>
                                    <option id="fs-2155" value="2155"> |- Lineage II&nbsp;</option>
                                    <option id="fs-2118" value="2118"> |- Прочие бесплатные&nbsp;</option>
                                    <option id="fs-2119" value="2119"> |- Прочие платные&nbsp;</option>
                                    <option id="fs-50" value="50"> |- Прочее для онлайн-игр&nbsp;</option>
                                    <option id="fs-2142" value="2142" >Microsoft Flight Simulator и аддоны для него&nbsp;</option>
                                    <option id="fs-2143" value="2143"> |- Сценарии, меши и аэропорты&nbsp;</option>
                                    <option id="fs-2145" value="2145"> |- Самолеты и вертолеты&nbsp;</option>
                                    <option id="fs-2146" value="2146"> |- Миссии, трафик, звуки, паки и утилиты&nbsp;</option>
                                    <option id="fs-139" value="139" >Прочее для PC-игр&nbsp;</option>
                                    <option id="fs-2478" value="2478"> |- Официальные патчи&nbsp;</option>
                                    <option id="fs-2479" value="2479"> |- Официальные моды, плагины, дополнения&nbsp;</option>
                                    <option id="fs-2480" value="2480"> |- Неофициальные моды, плагины, дополнения&nbsp;</option>
                                    <option id="fs-2481" value="2481"> |- Русификаторы&nbsp;</option>
                                    <option id="fs-761" value="761"> |- Редакторы, эмуляторы и прочие игровые утилиты&nbsp;</option>
                                    <option id="fs-2482" value="2482"> |- NoCD / NoDVD&nbsp;</option>
                                    <option id="fs-2533" value="2533"> |- Сохранения для игр&nbsp;</option>
                                    <option id="fs-2483" value="2483"> |- Чит-программы и трейнеры&nbsp;</option>
                                    <option id="fs-2484" value="2484"> |- Руководства и прохождения&nbsp;</option>
                                    <option id="fs-2485" value="2485"> |- Бонусные диски к играм&nbsp;</option>
                                    <option id="fs-240" value="240" >Игровое видео&nbsp;</option>
                                    <option id="fs-2415" value="2415"> |- Видеопрохождения игр&nbsp;</option>
                                    <option id="fs-2067" value="2067"> |- Lineage II Movies&nbsp;</option>
                                    <option id="fs-2147" value="2147"> |- World of Warcraft Movies&nbsp;</option>
                                    <option id="fs-960" value="960"> |- Counter Strike Movies&nbsp;</option>
                                    <option id="fs-548" value="548" >Игры для консолей&nbsp;</option>
                                    <option id="fs-129" value="129"> |- Портативные и Консольные (Игры)&nbsp;</option>
                                    <option id="fs-908" value="908"> |- PS&nbsp;</option>
                                    <option id="fs-357" value="357"> |- PS2&nbsp;</option>
                                    <option id="fs-510" value="510"> |- Xbox 360&nbsp;</option>
                                    <option id="fs-887" value="887"> |- Original Xbox&nbsp;</option>
                                    <option id="fs-1116" value="1116"> |- Игры PS1 для PSP&nbsp;</option>
                                    <option id="fs-973" value="973"> |- Программы для PSP (Homebrew)&nbsp;</option>
                                    <option id="fs-773" value="773"> |- Wii&nbsp;</option>
                                    <option id="fs-774" value="774"> |- NDS&nbsp;</option>
                                    <option id="fs-968" value="968"> |- Dreamcast&nbsp;</option>
                                    <option id="fs-546" value="546"> |- Игры для DVD плеера&nbsp;</option>
                                    <option id="fs-2185" value="2185" >Видео для консолей&nbsp;</option>
                                    <option id="fs-2487" value="2487"> |- Видео для PSVita&nbsp;</option>
                                    <option id="fs-2182" value="2182"> |- Фильмы для PSP&nbsp;</option>
                                    <option id="fs-2181" value="2181"> |- Сериалы для PSP&nbsp;</option>
                                    <option id="fs-2180" value="2180"> |- Мультфильмы для PSP&nbsp;</option>
                                    <option id="fs-2179" value="2179"> |- Дорамы для PSP&nbsp;</option>
                                    <option id="fs-2186" value="2186"> |- Аниме для PSP&nbsp;</option>
                                    <option id="fs-700" value="700"> |- Видео для PSP&nbsp;</option>
                                    <option id="fs-1926" value="1926"> |- Видео для PS3 и других консолей&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Программы и Дизайн">
                                    <option id="fs-1012" value="1012" >Операционные системы от Microsoft&nbsp;</option>
                                    <option id="fs-1019" value="1019"> |- Настольные ОС от Microsoft (выпущенные до Windows XP)&nbsp;</option>
                                    <option id="fs-2153" value="2153"> |- Настольные ОС от Microsoft (начиная с Windows XP)&nbsp;</option>
                                    <option id="fs-1021" value="1021"> |- Серверные ОС от Microsoft&nbsp;</option>
                                    <option id="fs-1025" value="1025"> |- Разное (Операционные системы от Microsoft)&nbsp;</option>
                                    <option id="fs-1376" value="1376" >Linux, Unix и другие ОС&nbsp;</option>
                                    <option id="fs-1379" value="1379"> |- Операционные системы (Linux, Unix)&nbsp;</option>
                                    <option id="fs-1381" value="1381"> |- Программное обеспечение (Linux, Unix)&nbsp;</option>
                                    <option id="fs-899" value="899"> |- Игры для Linux&nbsp;</option>
                                    <option id="fs-1473" value="1473"> |- Другие ОС и ПО под них&nbsp;</option>
                                    <option id="fs-1195" value="1195" class="root_forum">Тестовые диски для настройки аудио/видео аппаратуры&nbsp;</option>
                                    <option id="fs-1013" value="1013" >Системные программы&nbsp;</option>
                                    <option id="fs-1028" value="1028"> |- Работа с жёстким диском&nbsp;</option>
                                    <option id="fs-1029" value="1029"> |- Резервное копирование&nbsp;</option>
                                    <option id="fs-1030" value="1030"> |- Архиваторы и файловые менеджеры&nbsp;</option>
                                    <option id="fs-1031" value="1031"> |- Программы для настройки и оптимизации ОС&nbsp;</option>
                                    <option id="fs-1032" value="1032"> |- Сервисное обслуживание компьютера&nbsp;</option>
                                    <option id="fs-1033" value="1033"> |- Работа с носителями информации&nbsp;</option>
                                    <option id="fs-1034" value="1034"> |- Информация и диагностика&nbsp;</option>
                                    <option id="fs-1066" value="1066"> |- Программы для интернет и сетей&nbsp;</option>
                                    <option id="fs-1035" value="1035"> |- ПО для защиты компьютера (Антивирусное ПО, Фаерволлы)&nbsp;</option>
                                    <option id="fs-1038" value="1038"> |- Анти-шпионы и анти-трояны&nbsp;</option>
                                    <option id="fs-1039" value="1039"> |- Программы для защиты информации&nbsp;</option>
                                    <option id="fs-1536" value="1536"> |- Драйверы и прошивки&nbsp;</option>
                                    <option id="fs-1051" value="1051"> |- Оригинальные диски к компьютерам и комплектующим&nbsp;</option>
                                    <option id="fs-1040" value="1040"> |- Серверное ПО для Windows&nbsp;</option>
                                    <option id="fs-1041" value="1041"> |- Изменение интерфейса ОС Windows&nbsp;</option>
                                    <option id="fs-1636" value="1636"> |- Скринсейверы&nbsp;</option>
                                    <option id="fs-1042" value="1042"> |- Разное (Системные программы под Windows)&nbsp;</option>
                                    <option id="fs-1014" value="1014" >Системы для бизнеса, офиса, научной и проектной работы&nbsp;</option>
                                    <option id="fs-1060" value="1060"> |- Всё для дома: кройка, шитьё, кулинария&nbsp;</option>
                                    <option id="fs-1061" value="1061"> |- Офисные системы&nbsp;</option>
                                    <option id="fs-1062" value="1062"> |- Системы для бизнеса&nbsp;</option>
                                    <option id="fs-1067" value="1067"> |- Распознавание текста, звука и синтез речи&nbsp;</option>
                                    <option id="fs-1086" value="1086"> |- Работа с PDF и DjVu&nbsp;</option>
                                    <option id="fs-1068" value="1068"> |- Словари, переводчики&nbsp;</option>
                                    <option id="fs-1063" value="1063"> |- Системы для научной работы&nbsp;</option>
                                    <option id="fs-1087" value="1087"> |- САПР (общие и машиностроительные)&nbsp;</option>
                                    <option id="fs-1192" value="1192"> |- САПР (электроника, автоматика, ГАП)&nbsp;</option>
                                    <option id="fs-1088" value="1088"> |- Программы для архитекторов и строителей&nbsp;</option>
                                    <option id="fs-1193" value="1193"> |- Библиотеки и проекты для архитекторов и дизайнеров интер..&nbsp;</option>
                                    <option id="fs-1071" value="1071"> |- Прочие справочные системы&nbsp;</option>
                                    <option id="fs-1073" value="1073"> |- Разное (Системы для бизнеса, офиса, научной и проектной ..&nbsp;</option>
                                    <option id="fs-1052" value="1052" >Веб-разработка и Программирование&nbsp;</option>
                                    <option id="fs-1053" value="1053"> |- WYSIWYG Редакторы для веб-диза&nbsp;</option>
                                    <option id="fs-1054" value="1054"> |- Текстовые редакторы с подсветкой&nbsp;</option>
                                    <option id="fs-1055" value="1055"> |- Среды программирования, компиляторы и вспомогательные пр..&nbsp;</option>
                                    <option id="fs-1056" value="1056"> |- Компоненты для сред программирования&nbsp;</option>
                                    <option id="fs-2077" value="2077"> |- Системы управления базами данных&nbsp;</option>
                                    <option id="fs-1057" value="1057"> |- Скрипты и движки сайтов, CMS а также расширения к ним&nbsp;</option>
                                    <option id="fs-1018" value="1018"> |- Шаблоны для сайтов и CMS&nbsp;</option>
                                    <option id="fs-1058" value="1058"> |- Разное (Веб-разработка и программирование)&nbsp;</option>
                                    <option id="fs-1016" value="1016" >Программы для работы с мультимедиа и 3D&nbsp;</option>
                                    <option id="fs-1079" value="1079"> |- Программные комплекты&nbsp;</option>
                                    <option id="fs-1080" value="1080"> |- Плагины для программ компании Adobe&nbsp;</option>
                                    <option id="fs-1081" value="1081"> |- Графические редакторы&nbsp;</option>
                                    <option id="fs-1082" value="1082"> |- Программы для верстки, печати и работы со шрифтами&nbsp;</option>
                                    <option id="fs-1083" value="1083"> |- 3D моделирование, рендеринг и плагины для них&nbsp;</option>
                                    <option id="fs-1084" value="1084"> |- Анимация&nbsp;</option>
                                    <option id="fs-1085" value="1085"> |- Создание BD/HD/DVD-видео&nbsp;</option>
                                    <option id="fs-1089" value="1089"> |- Редакторы видео&nbsp;</option>
                                    <option id="fs-1090" value="1090"> |- Видео- Аудио- конверторы&nbsp;</option>
                                    <option id="fs-1065" value="1065"> |- Аудио- и видео-, CD- проигрыватели и каталогизаторы&nbsp;</option>
                                    <option id="fs-1064" value="1064"> |- Каталогизаторы и просмотрщики графики&nbsp;</option>
                                    <option id="fs-1092" value="1092"> |- Разное (Программы для работы с мультимедиа и 3D)&nbsp;</option>
                                    <option id="fs-1204" value="1204"> |- Виртуальные студии, секвенсоры и аудиоредакторы&nbsp;</option>
                                    <option id="fs-1027" value="1027"> |- Виртуальные инструменты и синтезаторы&nbsp;</option>
                                    <option id="fs-1199" value="1199"> |- Плагины для обработки звука&nbsp;</option>
                                    <option id="fs-1091" value="1091"> |- Разное (Программы для работы со звуком)&nbsp;</option>
                                    <option id="fs-828" value="828" >Материалы для мультимедиа и дизайна&nbsp;</option>
                                    <option id="fs-1357" value="1357"> |- Авторские работы&nbsp;</option>
                                    <option id="fs-890" value="890"> |- Официальные сборники векторных клипартов&nbsp;</option>
                                    <option id="fs-830" value="830"> |- Прочие векторные клипарты&nbsp;</option>
                                    <option id="fs-1290" value="1290"> |- Photostoсks&nbsp;</option>
                                    <option id="fs-1962" value="1962"> |- Костюмы для фотомонтажа&nbsp;</option>
                                    <option id="fs-831" value="831"> |- Рамки и виньетки для оформления фотографий&nbsp;</option>
                                    <option id="fs-829" value="829"> |- Прочие растровые клипарты&nbsp;</option>
                                    <option id="fs-633" value="633"> |- 3D модели, сцены и материалы&nbsp;</option>
                                    <option id="fs-1009" value="1009"> |- Футажи&nbsp;</option>
                                    <option id="fs-1963" value="1963"> |- Прочие сборники футажей&nbsp;</option>
                                    <option id="fs-1954" value="1954"> |- Музыкальные библиотеки&nbsp;</option>
                                    <option id="fs-1010" value="1010"> |- Звуковые эффекты&nbsp;</option>
                                    <option id="fs-1674" value="1674"> |- Библиотеки сэмплов&nbsp;</option>
                                    <option id="fs-2421" value="2421"> |- Библиотеки и саундбанки для сэмплеров, пресеты для синте..&nbsp;</option>
                                    <option id="fs-2492" value="2492"> |- Multitracks&nbsp;</option>
                                    <option id="fs-839" value="839"> |- Материалы для создания меню и обложек DVD&nbsp;</option>
                                    <option id="fs-1679" value="1679"> |- Стили, кисти, формы и узоры для Adobe Photoshop&nbsp;</option>
                                    <option id="fs-1011" value="1011"> |- Шрифты&nbsp;</option>
                                    <option id="fs-835" value="835"> |- Разное (Материалы для мультимедиа и дизайна)&nbsp;</option>
                                    <option id="fs-1503" value="1503" >ГИС, системы навигации и карты&nbsp;</option>
                                    <option id="fs-1507" value="1507"> |- ГИС (Геоинформационные системы)&nbsp;</option>
                                    <option id="fs-1526" value="1526"> |- Карты, снабженные программной оболочкой&nbsp;</option>
                                    <option id="fs-1508" value="1508"> |- Атласы и карты современные (после 1950 г.)&nbsp;</option>
                                    <option id="fs-1509" value="1509"> |- Атласы и карты старинные (до 1950 г.)&nbsp;</option>
                                    <option id="fs-1510" value="1510"> |- Карты прочие (астрономические, исторические, тематически..&nbsp;</option>
                                    <option id="fs-1511" value="1511"> |- Встроенная автомобильная навигация&nbsp;</option>
                                    <option id="fs-1512" value="1512"> |- Garmin&nbsp;</option>
                                    <option id="fs-1513" value="1513"> |- Ozi&nbsp;</option>
                                    <option id="fs-1514" value="1514"> |- TomTom&nbsp;</option>
                                    <option id="fs-1515" value="1515"> |- Navigon / Navitel&nbsp;</option>
                                    <option id="fs-1516" value="1516"> |- Igo&nbsp;</option>
                                    <option id="fs-1517" value="1517"> |- Разное - системы навигации и карты&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Мобильные устройства">
                                    <option id="fs-285" value="285" >Игры, приложения и пр. для мобильных устройств&nbsp;</option>
                                    <option id="fs-2149" value="2149"> |- Игры для Android OS&nbsp;</option>
                                    <option id="fs-2154" value="2154"> |- Приложения для Android OS&nbsp;</option>
                                    <option id="fs-2419" value="2419"> |- Приложения для Windows Phone 7,8&nbsp;</option>
                                    <option id="fs-2420" value="2420"> |- Игры для Windows Phone 7,8&nbsp;</option>
                                    <option id="fs-1004" value="1004"> |- Игры для Symbian&nbsp;</option>
                                    <option id="fs-289" value="289"> |- Приложения для Symbian&nbsp;</option>
                                    <option id="fs-1001" value="1001"> |- Игры для Java&nbsp;</option>
                                    <option id="fs-1005" value="1005"> |- Приложения для Java&nbsp;</option>
                                    <option id="fs-1002" value="1002"> |- Игры для Windows Mobile, Palm OS, BlackBerry и пр.&nbsp;</option>
                                    <option id="fs-290" value="290"> |- Приложения для Windows Mobile, Palm OS, BlackBerry и пр.&nbsp;</option>
                                    <option id="fs-288" value="288"> |- Софт для работы с телефоном&nbsp;</option>
                                    <option id="fs-292" value="292"> |- Прошивки для телефонов&nbsp;</option>
                                    <option id="fs-291" value="291"> |- Обои и темы&nbsp;</option>
                                    <option id="fs-957" value="957" >Видео для мобильных устройств&nbsp;</option>
                                    <option id="fs-287" value="287"> |- Видео для смартфонов и КПК&nbsp;</option>
                                    <option id="fs-286" value="286"> |- Видео для мобильных (3GP)&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Apple">
                                    <option id="fs-1366" value="1366" >Apple Macintosh&nbsp;</option>
                                    <option id="fs-1368" value="1368"> |- Mac OS (для Macintosh)&nbsp;</option>
                                    <option id="fs-1383" value="1383"> |- Mac OS (для РС-Хакинтош)&nbsp;</option>
                                    <option id="fs-537" value="537"> |- Игры Mac OS&nbsp;</option>
                                    <option id="fs-1394" value="1394"> |- Программы для просмотра и обработки видео&nbsp;</option>
                                    <option id="fs-1370" value="1370"> |- Программы для создания и обработки графики&nbsp;</option>
                                    <option id="fs-2237" value="2237"> |- Плагины для программ компании Adobe&nbsp;</option>
                                    <option id="fs-1372" value="1372"> |- Аудио редакторы и конверторы&nbsp;</option>
                                    <option id="fs-1373" value="1373"> |- Системные программы&nbsp;</option>
                                    <option id="fs-1375" value="1375"> |- Офисные программы&nbsp;</option>
                                    <option id="fs-1371" value="1371"> |- Программы для интернета и сетей&nbsp;</option>
                                    <option id="fs-1374" value="1374"> |- Другие программы&nbsp;</option>
                                    <option id="fs-1933" value="1933" >iOS&nbsp;</option>
                                    <option id="fs-1935" value="1935"> |- Программы для iOS&nbsp;</option>
                                    <option id="fs-1003" value="1003"> |- Игры для iOS&nbsp;</option>
                                    <option id="fs-1937" value="1937"> |- Разное для iOS&nbsp;</option>
                                    <option id="fs-2235" value="2235" >Видео&nbsp;</option>
                                    <option id="fs-1908" value="1908"> |- Фильмы для iPod, iPhone, iPad&nbsp;</option>
                                    <option id="fs-864" value="864"> |- Сериалы для iPod, iPhone, iPad&nbsp;</option>
                                    <option id="fs-863" value="863"> |- Мультфильмы для iPod, iPhone, iPad&nbsp;</option>
                                    <option id="fs-2535" value="2535"> |- Аниме для iPod, iPhone, iPad&nbsp;</option>
                                    <option id="fs-2534" value="2534"> |- Музыкальное видео для iPod, iPhone, iPad&nbsp;</option>
                                    <option id="fs-2238" value="2238" >Видео HD&nbsp;</option>
                                    <option id="fs-1936" value="1936"> |- Фильмы HD для Apple TV&nbsp;</option>
                                    <option id="fs-315" value="315"> |- Сериалы HD для Apple TV&nbsp;</option>
                                    <option id="fs-1363" value="1363"> |- Мультфильмы HD для Apple TV&nbsp;</option>
                                    <option id="fs-2082" value="2082"> |- Документальное видео HD для Apple TV&nbsp;</option>
                                    <option id="fs-2241" value="2241"> |- Музыкальное видео HD для Apple TV&nbsp;</option>
                                    <option id="fs-2236" value="2236" >Аудио&nbsp;</option>
                                    <option id="fs-1909" value="1909"> |- Аудиокниги (AAC, ALAC)&nbsp;</option>
                                    <option id="fs-1927" value="1927"> |- Музыка Lossless (ALAC)&nbsp;</option>
                                    <option id="fs-2240" value="2240"> |- Музыка Lossy (AAC)&nbsp;</option>
                                    <option id="fs-2244" value="2244"> |- Музыка Lossy (AAC) (Singles, EPs)&nbsp;</option>
                                    <option id="fs-2243" value="2243" >F.A.Q.&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Медицина и здоровье">
                                    <option id="fs-2125" value="2125">Книги, журналы и программы&nbsp;</option>
                                    <option id="fs-2133" value="2133"> |- Клиническая медицина до 1980 г.&nbsp;</option>
                                    <option id="fs-2130" value="2130"> |- Клиническая медицина с 1980 по 2000 г.&nbsp;</option>
                                    <option id="fs-2313" value="2313"> |- Клиническая медицина после 2000 г.&nbsp;</option>
                                    <option id="fs-2314" value="2314"> |- Популярная медицинская периодика (газеты и журналы)&nbsp;</option>
                                    <option id="fs-2528" value="2528"> |- Научная медицинская периодика (газеты и журналы)&nbsp;</option>
                                    <option id="fs-2129" value="2129"> |- Медико-биологические науки&nbsp;</option>
                                    <option id="fs-2141" value="2141"> |- Фармация и фармакология&nbsp;</option>
                                    <option id="fs-2132" value="2132"> |- Нетрадиционная, народная медицина и популярные книги о з..&nbsp;</option>
                                    <option id="fs-2131" value="2131"> |- Ветеринария, разное&nbsp;</option>
                                    <option id="fs-2315" value="2315"> |- Тематические коллекции книг&nbsp;</option>
                                    <option id="fs-1350" value="1350"> |- Аудиокниги по медицине&nbsp;</option>
                                    <option id="fs-2134" value="2134"> |- Медицинский софт&nbsp;</option>
                                    <option id="fs-2126" value="2126">Видеоуроки, док. фильмы и телепередачи по медицине&nbsp;</option>
                                    <option id="fs-2135" value="2135"> |- Медицина и стоматология&nbsp;</option>
                                    <option id="fs-2140" value="2140"> |- Психотерапия и клиническая психология&nbsp;</option>
                                    <option id="fs-2136" value="2136"> |- Массаж&nbsp;</option>
                                    <option id="fs-2138" value="2138"> |- Здоровье&nbsp;</option>
                                    <option id="fs-2139" value="2139"> |- Документальные фильмы и телепередачи по медицине&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Разное">
                                    <option id="fs-10" value="10">Разное&nbsp;</option>
                                    <option id="fs-865" value="865"> |- Психоактивные аудиопрограммы&nbsp;</option>
                                    <option id="fs-1100" value="1100"> |- Аватары, Иконки, Смайлы&nbsp;</option>
                                    <option id="fs-1643" value="1643"> |- Живопись, Графика, Скульптура, Digital Art&nbsp;</option>
                                    <option id="fs-848" value="848"> |- Картинки&nbsp;</option>
                                    <option id="fs-808" value="808"> |- Любительские фотографии&nbsp;</option>
                                    <option id="fs-630" value="630"> |- Обои&nbsp;</option>
                                    <option id="fs-1664" value="1664"> |- Фото знаменитостей&nbsp;</option>
                                    <option id="fs-148" value="148"> |- Аудио&nbsp;</option>
                                    <option id="fs-807" value="807"> |- Видео&nbsp;</option>
                                    <option id="fs-147" value="147"> |- Публикации и учебные материалы (тексты)&nbsp;</option>
                                    <option id="fs-1319" value="1319"> |- Спорт (видео)&nbsp;</option>
                                    <option id="fs-847" value="847"> |- Трейлеры и дополнительные материалы к фильмам&nbsp;</option>
                                    <option id="fs-1167" value="1167"> |- Любительские видеоклипы&nbsp;</option>
                                    <option id="fs-19" value="19">Тестовый форум&nbsp;</option>
                                </optgroup>
                                <optgroup label="&nbsp;Обсуждения, встречи, общение">
                                    <option id="fs-1341" value="1341"> |- ПРАВОВЫЕ ВОПРОСЫ ПО БЛОКИРОВКЕ РЕСУРСА&nbsp;</option>
                                    <option id="fs-321" value="321"> |- Отчеты о встречах&nbsp;</option>
                                </optgroup>
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
    $rt_result = array();
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

        if(isset($_GET['f'])){
            for($i = 0; $i< count($_GET['f']); $i++){
                if($i == 0){
                    $groups .= $_GET['f'][$i];
                }  else{
                    $groups .= ",". $_GET['f'][$i];
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

        $rt_result = $rt->search($search_str, $options);
    }

    if(count($rt_result) !== 0 && !array_key_exists('error', $rt_result)){
        echo "<p> <span class='search-word'>Rutracker</span> Found: ". (count($rt_result) -2) . " results</p><br>";

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
                echo '<td><a href="index.php?' . $rt_result[$i]["section_link_search"] . '&nm='.$search_str.'">
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

