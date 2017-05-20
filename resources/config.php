<?php

/*
 Creating constants for heavily used paths makes things a lot easier.
 ex. require_once(LIBRARY_PATH . "Paginator.php")
 */

defined("INIT_PATH") or define("INIT_PATH", realpath(dirname(__FILE__) . '/init'));
defined("CRON_PATH") or define("CRON_PATH", realpath(dirname(__FILE__) . '/cron'));

defined("LIBRARY_PATH") or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
defined("ERRORLOG_PATH") or define("ERRORLOG_PATH", realpath(dirname(__FILE__) . '/error_log'));
defined("TEMPLATES_PATH") or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT']);

/*
 The important thing to realize is that the config file should be included in every
 page of your project, or at least any page you want access to these settings.
 This allows you to confidently use these settings throughout a project because
 if something changes such as your database credentials, or a path to a specific resource,
 you'll only need to update it here.
 */

$pbConfig = array(
		"urls" => array(
				"baseUrl" => "http://ggez.kr"
		),
		"paths" => array(
				"resources" => "/path/to/resources",
				"images" =>  $_SERVER["DOCUMENT_ROOT"] . "/img",
				// 				"images" => array(
				// 						"content" => $_SERVER["DOCUMENT_ROOT"] . "/img",
				// 						"layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
				// 				)
		),
);

/** Network Config **/
// $config = array(
// 		"db" => array(
// 			"main" => array(
// 					"dbname" => "ggezkr_raw",
// 					"username" => "ggezkr_root",
// 					"password" => ";9#?!ECM5--t",
// 					"host" => "localhost:3306"
// 			)
// 		),
// 		"registerOauth" => array(
// 				"key" => "jjn57g9fnvvgt8gaterccc586vpf267s",
// 				"secret" => "jgDswXy9H6w44FmTvNRNKHtezbDWcTAS"
// 		),
// 		"removeOauth" => array(
// 				"key" => "8xxazw8xnxm5m5gxdatcptn58gzznbaw",
// 				"secret" => "mMg2MjqCKf3KhwUcsQe64kKzH3MByKZx"
// 		),
// 		"pbConfig" => $pbConfig,
// 		"updateTime_S" => "30",
// 		"test_mode" => false,
// );


/** Local Config **/
$config = array(
		"db" => array(
 				"main" => array(
 						"dbname" => "ggez_db",
 						"username" => "root",
 						"password" => "1111",
 						"host" => "localhost:3306"
 				),
		),
		"pbConfig" => $pbConfig,
		"updateTime_S" => "30000000000",
		"test_mode" => true,
);

/** Error Config **/
$error_config = array(
		"errLogFileName" => "error.log",
		"sqlErrLogFileName" => "mysql_error.log",
);

function errorLog($fileName, $errMessage){
	/* Error 글로벌 변수 선언 */
	global $error_config;	// 같은 파일내에서 위에 선언 되었더라도 Parameter를 전달받고 싶으면 global을 선언해야한다.
	/* Error 시간 설정 */
	$errTime = date('y-m-d H:i:s');
	/* Erro 발생장소 */
	$fileName = basename($fileName, ".php");
	
// 	error_log("[" . $errTime ."] " . $fileName . "		" . $errMessage . "\n", 3, ERRORLOG_PATH . "/" . $error_config["errLogFileName"]);
}

/**
 *  Enviorment Variable
 */
/* Cookie Name */
$cookie = array(
		"favoriteIds" => array("cookieName" => "cFavoriteIds", "expireTime" => 3600),
);

/* Tier Average */ 
$tierAverageVars = array(
		"tableName" => "tierdata",
		"pkHeaderName" => "id",
		"data" => "Datas"
);


/*
 I will usually place the following in a bootstrap file or some type of environment
 setup file (code that is run at the start of every page request), but they work
 just as well in your config file if it's in php (some alternatives to php are xml or ini files).
 */

 /** Error reporting. **/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

/** Example **/
/*
 define("BASE_URL", "/geo15/" );
 define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . "geo15/" );
 */

?>