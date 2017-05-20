<?php



/**
 *  Battle net oauth
 */
use OAuth2\Client;

require_once($_SERVER['DOCUMENT_ROOT'] . "/../resources/config.php");

require_once(LIBRARY_PATH . "/register/blizzard/Client.php");
require_once(LIBRARY_PATH . "/register/blizzard/GrantType/IGrantType.php");
require_once(LIBRARY_PATH . "/register/blizzard/GrantType/AuthorizationCode.php");
		
function crtClient($client_id, $client_secret, $region, $locale, $redirect_uri){
	return new Client ( $client_id, $client_secret, $region, $locale, $redirect_uri );
}

function verifyOauth($client) {

	// $r = $client->fetch('user', array('source'=>'account'));
	$response['boolResult'] = false;
	if (! isset ( $_GET ['code'] )) {	// code가 없을때는...
		$auth_url = $client->getAuthenticationUrl ( $client->baseurl [$client->region] ['AUTHORIZATION_ENDPOINT'], $client->redirect_uri );
		header ( 'Location: ' . $auth_url );
		die ( 'Redirect' );
	} else {
		$params = array (
				'code' => $_GET ['code'],
				'auth_flow' => 'auth_code',
				'redirect_uri' => $client->redirect_uri
		);
		$response = $client->getAccessToken ( $client->baseurl [$client->region] ['TOKEN_ENDPOINT'], 'authorization_code', $params );
		$client->setAccessToken ( $response ['result'] ['access_token'] );
		$response = $client->fetch ( 'user', array (
				'source' => 'account'
		));
		$response['boolResult'] = true;
	}
	return $response;
}
?>
