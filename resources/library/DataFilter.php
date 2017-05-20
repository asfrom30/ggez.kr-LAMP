<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (INIT_PATH . "/HeroInfoList.php");
require_once (LIBRARY_PATH . "/ChromePhp.php");


// Data Filter 진전사항 없음

// 이유 1.
// 플레이시간이 1시간단위로 나옴... 4.9에서 10분만 플레이해도 5로 바로 뜀
// 이 경우 분당 딜량이 갑자기 급감하게 됨, 즉 플레이시간이 분당으로 정확하게 측정이 안됨.

// 이유 2.
// 치른 게임도 마찬가지임... 1판을 넘어가는 구간이 애매함...
// 차이를 구하는 건데 이게 소수점까지를 모르면 계산할 수가 없음..
// 28->29 한구간 넘어갈때가 조금 애매함..

// 이유 3.
// 유일하게 정상 측정 할 수 있는 것은 경기나 분당을 제외함
// 죽음당 또는 발사당 명중임..

// 참고..
// http://masteroverwatch.com/heroes/pc/kr/mode/ranked

// 안죽고 데미지나 킬수가 올라가면 PERFECT로 등록

// Send an error message by email.
// http://www.w3schools.com/Php/php_error.asp


// error_reporting 설정
// http://php.net/manual/kr/function.error-reporting.php
error_reporting(0);

// 첫번째 파라메터는 이미 removeCtg가 되어 있는 상태이다.
function removeCtgPrd($currentData, $data){
	// recent Day Data가 없으면 CurrentData를 대입해서 치른경기가 0으로 만들어버린다.
	// recent Week Data가 없어서 이대로 진행하면 getValues에서 0을 가져와 계산이 진행되어 버리기 때문에
	// 차라리 동일한 값을 대입해서 45(치른경기) -45(치른경기) = 0으로 업데이트를 할 상황이 안되게 만들어 버린다.
	if($data == null){
		$data = $currentData;
	} else {
		$data = removeCtg($data);
	}
	return $data;
}


// B(beforeData, preData), A(currentData, postData), A-B
function addPrdDatas($result, /** label(Javascript uses this id) **/ $label, $dataB, $dataA){
	if($dataA == null){
		$result['All']['update'][$label] = 0;
		return $result;
	}
	$dataA = removeCtg($dataA);
	$dataB = removeCtgPrd($dataA, $dataB);
	
	// Get Hero List
	$statMap = getStatMap();
	$heroList = array_keys($statMap);
	
	// 각 영웅별 플레이타임을 생성..
	$result = createUpdateL($result, $heroList, $dataB, $dataA, $label);
	
	//경쟁전 점수 현황 생성
	$result = createCptPtL($result, $dataB, $label);
	
	// 영웅별 WinRating생성(Avg, Today, Week)
	$result = createWinRatingL($result ,$heroList, $dataB, $dataA, $label);
	
	// Create Radar Data(Label, RadarPt, RadarRaw)
	$result = createRadarDataL($result, $heroList, $dataB, $dataA, $label);
	
	// 영웅별 PtRating생성
	$result = createPtRatingL($result, $heroList, $label);
	
	return $result;
}

