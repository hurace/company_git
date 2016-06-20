<?php
$arr = Array
(
    '0' => Array
        (
            'key' => "AJ",
            'creat' => 1413643639
        ),

    '1' => Array
        (
            'key' => "AJ",
            'creat' => 1417527002
        ),

    '2' => Array
        (
            'key' => "CG",
            'creat' => 1417527002
        ),

    '3' => Array
        (
            'key'=> "Co",
            'creat' => 1427114702
        ),

    '4' => Array
        (
            'key'=> "Cb",
            'creat' => 1431938402
        )
);
array_unique($arr);
var_dump($arr);
function test($arr,$key){
	for($i=0;$i<count($arr);$i++){
		if($arr[$i]['key'] == $key){
			echo $key;
			return false;
		}
	}
	return true;
}

$res = test($arr,'J');
var_dump($res);

?>