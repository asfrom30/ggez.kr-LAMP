<?php
// 숫자를 확인하느 것 뿐입니다.

require_once '../../resources/config.php';

require_once (LIBRARY_PATH . "/DbHandler.php");	// Connect DB
require_once (LIBRARY_PATH . "/CronDatas.php");	// Connect DB


echo '===== Start ======<br>';

// cronCheck($mysqli, $today_date);
// Cron Daily Data
$mysqli = connectDB();
echo 'Connect Db Success<br>';
$playerNum = getRowNum($mysqli, 'playerlist');
// 12시가 넘으면 정상 작동하지 않는다. 4시 기준이기 때문에
$today_date = date('Y_m_d');
$todayNum = getRowNum($mysqli, $today_date);

echo "Player Number is " . $playerNum . "<br>";
echo $today_date . "] Today Crawl Number is " . $todayNum . "<br>";



$mysqli->close();


// cronIndex를 비운다.
// playerlist를 가져온다.
// index가 500이 될때마다.
// cronIndex Table에 저장한다.

// 크론잡을 병렬로 수행한다.
// 5개 추가하고 table이 없으면 NULL을 반환한다.



echo '===== End ======<br>';