function createDefaultData($currentData, $rctDayData, $rctWeekData){

	// Current Data가 없으면 바로 중단
	if($currentData == null){
		return null;
	} else { // z Current Data 생성
		$zDataCurrent = $currentData;
		$currentData = removeCtg($currentData);
	}
	
	// Remove Catecory for Data Loop
	$rctDayData = removeCtgPrd($currentData, $rctDayData);
	$rctWeekData = removeCtgPrd($currentData, $rctWeekData);
	
	
// 	if($rctDayData == null){
// 		$rctDayData = $currentData;
// 	} else {
// 		$rctDayData = removeCtg($rctDayData);
// 	}
// 	if($rctWeekData == null){
// 		$rctWeekData = $currentData;
// 	} else {
// 		$rctWeekData = removeCtg($rctWeekData);
// 	}
// 	if($rctYstData == null){
// 		$rctYstData = $currentData;
// 	} else {
// 		$rctYstData = removeCtg($rctYstData);
// 	}
		
	// Get Hero List
	$statMap = getStatMap();
	$heroList = array_keys($statMap);
	
	// Creat Result
	$result = array();
	
	// 각 영웅별 플레이타임을 생성..
	$result = createUpdate($result, $heroList, $currentData, $rctDayData, $rctWeekData);
	
	//경쟁전 점수 현황 생성
	$result = createCptPt($result, $currentData, $rctDayData, $rctWeekData);
	
	// 영웅별 WinRating생성(Avg, Today, Week)
	$result = createWinRating($result, $heroList, $currentData, $rctDayData, $rctWeekData);
	
	// Create Radar Data(Label, RadarPt, RadarRaw)
	$result = createRadarData($result, $heroList, $currentData, $rctDayData, $rctWeekData);
	
	// 영웅별 PtRating생성
	$result = createPtRating($result, $heroList);
	
	// zData 생성
	$result['All']['zData'] = $zDataCurrent;

	return $result;
}


function calcTierData($tierData){
	/** result data 구조 
	 bronze => hero => radarPt => array();
	 				   radarRaw => array();
	 				   playerNum => int;
	 **/
	$result = array();
	
	$statMap = getStatMap();
	$heroList = array_keys($statMap);
	$tierList = array_keys($tierData);
	foreach($tierList as $tier){
		if($tierList == 'etcData'){
			
		} else {
			foreach($heroList as $hero){
					
				$result[$tier][$hero]['radarRaw'] = array();
				$result[$tier][$hero]['radarPt'] = array();
				$result[$tier][$hero]['playerNum'] = array();
					
				$stats = $statMap[$hero];
				foreach ($stats as $stat){
					$label = $stat['label'];
					$unit = $stat['unit'];
					$grades = $stat['grade'];
			
					$sum = floatval($tierData[$tier][$hero][$label]['sum']);
					$num = floatval($tierData[$tier][$hero][$label]['num']);
			
					if($sum == null || $num == null || $sum == 0 || $num == 0){
						$radarRaw = null;
						$radarPt = null;
					} else {
						$radarRaw = $sum/$num;
						if($unit == "%"){
							$radarRaw = $radarRaw*100;
						}
						$radarPt = getPtData($radarRaw, $grades);
					}
			
					array_push($result[$tier][$hero]['radarRaw'], $radarRaw);
					array_push($result[$tier][$hero]['radarPt'], $radarPt);
					array_push($result[$tier][$hero]['playerNum'], $num);
				}
			}
		}
	}
	
	return $result;	          
}

function createCptPT($result, $currentData, $rctDayData, $rctWeekData){
	
	$result['All']['cptPt']['avg'] = getValueWithNull($currentData['All'], 'cptPt');
	$result['All']['cptPt']['today'] = getValueWithNull($rctDayData['All'], 'cptPt');
	$result['All']['cptPt']['week'] = getValueWithNull($rctWeekData['All'], 'cptPt');
	
	return $result;
}

// Update가 없어도 cptPt는 저장할 것
function createCptPtL($result, $dataB, $label){

	$result['All']['cptPt'][$label] = getValueWithNull($dataB['All'], 'cptPt');
	return $result;
}

// 이걸로 Update Badge 판단하는데 사용할 것
function createUpdate($result, $heroList, $currentData, $rctDayData, $rctWeekData){

	foreach ($heroList as $hero){
		$totalPlayNum = intval(getValue($currentData[$hero], '치른 게임'));
		$rctTodayPlayNum = intval(getValue($rctDayData[$hero], '치른 게임'));
		$rctWeekPlayNum = intval(getValue($rctWeekData[$hero], '치른 게임'));
			
		$result[$hero]['update']['avg'] = $totalPlayNum;
		$result[$hero]['update']['today'] = $totalPlayNum - $rctTodayPlayNum;
		$result[$hero]['update']['week'] = $totalPlayNum - $rctWeekPlayNum;
	}
	return $result;
}

