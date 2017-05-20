<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (LIBRARY_PATH . "/TierDataHandler.php");
require_once (LIBRARY_PATH . "/DbHandler.php");
require_once (LIBRARY_PATH . "/DataFilter.php");
require_once (INIT_PATH . "/HeroInfoList.php");

$mysqli = connectDB();
$statMap = getStatMap();
$tierDataHandler = new TierDataHandler($mysqli, $statMap);
$tierDataHandler -> testRun(/** object row **/ '2017_02_06',/** target row **/  '2017_02_06');

$mysqli -> close();

echo 'success';