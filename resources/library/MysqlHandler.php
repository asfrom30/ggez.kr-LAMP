<?php 
/**
 * 
 * -. query를 실행하고 실패하면 result값이 false로 나온다. 실행에 성공하면 array로 반환(논리값은 true)  
 * -. query 결과값을 받았으면 mysqli - 
 * -. 가장 최근에 실행한 query에 대한 에러값을 확인하고 싶으면 $this -> mysqli -> error;
 * -. $result -> fetch_array와 $result -> fetch_all의 차이는 연산속도 차이 대부분의 경우 all이 조금 빠르다.
 */

/**
 * Note
 * doQuery에 실패하면 Null 값을 반환합니다. 논리값은 반드시 ===(Strict Comparisons로 검증하세요) - false와 NULL을 구분하지 못함.
 * 
 */

require_once '../../resources/config.php';

class MysqlHandler {
	
	private $errorLogFileName;
	
	private $mysqli;
	
	function __construct($mysqli) {
		// TODO : $this -> mysqli = connectDB로 수정할것.
		$this -> mysqli = $mysqli;

		/* Error Variable */
		global $error_config;
		$this -> errorLogFileName = $error_config["sqlErrLogFileName"];
	}
	
	function __destruct() {
		$this-> mysqli -> close();
	}
	
	/* $tableName의 $pkHeaderName항목에서 $pkKey와 일치하는 값을 찾습니다. */
	/* Sql syntax : SELECT COUNT(*) FROM tierdata WHERE id="2017_02_22" */
	function hasRowWithPK($tableName, $pkHeaderName, $pkKey){
		$query = "SELECT COUNT(*) from " . $tableName . " where " . $pkHeaderName . "='" . $pkKey . "';";
		$result = $this -> doQuery($query);

		if($result === null){	// Query 실패
			return null;
		} else {
			$count = mysqli_fetch_all($result);
			if($count[0][0] == 0){
				return false;
			} else {
				return true;
			}
		}
	}
	
	/* $tableName의 $pkHeaderName항목에서 $pkKey와 일치하는 값을 삭제합니다. */
	/* Return 'True' Query excute succesfully, Not returns Null */
	function deleteRowWithPK($tableName, $pkHeaderName, $pkKey){
		$query = "DELETE FROM " . $tableName . " WHERE " . $pkHeaderName . " = '" . $pkKey . "';";
		$result = $this->doQuery($query);
		return $result;
	}
	
	/* $tableName의 $pkHeaderName이 $pkKey인 Row를 입력합니다. 입력하지 않은 모든 값은 NULL이 됩니다. */
	function insertRowWithPK($tableName, $pkHeaderName, $pkKey){
		

		$query = "INSERT INTO " . $tableName . " (" . $pkHeaderName . ") VALUES ('" . $pkKey ."');";
		$result = $this->doQuery($query);
		return $result;
	}
	
	/* $pkHeaderName에서 $pkKey가진 Row를 찾습니다. $colHeader의 항목(Column)의 값을 반환합니다. */ 
	function getRowValueWithPK($tableName, $pkHeaderName, $pkKey, $selColHeader){
		$query = "SELECT " . $selColHeader . " from " . $tableName. " where " . $pkHeaderName . "='" . $pkKey . "';";
		$result = $this->doQuery($query);
		
		if($result === null){
			return null;		// Query 실행에 실패하면 null을 반환
		} else {
			$exist = ($result->num_rows > 0);
			if($exist){
				$result = $result -> fetch_row();
				return $result[0];
			} else {
				return false;
			}
		}
		
	}
	
	/* $pkHeaderName에서 $pkKey가진 Row를 찾습니다. $colHeader의 항목(Column)의 $colValue값을 Update 합니다. */
	function updateVal2RowWithPK($tableName, $pkHeaderName, $pkKey, $colHeader, $colValue){
		$query = $query = "UPDATE " . $tableName
				. " SET " . $colHeader . "='" . $colValue
				. "' WHERE " . $pkHeaderName . "='" . $pkKey . "';";
// 				. "', reg_date=now() WHERE " . $pkHeaderName . "='" . $pkKey . "';";	// If you want to update reg_date to recent Time, Use this statement
		$result = $this -> doQuery($query);
		return $result;
	}
	