// createUpdate with Label
function createUpdateL($result, $heroList, $dataB, $dataA, $label){

	foreach ($heroList as $hero){
		$dataAPlayNum = getValue($dataA[$hero], '치른 게임');
		$dataBPlayNum = getValue($dataB[$hero], '치른 게임');
		$result[$hero]['update'][$label] = $dataAPlayNum - $dataBPlayNum;
	}
	return $result;
}

// 총/승/무/패/승률 12전 9승 3패 40% 형식으로 출력 array
function createWinRating($result ,$heroList, $currentData, $rctDayData, $rctWeekData){

	foreach ($heroList as $hero){
		
		$avgWinRatings = array(0,0,0,0,0);
		$todayRatings = array(0,0,0,0,0);
		$weekRatings = array(0,0,0,0,0);
		
		// 업데이트 기록이 없으면 위의 0,0,0,0,0 값을 반영한다.
		if($result[$hero]['update']['avg'] != 0){
			$avgWinRatings = getWinRatings($currentData, $hero);
			
			if($result[$hero]['update']['today'] != 0){
				$todayRatings = getWinRatingsBtw($currentData, $rctDayData, $hero);
			}
			if($result[$hero]['update']['week'] != 0){
				$weekRatings = getWinRatingsBtw($currentData, $rctWeekData, $hero);
			}
		}
		
		$result[$hero]['winRating']['avg'] = $avgWinRatings;
		$result[$hero]['winRating']['today'] = $todayRatings;
		$result[$hero]['winRating']['week'] = $weekRatings;
	}
	return $result;
}

function createWinRatingL($result ,$heroList, $dataB, $dataA, $label){
	foreach ($heroList as $hero){
	
		$ratings = array(0,0,0,0,0);

		if($result[$hero]['update'][$label] != 0){
			$ratings = getWinRatingsBtw($dataA, $dataB, $hero);
		}
		$result[$hero]['winRating'][$label] = $ratings;
	}
	return $result;
}

function createRadarData($result ,$heroList, $currentData, $rctDayData, $rctWeekData){
	// Initcialize
	$statMap = getStatMap();
	// 경기를 했으면 데이터를 생성함.
	foreach ($heroList as $hero){
		
		// Get Stats Key
		$stats = $statMap[$hero];
		
		$result[$hero]['radarLabel'] = array();
		$result[$hero]['radarUnit'] = array();
		$result[$hero]['radarDescription'] = array();
		
		$result[$hero]['radarRaw']['avg'] = array(); 
		$result[$hero]['radarRaw']['today'] = array();
		$result[$hero]['radarRaw']['week'] = array();
		
		$result[$hero]['radarPt']['avg']  = array();
		$result[$hero]['radarPt']['today']  = array();
		$result[$hero]['radarPt']['week']  = array();

		foreach ($stats as $stat){
			/*
			 * Create Label
			 */
			// get Map ID from stat map
			// factor type 1은 crawl 안에 존재하므로 가져오는것, 2는 없어서 일일히 계산하는것 // unit은 단위
			
			$label = $stat['label']; $description = $stat['description']; $factors = $stat['factor']; $grades = $stat['grade']; $unit = $stat['unit'];// push Label to Array
			array_push($result[$hero]['radarLabel'], $label);
			array_push($result[$hero]['radarUnit'], $unit);
			array_push($result[$hero]['radarDescription'], $description);
			
			/*
			 * Create Raw(Float) Data(avg, today, week)
			 * Data가 없을 경우 0을 입력해야 함.
			 */
			if($result[$hero]['update']['avg'] != 0){
				//debug
				
				// Create Average Raw in Radar Chart
				$radarAvgRaw = getRadarRaw($hero, $factors, $unit, $currentData);
				array_push($result[$hero]['radarRaw']['avg'], $radarAvgRaw);
				
				// Creat Average Pt
				$radarAvgPt = getPtData($radarAvgRaw, $grades);
				array_push($result[$hero]['radarPt']['avg'], $radarAvgPt);
								
				if($result[$hero]['update']['today'] != 0){
					$radarTodayRaw = getRadarRawBtw($hero, $factors, $unit, $currentData, $rctDayData);
					array_push($result[$hero]['radarRaw']['today'], $radarTodayRaw);
					
					// Creat Today Pt
					$radarTodayPt = getPtData($radarTodayRaw, $grades);
					array_push($result[$hero]['radarPt']['today'], $radarTodayPt);
				} 
				if($result[$hero]['update']['week'] != 0){
					$radarWeekRaw = getRadarRawBtw($hero, $factors, $unit, $currentData, $rctWeekData);
					array_push($result[$hero]['radarRaw']['week'], $radarWeekRaw);
					
					// Creat Today Pt
					$radarWeekPt= getPtData($radarWeekRaw, $grades);
					array_push($result[$hero]['radarPt']['week'], $radarWeekPt);
				} 
			} 
			

			
			//Filt Raw -> Point
// 			$radarPoint =
// 			array_push($result[$hero]['radarRaw'], $radarPoint);
		}
	}
	return $result;
}

