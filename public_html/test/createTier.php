<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (LIBRARY_PATH . "/TierDataHandler.php");
require_once (LIBRARY_PATH . "/DbHandler.php");
require_once (LIBRARY_PATH . "/DataFilter.php");
require_once (INIT_PATH . "/HeroInfoList.php");

echo '=========== start ===========<br>';

$mysqli = connectDB();
$statMap = getStatMap();
$tierDataHandler = new TierDataHandler($mysqli, $statMap);
// $beforeDay = date('Y_m_d');
$beforeDay = date("Y_m_d", strtotime($day." -1 day"));

$tierDataHandler -> manualRun(/** object row **/ $beforeDay,/** target row **/  $beforeDay);
// org
// $tierDataHandler -> manualRun(/** object row **/ $today_date,/** target row **/  '2017_02_11');
$mysqli -> close();

echo '=========== end ============<br>';