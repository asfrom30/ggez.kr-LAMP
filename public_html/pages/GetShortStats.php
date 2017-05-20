<?php


require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(LIBRARY_PATH . '/DbHandler.php');	// Only use for connectDB
require_once(LIBRARY_PATH . "/MysqlHandler.php"); // Only use for sql query
require_once(LIBRARY_PATH . "/DataComputeHandler.php"); // Only use for sql query

header ( "Content-Type: text/html; charset=UTF-8" );

/** Global Variable Decalartion **/
/* Debug Mode */
$isDebugMode = false;

/* Create Result Structure */
$result = array();
$error = array("hasError" => true, "errorCode" => "default error");

/* Parameter 설정확인 */
if(!isset($_GET['playerId'])){
	$error["errorCode"] = "playerId가 설정되어 있지 않습니다.";
	sendResponse($result, $error, $isDebugMode);
	return;
}
if(!isset($_GET['searchType'])){
	$error["errorCode"] = "searchType이 설정되어 있지 않습니다.";
	sendResponse($result, $error, $isDebugMode);
	return;
}

/* Connect DB */
$mysqli = connectDB();

/* pkHeaderName */
$pkHeaderName = 'btg';	// $pkHeaderName='id'로 변경할 것

if($mysqli == null){
	$error["errorCode"] = "DB 연결중에 에러가 발생했습니다.";
	sendResponse($result, $error, $isDebugMode);
	return;
}

/* Initiate mysqlHandler Object for Query */
$mysqlHandler = new MysqlHandler($mysqli);

/* Explode playerId and make array */
$playerIds = explode(" ", $_GET['playerId']);	// url encode space with '+' sign or with '$20'

/* Get Search Type */
$searchType = $_GET['searchType'];
$searchType = filter_var($searchType, FILTER_VALIDATE_BOOLEAN);

/* Create Compute Data Handler Object for Most3 */
$dataComputeHandler = new DataComputeHandler();

/* 배틀네임으로 조회 합니다. */
if($searchType==false){
	$pattern = $playerIds[0] . '-'; // 끝에 -를 붙여준다. 아무글자만 쳐서 검색되지 않기 하기 위함.
	$rows = $mysqlHandler -> getRowValuesWithLike('playerlist', 'btg', $pattern.'%', 'btg');
	if($rows != false){
		foreach ($rows as $row){
			array_push($playerIds, $row[0]);
		}	
	}
} else {	
	/* No need to do */
	/* Pramary Key(Id)로 조회 합니다. */
}


/* Each Player Loop and store data */
foreach ($playerIds as $playerId){
	/* Get data from Database */
// 	$playerIds[0] = '%EB%B0%98%EC%A7%9D%EB%B0%98%EC%A7%9D-3939';
// 	$playerId = urldecode($playerId);	//TODO:consider this code
	$playerDatas = getCurrentData($playerId);	// Query to Database
	
	/* fetch data */
	$battleName = $playerDatas[0];
	$dbDate = $playerDatas[1];
	$playerData = $playerDatas[2];
	
	/* 사용자 데이터가 없으면 무시한다. */
// 	if(id == null) continue;
	if($battleName == null) continue;
	
	/* Json to Array */
	$playerData = json_decode($playerData, true);
	
	/* Store Variable */
	global $result;
	$result[$playerId]['battleName'] = $battleName;
	$result[$playerId]['dbDate'] = $dbDate;
	$result[$playerId]['platform'] = 'PC-KR';
	$result[$playerId]['iconUrl'] = $playerData['All']['etcData']['iconUrl'];
	$result[$playerId]['cptPt'] = $playerData['All']['etcData']['cptPt'];
	/* Store Battle Name */
	/* Store Most 3 */
	$tempData = $dataComputeHandler -> removeCtg($playerData);
	$result[$playerId]['most3'] = $dataComputeHandler -> getMost3($tempData);
}

/* send Response */
$error["hasError"] = false;	$error["errorCode"] = null;
sendResponse($result, $error, $isDebugMode);

/**
 *  End of Code
 */

/* 오늘 날짜 조회후 없으면 어제날짜, 그래도 없으면 current에서 가져온다. */
function getCurrentData($playerId){
	
	global $mysqlHandler;
	global $pkHeaderName;
	
	$todayDbName = date("Y_m_d"); // 오늘
	$yesterDbName = date("Y_m_d", strtotime("-1 day")); // 어제
	$currentDbName ='current';
	$pkKey = $playerId;
	
	/* Return Data */
	if($mysqlHandler -> hasRowWithPK($todayDbName, $pkHeaderName, $pkKey)){
		$tableName = $todayDbName;
	} else if ($mysqlHandler -> hasRowWithPK($yesterDbName, $pkHeaderName, $pkKey)){
		$tableName = $yesterDbName;
	} else if ($mysqlHandler -> hasRowWithPK($currentDbName, $pkHeaderName, $pkKey)){
		$tableName = $currentDbName;
	} else {
		return null;
	}
	
	$battleTag = $mysqlHandler -> getRowValueWithPK($tableName, $pkHeaderName, $pkKey, 'btg');
	$battleName = convBtg2Btn($battleTag);
	$playerData = $mysqlHandler -> getRowValueWithPK($tableName, $pkHeaderName, $pkKey, 'Datas');
	
	return array($battleName, $tableName, $playerData);
}

function sendResponse($result, $error, $isDebugMode){
	$response["result"] = $result;
	$response["error"] = $error;

	if($isDebugMode){
		var_dump($response);
	} else {
		echo json_encode($response);
	}
}

/* Battle Tag를 Battle Name으로 변경합니다 */
function convBtg2Btn($battleTag){
	$pos = strpos($battleTag, '-');
	
	/* $pos가 정수값을 갖으면 true 입니다. */
	if($pos){
		return substr($battleTag, 0, $pos);
	} else {
		
		$pos = strpos($battleTag, '#');
		if ($pos){
			return substr($battleTag, 0, $pos);
		} else {
			return $battleTag;
		}
	}
}







