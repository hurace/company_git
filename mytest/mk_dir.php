<?php

function mk_dir($path){
	$arr = array();

	while(!is_dir($path)){
		array_push($arr,$path);
		$path = dirname($path);
	}

	while(count($arr)){
		$tmp = array_pop($arr);
		mkdir($tmp);
	}

}

print_r(mk_dir("./aa/bb/cc/d/e/f"));


?>