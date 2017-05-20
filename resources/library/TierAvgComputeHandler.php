<?php

// require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once '../../resources/config.php';

require_once (LIBRARY_PATH . "/DbHandler.php");
require_once (LIBRARY_PATH . "/MysqlHandler.php");
require_once (LIBRARY_PATH . "/CronDataFilter.php");
require_once (INIT_PATH . "/HeroInfoList.php");

/** example usage 
	$mysqli = connectDB();
	$statMap = getStatMap(); 
	$tierDataHandler = new TierDataHandler($mysqli, $statMap);
	$tierDataHandler -> manualRun('2017_01_25', '2017_01_25');	
**/

class TierAvgComputeHandler{
	
	private $errorLogFileName;
	
	private $mysqli;
	private $statMap;
	private $mysqlHandler;
	private $tableName;
	private $pkHeaderName;
	private $dataHeaderName;
	
	
	function __construct() {
		//TODO : mysqli가 Null 일때 핸들링할것
		$this-> mysqli = connectDB();
		$this-> statMap = getStatMap();
		$this -> mysqlHandler = new MysqlHandler($this->mysqli);
		
		global $tierAverageVars;
		$this -> tableName = $tierAverageVars["tableName"];
		$this -> pkHeaderName = $tierAverageVars["pkHeaderName"];
		$this -> dataHeaderName = $tierAverageVars["data"];
		
		/* Error Variable */
		global $error_config;
		$this -> errorLogFileName = $error_config["errLogFileName"];
	}
	
	function __destruct() {
		$this-> mysqli -> close();
	}
	
	function autoRun(){
		$this-> mysqli -> close();
	}
	
	/**	ManualRun Description **/
	/**	heroInfo 기준으로 json 구조 생성(합/개수)
		기준 테이블 이름을 선정
		기준 테이블의 모든 데이터 fetch array
		LOOP를 돌면서.
		하나씩 분석해서 json에 입력 저장하고 나오기
	**/
	
	function manualRun($objectDate, $targetDate){
		
		/* 핸들러 준비 */
		$mysqlHandler = $this -> mysqlHandler;

		/* objectDate 테이블이 있는지 먼저 확인한다. */
		$hasTable = $mysqlHandler -> hasTable($objectDate);
		if(!$hasTable){
			if($hasTable===false){	// False는 No table(Null은 Query실패)
				errorLog(__FILE__, "분석하고자 하는 테이블(" . $objectDate . ")이 데이터베이스에 존재하지 않습니다.");
			}
			return;
		}

		/* ObjectTable에 있는 모든 Row값을 가져온다. */
		$result = $mysqlHandler->getAllRowsAsResult($objectDate);
		
		if(!$result){
			if($allRows===false){	// False = Row값이 0(Null은 Query실패)
				errorLog(__FILE__, "분석하고자 하는 테이블(" . $objectDate . ")에 사용자 Row가 0입니다.");
			}
			return;
		}
		
		/* 테이블 내에 저장될 Row를 확보한다. Row에 빈값을 넣어서 초기화를 완료한다.*/
		$this->initiate($targetDate);
		
		//TODO:17/04/23 데이터 기준으로 실제 사용자는 13774이지만, TIER AVERAGE값은 11299 왜 이런차이가 발생하는지 로그로 남겨서 알아보기.
		/* 사용자들의 데이터를 하나씩 업데이트 합니다.*/
		while ($row = $result->fetch_assoc()){
			/* 저장소에 있는 Datas를 가져와서 초기화 합니다.*/
			$arrTierAvgData = $mysqlHandler -> getRowValueWithPK($this -> tableName, $this -> pkHeaderName, $targetDate, $this -> dataHeaderName);
			$arrTierAvgData = json_decode($arrTierAvgData, true);	// true는 array 타입
			
			/* 데이터 사용자 1명 추가*/
			$arrTierAvgData['etcData']['sum'] += 1;
			
			/* player data를 array 형태로 준비 */
			$playerData = json_decode($row[$this->dataHeaderName], true);
			$playerData = $this -> removeCtg($playerData);	// 1 layer Category 제거
			
			/* 사용자 티어 분석 */
			$cptPt = $playerData['All']['cptPt'];
			$tierIndex = $this -> getTierIndex($cptPt);
			if($tierIndex == null){	// TODO : 검증 필요
				continue;
			}
			
			/* $arrTierAvgData값에 1명의 PlayerData를 업데이트 한다. */
			/* 해당함수에서는 Hero Loop만 돈다. */
			$arrTierAvgData = $this->updateHeroData($arrTierAvgData, $tierIndex, $playerData);
			
			/* 개별 데이터를 데이터를 업데이트 */
			$mysqlHandler -> updateArr2RowWithPK($this -> tableName, $this -> pkHeaderName, $targetDate, $this -> dataHeaderName, $arrTierAvgData);
		}
		
		/* 저장된 데이터를 TEMP로 옮길것. */
// 		$arrTierAvgData = $mysqlHandler -> getRowValueWithPK($this -> tableName, $this -> pkHeaderName, $targetDate, $this -> dataHeaderName);
// 		$arrTierAvgData = json_decode($arrTierAvgData, true);	// true는 array 타입	
		// temp에 있는 사항을 날짜 데이터에 저장합니다.에 저장합니다.
		// 		if($targetDate != null){
		// 			$tempData = $this->getRow('tierdata', 'datas', 'temp_backup');
		// 			$tempData = mysqli_fetch_row($tempData)[0];
		// 			$tempData = json_decode($tempData, true);
		// 			$this->insertRow('tierdata', $targetDate, $tempData);
		// 		}
		
		/* 작업결과 이메일로 보내기 */
		//TODO : 이메일로 로그 남기는 법 알아 두기..

	}
	
