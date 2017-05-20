<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once(LIBRARY_PATH . '/DbHandler.php');

// if(!isset($_GET['btg'])) {
// 	exit ();
// }

// 구분자로 btg 배열에 저장
$btgs = explode(", ", $_GET['btg']);

$btg = urldecode($btgs[0]);
// $loc = $_GET['loc'];
// $device = $_GET['device'];

// Test Code
$result = getDuplicateBtg($btg . "-");

$length = count($result);

for($index = 0; $index < $length; $index++){
	$row = $result[$index];
	$tableName = 'current';
	$columnName = 'Datas';
	$rowName = 'btg';
	$rowId = $row['btg'];
	$datas = getRow($tableName, $columnName, $rowName, $rowId);
	$datas = json_decode($datas, true);
	$result[$index]['btg'] = $rowId;
	$result[$index]['hiddenBtg'] = $btg;
	$result[$index]['cptPt'] = $datas['All']['etcData']['cptPt'];
	$result[$index]['iconUrl'] = $datas['All']['etcData']['iconUrl'];
}

//TODO: 경쟁전 점수 오름차순으로 정렬해서 반환 할 것.
$response = array('staus' => 'ok', 'result' => $result);
echo json_encode($response);
