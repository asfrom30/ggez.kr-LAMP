<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once(INIT_PATH . "/HeroInfoList.php");
require_once(LIBRARY_PATH . "/CrawlDatas.php");
require_once(LIBRARY_PATH . "/DataFilter.php");
require_once(LIBRARY_PATH . '/DbHandler.php');

// 85-100
// $btgs = array("KimJungil#31622", "분노의포카락#3328", "SamsungBlack#1998", "초아와맴순이들#3991", "안양밥줘#3751", "대홍단감자#31400", "Dynamic#11519", "양학#3608", "비트에옷을맡겨#3402", "활쟁이#3261", "녀자#31416", "힐러#31385");
// $btgs = array("존망겜유저#3750", "바람난훈#3611", "우유맛치즈#31468", "탱하기싫어요#3857", "JOGAY#11134", "어라#31665", "헤이나쫌뽜#3555", "늠민#3307", "타치바나카나데#31536", "delicious#11621", "메이메이해#3490", "쵸코살#3407", "Ride#31617");
// $btgs = array("코코로#3383", "Kuro#31440", "TurtleKing#31553", "최삼#31177", "여우비#32988", "감자#32943", "지권#31667", "zizo#3258", "YOOBOTTLEJAE#1450", "HelloSCV#3575", "꽃달고잇힝#3648");
// $btgs = array("마마무#3139", "ING0616#3112", "바다공주#31751", "Hun#11844", "오호이런방법이#3431", "뿌꾸뿌꾸#31746", "Pavet#1685", "궯휅#3845", "하얀쑥떡#3344", "포도#31961", "아랫집루시우#3565", "HOLDTHEDOOR#3320");

// 228-244
// $btgs = array("faker4#1461", "BIBLUELINE#3116", "창공노을#3595", "야아근맨#3510", "SKAN#31855", "오이맛사탕#3332", "CPU#11590", "Kenshi#31632");
// $btgs = array("dark#15571", "Mensch#11834", "sleeapark4#1307", "JUNKRAT#3567", "치우꿍꿍꿍이#3808", "겐지선픽#3484"); 
// $btgs = array("SANA#3302", "제레#3370", "감자맛우유#31115", "menteur#31643", "TOBIY#31128", "소은#31154", "사나는사랑입니다#3834", "BT160616#15626", "까리한나쵸#3819", "최규현#31839", "앙기무띠주는나무#3886");
// $btgs = array("Daily#31667", "프렌치블랙#31563", "겜플#31130", "mining#3864", "Reborn#3334", "밀키#31679", "함마린#3659", "GoldFrosch#3208", "Hazel#31897", "Batle#11561", "찼씨#3603");
// $btgs = array("DrPepper#31866", "호부장#3994", "은찬#3359", "구르미#3843", "REDGOST#11605", "우윤식빵#3617", "데스페라도#31551", "KSS#31445", "모솔중위#3402", "게임이진다#3330", "JuNi#31369");
// $btgs = array("Yoonbro#3969", "은뀨잉#3118", "프로숨쉼이#31920", "정환이는히오스왕#3187", "LOTTE#3460", "불꽃계집#3887", "북극곰#31455", "Godblin#3323", "김첨지#32103");
// $btgs = array("성북동왕돈까스#3231", "SoraHH#3660", "치글러#3141", "급습#31213", "성실관사감님#3572", "Hallelujah#31286", "COMICADO#3466", "project#11781", "근성#31352", "Arc#3694");
// $btgs = array("신까꿍#31257", "Danda#3377", "레고111#3366", "MccRe#1978", "야무진녀석#3856", "내삶은로또#3216", "CreePerS#11505");

