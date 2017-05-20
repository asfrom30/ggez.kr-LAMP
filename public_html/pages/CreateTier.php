<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (LIBRARY_PATH . "/DbHandler.php");
require_once (LIBRARY_PATH . "/DataFilter.php");
require_once (INIT_PATH . "/HeroInfoList.php");
require_once (LIBRARY_PATH . "/TierDataHandler.php");

echo 'tier Table create start<br>';
$mysqli = connectDB();
$statMap = getStatMap();
$tierDataHandler = new TierDataHandler($mysqli, $statMap);
$tierDataHandler -> manualRun('current', '2017_01_25');
echo 'tier Table created success';
