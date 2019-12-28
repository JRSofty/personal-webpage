<?php

	header('Content-Type: application/json; charset=utf-8');
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	$curlHandle = curl_init();
	curl_setopt($curlHandle, CURLOPT_URL, "https://dev.to/api/articles?username=jrsofty");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($curlHandle);
	curl_close($curlHandle);
	
	echo $result;

?>
