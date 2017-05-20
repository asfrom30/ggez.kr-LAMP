<?php

// require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");
require_once (LIBRARY_PATH . "/CrawlDatas.php");

class AsyncOperation extends Threaded {
    public $btg;
    public $table;

    public function __construct($btg, $table) {
        $this->btg = $btg;
        $this->table = $table;
    }

    public function run() {
        if ($this->btg) {
            $btg = $this->btg;
			// PlayOverWatch에서 조회.
			$datas = getCrawlDatas($btg);
			echo "run btg = ". $btg;
			// 아이디가 없으면 INSERT
            $table = $this->table;

			if(checkId($btg, $table)){
				updateDbDatas($table, $btg, $datas);
				// 아이디가 있으면 UPDATE
			} else {
				instDbDatas($table, $btg, $datas);
			}
        }
    }
}

function cronDatas($mysqli, $table){
	// Create Todat Date Table

	//Check Table name Exists
	if(true){
		// 플레이어리스트에 등록된 배틀태그 모두 가져오기
		$query = "SELECT btg from playerlist;";
		$result = $mysqli -> query($query);

		// 오늘 데이터 입력하기
		while($row = $result -> fetch_array()){
			$btg = $row['btg'];
			echo "in while btg = ".$btg;
			$stack[] = new AsyncOperation($btg, $table);

//
//			// PlayOverWatch에서 조회.
//			$datas = getCrawlDatas($btg);
//
//			// 아이디가 없으면 INSERT
//			if(checkId($btg, $table)){
//				updateDbDatas($table, $btg, $datas);
//				// 아이디가 있으면 UPDATE
//			} else {
//				instDbDatas($table, $btg, $datas);
//			}
		}

        foreach ( $stack as $t ) {
		    echo "start";
            $t->start();
        }

    }
}

// 숫자가 맞지 않으면 실행
function cronCheck($mysqli, $targetTable){
	$query = "SELECT btg from playerlist;";
	$result = $mysqli -> query($query);

	while($playerList = $result -> fetch_array()){

		$btg = $playerList['btg'];

		if(!checkId($btg, $targetTable)){
			echo $btg . '<br>';
			$datas = getCrawlDatas($btg);
			instDbDatas($targetTable, $btg, $datas);
		}
	}

}

function getRowNum($mysqli, $targetTable){
	$query = "SELECT btg from " . $targetTable;
	$result = $mysqli -> query($query);
	$array = mysqli_fetch_all($result);
	return count($array);
}

function createTable($mysqli, $table){
	$result = false;

	// sql to create table
	$sql = "CREATE TABLE " . $table .
	// 	    " (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,"	// not using id key
	//배틀태그 한글8글자, 영어12글자
	"(btg VARCHAR(30) PRIMARY KEY, "
	. "Datas TEXT, "
	. "reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE = INNODB;";

	if ($mysqli->query($sql) === TRUE) {
		echo "Table [" . $table . "] created successfully";
// 		ChromePhp::log("Table [" . $table . "] created successfully");
	} else {
		// 이메일로 전송해야 할듯..
		echo "Error creating table: " . $mysqli->error;
// 		ChromePhp::log("Error creating table: " . $mysqli->error);
	}
}


function deleteTableRows($mysqli, $db){
	// table내에 있는 row 모두 삭제..
	$query = "DELETE FROM " . $db;
	$result = $mysqli -> query($query);
	queryLog($query, $result);
}
