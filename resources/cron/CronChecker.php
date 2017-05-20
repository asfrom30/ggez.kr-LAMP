<?php

require_once '../../resources/config.php';

require_once (LIBRARY_PATH . "/DbHandler.php");	// Connect DB
require_once (LIBRARY_PATH . "/CronDatas.php");	// Connect DB

// Cron Daily Data
$mysqli = connectDB();


// 오늘 날짜로 된 2017.2.12일 데이터를 꺼내오고
// player list에 있는지 확인하고
// 없으면 다시 크론을 실행한다.

$today_date = date('Y_m_d');

echo 'start';

cronCheck($mysqli, $today_date);
$mysqli->close();