// 211~227
// $btgs = array("은쓰#31977", "리퍼#37669", "대형루키#3858", "masai#3595", "공태철#3142", "C00#31580", "MagiResistor#3591", "Hui#3715", "지옥의조노#3114", "Lockin#3148", "최고의플레이어#31672");
// $btgs = array("DooMHaDeS#3560", "TWICE#1894", "수학시간에머하냐#3268", "SUN76#3614", "Cryingms#1751", "마늘닭#31316", "GLaDOS#3591", "후이#3984", "신보은#3467", "DioGenes#31604");
// $btgs = array("theorigin27#1693", "xereon#3102", "양념통닭스모프#3802", "젖소방#3831", "JENO#31108", "루키프#3555", "환뜡이#3204", "파로크립#3495", "욘삼#3731", "rit#3336", "KiraRausch#1700");
// $btgs = array("NSP#31662", "GiantDwarf#34554", "뙤마시아#3999", "새봄#31731", "adios#11873", "HipAss#11769", "LiFe#3101", "starcraft#12639", "포카리일리#3515", "Door#21399");
// $btgs = array("Lynnssaying#3472", "black#13703", "Update#11470", "디펜스타#3254", "석양이진다#3675", "ToTheMoon#31101", "감자옆#3734", "dream#32654");
// $btgs = array("케이#31573", "하드탱커#3836", "파이리#31593", "부루기#3741", "행복한바지#32433", "박교양#3101", "Sonic#32278", "급식먹으러학교감#3895");

// 194~210
// $btgs = array("꿀꿀유비니#3355", "hardcarry#31151", "불꽃간지#3988", "BLACK#13142", "YuN2#31605", "단댕이#3119", "제우스#32735", "NonVideri#3715", "버거킹#3791", "coco#32240");
// $btgs = array("sagehero#3727", "열한번째#3716", "즐겜유저#31939", "헤이즈#3334", "hani#31149", "빵찬#31430", "Runner#31120", "Euphemia#3480", "베가스프로11#3763");
// $btgs = array("xFreShCPUx#3169", "toondra#11380", "헤니니#31520", "싹싹김치#3809", "NGNY#3495", "은쓰#31977", "fxxx#11927", "France#31371", "sjsaesrwt#3539", "설경#31338");
// $btgs = array("불도저#31963", "보르헤스#3783", "Ballatines#11716", "물리학도#31643", "galho#3490", "krad03#3712", "minseo#11674", "kbh96941#1587", "안녕그지#3376", "사이드이펙트#3734");
// $btgs = array("히라유키사마#3560", "사과맛식초#3869", "GenDao#3165", "광란의빠따파티#3330", "SWOOKE#31219", "나는일베#3631", "LYGgd#1442", "쿠욱쿠욱#3845", "실버호크#31443", "blackjone#3175");
// $btgs = array("피아노를치는소녀#3386", "후배위도우메이커#3507", "역시#31545", "오린이#31289", "lhssjst#3996", "Roto#3982", "Lastromance#3453", "앙앙이#3879", "cenchihum#3506");
// $btgs = array("unlimited#31592", "FC3#11361", "블랙아웃#31653", "윤공주#31347", "게이부왁#3839", "대표폐인준흠쓰#3547", "푸른바람#3597", "RedFish#1837", "Lego#31311", "새알미역국#3479");
// $btgs = array("겐지나는닉넴#3350", "stmidas#3232", "블루헌터#31345", "Real9#3529", "koomani#3166", "레퐁#3838", "악당곰#3468", "until#11121", "고광현#3443", "히어로#31695", "오퍼튜니티#3293");
// $btgs = array("forever0107#3313", "둡다현#3817", "ZERO#35498", "아바충은웁니다#3929", "kyun0703#1324", "마춘배#3893", "shkim714#3601", "xereon#3102", "양념통닭스모프#3802");

