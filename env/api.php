<?php

	header('Content-Type: application/json; charset=utf-8');

	$curlHandle = curl_init();
	curl_setopt($curlHandle, CURLOPT_URL, "https://dev.to/api/articles?username=jrsofty");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curlHandle);
	curl_close($curlHandle);
	
	echo $result;

?>
