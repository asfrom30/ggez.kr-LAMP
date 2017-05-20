<?php
// 직접 접속 방지
// 이 코드가 있으면 $_GET에 실어보내면 안먹히네... 왜그러지??
// if (! defined ( 'ABSPATH' )) {
// header('Location: http://localhost/ggez/');
// exit ();
// }
header ( "Content-Type: text/html; charset=UTF-8" );
// 에러발생시 어떻게 할 것인지 처리, 간헐적 필요
require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(INIT_PATH . "/HeroInfoList.php");
require_once(LIBRARY_PATH . "/CrawlDatas.php");
require_once(LIBRARY_PATH . "/DataFilter.php");
require_once(LIBRARY_PATH . '/DbHandler.php');
// require_once(LIBRARY_PATH . '/TierDataHandler.php');

$btg = $_GET ['btg'];
$btg = urldecode($btg);

$isRefresh = $_GET ['refresh'];
if ($btg == null) {
	$response = array('status' => 'false', 'errMessage' => getErrMessage(0)); $error = array('errFlag' => false, 'errCode' => 0, 'errMessage' => '');
	echo json_encode ( $response, JSON_UNESCAPED_UNICODE );	// 한글깨짐 방지.
	return;
} 

/** Stauts Defaul Value **/
$dataStatus = array(); // label => true; ex) ystDay => true, week => true;
$dataResult = array();
$isFirstVisit = false;

$isExistPlayer = checkId($btg, 'playerlist');	// playerlist table에 해당 btg가 있는지 확인

if(!$isExistPlayer){	// Player List에 등록된 아이디가 아니면
	$crawlData = getCrawlDatas($btg);	// Crawl Data를 시도
	if($crawlData == null){ 	// Crawl Data가 검증되지 않았으면 해당 사용자는 존재하지 않음.
		sendError(2, getErrMessage(2));	//"배틀넷에 존재하지 않는 사용자입니다.";
		return;
	} else {	// Crawl Data가 검증된 데이터이면 사용자를 추가합니다.
		$isFirstVisit = true; // 처음 방문하여서 배틀태그가 추가되는 사용자 입니다.
		if(!regNewBtg($btg, $crawlData)){
			sendError(3, getErrMessage(3));
			return;
		} else {	// 정상적으로 regsiter New Battle Tag가 된 경우
			// continue
		}
	}
} 

// Deprecated
// 이유. CurrentData를 체크할 이유가 없다. 등록과정에서 Current Data를 무조건 삽입하고 지우지 않기 때문에.
// 오히려 Current Data가 지난 데이터라면 update만 해주면 된다.
// $isExistCurrentData = checkId($btg, 'current');			// current table에 btg가 있는지 확인한다.
// if($isExistCurrentData)
// }

// 처음 접속하는 사용자라고 하더라도, 검증된 아이디이면 instDbDatas로 current와 today에 저장 되므로 이미 있는 ID가 된다.
// 그러므로, CurrentData의 개수와 PlayerList의 개수는 일치해야한다.
// 즉 첫번째 if문을 거치면 있는 사용자라고 봐야한다.

// Db 준비하기.
// Fetch Array
$btg = getCaseSensitiveId($btg, 'playerlist');

global $config;
if($config["test_mode"]){
	$currentData = getDBDatas($btg, "2017_02_12");
	$rctDayData = getDBDatas($btg, "2017_02_11");
	$rctWeekData = getDBDatas($btg, "2017_01_29");
	$rctYstData = getDbDatas($btg, "2017_02_01");
} else {
	$currentData = getDBDatas($btg, 'current');
	$rctDayData = getDBDatas($btg, getDbName('recentDay'));
	$rctWeekData = getDBDatas($btg, getDbName('recentWeek'));
	$rctYstData = getDbDatas($btg, getDbName('yesterDay'));
}



// TODO : table 에러 추가.
// isTable($dbName) Table Error 추가

// CurrentData는 기준 데이터 이므로 NULL이면 중지.
if($currentData == null){
	sendError(4, getErrMessage(4));
	return;
}