// Label이 겹쳐서 tag로 교환
function createRadarDataL($result, $heroList, $dataB, $dataA, $label){
	// Initcialize
	$statMap = getStatMap();
	// 경기를 했으면 데이터를 생성함.
	foreach ($heroList as $hero){

		// Get Stats Key
		$stats = $statMap[$hero];
// 		$result[$hero]['radarLabel'] = array();
		$result[$hero]['radarRaw'][$label] = array();
		$result[$hero]['radarPt'][$label]  = array();

		foreach ($stats as $stat){
			$factors = $stat['factor']; $grades = $stat['grade']; $unit = $stat['unit'];
			if($result[$hero]['update'][$label] != 0){
				$radarRaw= getRadarRawBtw($hero, $factors, $unit, $dataA, $dataB);
				array_push($result[$hero]['radarRaw'][$label], $radarRaw);
			
				// Creat Today Pt
				$radarPt= getPtData($radarRaw, $grades);
				array_push($result[$hero]['radarPt'][$label], $radarPt);
			}
		}
	}
	return $result;
}


// Point Rating은 Radar Pt 기반으로 계산한다.
function createPtRating($result, $heroList){
	foreach ($heroList as $hero){
		if($result[$hero]['update']['avg'] != 0){
			$datas = $result[$hero]['radarPt']['avg'];
			$result[$hero]['ptRating']['avg'] = getPt($datas);
				
			if($result[$hero]['update']['today'] != 0){
				$datas = $result[$hero]['radarPt']['today'];
				$result[$hero]['ptRating']['today'] = getPt($datas);
			} else {
				$result[$hero]['ptRating']['today'] = null;
			}
			if($result[$hero]['update']['week'] != 0){
				$datas = $result[$hero]['radarPt']['week'];
				$result[$hero]['ptRating']['week'] = getPt($datas);
			} else {
				$result[$hero]['ptRating']['week'] = null;
			}
		}
	}
	
	return $result;
}

function createPtRatingL($result, $heroList, $label){
	foreach ($heroList as $hero){
		if($result[$hero]['update']['avg'] != 0){
			$datas = $result[$hero]['radarPt'][$label];
			$result[$hero]['ptRating'][$label] = getPt($datas);
		} else {
			$result[$hero]['ptRating'][$label] = null;
		}
	}
	return $result;
}

function getPt($datas){
	if($datas == null){
		return null;
	} else {
		$num = count($datas);
		$sum = 0;
		foreach ($datas as $data){
			$sum = $sum + $data;
		}
		$result = $sum/($num*20);
		$result = round($result, 1);
		return $result;
	}
	
}

