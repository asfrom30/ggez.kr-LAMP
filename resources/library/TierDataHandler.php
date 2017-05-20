<?php


// require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once (LIBRARY_PATH . "/DbHandler.php");
require_once (LIBRARY_PATH . "/CronDataFilter.php");
require_once (INIT_PATH . "/HeroInfoList.php");

require_once (LIBRARY_PATH . "/ChromePhp.php");

/** example usage 
$mysqli = connectDB();
$statMap = getStatMap(); 
$tierDataHandler = new TierDataHandler($mysqli, $statMap);
$tierDataHandler -> manualRun('2017_01_25', '2017_01_25');	
**/

class TierDataHandler{
	
	private $mysqli;
	private $statMap;
	
	function __construct($mysqli, $statMap) {
		$this-> mysqli = $mysqli; 
		$this-> statMap = $statMap;
	}
	
	function __destruct() {
		
	}
	
	function autoRun(){
		$this-> mysqli -> close();
	}
	
	function manualRun($objectRow, $targetRow){
		
		// 초기값준비 완료
		$this -> initTierData();
	
		// heroInfo 기준으로 json 구조 생성(합/개수)
	
		// 기준 테이블 이름을 선정
		// 기준 테이블의 모든 데이터 fetch array
		// LOOP를 돌면서.
		// 하나씩 분석해서 json에 입력 저장하고 나오기
		
		$fetchSubjectRows= $this -> getAllRows($objectRow);
		
		if(mysqli_num_rows($fetchSubjectRows) == 0){
			return;
		}
				
		$subjectRows = mysqli_fetch_all($fetchSubjectRows, MYSQLI_ASSOC);
		
		// SubjectRows는 sql에서 가져온 모든 사용자들의 데이터
		foreach($subjectRows as $subjectRow){
			
			// sql에서 가져온 data를 array 형태로 만든다.
			$fetchOrgDatas = $this -> getRow(/** table name **/ 'tierdata', /** column name **/ 'datas', /** row id **/ 'temp');
			$orgDatas = mysqli_fetch_row($fetchOrgDatas)[0];
			$orgDatas = json_decode($orgDatas, true);
			
// 			$orgDatas를 가져온 뒤에 sum을 추가할 수 있다.
			$orgDatas['etcData']['sum'] += 1;
			
			$playerDatas = json_decode($subjectRow['Datas'], true);
			$playerDatas = $this -> removeCtg($playerDatas);	// 1 layer Category 제거
			
			$cptPt = $playerDatas['All']['cptPt'];
			$tierIndex = $this -> getTierIndex($cptPt);
			
			
			if($tierIndex == null){
				continue;
			}
			
			// 영웅 loop를 돌면서 치른게임이 10이상이면 해당 영웅 데이터를 orgDatas에 저장
			foreach ($this->statMap as $hero => $eachStatMap){
				if($hero == 'All'){
					continue;
				}
				if($playerDatas[$hero]['치른 게임'] > 10){
					// 영웅데이터의 한열을 계산하는 것.
					foreach ($eachStatMap as $eachStat){
						$label = $eachStat['label'];
						$factors = $eachStat['factor'];
						
						//소수점 자리수 설정. 기본값은 2로 설정
						$round = $eachStat['round'];
						if($round == null){
							$round = 2;
						}
						
						$unitData = $this -> getUnitData($playerDatas, $hero, $factors, $round);
						
						$orgDatas[$tierIndex][$hero][$label]['sum'] += $unitData;
						$orgDatas[$tierIndex][$hero][$label]['num'] += 1;
					}
				}
			}
			$this-> updateRow(/** table name **/ 'tierdata', 'temp', $orgDatas);
		}
		
		// temp에 있는 사항을 날짜 데이터에 저장합니다.에 저장합니다.
		if($targetRow != null){
			$tempData = $this->getRow('tierdata', 'datas', 'temp_backup');
			$tempData = mysqli_fetch_row($tempData)[0];
			$tempData = json_decode($tempData, true);
			$this->insertRow('tierdata', $targetRow, $tempData);
		}
	}
	
	function initTierData(){
		$this->initTable();
		$this->initTempRow();
	}
	
