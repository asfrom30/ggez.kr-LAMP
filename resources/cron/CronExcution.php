<?php

/**
 * 주의 Cron에서는 $_SERVER['DOCUMENT_ROOT']가 NULL 이다.
*/
require_once '../../resources/config.php';

require_once (LIBRARY_PATH . "/DbHandler.php");	// Connect DB
require_once (LIBRARY_PATH . "/CronDatas.php");	// Connect DB


// Cron Daily Data
$mysqli = connectDB();

if($mysqli == null){
	die();
} else {
	$today_date = date('Y_m_d');
	$table = $today_date;
    $table = $table + 'test';
	//TODO: 이미 있는 테이블 네임인지를 확인할 것. 이미 있다면 안에 있는 테이블 내용을.. 어떻게 할것인가... 충돌 메일을 보내야할듯..
	createTable($mysqli, $table);
	cronDatas($mysqli, $table);
}

$mysqli->close();
