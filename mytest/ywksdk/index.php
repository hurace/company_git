<?php
error_reporting(0);
require_once('./YouwukuSdk.php');
$client_id = 'w18151';
$client_id = 'w11067';
$appsecret = '7725edca4b89f3f4e5a1b3f5d956f472';
$appsecret = 'ywk&dianking321';
$service_id = 22367;
$service_id = 4174;
$service_id = 5860;
$page_no = 100;
$page = 1;
$start_day = '2016-03-11';
$end_day = '2016-03-11';
$uwuku = new YwkClient($client_id, $appsecret);
$res = $uwuku->chatinfo_get($client_id,$service_id,100,1,$start_day,$end_day);
var_export(json_decode($res,true));
?>