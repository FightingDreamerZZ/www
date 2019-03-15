<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onlinetools.ups.com/ups.app/xml/Rate");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
var_dump(curl_exec($ch));
var_dump(curl_error($ch));
?>