	function initiate($targetDate){
		/* $mysqlHandler 지역변수로 가져오기 */
		$mysqlHandler = $this -> mysqlHandler;
		$tableName = $this -> tableName;
		$pkHeaderName = $this -> pkHeaderName;
		$pkKey = $targetDate;
		$dataHeaderName = $this -> dataHeaderName;
	
		/* $targetDate Row가 있으면 삭제합니다. */
		$hasRow = $mysqlHandler ->hasRowWithPK($tableName, $pkHeaderName, $pkKey);
		if($hasRow){
			$this -> mysqlHandler -> deleteRowWithPK($tableName, $pkHeaderName, $pkKey);
		} 
		
		/* $targetDate Row를 생성합니다. */
		$this -> mysqlHandler -> insertRowWithPK($tableName, $pkHeaderName, $pkKey);
		
		/**
		 *  초기화 값을 $targetDate Row에 업데이트 합니다.
		 */
		
		/* Initiate Variable */
		$heroStatMap = $this-> statMap;	$tierInitResult = array();
		
		/* $tierInitResult안에 영웅별/항목별 Array를 생성합니다. Array는 sum과 num으로 이루어져 추후 sum/num으로 계산합니다. */
		foreach ($heroStatMap as $hero=>$heroRows /*$key => $value */){
			$tierInitResult[$hero] = array();
			foreach ($heroRows as $heroRow){
				$tierInitResult[$hero][$heroRow['label']] = array('sum' => 0, 'num' => 0);
			}
		}
		
		/* Init Tier Average Date 구조 생성*/
		$initTierAvgDatas = array( 'etcData' => array('sum' => 0), 'bronze' => $tierInitResult, 'silver' => $tierInitResult, 'gold' => $tierInitResult, 'platinum' => $tierInitResult, 'dia' => $tierInitResult, 'master' => $tierInitResult, 'grandMaster' => $tierInitResult);

		/* 값을 업데이트 합니다. */
		$this -> mysqlHandler -> updateArr2RowWithPK($tableName, $pkHeaderName, $pkKey, $dataHeaderName /* $colHeader */, $initTierAvgDatas /* $colArray */);
	}
	
