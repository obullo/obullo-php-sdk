<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Obullo\Jwt\TokenRequest;
use Obullo\Jwt\Grants\VideoGrant;
use Obullo\Http\RemoteAddress;
use Obullo\Utils\Random;

define('ACCOUNT_ID', '6117f1158a172ed805bdd43b');
define('API_KEY', 'mIky5H1A1AcbjO1aIL8T1DrdkipIOUPgfBBF');
define('API_KEY_SECRET', 'bIDT8hZ9U5nKf6mGTm5nBeVEg50J6jrtHWI3');

$random = new Random;
$roomId = $random->generateHash('my-unique-room-name');
// $roomId = $random->generateInteger(); // do this if you prefer to use room id as integer 

$username = 'ersin_165';
$videoGrant = new VideoGrant();
$videoGrant->setRoomId($roomId);

$remoteAddress = new RemoteAddress(); // get real ip of user
try {
    $tokenRequest = new TokenRequest(
        ACCOUNT_ID,
        API_KEY,
        API_KEY_SECRET,
        $username
    );
    $tokenRequest->setRemoteAddress($remoteAddress);
    $tokenRequest->sslVerifyFile('/etc/ssl/ssl/RootCA.pem');
    $tokenRequest->addGrant($videoGrant);

    $response = $tokenRequest->send();
    $hostname = $response->getHostName(); // returns to most available server
    $token = $response->getJWT();

    echo 'host: '.$hostname.'<br>';
    echo 'token: '.$token;

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}