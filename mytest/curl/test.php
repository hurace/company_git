<?php

	header("content-type:text/html;charset=utr-8");
	$url = 'http://localhost/mytest/curl/request.php?id=3';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch); 

	echo $data;
 ?>