// 여기까지 정상 진행 되었으면 currentDay와 rctDayData는 무조건 있을거라는 전제하에 진행한다.
if(needUpdate($currentData, $rctDayData)){
	$crawlData = getCrawlDatas($btg);
	updateDbDatas('current', $btg, $crawlData);
	$currentData = getDBDatas($btg, 'current');
}

// Column Data
$currentData = getDatasColumn($currentData);
$rctDayData = getDatasColumn($rctDayData);
$rctWeekData = getDatasColumn($rctWeekData);
$rctYstData = getDatasColumn($rctYstData);

if($currentData == null){
	sendError(5, getErrMessage(5));
	return;
}
// Create Radar Data Version 2
$dataResult = createDefaultData($currentData, $rctDayData, $rctWeekData);

// Add Period Data(B(pre)-A(post))
$dataResult = addPrdDatas($dataResult,/** label(Javascript uses this id) **/ 'ystday', /** dataB **/ $rctYstData, /** dataA **/ $rctDayData);

// Add Tier Data
// TODO : temp가 아니라 실제 어떤 데이터를 목적으로할건지 변경할것.
$tierData = getRow('tierdata', 'Datas', 'id', 'temp');
$tierData = json_decode($tierData, true);
$tierResult = calcTierData($tierData);

// Create in working Response
$response = array('status' => 'ok', 'isFirstVisit' => $isFirstVisit, 'dataStatus' =>  $dataStatus, 'dataResult' => $dataResult, 'tierResult' => $tierResult, 'errFlag' => false); // after
sendResponse($response, /** test Mode **/ false);

// Debug Message
// printDebugMessage(printDebugMessage($result, $currentData, $rctDayData, $rctWeekData);

// DB 행에서 data 열의 내용만 추출합니다.
function getDatasColumn($data){
	if($data == null){
		return null;
	}
	
	if(array_key_exists('Datas', $data)){
		return json_decode($data['Datas'],true); // 뒤에 옵션이 true이면 Array형태 그렇지 않으면 stdclass 형태로 나온다..
	} else {
		return null;
	}
}

// 업데이트 필요한지 점검합니다.
function needUpdate($currentData, $rctDayData){
	
	$interval = getInterval ( $currentData ['reg_date'] );
	
	/* Get Update Time*/
	global $config;
	$updateTime = intval($config["updateTime_S"]);
	
	if($interval > $updateTime){	// 등록된 사용자는 사용자가 600초 이후에 접속하면 무조건 갱신하기..
		return true;
	}
	
	if($rctDayData == null){
		return false;
	} else {
		$currentRegTime = strtotime($currentData['reg_date']);
		$todayRegTime = strtotime($rctDayData['reg_date']);
		$bufferTime_s = 60;
		
		if($currentRegTime + $bufferTime_s < $todayRegTime ){ // 혹시나 최근 조회 데이터가 오늘 조회 데이터보다 오래됐으면 업데이트가 필요
			return true;
		} else {
			return false;
		}
	}
}

function sendResponse($response, $testMode){
	if($testMode){
// 		echo "<pre>";
// 		print_r($response);
// 		echo "</pre>";
		echo var_dump($response);
	} else {
		echo json_encode ( $response, JSON_UNESCAPED_UNICODE );	// 한글깨짐 방지.
	}
}

function printDebugMessage($result, $currentData, $rctDayData, $rctWeekData){
	$result = array ("error" => "");	// error 코드 추가.
	echo '<pre>';
	echo print_r($result);
	echo "========CurrentData";
	print_r($currentData['All']);
	echo "========recent Day Data";
	print_r($rctDayData['All']);
	echo "========recent Week Data";
	print_r($rctWeekData['All']);
	echo '</pre>';
}

function regNewBtg($btg, $crawlData){
	$result = true;

	addBtg($btg, 'playerlist');
	instDbDatas (getDbName('recentDay'), $btg, $crawlData);	// crawl data를 today 데이터에 db에 저장.
	instDbDatas ('current', $btg, $crawlData);	// crawl data를 current 데이터에 db에 저장.
	

	if($result == false){
		//TODO : roll back code
	}

	return $result;
}