	/* 영웅 loop를 돌면서 치른게임이 10이상이면 해당 영웅 데이터를 orgDatas에 저장 */
	function updateHeroData($arrTierAvgData, $tierIndex, $playerData){
		
		foreach ($this->statMap as $hero => $eachStatMap){
			if($hero == 'All'){
				continue;
			}
			// TODO : #ROUND 계산방법 검증할 것. 아래 코드를 정확히 아직 확인을 못함(소수점이라던지..)
			if($playerData[$hero]['치른 게임'] > 10){
				// 영웅데이터의 한열을 계산하는 것.
				foreach ($eachStatMap as $eachStat){
					$label = $eachStat['label'];
					$factors = $eachStat['factor'];
					
					//소수점 자리수 설정. 기본값은 2로 설정
					$round = $eachStat['round'];
					if($round == null){
						$round = 2;
					}
						
					$unitData = $this -> getUnitData($playerData, $hero, $factors, $round);
					
					$arrTierAvgData[$tierIndex][$hero][$label]['sum'] += $unitData;
					$arrTierAvgData[$tierIndex][$hero][$label]['num'] += 1;
				}
			}
		}
		
		return $arrTierAvgData;
	}
	
	
	/* Deprecated, Now This function is not needed */
	/* CreateTable should be needed Enviorment Variable(ex:tierdata Table name)*/
	/* Sql Query */
	/* Queyr example : "(id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"	// not using id key */
	/* 배틀태그 한글8글자, 영어12글자 */
// 	function createTierTable(){
// 		/* Initiate Needed Variable */
// 		$mysqli = $this->mysqli;
// 		$result = false;

// 		$sql = 	"CREATE TABLE tierdata " .
// 				"(id VARCHAR(30) PRIMARY KEY, " .	
// 				"Datas TEXT, " .
// 				"reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE = INNODB;";
// 	}
	

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
	
	function getTierIndex($cptPt){
		
		$cptPt = intval($cptPt);
		if(1< $cptPt && $cptPt < 1499){
			return "bronze";
		} else if(1500 < $cptPt && $cptPt < 1999){
			return "silver";
		} else if(2000 < $cptPt && $cptPt < 2499){
			return "gold";
		} else if(2500 < $cptPt && $cptPt < 2999){
			return "platinum";
		} else if(3000 < $cptPt && $cptPt < 3499){
			return "dia";
		} else if(3500 < $cptPt && $cptPt < 3999){
			return "master";
		} else if(4000 < $cptPt && $cptPt <= 5000){
			return "grandMaster";
		} else {
			return null;
		}
	}
	
	function getUnitData($playerDatas, $hero, $factors, $round){
		$radarRaw = 0;
		
		//Factor Type에 관계없이 무조건 계산으로만 구할 수 있다.
		$numeratorLabel = $factors[1]; $denominatorLabel = $factors[2];
		$numerator = $this->getValue($playerDatas[$hero], $numeratorLabel);		//numerator 분자 
		$denominator = $this->getValue($playerDatas[$hero], $denominatorLabel); //denominator 분모
		
		// 가져온 값이 String 이므로 String to Float
		$numerator = tofloat($numerator);
		$denominator = tofloat($denominator);
		
		if($numerator == null || $denominator == null || $numerator == 0 || $denominator == 0){
			return null;
		}
		
		$result = $numerator/$denominator;
		return round($result, $round);
	}
	
	function getValue($array, $key){
		// 		해당 항목에 키가 있으면 정해진 값을 반환하고.
		if(array_key_exists($key, $array)){
			return $array[$key];
			//		해당 항목에 키가 없으면 null을 반환한다.
		} else {
			return null;
		}
	}
	
}





// Create Tier Table
