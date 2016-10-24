<?php

chdir(__DIR__);

// Configuration
if (file_exists('../config.php')) {
	require_once('../config.php');
}


//0,5,10,15,20,25,30,35,40,45,50,55 * * * * php /absolute/path/to/opencart/ecron/cron.php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, HTTP_SERVER . '?route=ebay_channel/notification/cron');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$a = curl_exec($ch);
curl_close($ch);

echo $a;

?>