function getRadarRaw($hero, $factors, $unit, $data){
	
	$radarRaw = 0;
	
	if($factors[0] == 1){	// Factor Type1(오버워치 내 데이터 존재)
		$averageLabel = $factors[3];	// 오버워치 내 데이터 가져올 데이트 '이름'
		$radarRaw = getValue($data[$hero], $averageLabel);// 해당 키가 없으면 0으로 반환.
		$radarRaw = tofloat($radarRaw);
	} elseif ($factors[0] == 2) {	// Factor Type2(직접계산해야하는 데이터)
		//numerator 분자	//denominator 분모
		//데이터 접근.
		$numeratorLabel = $factors[1];	$numerator = getValue($data[$hero], $numeratorLabel);
		$denominatorLabel = $factors[2]; $denominator = getValue($data[$hero], $denominatorLabel);
		
		// 가져온 값이 String 이므로 String to Float
		$numerator = tofloat($numerator); $denominator = tofloat($denominator);
		
		// 분자와 분모가 0이 아니여만 계산을 시작한다. CaliData에서는 (%일시 *100), (소수점 한자리로 round 한다.)
		if($numerator != 0 && $denominator != 0){
			$radarRaw = $numerator/$denominator;
			$radarRaw = caliData($radarRaw, $unit);	//계산한 값은 무조건 cali를 해줘야 한다.
		} 
	}
	
	return $radarRaw;
}

function getRadarRawBtw($hero, $factors, $unit, $dataB, $dataA){
	
	$radarRaw = 0;
	
	//Factor Type에 관계없이 무조건 계산으로만 구할 수 있다.
	$numeratorLabel = $factors[1]; $denominatorLabel = $factors[2];
	
	//numerator 분자	//denominator 분모
	$numeratorB = getValue($dataB[$hero], $numeratorLabel); $numeratorA = getValue($dataA[$hero], $numeratorLabel);
	$denominatorB = getValue($dataB[$hero], $denominatorLabel); $denominatorA = getValue($dataA[$hero], $denominatorLabel);
	
	// 가져온 값이 String 이므로 String to Float
	$numerator = tofloat($numeratorB) - tofloat($numeratorA);
	$denominator = tofloat($denominatorB) - tofloat($denominatorA);
	
	//Check PerfectGame
	if($denominator == 0){
		$radarRaw = checkPerfect($denominatorLabel);
	} else {
		$radarRaw = $numerator/$denominator;
		$radarRaw = caliData($radarRaw, $unit);
	}
		
	return $radarRaw;
}

function checkPerfect($label){
	if($label == "죽음"){
		return 777;
	}
}

// Average Rating은 Current 게임으로만 판단하므로 다른 자료가 필요없음.
function getWinRatings($data, $hero){
	$totalGame = getValue($data[$hero], '치른 게임');
	$winGame = getValue($data[$hero], '승리한 게임');
	$drawGame = getValue($data[$hero], '무승부 게임');
	$defeatGame = getValue($data[$hero], '패배한 게임');
	$rate = intval($winGame/($winGame + $defeatGame)*100);
	
	return array($totalGame, $winGame, $drawGame, $defeatGame, $rate);
}

//Today, Week Rating은 두개 데이터의 차이로 판단하므로 변수를 두개를 받음
// DataB와 DataA의 위치가 바뀐것 같다..
function getWinRatingsBtw($dataB, $dataA, $hero){
	$totalGame = getValue($dataB[$hero], '치른 게임') - getValue($dataA[$hero], '치른 게임');
	$winGame = getValue($dataB[$hero], '승리한 게임') - getValue($dataA[$hero], '승리한 게임');
	$drawGame = getValue($dataB[$hero], '무승부 게임') - getValue($dataA[$hero], '무승부 게임');
	$defeatGame = getValue($dataB[$hero], '패배한 게임') - getValue($dataA[$hero], '패배한 게임');
	
	if(($winGame + $defeatGame) == 0){
		$rate = 0;
	} else {
		$rate = intval($winGame/($winGame + $defeatGame)*100);
	}
	return array($totalGame, $winGame, $drawGame, $defeatGame, $rate);
}


function getValue($array, $key){
// 		해당 항목에 키가 있으면 정해진 값을 반환하고.
	if(array_key_exists($key, $array)){
		return $array[$key];
//		해당 항목에 키가 없으면 0을 반환한다. **Error 해당 키가 없는경우 에러 리포팅 해줄것.
	} else {
		return 0;
	}
}

