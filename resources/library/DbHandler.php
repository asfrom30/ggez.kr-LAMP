<?php

// url은 ASCII character-set. 임.. urlencode를 써줘야됨.
/*
 * Improve Things to Do
*/
// 1.
// DB 세션확인, 세션 공유, PHP 세션유지, 세션 삭제 등등
// 세션에 관련된 것들

// 2. url 주소 리소스에 저장할것
// DB 세션확인, 세션 공유, PHP 세션유지, 세션 삭제 등등
// 세션에 관련된 것들

// 4.
// DB 세션확인, 세션 공유, PHP 세션유지, 세션 삭제 등등
// 세션에 관련된 것들

// 5. 알수 없는 배틀태그일때는 어떻게 처리할 것인가
// Debug : https://paulund.co.uk/output-php-data-in-browser-console
// Hash Map : $hashMap = array('mykey'=>"value1", 'mykey2' => "value2");


// 6. LootBox Api 정보 주고받는 속도 개선필요...
// PHP를 여러개 만들어서 REQUEST를 한번에 날리는 것은 어떨까....


// double encoded
// 1. JSON ''로 감싸면 텍스트형태이기 때문에 파싱을해야한다. ECHO나 다른곳에서 오는경우
// {} 되어 있는 거는 var 선언이 json 타입으로 되는듯 그래서 굳이 파싱을 하지 않아도 되는듯.
// 2. [] 브라켓의 배열은 똑같은 함수를 여러번 사용하고 싶을때 사용한다.
// 따라서 test.Soldier76.WeaponAccuracy 배열을 사용하게되면 Soldier76의 WeaponAccuracy가 0,1,2,3,4, 이런식으로 생기는것
// 접근방법은  test.Soldier76[0].WeaponAccuracy, 즉 Soldier76의 [0]번째 배열로 WeaponAccuracy접근...


// Query
//  Delete All rows from table
//  -. $query = "DELETE FROM " . $db;


/*
 * Improve Things to Do
*/


/*
 * row column fetch array
*/

// if(mysqli_num_rows($result) == 0){


// } else {
// 	// 	//$row가 null 값이 되지 않게 주의!!
// 	// 	$row = $result->fetch_row();
// 	// 	$row[0];

// 	return $data;

// }

// mysql fetch_row
// http://php.net/manual/en/function.mysql-fetch-row.php

// mysql fetch_array
// http://php.net/manual/en/function.mysql-fetch-array.php

// 	if($rows == 0){

// 	} else {
// 		$row = mysql_fetch_row($result);
// 		$id = $row['id'];
// 	};

// $mysqli = connectDB();
// $query = "INSERT INTO playerlist (btg) VALUES ('" . $btg . "');";
// $mysqli->close();



//
// Insert Into는 새 row를 입력할때 사용하는 것이다.

// Update Row Example
// UPDATE Customers
// SET ContactName='Alfred Schmidt', City='Hamburg'
// 		WHERE CustomerName='Alfreds Futterkiste';

// 		$query = "UPDATE " . $db .
// 		" SET PlayTime='" . $playtimes . "'
// 	WHERE btg='" . $btg . "';";

/*
 * Data 꺼내기
*/
// get row_num
// 	$row_num = mysqli_num_rows($result);

// while문
// while($row = $result->fetch_array())
// {
// 	echo $row['btg'];
// 	echo "<br />";
// }

// fetch_all
// 	$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);	//option MYSQLI_ASSOC(char), MYSQLI_NUM(int), MYSQLI_BOTH(both)

// 이 두개는 어디에 쓰는건지?(wile문에는 쓰임)
//  $row = $result -> fetch_array()
// 	$row = $result -> fetch_row();

// TODO : UTF8MB4, UNICODE 어디에 쓰이는거지??

// require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
// require_once (INIT_PATH . "/HeroInfoList.php");


//TODO : connect DB 로그 남기는것과 에러 핸들링 하는 것 정리할 것..
function connectDB(){
	
	/* Set error Handler */
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
		// error was suppressed with the @-operator
		if (0 === error_reporting()) {
			return false;
		}
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});
	
	/* Variable Initiate */
	global $config;
	$mysqli = null;
	$dbname = $config['db']['main']['dbname'];
	$username = $config['db']['main']['username'];
	$password = $config['db']['main']['password'];
	$host = $config['db']['main']['host'];
	
	try {
		/* Connect DB */ 
		$mysqli = new mysqli ( $host, $username, $password, $dbname );
	
		/* Leave Log */
		if ($mysqli->connect_errno) {
			my_error_log(__DIR__, "Connect failed: %s\n", $mysqli->connect_error);
		} else {
	// 		my_error_log(__DIR__, "[Connect Success] Initial character set: " . $mysqli->character_set_name ());
			/* 문자 세트를 utf8&nbsp을 변경합니다&nbsp; */
			if (! $mysqli->set_charset ( "utf8" )) {
				my_error_log(__DIR__, "Error loading character set utf8: " . $mysqli->connect_error);
				exit ();
			} else {
	// 			my_error_log(__DIR__, "Success loading character set utf8: " . $mysqli->character_set_name ());
			}
		}
		restore_error_handler();
		return $mysqli;
	} catch (Exception $e) {
		restore_error_handler();
		return null;
	}
}

/*
 * Get Data from Db
 */