function sendError($errorCode, $errMessage){
	$error = array('errCode' => $errorCode, 'errMessage' => $errMessage);
	$response = array('status' => 'ok', 'errFlag' => true, 'error' => $error);
	sendResponse($response);
}

function getErrMessage($errorCode){
	switch ($errorCode) {
		case 1:
			return "btg 파라메터를 전달받지 못했습니다.";
			break;
		case 2:
			return "배틀넷에 존재하지 않는 사용자입니다.(Crawal 값이 NULL 입니다.)";
			break;
		case 3:
			break;
			return "Crawl은 성공하였으나, 새로운 사용자 DB입력에 실패했습니다. DB를 점검해주세요";
		case 4:
			return "current data update에 실패했습니다. 또는 crawl data 값이 NULL입니다. 점검해주세요.";
			break;
		case 5:
			return "Current Table에서 해당 btg의 행을 찾을 수 없습니다.";	// JsonDecode가 실패해도 이런 메세지가 나옴.
			break;
		default:
			return null;
			break;
	}
	
}

/** Deprecated
function getDbData($dbId, $btg){
	$dbName = getDbName($dbId);
	
	if(isTable($dbName)){
		if(checkId($btg, $dbName)){
			$data = getDBDatas($btg, $dbName);
			$data = $data['Datas'];// get Data Datas column
			$data = json_decode($data, true);
			return $data;
		} else {	//Not in here
			return null;
		}
	} else {
		return null;
	}
}
 **/

function getDbName($type){
	if($type == 'recentWeek'){
		$result = date("Y_m_d", strtotime("-1 week"));
	} else if($type == 'recentDay') {
		// TODO: 몇일날 데이터 테이블에 가져왔는지 JSON에 포함해서 날릴 것.
		// Today Cron File은 4:00에 생성되기 때문에, 00:00 ~ 04:00에 조회했을때는 데이터가 없게된다.
		// 이때는 전전날 데이터를 빌려 오는 것으로 하자.
		if(date("H") < 6){ // 6시전까지 Cron 작업을 마무리 할것.
			$result = date("Y_m_d", strtotime("-1 day"));
		} else {
			$result = date("Y_m_d");
		}
	} else if($type == 'yesterDay'){
		if(date("H") < 6){ // 6시전까지 Cron 작업을 마무리 할것.
			$result = date("Y_m_d", strtotime("-2 day"));
		} else {
			$result = date("Y_m_d", strtotime("-1 day"));
		}
	} else {
		$result = null;
	}
	return $result;
}

function getInterval($time) {
	$currentTime = $time;
	$presentTime = date ( "Y-m-d H:i:s", time () );
	$interval = strtotime ( $presentTime ) - strtotime ( $currentTime );
	
	return $interval;
}

