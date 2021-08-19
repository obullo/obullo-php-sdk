<?php

require 'vendor/autoload.php';

use Obullo\Jwt\TokenRequest;
use Obullo\Jwt\Grants\VideoGrant;

define('ACCOUNT_ID', '6117f1158a172ed805bdd43b');
define('API_KEY', 'mIky5H1A1AcbjO1aIL8T1DrdkipIOUPgfBBF');
define('API_KEY_SECRET', 'bIDT8hZ9U5nKf6mGTm5nBeVEg50J6jrtHWI3');

$username = 'ersin_165';

try {
    $tokenRequest = new TokenRequest(
        ACCOUNT_ID,
        API_KEY,
        API_KEY_SECRET,
        $username
    );
    $tokenRequest->sslVerify(false);
    $tokenRequest->addGrant($videoGrant);
    $tokenResponse = $tokenRequest->send();

    $accessToken = $tokenResponse->getJWT();
} catch (Exception $e) {
    echo 'Error:'.$e->getMessage();
}


$url = 'https://token.obullo.local:3000/v1/getAccessToken';
$data = [
    'ACCOUNT_ID' => '6117f1158a172ed805bdd43b',
    'API_KEY' => 'mIky5H1A1AcbjO1aIL8T1DrdkipIOUPgfBBF',
    'API_KEY_SECRET' => 'bIDT8hZ9U5nKf6mGTm5nBeVEg50J6jrtHWI3',
    'GRANTS' => [
        'video' => [
            'roomId' => 1234567891011125
        ]
    ],
    'username' => 'ersin165',
    'ip' => $ipa,
    'agent' => $agt
];

$verify = false;
$response = null;
if (function_exists('curl_version')) {
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verify);
    $res = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($statusCode == 200) {
        $response = json_decode($res, true);
    }
    curl_close($ch);
} else {
    // On the dev server verify must be "true"
    // 
    $client = new GuzzleHttp\Client(['verify' => $verify]);
    $headers = [
        'Accept' => 'application/json',
    ];
    $res = $client->post(
        $url,
        [
            'headers' => $headers,
            'json' => $data
        ]
    );
    $statusCode = $res->getStatusCode();
    if ($statusCode == 200) {
        $response = json_decode($res->getBody(), true);
    }
}

if (! empty($response['token'])) {
    echo $response['token'];
} else {
    echo "Error: " . $response['error'];
}