	function initTable(){
		if(!isTable('tierData')){
			$this->createTable();
		}
	}
	// Temp Row를 비운다.
	function initTempRow(){
		
		$tableName = 'tierdata';
		$column = 'id';
		$value = 'temp';
		
		// tierdata 테이블에 temp 행일 가진 데이터 있는지 확인
		// 있으면 삭제 한다.
		if($this->hasRow($tableName, $column, $value)){
			$this->deleteRow($tableName, $column, $value);
		}
		
		
		// temp 값을 초기화 하여 집어넣는다.
		$heroStatMap = getStatMap();
		$totalUser = 0;
		$readUser = 0;
		$result = array();
		
		foreach ($heroStatMap as $hero=>$heroRows){
			$result[$hero] = array();
			foreach ($heroRows as $heroRow){
				$result[$hero][$heroRow['label']] = array('sum' => 0, 'num' => 0);
			}
		}
	
// 		$result = array('totalUser' => 0, 'readUser' => 0, 'writeUser' => 0, 'result' => $result);
		$datas = array( 'etcData' => array('sum' => 0), 'bronze' => $result, 'silver' => $result, 'gold' => $result, 'platinum' => $result, 'dia' => $result, 'master' => $result, 'grandMaster' => $result);	
		// insert into db
		$this-> insertRow($tableName, 'temp', $datas);
	}
	
	function createTable(){
		$mysqli = $this->mysqli;
		$result = false;
	
		// sql to create table
		$sql = "CREATE TABLE tierdata " .
		// 	    " (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"	// not using id key
		//배틀태그 한글8글자, 영어12글자
		"(id VARCHAR(30) PRIMARY KEY, "
				. "Datas TEXT, "
						. "reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		) ENGINE = INNODB;";
						
// 		if ($mysqli -> query($sql) === TRUE) {
// 			echo "Table [tierData] created successfully";
// 		} else {
// 			echo "Error creating table: " . $mysqli->error;
// 		}
	}
	
	function hasRow($tableName, $column, $row){
		$mysqli = $this->mysqli;
		$query = "SELECT " . $column . " from " . $tableName . " where " . $column . "='" . $row . "';";	// today에서 조회해야됨. playerlist는
		
		$result = $mysqli->query($query);
		$rows = mysqli_num_rows($result);
		
		if($rows == 0){
			return false;
		} else {
			return true;
		}
	}
	
	function deleteRow($tableName, $column, $value){
		$mysqli = $this->mysqli;
		$query = "DELETE FROM " . $tableName . " WHERE " . $column . " = '" . $value . "';";
		$this->doQuery($mysqli, $query);
	}
	
	
	function insertRow($tableName, $id, $datas){
		// 두번째 옵션은 JSON 한글깨짐 방지하기..
		$datas = json_encode($datas, JSON_UNESCAPED_UNICODE);
		
		$mysqli = $this->mysqli;
		$query = "INSERT INTO " . $tableName . " (id, datas) VALUES ('" . $id ."','".$datas."');";
		$this->doQuery($mysqli, $query);
	}
	
	function updateRow($tableName, $id, $datas){
		$datas = json_encode($datas, JSON_UNESCAPED_UNICODE);	//한글깨짐방지.
		
		$mysqli = $this->mysqli;
		$query = "UPDATE " . $tableName .
		" SET datas ='" . $datas .
		"', reg_date=now() WHERE id='" . $id . "';";
		
		$this->doQuery($mysqli, $query);
	}
	
	function getAllRows($tableName){
		$mysqli = $this->mysqli;
		$query = "SELECT * from " . $tableName . ";";
		$result = $mysqli->query($query);
	
		return $result;
	}
	
	function getRow($tableName, $column, $id){
		$mysqli = $this->mysqli;
		$query = "SELECT " . $column . " from " . $tableName. " where id='" . $id . "';";
		$result = $mysqli->query($query);
		
		return $result;
	}
	
	
	function doQuery($mysqli, $sql){
		if ($mysqli -> query($sql) === TRUE) {
// 			echo "[" . $sql . "] successfully";
		} else {
// 			echo "Error creating table: " . $mysqli->error;
		}
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
