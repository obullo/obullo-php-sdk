<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

// echo date('H:i:s',286406721);

use Obullo\Jwt\TokenRequest;
use Obullo\Jwt\Grants\VideoGrant;
use Obullo\Utils\Random;

define('ACCOUNT_ID', '36d637f6-0217-498f-8d46-6941b943ac6c');
define('API_KEY', 'mIky5H1A1AcbjO1aIL8T1DrdkipIOUPgfBBF');
define('API_KEY_SECRET', 'bIDT8hZ9U5nKf6mGTm5nBeVEg50J6jrtHWI3');

$random = new Random;
$roomId = $random->generateHash('my-unique-room-id'); // room id must be a unique value
// $roomId = $random->generateInteger(); // do this if you prefer to use room id as integer 

$username = 'ersin_165'; // user identity must be a unique value
$videoGrant = new VideoGrant();
$videoGrant->setRoomId($roomId);

try {
    $tokenRequest = new TokenRequest(
        ACCOUNT_ID,
        API_KEY,
        API_KEY_SECRET,
        $username
    );
    $tokenRequest->setVerifyFile('/etc/ssl/ssl/RootCA.pem');
    $tokenRequest->setOrigin("https://obullo.local");
    $tokenRequest->addGrant($videoGrant);
    
    $response = $tokenRequest->send();
    $token = $response->getJWT();
    
    echo 'token: '.$token;

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}