// $btgs = array("Eltrium#31685", "령구탱크#3325", "Hyper#11338", "Tteolbeo#3683", "roopuss#3823", "ICE#32732", "유우베이토#3770", "너의이름은#3416", "악세루#3395", "함딩#3678");
// $btgs = array("씨없는수박#3396", "해니쓰#3739", "gusals#1149", "공유버스#3436", "요시노#31998", "sonnaldo#3565", "Spromise#3811", "김형준#3340", "탱커없다#3354", "스패릭#3961");
// $btgs = array("삼화#3708", "양고기#31659", "메기드#3307", "에로스#31759", "Léa#21557", "한토스피헐#3602", "갱빠#31932", "김인#31388", "강박사#3409", "옷촛#3583", "용용이#32942");
// $btgs = array("WHJ#11490", "띵아힝아#3825", "시스터다메요#3247", "안톤쉬거#3387", "너래쟁이#31941", "사진속추억#3601", "Kratos#3741", "프로야근러#3763", "퀜틴#3271", "cLone#12264");
// $btgs = array("다리우스#3111", "한조#37284", "예숑#3802", "IlIIlIIl#1847", "심해불편러#3267", "zergking#11199", "pluto#32890", "현지아코#3318", "요괴워치#31807", "본드머#3619");
// $btgs = array("불타는물#31192", "Leodio#3625", "Racoon#11880", "쏘지이몽#3796", "순팡#3713", "Last#31242", "사형#31465", "TEA#31633", "메이명박#3540", "미즈노#3636", "울쓰#31683");
// $btgs = array("엉큼한거북이#35952", "대장군#31925", "Flez#21733", "장씨#31756", "너무좋다이겜#3942", "TrickyZerg#3749", "BoKyems#3737", "IzA#2997", "갓대윤#3832", "반짝이는바지#3886");
// $btgs = array("인간#3373", "신율#31573", "짬내#3905", "Caley48#3577", "원나인#31336", "애널컨트#3127", "SKETOM#1640", "Lea#31379", "한조#31578", "WidowMaker#3699");



foreach ($btgs as $btg){
	addMultipleBtgs($btg);
}

echo '<br>success<br>';

function addMultipleBtgs($btg){
	$btg = str_replace("#", "-", $btg);
	
	/** Stauts Defaul Value **/
	$dataStatus = array(); // label => true; ex) ystDay => true, week => true;
	$dataResult = array();
	$isFirstVisit = false;
	
	$isExistPlayer = checkId($btg, 'playerlist');	// playerlist table에 해당 btg가 있는지 확인
	
	if(!$isExistPlayer){	// Player List에 등록된 아이디가 아니면
		$crawlData = getCrawlDatas($btg);	// Crawl Data를 시도
		if($crawlData == null){ 	// Crawl Data가 검증되지 않았으면 해당 사용자는 존재하지 않음.
			echo $btg . '<br>';
			sendError(2, getErrMessage(2));	//"배틀넷에 존재하지 않는 사용자입니다.";
			return;
		} else {	// Crawl Data가 검증된 데이터이면 사용자를 추가합니다.
			$isFirstVisit = true; // 처음 방문하여서 배틀태그가 추가되는 사용자 입니다.
			if(!regNewBtg($btg, $crawlData)){
				echo $btg . '<br>';
				sendError(3, getErrMessage(3));
				return;
			} else {	// 정상적으로 regsiter New Battle Tag가 된 경우
				// continue
			}
		}
	}

	$btg = getCaseSensitiveId($btg, 'playerlist');
	$currentData = getDBDatas($btg, 'current');
	$rctDayData = getDBDatas($btg, getDbName('recentDay'));
	$rctWeekData = getDBDatas($btg, getDbName('recentWeek'));
	$rctYstData = getDbDatas($btg, getDbName('yesterDay'));
	
	// TODO : table 에러 추가.
	// isTable($dbName) Table Error 추가
	
	// CurrentData는 기준 데이터 이므로 NULL이면 중지.
	if($currentData == null){
		echo $btg . '<br>';
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
		echo $btg . '<br>';
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
}

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
	if($interval > 600000){	// 등록된 사용자는 사용자가 600초 이후에 접속하면 무조건 갱신하기..
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