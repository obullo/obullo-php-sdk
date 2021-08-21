
## Obullo Php SDK

Php SDK for Obullo API

CURL example (old version)

```php
$ch = curl_init(Self::getUrl()); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // it should be default 2, 1 no longer supported

if (! empty($this->verifyFile) && ! file_exists($this->verifyFile)) {
    throw new VerifyFileNotExistsException('Verify ssl file does not exists');
} else {
    curl_setopt($ch, CURLOPT_CAINFO, $this->verifyFile);
}
$res = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($statusCode == 200) {
    $response = json_decode($res, true);
} else {
    throw new HttpConnectionException(
        sprintf(
            'Cannot connect to the server. Status code: %'
        )
    );
}
curl_close($ch);
```