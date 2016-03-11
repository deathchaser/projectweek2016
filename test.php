<?php

$host = "https://c05b24fd-cb71-48ea-bfeb-b81ff5bffcff:pHZwHBOSBOIR@gateway.watsonplatform.net/tone-analyzer-beta/api/v3/tone?version=2016-02-11&text=A%20word%20is%20dead%20when%20it%20is%20said,%20some%20say.%20Emily%20Dickinson";
$username = "c05b24fd-cb71-48ea-bfeb-b81ff5bffcff";
$password = "pHZwHBOSBOIR";
$additionalHeaders = "";

$file = file_get_contents($host);

echo $file;


?>