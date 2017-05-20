<?php

require_once '../../resources/config.php';

require_once (LIBRARY_PATH . "/DbHandler.php");	// Connect DB
require_once (LIBRARY_PATH . "/CronDatas.php");	// Connect DB

echo '===== Start ======<br>';

// cronCheck($mysqli, $today_date);
// Cron Daily Data
$mysqli = connectDB();
echo 'Connect Db Success<br>';
$playerNum = getRowNum($mysqli, 'playerlist');
$today_date = date('Y_m_d');
$todayNum = getRowNum($mysqli, $today_date);

$tierNumResult = getRow('tierdata', 'Datas', 'id', 'temp');
$tierNumResult = json_decode($tierNumResult, true);
$tierNumSum = $tierNumResult['etcData']['sum'];

echo "Player Number is " . $playerNum . "<br>";
echo $today_date . "] Today Crawl Number is " . $todayNum . "<br>";
echo "Tier Data Total Sum number is " . $tierNumSum . "<br>";


$mysqli->close();


// cronIndex를 비운다.
// playerlist를 가져온다.
// index가 500이 될때마다.
// cronIndex Table에 저장한다.

// 크론잡을 병렬로 수행한다.
// 5개 추가하고 table이 없으면 NULL을 반환한다.



echo '===== End ======<br>';