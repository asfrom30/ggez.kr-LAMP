<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(LIBRARY_PATH . '/DbHandler.php');

$mysqli = connectDB();

if($mysqli == null){
	echo 'failed Db Connect<br>';
	echo '$mysqli->connect_error';
} else {
	echo 'Connect Success<br>';
	echo 'Current Charset : ' . $mysqli->character_set_name () . '<br>';
// 	echo '총사용자수는' xx 명입니다
// 전체 사용자수는 xxx명
// 오늘 등록한 사용자수는 xxx명
// 오늘 접속한 사람 수는 xxx명 입니다.
}
$mysqli -> close();

?>