function getValueWithNull($array, $key){
	if(array_key_exists($key, $array)){
		return $array[$key];
		//		해당 항목에 키가 없으면 0을 반환한다. **Error 해당 키가 없는경우 에러 리포팅 해줄것.
	} else {
		return null;
	}
}

// Overall Rating은  전체 항목의 평균임..
function createOvlRating(){
	
	$result['ovlRating']['avg'] = $avgPlay;
	$result['ovlRating']['today'] = $todayPlay;
	$result['ovlRating']['week'] = $weekPlay;
}

function logNull($hero, $datas, $labels){
	foreach ($datas as $data){
		foreach ($labels as $label) {
			if($data[$hero][$label] == null){
				ChromePhp::log("Error :: Hero[" . $hero. "], Label[" . $label . "]의 값을 JSON에서 찾을 수 없습니다.");
			}
		}
	}
}

// Linear Calculate
function getPtData($data, $grades){
	
	// 에러가 생겨도 0은 리턴 하게끔 함수 수정할 것.
	$minValue = $grades[0];
	$maxValue = $grades[4];

	if($data == 777){ //Perfect 게임.
		return 777;
	} elseif ($data < $minValue) {
		return 0;
	} elseif ($data > $maxValue) {
		return 100;
	}

	$result = (100*($data - $minValue))/($maxValue - $minValue);
	return intval($result);
}

// Linear Calculate
function getFiltData($data, $grades){
	
	$minValue = $grades[0];
	$maxValue = $grades[4];
	
	if($data == -1){ //Perfect 게임.
		return -1;
	} elseif ($data < $minValue) {
		return 0;
	} elseif ($data > $maxValue) {
		return 100;
	}
	
	$result = (100*($data - $minValue))/($maxValue - $minValue);
	return intval($result);
}

// 보정계수 unit이 %이면 *100, 소수점은 1자리수까지만...
function caliData($data, $unit){
	if($unit == "%"){
		$data = $data*100;
	}
	$data = round($data, 2);
	return $data;
}

function debugValue(){
// 	For Debug
// 	echo "<br>===".$label."===<br>";
// 	echo "[" . $label  ."] = [".$denominatorLabel."B - " . $denominatorLabel . "A]<br>";
// 	echo "[" . $numerator  ."] = [".tofloat($currentData[$hero][$numeratorLabel])." - " . tofloat($rctDayData[$hero][$numeratorLabel]) . "]<br>";
// 	echo "[" . $denominator  ."] = [".tofloat($currentData[$hero][$denominatorLabel])." - " . tofloat($rctDayData[$hero][$denominatorLabel]) . "]<br>";
}

// Make Clean Float
// $num = '1.999,369€';
// var_dump(tofloat($num)); // float(1999.369)
// $otherNum = '126,564,789.33 m²';
// var_dump(tofloat($otherNum)); // float(126564789.33)
function tofloat($num) {
	
	/* 내가 추가함 
	 * -. [,] 제거 기능이 제대로 동작하지 않음...
	 * */
	$num =  str_replace(',','', $num);
	
	$dotPos = strrpos($num, '.');
	$commaPos = strrpos($num, ',');
	$sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
	((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
	 
	if (!$sep) {
		return floatval(preg_replace("/[^0-9]/", "", $num));
	}

	return floatval(
			preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
			preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
			);
}

// 시간차이 구하는 함수..
function getDiffTime($str1, $str2){
	
	
	
	return $minute;
}

// 시간으로 변경하는 함수..
function converMinute($str){

	if(strpos($str, "시간")){
		$minute = 60 * intval($str);
	} elseif(strpos($str, "초")){
		$minute = 1;
	} else {
		$minute = intval($str);
	}

	return $minute;
}

function removeCtg($data){
	if($data == null){
		return null;
	}

	$result= array();
	$heros = array_keys($data);

	foreach ($heros as $hero){
		$result[$hero] = array();
		$heroDatas = $data[$hero];

		foreach ($heroDatas as $key => $value){
			foreach($value as $key => $value){
				$result[$hero][$key] = $value;
			}
		}
	}
	return $result;
}