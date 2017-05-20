<?php
/**
 *  Battle net oauth
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once(LIBRARY_PATH . '/register/oauth-battle-net.php');
require_once(LIBRARY_PATH . '/DbHandler.php');

$client_id = $config['registerOauth']['key'];
$client_secret = $config['registerOauth']['secret'];
$region = 'KR';
$locale = 'ko';
$redirect_uri = 'https://ggez.kr/register';

$client = crtClient($client_id, $client_secret, $region, $locale, $redirect_uri);
$response = verifyOauth($client);

if($response['boolResult'] == true){
	// Welcome Page 작성하기..
	$orgBtg = $response['result']['battletag'];
	$transBtg = str_replace('#', '-', $orgBtg);
	
	if(checkId($transBtg, 'playerlist')){
		echo '<script>alert("이미 등록된 아이디 입니다. (' . $orgBtg . ')")</script>';
	} else {
		addBtg($transBtg, 'playerlist');
		echo '<script>alert("' . $orgBtg . ' 님 환영합니다. 정상적으로 등록되었습니다")</script>';
		echo '<script>window.close()</script>';   // Closes the new window
	}
}


	