function getDBDatas($btg, $db){
	$mysqli = connectDB();
	
// 	$query = "SELECT datas from " . $db. " where btg='" . $btg . "';";	// today에서 조회해야됨. playerlist는
	$query = "SELECT * from " . $db. " where btg='" . $btg . "';";
	$result = $mysqli->query($query);
	
	$rows = mysqli_num_rows($result);
	//log남기기
	queryLog($query, $result);

	if($rows == 0){
		// log 필요
		return null;
	} else {
		return mysqli_fetch_array($result);
// 		return mysqli_fetch_row($result);
	}
	$mysqli->close();
}

function getRow($tableName, $columnName, $rowName, $rowId){
	$mysqli = connectDB();
	$query = "SELECT " . $columnName . " from " . $tableName. " where " . $rowName . "='" . $rowId . "';";
	$result = $mysqli->query($query);
	$rows = mysqli_num_rows($result);

	if($rows == 0){
		return null;
	} else {
		return mysqli_fetch_row($result)[0];
	}
}

function instDbDatas($table, $btg, $datas){
	$mysqli = connectDB();
	
	// 두번째 옵션은 JSON 한글깨짐 방지하기..
	$datas = json_encode($datas, JSON_UNESCAPED_UNICODE);
	
	$query = "INSERT INTO " . $table . " (btg, datas) VALUES ('" . $btg ."','".$datas."');";
	$result = $mysqli -> query($query);
	
	//log남기기
	queryLog($query, $result);
	$mysqli->close();
}

function updateDbDatas($table, $btg, $datas){
	
	$mysqli = connectDB();
	$datas = json_encode($datas, JSON_UNESCAPED_UNICODE);	//한글깨짐방지.

	$query = "UPDATE " . $table .
	" SET datas ='" . $datas .
	"', reg_date=now() WHERE btg='" . $btg . "';";

	$result = $mysqli -> query($query);
	//log남기기
	queryLog($query, $result);
	
	$mysqli->close();
}


/*
 * MySql Update Data
 */
// ex 'profile' 'notjson' 'btg' 'today'
function updateData($field, $datas, $btg, $db){
	$mysqli = connectDB();
	$datas = json_encode($datas);

	// UPDATE current SET datas='$data',
	$query = "UPDATE " . $db .
	" SET " . $field . "='" . $datas .
	"', reg_date=now() WHERE btg='" . $btg . "';";
	
	$result = $mysqli -> query($query);

	//log남기기
	queryLog($query, $result);

	$mysqli->close();
}

// 해당 table에서 check 해당 btg가 있는지 확인
function checkId($btg, $db){
	$mysqli = connectDB();
	$query = "SELECT btg from " . $db. " where btg='" . $btg . "';";	// today에서 조회해야됨. playerlist는

	$result = $mysqli->query($query);
	$rows = mysqli_num_rows($result);
	
	if($rows == 0){
		return false;
	} else {
		return true;
	}
	$mysqli->close();
}

function getCaseSensitiveId($btg, $db){
	$mysqli = connectDB();
	$query = "SELECT btg from " . $db. " where btg='" . $btg . "';";	// today에서 조회해야됨. playerlist는
	$result = $mysqli->query($query);
	$mysqli->close();
	return mysqli_fetch_row($result)[0];
}

function addBtg($btg, $db, $loc, $device){
	$mysqli = connectDB();
	// 	$query = "INSERT INTO " . $db . " (btg, loc, device) VALUES ('" . $btg . "', '" . $loc . "', '" . $device . "');"; // location & device 추가 쿼리
	$query = "INSERT INTO " . $db . " (btg) VALUES ('" . $btg . "');";
	$result = $mysqli->query($query);

	queryLog($query, $result);

	// 	echo 'btg= [' . $query . ']<br>';
	// 	if ($mysqli->query($query) === TRUE) {
	// 		echo "New record created successfully";
	// 	} else {
	// 		echo "Error: " . $query . "<br>" . $mysqli->error;
	// 	}
	$mysqli->close();
}

// function addBtg($btg, $db){
// 	$mysqli = connectDB();
// 	$query = "INSERT INTO " . $db . " (btg) VALUES ('" . $btg . "');";
// 	$result = $mysqli->query($query);

// 	queryLog($query, $result);

// 	// 	echo 'btg= [' . $query . ']<br>';
// 	// 	if ($mysqli->query($query) === TRUE) {
// 	// 		echo "New record created successfully";
// 	// 	} else {
// 	// 		echo "Error: " . $query . "<br>" . $mysqli->error;
// 	// 	}
// 	$mysqli->close();
// }


function getDuplicateBtg($btg){
	$mysqli = connectDB();
	$query = "SELECT * FROM `playerlist` WHERE btg LIKE '" . $btg . "%';";
	$result = $mysqli->query($query);
	$mysqli->close();
	return 	mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function isTable($tableName){
	$mysqli = connectDB();
	
	$result = $mysqli->query("SHOW TABLES LIKE '" . $tableName . "'");
	$exist = ( $result->num_rows > 0 );
	$mysqli->close();

	return $exist;
}


// Validate String to Json
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

// Query Log
function queryLog($query, $result){
	if($result){
// 		echo "쿼리실행 : [" . $query . "] => 쿼리성공";
// 		ChromePhp::log("쿼리실행 : [" . $query . "] => 쿼리성공");
	} else {
// 		echo "쿼리실행 : [" . $query . "] => 쿼리실패";
// 		ChromePhp::log("쿼리실행 : [" . $query . "] => 쿼리실패");
	}
}


