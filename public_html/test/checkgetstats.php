<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(INIT_PATH . "/HeroInfoList.php");
require_once(LIBRARY_PATH . "/CrawlDatas.php");
require_once(LIBRARY_PATH . "/DataFilter.php");
require_once(LIBRARY_PATH . '/DbHandler.php');

$btg = urlencode("godfather-3920");

if ($btg == null) {
	$error = "btg 파라메터를 전달받지 못했습니다.";
} else {
	// TODO : 자주 요청하지 못하게 방지하는 기능... 잘못하면 블럭당함.
	// if(true){ //최근에 업데이트 한 이력이 없으면...

	$btgType = "";	// playerlist에 있는지 없는지, current에 있는지 없는지, playoverwatch에 있는지 없는지..

	$currentData = null;
	$rctDayData = null; // dataA는 today, week 선택에 따라 있을 수도 있고 없을 수도 있다...
	$rctWeekData = null;
	$result= "";

	/*
	 * Current Data
	 * 사용자가 오늘 접속하면 남기는 log data임... 갱신주기 5분.
	 */
	$dbFields = getDBDatas ( $btg, "current" ); // 최근 조회 이력이 있는지 확인..
	if ($dbFields) { // 최근조회(오늘)조회 이력이 있으면//null 아니면(값이 채워져 있으면 true가 됨)
		$interval = getInterval ( $dbFields ['reg_date'] );
		if ($interval > 30000000000000) { // 오늘 조회이력이 5(300)분을 넘었으면 자동 갱신.
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
	
	echo 'hi2';

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
		echo '<pre>';
		echo print_r($result);
	// 	echo "========CurrentData";
	// 	print_r($currentData['All']);
	// 	echo "========recent Day Data";
	// 	print_r($rctDayData['All']);
	// 	echo "========recent Week Data";
	// 	print_r($rctWeekData['All']);
		echo '</pre>';

	// 사용자가 없을때는
	// $result가 NULL 이되도록한다.
	$response = array('status' => 'ok', 'data' => $result);
}

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


/** others **/
// Root Server Name
// http://를 제거하면 안된다. http 형식으로 요청하는 것이기 때문에..
// $_SERVER['HTTP_HOST'];
// $url = 'http://' . $_SERVER['HTTP_HOST'] . '/pages/GetStats.php?btg=' . $btg;
// $response = file_get_contents($url);
// $response = json_decode($response, true);
// echo '<pre>';
// if($response == null){
// 	echo 'response 값이 null 입니다.';
// } else {
// 	print_r($response);
// }
// echo '</pre>';
