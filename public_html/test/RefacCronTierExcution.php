<?php 

require_once '../../resources/config.php';
require_once (LIBRARY_PATH . "/TierAvgComputeHandler.php");
require_once (LIBRARY_PATH . "/MysqlHandler.php");
require_once (LIBRARY_PATH . "/DbHandler.php");

/**
 * Initiate Parameter
 */

echo '=========== start ===========<br>';

/* Declare data date which is criteria */
$yesterday = date("Y_m_d", strtotime($day." -1 day"));
$today = date('Y_m_d');


/* Declare object database, table, row name */
$objectDate = $yesterday;

/* Declare what Name you want to store in table */
$targetDate = $yesterday;


$tierAvgComputeHandler = new TierAvgComputeHandler();
$tierAvgComputeHandler -> manualRun($objectDate, $targetDate);

echo '=========== end ============<br>';
?>