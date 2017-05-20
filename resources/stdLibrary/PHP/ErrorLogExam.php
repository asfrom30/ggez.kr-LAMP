<?php



/* 절대 경로로 로그 남기기 */
// error_log("You messed up!\n", 3, dirname(__FILE__) . "C:/my-errors.log");

/* 현재 폴더에 로그 남기기 */ 
// error_log("You messed up!\n", 3, "my-errors.log");	
// error_log("You messed up!\n", 3, dirname(__FILE__) . "/my-errors.log");

/* 응용하기 */
/* 서버루트 경로에 로그 남기기 */
// error_log("You messed up!\n", 3, dirname(__DIR__) . "/my-errors.log");

echo 'end1';
?>