	/* updateVal2RowWithPK() 함수를 이용하여 Array -> Json으로 변환하여 Update합니다. */
	function updateArr2RowWithPK($tableName, $pkHeaderName, $pkKey, $colHeader, $colArray){
		/* Array to Json */
		$colValue = json_encode($colArray, JSON_UNESCAPED_UNICODE);// 'JSON_UNESCAPED_UNICODE' 옵션은 한글깨짐을 방지합니다.
		
		$result = $this -> updateVal2RowWithPK($tableName, $pkHeaderName, $pkKey, $colHeader, $colValue);
		return $result;
	}
	
	/* Table 내의 모든 Row를 가져옵니다. */
	function getAllRows($tableName){
		$query = "SELECT * from " . $tableName . ";";
		$result = $this -> doQuery($query);
		
		if($result === null){
			return null;		// Query 실행에 실패하면 null을 반환
		} else {
			$exist = ($result->num_rows > 0);
			if($exist){
				return $result -> fetch_all();
			} else {
				return false;
			}
		}
	}
	
	function getAllRowsAsResult($tableName){
		$query = "SELECT * from " . $tableName . ";";
		$result = $this -> doQuery($query);
		
		if($result === null){
			return null;		// Query 실행에 실패하면 null을 반환
		} else {
			$exist = ($result->num_rows > 0);
			if($exist){
				return $result;
			} else {
				return false;
			}
		}
	}
	
	/* Table이 있는지 확인합니다.*/
	function hasTable($tableName){
		$query = "SHOW TABLES LIKE '" . $tableName . "'";
		$result = $this -> doQuery($query);
		
		if($result === null){	// Query 실패
			return null;
		} else {
			$exist = ($result->num_rows > 0);		// if 문처럼 사용해서 true를 반환하는 구문입니다.
			if($exist){
				return true;
			} else {
				return false;
			}
		}
	}
	
	/* get Rwo Values With Like */
	/* a% : Finds any values that starts with "a" */
	/* %a : Finds any values that ends with "a" */
	function getRowValuesWithLike($tableName, $patternHeader, $pattern, $selColHeader){
		
		$query = "SELECT " . $selColHeader . " FROM `". $tableName ."` WHERE " . $patternHeader . " LIKE '" . $pattern . "';";
		$result = $this -> doQuery($query);
		
		if($result === null){
			return null;		// Query 실행에 실패하면 null을 반환
		} else {
			$exist = ($result->num_rows > 0);
			if($exist){
				return $result -> fetch_all();
			} else {
				return false;
			}
		}
	}
	
		/**
	 *  Utility
	 */
	
	/* 결과값 가져오는 함수 */
	/* result는 row값들의 집합 */
	/* $rowNum로 몇번째 열을 가르키고, $field는 몇번째 행인지를 가르킨다.*/ 
	function mysqli_result($result, $rowNum, $field=1) {
		$result->data_seek($rowNum);
		$datarow = $result->fetch_array();
		return $datarow[$field];
	}
	
	/* 쿼리를 실행해서 에러시 로그를 남기는 함수 */
	/* Query에 실패할 경우 NULL 값을 반환 합니다. */
	function doQuery($query){
		$result = $this -> mysqli -> query($query);
		if ($result == TRUE) {
			return $result;
		} else {
			$errTime = date('y-m-d H:i:s');
			$errMessage = $this -> mysqli -> error;
// 			error_log("[" . $errTime ."]		". $errMessage ." [". $query . "]\n", 3, ERRORLOG_PATH . "/" . $this -> errorLogFileName);
			return NULL;
		}
	}
}




?>