/** Deprecated
function old(){

	// TODO : 자주 요청하지 못하게 방지하는 기능... 잘못하면 블럭당함.
	// if(true){ //최근에 업데이트 한 이력이 없으면...

	$btgType = "";	// playerlist에 있는지 없는지, current에 있는지 없는지, playoverwatch에 있는지 없는지..

	$currentData = null;
	$rctDayData = null; // dataA는 today, week 선택에 따라 있을 수도 있고 없을 수도 있다...
	$rctWeekData = null;
	$result= "";

	$dbFields = getDBDatas ( $btg, "current" ); // 최근 조회 이력이 있는지 확인..
	if ($dbFields) { // 최근조회(오늘)조회 이력이 있으면//null 아니면(값이 채워져 있으면 true가 됨)
		$interval = getInterval ( $dbFields ['reg_date'] );
		if ($interval > 300) { // 오늘 조회이력이 5(300)분을 넘었으면 자동 갱신.
			$currentData = getCrawlDatas ( $btg );	// Array 형태로 리턴됨.
			if ($currentData) {
				// 사용시간 지남으로 Update
				updateDbDatas ( 'current', $btg, $currentData ); // 최근조회이력o, db시간 UP -> updateDB로변경.
				// 				ChromePhp::log("■사용자[".$btg."]의 datas를 update합니다. ");	//시간 같이 갱신되나...
			} else {
				// 				ChromePhp::log("■사용자[".$btg."]의 정보가 이상합니다. Current에서 확인되었으나, playoverwatch에서 data를 가져오지 못했습니다.");
			}
		} else { // 아니면 이전 데이터를 가져다가 씀..
			$currentData = $dbFields['Datas']; // 최근조회이력o, 지난업데이트x -> 가져다가 씀.
			$currentData = json_decode($currentData,true);
			// 			ChromePhp::log("■사용자[".$btg."]의 datasB를 가져와서 사용합니다. ");	//시간 같이 갱신되나...
		}
	} else { // 최근 조회이력이 없으면 playOverWatch에서 가져옴.
		$currentData = getCrawlDatas ( $btg );	// Array 형태로 리턴됨. // Crawl Data를 해도 배틀태그가 존재하지 않아도. 어레이를 반환해버리네(그래서 NULL로 수정)
		if ($currentData) {
			// 			ChromePhp::log("■사용자[".$btg."]의 datas를 INSERT합니다. ");	//시간 같이 갱신되나...
			instDbDatas ( 'current', $btg, $currentData ); // 최근조회이력x
		} else {
			// 			ChromePhp::log("■사용자[".$btg."]는 playoverwatch에 등록되지 않은 사용자입니다.");
		}
	}
	// Db에서 가져올때는 String, Crawl을 하면 Array 형태구나...
	// updateDB를 하면 encode를 안에서 하는구나..

	// 배틀태그 등록.. 그치만 그냥 무조건 집어놓고 쿼리 실패 해도 될거 같긴한데...
	if (! checkId ( $btg, 'playerlist' ) && ($currentData != null)) {
		addBtg ( $btg, 'playerlist' );
		// 		ChromePhp::log("■사용자[".$btg."]를 플레이어리스트에 등록합니다.");
	}

	// dailyData(오늘성적) = $currentData - $rctDayData(recent today data)
	// weeklyData(주간성적) = $currentData - $weekData(recent week data)

	//Recent today Data 필요...
	$rctDayDbName = getDbName('recentDay');
	if(isTable($rctDayDbName)){
		$rctDayData = getDBDatas($btg, $rctDayDbName);
		$rctDayData = $rctDayData['Datas'];	//row에서 data만 빼옴...
		$rctDayData = json_decode($rctDayData,true); // 뒤에 옵션이 true이면 Array형태 그렇지 않으면 stdclass 형태로 나온다..
	}


	//Recent Week Data 필요...
	$rctWeekDbName = getDbName('recentWeek');
	if(isTable($rctWeekDbName)){
		$rctWeekData = getDBDatas($btg, $rctWeekDbName);
		$rctWeekData = $rctWeekData['Datas']; // row에서 data만 빼옴...
		$rctWeekData = json_decode($rctWeekData,true); // 뒤에 옵션이 true이면 Array형태 그렇지 않으면 stdclass 형태로 나온다..
	}
	// Create Radar Data Version 2
	$result = newFunction($currentData, $rctDayData, $rctWeekData);

	//Recent Yester Data 필요
	$rctYstData = getDbData('yesterDay', $btg);

	// Add Data(B-A)
	$label = 'ystday'; // Javascript uses this id;
	$dataB = $rctYstData; // YesterDay Data
	$dataA = $rctDayData; // Today Data
	$result = addPrdDatas($result, $label, $dataB, $dataA);

	// #Raw Data
	// error 404 전송하는법 찾을것? 404가 맞나?
	// 	$result = array ("error" => "");	// error 코드 추가.
	// 	echo '<pre>';
	// 	echo print_r($result);
	// 	echo "========CurrentData";
	// 	print_r($currentData['All']);
	// 	echo "========recent Day Data";
	// 	print_r($rctDayData['All']);
	// 	echo "========recent Week Data";
	// 	print_r($rctWeekData['All']);
	// 	echo '</pre>';

	// 사용자가 없을때는
	// $result가 NULL 이되도록한다.
	// Todo : 에러 핸들링 추가할것.
	$response = array('status' => 'ok', 'errMessage' => 0, 'isFaultUser' => false, 'isFirstVisit' => false, 'data' => $result);

	echo json_encode ( $response, JSON_UNESCAPED_UNICODE );	// 한글깨짐 방지.
}
**/	
